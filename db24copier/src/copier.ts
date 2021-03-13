import $ from "jquery";

export class Copier {
    private sendAjaxRequest(_type: string, _url: string, _params: string, _callback: any) {

        var request = $.ajax({
            type: _type,
            url: _url,
            data: _params,
            contentType: 'json'
        });
        request.done(function(res) {
            _callback(res);
        });
        request.fail(function(jqXHR, textStatus) {
            console.error(jqXHR)
            _callback({ err: true, message: "Request failed: " + textStatus });
        });
    }
    private mostraris(res){
        console.log(res);
    }

    public run(){
        this.sendAjaxRequest("GET","",null,cp.mostraris);
    }
}

let cp : Copier = new Copier();
cp.run();


