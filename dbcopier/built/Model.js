/**
 * Classe Model che rapresenta le info del Modello di macchina di un certo Make Marchio
 */
export class Model {
    /**
     * Costruttore
     */
    constructor(_bodyTypeID, _modelID, _modelName, _noOfDoors, _makeID, _year, _month) {
        this.bodyTypeID = _bodyTypeID;
        this.modelID = _modelID;
        this.modelName = _modelName;
        this.noOfDoors = _noOfDoors;
        this.makeID = _makeID;
        this.year = _year;
        this.month = _month;
    }
    getNoOfDoors() { return this.noOfDoors; }
    getMakeID() { return this.makeID; }
    getBodyTypeID() { return this.bodyTypeID; }
    getModelID() { return this.modelID; }
    getModelName() { return this.modelName; }
    getYear() { return this.year; }
    getMonth() { return this.month; }
    /**
     * Metodo to string
     * @returns stringa contente info del oggetto, tranne i details
     */
    toString() {
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
    setDetails(dets) {
        this.details = dets;
    }
    /**
     * Metodo Get dei Details
     * @returns array di Detail associati al Model
     */
    getDetails() {
        return this.details;
    }
}
