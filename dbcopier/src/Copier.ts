import {Ajax} from "./Ajax.js"
import { Detail , DetailInterface} from "./Detail.js";
import { Model } from "./Model.js";

/**
 * Classe Copier che serve a copiare i dati da AS24 riguardanti i veicoli, elaborarli,
 * e successivamente inserirli nel DB di chethoo
 */
export class Copier {
    //Controller per fare richieste Ajax HTTP
    private ajaxController : Ajax;

    //Dati statici da AS24 dal file staticDataJS
    private staticDataJS : any;
    //oggetto json contenente info di tutte le marche
    private makes : any;
    //oggetto json contenente info di tutti i modelli
    private modelsName: any; //Ta cui prendere I ModelName
    //oggetto json contenente info di tutti i gearTypes, tipi di cambio
    private gearingTypes: any;
    //oggetto json contenente info di tutti i fuel, tipi di camrburante
    private fuel: any;
    //oggetto json contenente info di tutti i bodyTypes, forma veicolo
    private bodyTypes: any;

    //Array di Model, insieme del prodotto scalare di ogni modello per marca per ogni mese di ogni anno, se esiste
    //Inizialmente vuoto, viene riempito da extractModels()
    private modelsTotal : Model[] = [];

    //Variabili che servono per gestire le istanze ricorsive in maniera assicnrona facendo diverse chiamate HTTP
    //contemporaneamente ma limitandone comunque il numero
    //Se n > 0 ci sono ancora istanze ricorsive in funzione
    //Se n == 1 c'è solo una istanza ricorsiva da eseguire e quella sarà lultima che eseguirà il caso finale
    //Numero di istanze ricorsive di ricerca dei modelli.
    private recursionInstanceModel : number = 0;
    //Numero di istanzze ricorsive di ricerca dei dettagli
    private recursionInstanceDetail : number = 0;
    //
    private recursionInstanceMPD : number = 0;

    //Variabile che serve per la gestione della percentuale dei modelli esaminati
    //la percentuale che si realizza è sui modelli esmainati in totale sui totale aventi
    //per trovare i Details per ogni Model
    private noOfModelExamined: number = 0;
    

    /**
     * Costruttore, qui viene solamente inizializzato il controller Ajax
     */
    public constructor(){
        this.ajaxController = new Ajax();
    }

    /**
     * Metodo che fa una richiesta ad AS24 per i dati statici
     * Successivamente smista i dati in sotto oggetti più facilmente categorizzabili
     * Successivamente ne estrae tutti i modelli sotto la forma di Model
     */
    /**
     * Metodo che inizialmente fa una rihiesta ad AS24 per prendere i staticDataJS
     * Li salva ripartizionandoli in oggetti json più piccoli.
     * Viene poi, in base ai parametri, fatta l'operazione di recupero dei Model.
     * Dati i due parametri si fanno iniziare delle istanze ricorsive contemporanee, una per ogni anno
     * Ciascuna troverà i modelli di tutte le marche di quel anno.
     * @param yearstart anno iniziale da cui cominciare a cercare modelli, incluso questo anno
     * @param yearstop anno finale fino al quale non cercare più modelli, escluso questo anno
     */
    private getStaticDataJSfrom24(yearstart:number, yearstop:number):void{
        this.ajaxController.sendAjaxRequest("GET","./dataDispatcher.php?staticDataJS",null,
        (res: any): void => {
            //salvo i risultati
            this.staticDataJS = res;
            this.makes = this.staticDataJS.makes;
            this.modelsName = this.staticDataJS.models;
            this.gearingTypes = this.staticDataJS.gearingTypes;
            this.fuel = this.staticDataJS.fuel;
            this.bodyTypes = this.staticDataJS.bodyTypes;

            //Invio dei dati statici al DB
            this.postMakes();
            this.postGearingTypes();
            this.postFuel();
            this.postBodyTypes();
            //Post ModelName
            
            //Extract Model From makes for each make
            console.log("NUMERO MAKES:"+this.makes.length);
            $("#dateRecuperoDati").html("<span>["+yearstart+"-"+(yearstop)+"[</span>");
            //ciclo ciascun anno da cui carpire i dati, faccio partire una iterazione ricorsiva assincrana per ciascun anno
            for (let anno = yearstart; anno > yearstop; anno--) {
                this.recursionInstanceModel++;//Incremento delle istanze di ricorsione
                this.extractModels(170,anno,11,anno,anno-1);//Ciascuna iterazione ricorsiva controlla 1 anno
            }
        });
    }

