import { Ajax } from "./Ajax.js";
import { Detail } from "./Detail.js";
import { Model } from "./Model.js";
/**
 * Classe Copier che serve a copiare i dati da AS24 riguardanti i veicoli, elaborarli,
 * e successivamente inserirli nel DB di chethoo
 */
export class Copier {
    /**
     * Costruttore, qui viene solamente inizializzato il controller Ajax
     */
    constructor() {
        //Array di Model, insieme del prodotto scalare di ogni modello per marca per ogni mese di ogni anno, se esiste
        //Inizialmente vuoto, viene riempito da extractModels()
        this.modelsTotal = [];
        //Variabili che servono per gestire le istanze ricorsive in maniera assicnrona facendo diverse chiamate HTTP
        //contemporaneamente ma limitandone comunque il numero
        //Se n > 0 ci sono ancora istanze ricorsive in funzione
        //Se n == 1 c'è solo una istanza ricorsiva da eseguire e quella sarà lultima che eseguirà il caso finale
        //Numero di istanze ricorsive di ricerca dei modelli.
        this.recursionInstanceModel = 0;
        //Numero di istanzze ricorsive di ricerca dei dettagli
        this.recursionInstanceDetail = 0;
        //Variabile che serve per la gestione della percentuale dei modelli esaminati
        //la percentuale che si realizza è sui modelli esmainati in totale sui totale aventi
        //per trovare i Details per ogni Model
        this.noOfModelExamined = 0;
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
    getStaticDataJSfrom24(yearstart, yearstop) {
        this.ajaxController.sendAjaxRequest("GET", "./dataDispatcher.php?staticDataJS", null, (res) => {
            //salvo i risultati
            this.staticDataJS = res;
            this.makes = this.staticDataJS.makes;
            this.models = this.staticDataJS.models;
            this.gearingTypes = this.staticDataJS.gearingTypes;
            this.fuel = this.staticDataJS.fuel;
            this.bodyTypes = this.staticDataJS.bodyTypes;
            //Invio dei dati statici al DB
            this.postMakes();
            this.postGearingTypes();
            this.postFuel();
            this.postBodyTypes();
            //Extract Model From makes for each make
            console.log("NUMERO MAKES:" + this.makes.length);
            $("#dateRecuperoDati").html("<span>[" + yearstart + "-" + (yearstop) + "[</span>");
            //ciclo ciascun anno da cui carpire i dati, faccio partire una iterazione ricorsiva assincrana per ciascun anno
            for (let anno = yearstart; anno > yearstop; anno--) {
                this.recursionInstanceModel++; //Incremento delle istanze di ricorsione
                this.extractModels(180, anno, 12, anno, anno - 1); //Ciascuna iterazione ricorsiva controlla 1 anno
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
    insertModel(element, makeID, year, month) {
        this.modelsTotal.push(new Model(element.BodyTypeID, element.ModelID, element.NoOfDoors, makeID, year, month));
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
    extractModels(makesKey, year, month, yearstart, yearstop) {
        if (makesKey < this.makes.length) {
            //aggiunta di uno 0 al parametro zero
            let zero = "0";
            if (month > 9)
                zero = "";
            let makeID = this.makes[makesKey].Id;
            if (year > yearstop) {
                this.ajaxController.sendAjaxRequest("GET", `./dataDispatcher.php?ModelsForMake&make=${makeID}&year=${year}&month=${zero}${month}`, null, (res) => {
                    //foreach result insert Model
                    if (res.forEach !== undefined) {
                        res.forEach(element => {
                            this.insertModel(element, makeID, year, month);
                        });
                    }
                    else
                        console.log(`./dataDispatcher.php?ModelsForMake&make=${makeID}&year=${year}&month=${zero}${month}`);
                    if (month < 12)
                        this.extractModels(makesKey, year, month + 1, yearstart, yearstop);
                    else
                        this.extractModels(makesKey, year - 1, 12, yearstart, yearstop);
                });
            }
            else {
                let percentuale = Math.round((makesKey * 100 / this.makes.length) * 100) / 100;
                //console.log("Stato Download Modelli sotto "+yearstart+": "+percentuale+"%");
                $("#percModel").html(percentuale + "");
                this.extractModels(makesKey + 1, yearstart, 12, yearstart, yearstop);
            }
        }
        else {
            //Caso base, ma ci sono ancora iterazioni ricorsive operanti
            if (this.recursionInstanceModel > 1)
                this.recursionInstanceModel--;
            //Caso base, questa era l'unica iterazione ricorsiva rimanente
            else {
                //Inizio recupero dettaglio 
                $("#percModel").html(100 + "");
                //Recupero delle informazioni in maniera assincrona con 25 iterazioni ricorsive
                console.log("NUMERO MODELLI:" + this.modelsTotal.length);
                //Se il numero di modelli è <= a 0 non faccio richieste di dettagli
                if (this.modelsTotal.length <= 0)
                    console.log("Nessun modello da analizzare");
                //Se ci sono modelli
                else {
                    for (let minizio = 0, stop = false; minizio < 6 && !stop; minizio++) {
                        //Controllo degli indici del modello
                        if (minizio >= this.modelsTotal.length) {
                            stop = true;
                        }
                        else {
                            this.recursionInstanceDetail++;
                            this.getDetailForModel(minizio, this.modelsTotal.length);
                        }
                    }
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
    getDetailForModel(modelKey, modelEndKey) {
        //Aggiornamento Percentuale caricamento dettagli
        let percentuale = Math.round((++this.noOfModelExamined * 100 / this.modelsTotal.length) * 100) / 100;
        $("#percDetail").html(percentuale + "");
        //aggiunta di uno 0 al parametro zero
        let m = this.modelsTotal[modelKey];
        let zero = "0";
        if (m.getMonth() > 9)
            zero = "";
        this.ajaxController.sendAjaxRequest("GET", `./dataDispatcher.php?detailForModel&nDoors=${m.getNoOfDoors()}&bodytype=${m.getBodyTypeID()}&make=${m.getMakeID()}&model=${m.getModelID()}&year=${m.getYear()}&month=${zero}${m.getMonth()}`, null, (res) => {
            //Make of Details
            let dets = [];
            res.forEach(det => {
                dets.push(this.makeDetail(det));
            });
            //Set Details of Model
            m.setDetails(dets);
            //caso ric
            if (modelKey + 6 < modelEndKey) {
                this.getDetailForModel(modelKey + 6, modelEndKey);
                //Caso base
            }
            else {
                //Ci sono ulteriori istanze ricorsive che devono ancora terminare
                if (this.recursionInstanceDetail > 1) {
                    this.recursionInstanceDetail--;
                }
                //Questa è l'ultima istanza ricorsiva, si eleborano i dati
                else {
                    $("#percDetail").html(100 + "");
                    this.postModelsDetailsProductions(0);
                    console.log(this.modelsTotal);
                }
            }
        });
    }
    /**
     * Se il dato è undefined o non esistente, ritorna null, altrimenti il dato
     * @param data stringa o intero rapresentante del dato da mostrare
     * @returns
     */
    verifyAndSetNull(data) {
        if (data === undefined)
            data = null;
        return data;
    }
    /**
     * Funzione make detail che dati i parametri sottodorma di oggetto json, ne ricava i dati e li trasforma
     * in una istanza di classe di tipo Detail
     * @param det oggetto json contenente info riguardanti il Detail
     * @returns Oggetto Detail
     */
    makeDetail(det) {
        //Il parametro QueryString contiene informazioni utili, Da qui noi ne prendiamo le informazioni con le
        //seguenti righe
        //Controlla che ci siano 25 posizioni, perchè quelle sono previste, in caso diverso si buttano i dati.
        //per FUTORO
        let detQuery = JSON.parse('{"' + decodeURI(det.QueryString).replace(/"/g, '\\"').replace(/&/g, '","').replace(/=/g, '":"') + '"}');
        let detInt = {
            _buildPeriod: this.verifyAndSetNull(det.BuildPeriod),
            _codall: this.verifyAndSetNull(det.CODALL),
            _fuelTypeID: this.verifyAndSetNull(det.FuelTypeID),
            _hsn: this.verifyAndSetNull(det.HSN),
            _modelLine: this.verifyAndSetNull(det.ModelLine),
            _powerKW: this.verifyAndSetNull(det.PowerKW),
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
            _consumptionCity: this.verifyAndSetNull(detQuery.consumptionc),
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
        };
        return new Detail(detInt);
    }
    /**
     *
     * @param sParam nome del parametro da cui prendere i valori
     * @returns intero contenente il valore del parametro, intero perchè realizzato solo per ritornare anni
     * Ritorna 0 in caso di errori
     */
    getUrlParameter(sParam) {
        var sPageURL = window.location.search.substring(1), sURLVariables = sPageURL.split('&'), sParameterName, i;
        for (i = 0; i < sURLVariables.length; i++) {
            sParameterName = sURLVariables[i].split('=');
            if (sParameterName[0] === sParam) {
                return typeof sParameterName[1] === undefined ? 0 : +decodeURIComponent(sParameterName[1]);
            }
        }
        return 0;
    }
    ;
    postMakes() {
        this.ajaxController.sendPostRequest("./dataDispatcher.php?postMakes", { "makes": this.makes }, () => { });
    }
    postGearingTypes() {
        this.ajaxController.sendPostRequest("./dataDispatcher.php?postGearingTypes", { "gearingTypes": this.gearingTypes }, () => { });
    }
    postFuel() {
        this.ajaxController.sendPostRequest("./dataDispatcher.php?postFuel", { "fuel": this.fuel }, () => { });
    }
    postBodyTypes() {
        this.ajaxController.sendPostRequest("./dataDispatcher.php?postBodyTypes", { "bodyTypes": this.bodyTypes }, () => { });
    }
    postModelsDetailsProductions(modelKey) {
        if (modelKey < this.modelsTotal.length) {
            this.ajaxController.sendPostRequest("./dataDispatcher.php?postModelsDetailsProductions", { "mdp": this.modelsTotal[modelKey] }, () => {
                this.postModelsDetailsProductions(modelKey + 1);
            });
        }
    }
    /**
     * Metodo Run principale che fa partire il recupero del dati da AS24
     */
    run() {
        //Prendo i dati statici dal DB 24
        this.getStaticDataJSfrom24(this.getUrlParameter("yearstart"), this.getUrlParameter("yearstop"));
        //Ulteriori istruzioni eseguite nel suo callback
    }
}
