var chrome_page_bg = {
    is_running: false,
    run: function () {
        if (chrome_page_bg.is_running) {
            return;
        }
        chrome_page_bg.is_running = true;
        chrome_page_bg.start();
    },
    start: function () {
        window.addEventListener("hashchange", chrome_page_bg.event);
        chrome_page_bg.event();
    },
    event: function () {
        chrome_page_bg.send_message(location.href, document.title);
    },
    send_message: function (url, title) {
        chrome.runtime.sendMessage({
            greeting: "cr_log_data",
            page_type: document.contentType + "",
            log_data: {
                url: url,
                title: title,
                referrer: document.referrer,
                uuid: page_uuid
            }
        }, function (response) {
        });
    },
    load_event: function () {
        chrome_page_bg.run();
    }
};

function generateUUID() {
    var d = new Date().getTime();
    return 'xxxxxxxx-xxxx-4xxx-yxxx-xxxxxxxxxxxx'.replace(/[xy]/g, function (c) {
        var r = (d + Math.random() * 16) % 16 | 0;
        d = Math.floor(d / 16);
        return (c == 'x' ? r : (r & 0x7 | 0x8)).toString(16);
    });
}

var page_uuid = generateUUID();

(function () {
    var content_type = document.contentType + "";
    var load_event = function () {
        chrome_page_bg.load_event();
    };
    var no_doc_event = function (page_type) {
        //告知要记录当前页面事件
        chrome.runtime.sendMessage({
            greeting: "cr_no_doctype",
            page_type: page_type,
            log_data: {
                referrer: document.referrer,
                uuid: page_uuid
            }
        }, function (response) {
        });
    };
    if (document.doctype) {
        load_event();
    } else if (document.xmlEncoding || document.xmlEncoding) {
        no_doc_event(content_type);
    } else if (content_type == "text/html") {
        load_event();
    } else if (/^text\//.test(content_type)) {
        no_doc_event(content_type);
    } else if (document.getElementsByTagName('html').length > 0) {
        load_event();
    } else {
        no_doc_event(content_type);
    }
})();
