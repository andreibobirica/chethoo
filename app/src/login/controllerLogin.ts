import {Ajax} from "../Ajax.js"


export class ControllerLogin{

    //Controller per fare richieste Ajax HTTP
    private ajaxController : Ajax;

    /**
     * Costruttore, qui viene solamente inizializzato il controller Ajax
     */
     public constructor(){
        this.ajaxController = new Ajax();
    }
     
    public printView():void{
        console.log("cia");
        
        this.ajaxController.sendAjaxRequest("GET","http://localhost/core/view/login/login.php",null,
        (res: any): void => {
            $(document.body).html(res);
        });
    }
}

let cl : ControllerLogin = new ControllerLogin();
window.onload = ()=>{cl.printView()};