export class Detail {
    //costruttore del detail
    constructor(detInt) {
        this.data = detInt;
    }
    /**
     * Metodo Get che ritorna un oggetto JSON istanza di interfaccia DetailInterface contenente
     * info del Detail
     * @returns Dati del details
     */
    getData() {
        return this.data;
    }
}
