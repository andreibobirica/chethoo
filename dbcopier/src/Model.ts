import { Detail } from "./Detail";
/**
 * Classe Model che rapresenta le info del Modello di macchina di un certo Make Marchio
 */
export class Model{
    private bodyTypeID: number;
    private modelID: number;
    private noOfDoors: number;
    private makeID: number;
    //Data
    private year: number;
    private month: number;

    //Dettagli del Model
    private details: Detail[];

    public getNoOfDoors():number{return this.noOfDoors;}
    public getMakeID():number{return this.makeID;}
    public getBodyTypeID():number{return this.bodyTypeID;}
    public getModelID():number{return this.modelID;}
    public getYear():number{return this.year;}
    public getMonth():number{return this.month;}
    
    /**
     * Costruttore
     */
    public constructor(_bodyTypeID: number,_modelID: number,_noOfDoors: number, _makeID: number, _year: number, _month:number){
        this.bodyTypeID = _bodyTypeID;
        this.modelID = _modelID;
        this.noOfDoors = _noOfDoors;
        this.makeID = _makeID;
        this.year = _year;
        this.month = _month;
    }

    /**
     * Metodo to string
     * @returns stringa contente info del oggetto, tranne i details
     */
    public toString():string{
        return (this.bodyTypeID + " " +
            this.modelID + " " +
            this.noOfDoors + " " +
            this.makeID + " " +
            this.year + " " +
            this.month);
    }

    /**
     * Metodo Set che definisce i details del Model
     * @param dets Array di Detail da inserire e associare al Model
     */
    public setDetails(dets: Detail[]):void{
        this.details= dets;
    }

    /**
     * Metodo Get dei Details
     * @returns array di Detail associati al Model
     */
    public getDetails():Detail[]{
        return this.details;
    }
}