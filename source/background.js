var url_rule = {urls: ["<all_urls>"]};
var server_url = "http://127.0.0.1/chrome_record.php";
var queue = [];
var counter = 0;

var log_func = function (url, detail, type, requestId) {
	if (url == server_url) {
		return;
	}
	var obj = {url: url, type: type, requestId: requestId, detail: detail};
	queue.push(obj);
	if (counter < 1) {
		setTimeout(function () {
			counter--;
			if (queue.length > 0) {
				var tmp = queue;
				queue = [];
				var req = new XMLHttpRequest();
				req.open("POST", server_url, true);
				req.send(JSON.stringify(tmp));
			}
		}, 1000);
		counter++;
	}
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