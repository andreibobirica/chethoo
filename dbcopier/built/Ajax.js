export class Ajax {
    sendAjaxRequest(_type, _url, _params, _callback) {
        var request = $.ajax({
            type: _type,
            url: _url,
            data: _params,
            dataType: "json",
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
}
