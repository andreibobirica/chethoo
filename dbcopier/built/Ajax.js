export class Ajax {
    sendAjaxRequest(_type, _url, _data, _callback) {
        var request = $.ajax({
            type: _type,
            url: _url,
            data: _data,
            dataType: "json",
            contentType: 'application/json'
        });
        request.done(function (res) {
            _callback(res);
        });
        request.fail(function (jqXHR, textStatus) {
            console.error(jqXHR);
            _callback({ err: true, message: "Request failed: " + textStatus });
        });
    }
}
