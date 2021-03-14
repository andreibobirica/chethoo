import {Ajax} from "./Ajax.js"
export class Copier {
    private staticDataJS : object;
    private ajaxController : Ajax;

    public constructor(){
        this.ajaxController = new Ajax();
    }

    private getStaticDataJSfrom24(){
        this.ajaxController.sendAjaxRequest("GET","./dataDispatcher.php?staticDataJS",null,
        (res: object): void => {
            //salvo i risultati
            this.staticDataJS = res;
            //stampo nel log
            this.printStaticDataJS();

            //manipolazione temporanea
            this.manipulation();
        });
    }

    private printStaticDataJS(){
        console.log(this.staticDataJS);
    }

    private manipulation(){
        console.log(this.staticDataJS.makes);
    }



    public run(){
        //Prendo i dati statici dal DB 24
        this.getStaticDataJSfrom24();
        //Ulteriori istruzioni eseguite nel suo callback
    }
}