    /**
     * Funzione che inserisce crea un Model dati i parametri e lo inserisce nel array di model modelTotal
     * @param element info prese dal db riguardanti il singolo modello non in correllazione con altri dati
     * @param makeID 
     * @param year 
     * @param month 
     */
    private insertModel(element:any, makeID:number, year:number, month:number):void{
        this.modelsTotal.push(
            new Model(element.BodyTypeID,
            element.ModelID,
            element.NoOfDoors,
            makeID,
            year,
            month));
    }

    /**
     * Funzione ricorsiva, Funzione che effettua la richiesta dei modelli di una certa marca in una certa data
     * Una volta avuta la lista di modelli, inserisce il Model all'interno dell'arrat modelsTotal.
     * Effettua una ricarca da un anno di inizio ad un anno di fine in ordine decrescente.
     * Caso Base : !(makesKey < this.makes.length)
     *      Si è arrivati alla fine del array di marche makes, non ci sono più makes da esaminare
     * Caso Ric1 : year > yearstop && month<12
     *      Si sta esaminando ancora anno e un mese valido, andiamo sul mese successivo
     *      this.extractModels(makesKey,year,++month,yearstart,yearstop);
     * Caso Ric2 : year > yearstop && !(month<12)
     *      Si sta esaminando ancora un anno valido ma il mese esaminato è stato l'ultimo cioè 12
     *      Si va nel anno precedente da gennaio
     *      this.extractModels(makesKey,--year,1,yearstart,yearstop);
     * Caso Ric3: !(year > yearstop)
     *      Si è finito l'anno ultimo analizzabile, Si è finita questa marca, si passa alla make successiva
     *      this.extractModels(++makesKey,yearstart,1,yearstart,yearstop);
     *      
     * @param makesKey chiave del array makes su cui chiedere i modelli
     * @param year anno su cui chiedere i modelli
     * @param month mese su cui chiedere i modelli
     * @param yearstart anno di inizio da cui iniziare a cheidere i modelli > yearstop
     * @param yearstop anno di fine fino al quale si chiederanno i modelli < yearstart
     */
    private extractModels(makesKey: number, year : number, month:number ,yearstart:number,yearstop:number):void{
        if(makesKey < this.makes.length){
            //aggiunta di uno 0 al parametro zero
            let zero : string= "0";
            if(month>9)
            zero="";

            let makeID: number = this.makes[makesKey].Id;

            if(year > yearstop){
                this.ajaxController.sendAjaxRequest("GET",`./dataDispatcher.php?ModelsForMake&make=${makeID}&year=${year}&month=${zero}${month}`,null,
                (res: any): void => {
                    //foreach result insert Model
                    if(res.forEach!==undefined){
                    res.forEach(element => {
                        this.insertModel(element, makeID, year, month);
                    });}
                    else
                    console.log(`./dataDispatcher.php?ModelsForMake&make=${makeID}&year=${year}&month=${zero}${month}`);
                    
                    if(month<12)
                    this.extractModels(makesKey,year,month+1,yearstart,yearstop);
                    else
                    this.extractModels(makesKey,year-1,11,yearstart,yearstop);
                }); 
            }else{
                let percentuale : number = Math.round((makesKey*100/this.makes.length) * 100) / 100;
                //console.log("Stato Download Modelli sotto "+yearstart+": "+percentuale+"%");
                $("#percModel").html(percentuale+"");
                this.extractModels(makesKey+1,yearstart,11,yearstart,yearstop);
            }     
        }else{
            //Caso base, ma ci sono ancora iterazioni ricorsive operanti
            if(this.recursionInstanceModel>1)
            this.recursionInstanceModel--;
            //Caso base, questa era l'unica iterazione ricorsiva rimanente
            else{
                //Inizio recupero dettaglio 
                $("#percModel").html(100+"");
                //Recupero delle informazioni in maniera assincrona con 25 iterazioni ricorsive
                console.log("NUMERO MODELLI:"+this.modelsTotal.length);

                //Se il numero di modelli è <= a 0 non faccio richieste di dettagli
                if(this.modelsTotal.length<=0)
                    console.log("Nessun modello da analizzare");
                //Se ci sono modelli
                /*
                else{
                    for (let minizio : number = 0, stop:boolean = false; minizio < 6 && !stop; minizio++) {
                        //Controllo degli indici del modello
                        if(minizio>=this.modelsTotal.length){
                            stop = true;
                        }
                        else{
                            this.recursionInstanceDetail++;
                            this.getDetailForModel(minizio,this.modelsTotal.length);
                        }
                    }
                }
                */
                else{
                    const step : number = 6;
                    this.recursionInstanceDetail=step;
                    this.getDetailForModel(0,this.modelsTotal.length,step);
                    this.getDetailForModel(1,this.modelsTotal.length,step);
                    this.getDetailForModel(2,this.modelsTotal.length,step);
                    this.getDetailForModel(3,this.modelsTotal.length,step);
                    this.getDetailForModel(4,this.modelsTotal.length,step);
                    this.getDetailForModel(5,this.modelsTotal.length,step);
                }
            }
        }
    }

