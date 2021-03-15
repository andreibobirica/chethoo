export class Ajax{
    public sendAjaxRequest(_type: string, _url: string, _data: string, _callback: any) {
        var request = $.ajax({
            type: _type,
            url: _url,
            data: _data,
            dataType: "json",
            contentType: 'application/json'
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