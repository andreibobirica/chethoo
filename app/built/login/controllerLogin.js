import { Ajax } from "../Ajax.js";
export class ControllerLogin {
    /**
     * Costruttore, qui viene solamente inizializzato il controller Ajax
     */
    constructor() {
        this.ajaxController = new Ajax();
    }
    printView() {
        console.log("cia");
        this.ajaxController.sendAjaxRequest("GET", "http://localhost/core/view/login/login.php", null, (res) => {
            $(document.body).html(res);
        });
    }
}
let cl = new ControllerLogin();
window.onload = () => { cl.printView(); };
