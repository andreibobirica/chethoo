import {Manager} from "./Manager.js";
class Prova2{
    private num : number = 10;
    public getNum() : number{
        return this.num;
    }
}

let m1 = new Manager("Matteo");

console.log("hellso");
console.log(m1);

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
        //this.sendAjaxRequest("GET","https://www.autoscout24.it/offerb2c/data/Mdw/StaticData/StaticDataJs",null,cp.mostraris);
    }
}

let cp : Copier = new Copier();
cp.run();
