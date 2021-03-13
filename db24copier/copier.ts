export {}
import $ from "jquery";
function sendAjaxRequest(_type: string, _url: string, _params: string, _callback: any) {
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
        console.error(jqXHR);
        _callback({ err: true, message: "Request failed: " + textStatus });
    });

}

function sr(res){
    console.log(res);
}

sendAjaxRequest("GET","https://www.autoscout24.it/offerb2c/data/Mdw/StaticData/StaticDataJs",null,sr);