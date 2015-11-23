var url_rule = {urls: ["<all_urls>"]};
var server_url = "http://127.0.0.1/chrome_record.php";

var log_func = function (url, detail, type, requestId) {
	if (url == server_url) {
		return;
	}
	var obj = {url: url, type: type, requestId: requestId, detail: detail};
	setTimeout(function () {
		var req = new XMLHttpRequest();
		req.open("POST", server_url, true);
		req.send(JSON.stringify(obj));
	}, 0);
};

chrome.webRequest.onBeforeRequest.addListener(function (info) {
	log_func(info.url, info, "onBeforeRequest", info.requestId);
}, url_rule, ["requestBody"]);
chrome.webRequest.onBeforeSendHeaders.addListener(function (info) {
	log_func(info.url, info, "onBeforeSendHeaders", info.requestId);
}, url_rule, ["requestHeaders"]);
chrome.webRequest.onCompleted.addListener(function (info) {
	log_func(info.url, info, "onCompleted", info.requestId);
}, url_rule, ["responseHeaders"]);