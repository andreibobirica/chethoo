export class Manager {
    job : string;
    constructor(managerName:string){
        this.job = "nothing";
    }
    getJob(){
        return this.job;
    }
}