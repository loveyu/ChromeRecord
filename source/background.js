var server_url = "http://127.0.0.1/chrome_record.php";
(function () {
    var url_rule = {urls: ["<all_urls>"]};
    var queue = [];
    var counter = 0;

    var log_func = window.log_func = function (url, detail, type, requestId) {
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
            }, 1500);
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
})();


var page_record_obj = {
    record_tab: function (tab, page_type, log_data) {
        if (!page_record_obj.filter_tab(tab)) {
            return;
        }
        page_record_obj.post_data(tab.id, tab.url, tab.title, page_type, log_data);
    },
    record_data: function (tab, page_type, log_data) {
        if (!page_record_obj.filter_tab(tab)) {
            return;
        }
        page_record_obj.post_data(tab.id, tab.url, tab.title, page_type, log_data);
    },
    filter_tab: function (tab) {
        if (tab.url == "chrome://newtab/") {
            return false;
        } else if (tab.url == "") {
            return false;
        } else {
            return true;
        }
    },
    post_data: function (tab_id, url, title, page_type, more) {
        var detail = {
            url: url,
            title: title,
            content_type: page_type,
            tab_id: tab_id,
            datetime: (new Date()).getTime()
        };
        if (typeof more == "object") {
            for (var i in more) {
                if (more.hasOwnProperty(i)) {
                    detail[i] = more[i];
                }
            }
        }
        log_func(url, detail, "onView", tab_id);
    }
};

//页面数据追踪
(function () {
    chrome.runtime.onMessage.addListener(
        function (request, sender, sendResponse) {
            switch (request.greeting) {
                case "cr_no_doctype":
                    page_record_obj.record_tab(sender.tab, request.page_type, request.log_data);
                    break;
                case "cr_log_data":
                    page_record_obj.record_data(sender.tab, request.page_type, request.log_data);
                    break;
            }
        });
    chrome.tabs.onUpdated.addListener(function (tabID, updateInfo, tab) {
        if (updateInfo.status == "complete") {
            if (/^view-source:/i.test(tab.url)) {
                page_record_obj.record_tab(tab, 'view-source');
            }
        }
    });
})();
