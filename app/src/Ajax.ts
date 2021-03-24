export class Ajax{
    public sendAjaxRequest(_type: string, _url: string, _data: string, _callback: any) {
        var request = $.ajax({
            type: _type,
            url: _url,
            data: _data
        });
        request.done(function(res : object) {
            _callback(res);
        });
        request.fail(function(jqXHR, textStatus) {
            console.error(jqXHR)
            _callback({ err: true, message: "Request failed: " + textStatus });
        });
    }

    public sendPostRequest(_url: string, _data: object, _callback: any){
        let request = $.ajax({
            "url": _url,
            "method": "POST",
            "data": _data
        });

        request.done(function(res : object) {
            _callback(res);
        });
        request.fail(function(jqXHR, textStatus) {
            console.error(jqXHR)
            _callback({ err: true, message: "Request failed: " + textStatus });
        });
    }
}