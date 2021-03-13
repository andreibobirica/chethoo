"use strict";
exports.__esModule = true;
var jquery_1 = require("jquery");
function sendAjaxRequest(_type, _url, _params, _callback) {
    var request = jquery_1["default"].ajax({
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
function sr(res) {
    console.log(res);
}
sendAjaxRequest("GET", "https://www.autoscout24.it/offerb2c/data/Mdw/StaticData/StaticDataJs", null, sr);