    /**
     * Funzione ricorsiva
     * ad ogni ricorsione esamina il modello con il modelkey da parametro
     * fa una richiesta al DB AS24 con i dettagli
     * successviamente nel callback della chiamata crea un array di Detail e li setta sul Model di riferimento
     * alla fine se si è arrivati al modelendkey finale, si ha il caso base
     * se si ha (modelKey+1 < modelEndKey) allora si ha il caso ric
     * Nel caso ric si fa la ricorsione su modelKey+1
     * Nel caso base , unico e finale, si possono manipolare i dati ricevuti.
     * @param modelKey chiave del array di modelli , indice del modello da analizzare e dettagliare
     * @param modelEndKey chiave del array fino a fine prendere i dettagli
     */
    private getDetailForModel(modelKey:number,modelEndKey:number, step: number):void{
        //Aggiornamento Percentuale caricamento dettagli
        
        let percentuale : number = Math.round((++this.noOfModelExamined*100/this.modelsTotal.length) * 100) / 100;
        $("#percDetail").html(percentuale+"");

        //aggiunta di uno 0 al parametro zero
        let m = this.modelsTotal[modelKey];
        let zero : string= "0";
        if(m.getMonth()>9)
        zero="";

        this.ajaxController.sendAjaxRequest("GET",`./dataDispatcher.php?detailForModel&nDoors=${m.getNoOfDoors()}&bodytype=${m.getBodyTypeID()}&make=${m.getMakeID()}&model=${m.getModelID()}&year=${m.getYear()}&month=${zero}${m.getMonth()}`,null,
        (res: any): void => {
            
            //Make of Details
            let dets: Detail[] = [];
            
            res.forEach(det => {
                dets.push(this.makeDetail(det));
            });
            //Set Details of Model
            m.setDetails(dets);

            //caso ric
            if(modelKey+step < modelEndKey){
                this.getDetailForModel(modelKey+step,modelEndKey,step);
            //Caso base
            }else{
                //Ci sono ulteriori istanze ricorsive che devono ancora terminare
                if(this.recursionInstanceDetail>1){
                    this.recursionInstanceDetail--;
                }
                //Questa è l'ultima istanza ricorsiva, si eleborano i dati
                else{
                    $("#percDetail").html(100+"");
                    this.noOfModelExamined = 0;
                    const step : number = 6;
                    this.recursionInstanceMPD = step;
                    this.postModelsDetailsProductions(0,step);
                    this.postModelsDetailsProductions(1,step);
                    this.postModelsDetailsProductions(2,step);
                    this.postModelsDetailsProductions(3,step);
                    this.postModelsDetailsProductions(4,step);
                    this.postModelsDetailsProductions(5,step);
                }
            }
        }); 
    }


    /**
     * Se il dato è undefined o non esistente, ritorna null, altrimenti il dato
     * @param data stringa o intero rapresentante del dato da mostrare
     * @returns 
     */
    private verifyAndSetNull(data){
        if(data===undefined)
        data=null;
        return data;
    }

