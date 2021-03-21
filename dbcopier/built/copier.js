import { Ajax } from "./Ajax.js";
export class Copier {
    constructor() {
        this.ajaxController = new Ajax();
    }
    getStaticDataJSfrom24() {
        this.ajaxController.sendAjaxRequest("GET", "./dataDispatcher.php?staticDataJS", null, (res) => {
            //salvo i risultati
            this.staticDataJS = res;
            //stampo nel log
            this.printStaticDataJS();
            //manipolazione temporanea
            this.manipulation();
        });
    }
    printStaticDataJS() {
        console.log(this.staticDataJS);
    }
    manipulation() {
        console.log(this.staticDataJS.makes);
    }
    run() {
        //Prendo i dati statici dal DB 24
        this.getStaticDataJSfrom24();
        //Ulteriori istruzioni eseguite nel suo callback
    }
}
