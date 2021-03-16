/**
 * Interfaccia DetailInterface contenente tutti i parametri di un Detail 
 * per la sua corretta inizializzazione
 * Questa interfaccia viene usata anche da chi invoca il costruttore di un Detail per la sua creazione.
 */
export interface DetailInterface{
    _buildPeriod : string,
    _codall : number,
    _FuelTypeID: string,
    _hsn : unknown,
    _modelLine: string,
    _powerKW: number,
    _powerPS: number,
    _schckeId: unknown,
    _tsn: unknown,
    _version: string,
    _gearingTypeId: string,
    _noOfSeats: number,
    _gears:number,
    _ccm:number,
    _cylinders:number,
    _weight:number,
    _consumptionmixed:number,
    _consumptioncity:number,
    _type:number,
    _consumptionhighway:number,
    _co2emissionmixed:number,
    _adtype:unknown,
    _emclass:number,
    _transm:number,
    _equi:unknown,
    _upholsteryid:number,
    _firstreg_mth:number,
    _firstreg_year:number,
    _queryString:string
}


export class Detail{
    //dati del Detail
    private data : DetailInterface;

    //costruttore del detail
    public constructor(detInt:DetailInterface){
        this.data = detInt;
        this.verifyData();
    }

    private verifyData(){
        this.data._buildPeriod = this.data._buildPeriod== "0" ? "None" : this.data._buildPeriod,
        this.data._codall = this.data._codall== 0 ? 0 : this.data._codall,
        this.data._FuelTypeID = this.data._FuelTypeID===undefined ? "O" : this.data._FuelTypeID,
        this.data._hsn = this.data._hsn == 0 ? "None" : this.data._hsn,
        this.data._modelLine = this.data._modelLine == "0" ? "None" : this.data._modelLine,
        this.data._powerKW = this.data._powerKW == 0 ? 0 : this.data._powerKW,
        this.data._powerPS = this.data._powerPS == 0 ? 0 : this.data._powerPS,
        this.data._schckeId = this.data._schckeId== 0 ? "None" : this.data._schckeId,
        this.data._tsn = this.data._tsn== 0 ? "None" : this.data._tsn,
        this.data._version = this.data._version== "0" ? "None" : this.data._version,
        this.data._gearingTypeId= this.data._gearingTypeId== "0" ? "M" : this.data._gearingTypeId,
        this.data._noOfSeats= this.data._noOfSeats== 0 ? 0 : this.data._noOfSeats,
        this.data._gears= this.data._gears== 0 ? 0 : this.data._gears,
        this.data._ccm= this.data._ccm== 0 ? 0 : this.data._ccm,
        this.data._cylinders= this.data._cylinders== 0 ? 0 : this.data._cylinders,
        this.data._weight= this.data._weight== 0 ? 0 : this.data._weight,
        this.data._consumptionmixed= this.data._consumptionmixed== 0 ? 0 : this.data._consumptionmixed,
        this.data._consumptioncity= this.data._consumptioncity== 0 ? 0 : this.data._consumptioncity,
        this.data._type= this.data._type== 0 ? 0 : this.data._type,
        this.data._consumptionhighway= this.data._consumptionhighway== 0 ? 0 : this.data._consumptionhighway,
        this.data._co2emissionmixed= this.data._co2emissionmixed== 0 ? 0 : this.data._co2emissionmixed,
        this.data._adtype= this.data._adtype== 0 ? "None" : this.data._adtype,
        this.data._emclass= this.data._emclass== 0 ? 0 : this.data._emclass,
        this.data._transm= this.data._transm== 0 ? 0 : this.data._transm,
        this.data._equi= this.data._equi== 0 ? 0 : this.data._equi,
        this.data._upholsteryid= this.data._upholsteryid== 0 ? 0 : this.data._upholsteryid,
        this.data._firstreg_mth= this.data._firstreg_mth== 0 ? 0 : this.data._firstreg_mth,
        this.data._firstreg_year= this.data._firstreg_year== 0 ? 0 : this.data._firstreg_year,
        this.data._queryString= this.data._queryString== "0" ? "None" : this.data._queryString
    }

    /**
     * Metodo Get che ritorna un oggetto JSON istanza di interfaccia DetailInterface contenente
     * info del Detail
     * @returns Dati del details
     */
    public getData():DetailInterface{
        return this.data;
    }
}