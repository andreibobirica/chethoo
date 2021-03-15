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
    _firstreg_mth:string,
    _firstreg_year:number,
    _queryString:string
}


export class Detail{
    //dati del Detail
    private data : DetailInterface;

    //costruttore del detail
    public constructor(detInt:DetailInterface){
        this.data = detInt;
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