    /**
     * Funzione make detail che dati i parametri sottodorma di oggetto json, ne ricava i dati e li trasforma 
     * in una istanza di classe di tipo Detail
     * @param det oggetto json contenente info riguardanti il Detail
     * @returns Oggetto Detail
     */
    private makeDetail(det:any):Detail{

        let detQuery : any = null;
        if(det.QueryString!==undefined && det.QueryString!=="" && det.QueryString!==null){
            //Caso &firstreg_mth=07&firstreg_year=2020
            if(det.QueryString.charAt(0)=='&')
            det.QueryString = det.QueryString.substring(1,det.QueryString.length);
            //Caso normale
            detQuery = JSON.parse('{"' + decodeURI(det.QueryString).replace(/"/g, '\\"').replace(/&/g, '","').replace(/=/g,'":"') + '"}');
        }
        

        let detInt : DetailInterface = {
            _buildPeriod: this.verifyAndSetNull(det.BuildPeriod),
            _codall : this.verifyAndSetNull(det.CODALL),
            _fuelTypeID: this.verifyAndSetNull(det.FuelTypeID),
            _hsn : this.verifyAndSetNull(det.HSN),
            _modelLine: this.verifyAndSetNull(det.ModelLine),
            _powerKW:  this.verifyAndSetNull(det.PowerKW),
            _powerPS: this.verifyAndSetNull(det.PowerPS),
            _schckeId: this.verifyAndSetNull(det.SchckeId),
            _tsn: this.verifyAndSetNull(det.TSN),
            _version: this.verifyAndSetNull(det.Version),
            _gearingTypeId: this.verifyAndSetNull(det.GearingTypeId),
            _noOfSeats: this.verifyAndSetNull(det.NoOfSeats),
            _gears: this.verifyAndSetNull(detQuery.gears),
            _ccm: this.verifyAndSetNull(detQuery.ccm),
            _cylinders: this.verifyAndSetNull(detQuery.cylinders),
            _weight: this.verifyAndSetNull(detQuery.weight),
            _consumptionMixed: this.verifyAndSetNull(detQuery.consumptionmixed),
            _consumptionCity: this.verifyAndSetNull(detQuery.consumptioncity),
            _type: this.verifyAndSetNull(detQuery.type),
            _consumptionHighway: this.verifyAndSetNull(detQuery.consumptionhighway),
            _co2EmissionMixed: this.verifyAndSetNull(detQuery.co2emissionmixed),
            _adtype: this.verifyAndSetNull(detQuery.adtype),
            _emClass: this.verifyAndSetNull(detQuery.emclass),
            _transm: this.verifyAndSetNull(detQuery.transm),
            _equi: this.verifyAndSetNull(detQuery.equi),
            _upholsteryid: this.verifyAndSetNull(detQuery.upholsteryid),
            _firstreg_mth: this.verifyAndSetNull(detQuery.firstreg_mth),
            _firstreg_year: this.verifyAndSetNull(detQuery.firstreg_year),
            _queryString: this.verifyAndSetNull(det.QueryString)
        }
        return new Detail(detInt);
    }

    /**
     * 
     * @param sParam nome del parametro da cui prendere i valori
     * @returns intero contenente il valore del parametro, intero perchè realizzato solo per ritornare anni
     * Ritorna 0 in caso di errori
     */
    private getUrlParameter(sParam:string):number{
        var sPageURL = window.location.search.substring(1),
            sURLVariables = sPageURL.split('&'),
            sParameterName,
            i;
    
        for (i = 0; i < sURLVariables.length; i++) {
            sParameterName = sURLVariables[i].split('=');
            if (sParameterName[0] === sParam) {
                return typeof sParameterName[1] === undefined ? 0 : +decodeURIComponent(sParameterName[1]);
            }
        }
        return 0;
    };

    private postMakes():void{
        this.ajaxController.sendPostRequest(
            "./dataDispatcher.php?postMakes",
            {"makes": this.makes},
            () => {});
    }

    private postGearingTypes():void{
        this.ajaxController.sendPostRequest(
            "./dataDispatcher.php?postGearingTypes",
            {"gearingTypes": this.gearingTypes},
            () => {}); 
    }

    private postFuel():void{
        this.ajaxController.sendPostRequest(
            "./dataDispatcher.php?postFuel",
            {"fuel": this.fuel},
            () => {}); 
    }

    private postBodyTypes():void{
        this.ajaxController.sendPostRequest(
            "./dataDispatcher.php?postBodyTypes",
            {"bodyTypes": this.bodyTypes},
            () => {}); 
    }

    private postModelsDetailsProductions(modelKey:number, step:number):void{
        let percentuale : number = Math.round((++this.noOfModelExamined*100/this.modelsTotal.length) * 100) / 100;
        $("#percPostMPD").html(percentuale+"");

        if(modelKey < this.modelsTotal.length){
            this.ajaxController.sendPostRequest(
                "./dataDispatcher.php?postModelsDetailsProductions",
                {"mdp": this.modelsTotal[modelKey]},
                () => {
                    this.postModelsDetailsProductions(modelKey+step,step);
                }); 
        }else{
            if(this.recursionInstanceMPD>1){
                this.recursionInstanceMPD--;
            }
            else
            $("#percPostMPD").html("100");//FINE
        }
    }

    /**
     * Metodo Run principale che fa partire il recupero del dati da AS24
     */
    public run(){
        //Prendo i dati statici dal DB 24
        this.getStaticDataJSfrom24(this.getUrlParameter("yearstart"),this.getUrlParameter("yearstop"));
        //Ulteriori istruzioni eseguite nel suo callback
    }
}
