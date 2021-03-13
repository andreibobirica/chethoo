import { Manager } from "./Manager.js";
class Prova2 {
    constructor() {
        this.num = 10;
    }
    getNum() {
        return this.num;
    }
}
let m1 = new Manager("Matteo");
console.log("hellso");
console.log(m1);
export class Copier {
    sendAjaxRequest(_type, _url, _params, _callback) {
        var request = $.ajax({
            type: _type,
            url: _url,
            data: _params,
            contentType: 'json'
        });
        request.done(function (res) {
            _callback(res);
        });
        request.fail(function (jqXHR, textStatus) {
            console.error(jqXHR);
            _callback({ err: true, message: "Request failed: " + textStatus });
        });
    }
    mostraris(res) {
        console.log(res);
    }
    run() {
        //this.sendAjaxRequest("GET","https://www.autoscout24.it/offerb2c/data/Mdw/StaticData/StaticDataJs",null,cp.mostraris);
    }
}
let cp = new Copier();
cp.run();
