//HAR standard include
if (!Date.prototype.toISOString) {
    Date.prototype.toISOString = function () {
        function pad(n) { return n < 10 ? '0' + n : n; }
        function ms(n) { return n < 10 ? '00'+ n : n < 100 ? '0' + n : n }
        return this.getFullYear() + '-' +
            pad(this.getMonth() + 1) + '-' +
            pad(this.getDate()) + 'T' +
            pad(this.getHours()) + ':' +
            pad(this.getMinutes()) + ':' +
            pad(this.getSeconds()) + '.' +
            ms(this.getMilliseconds()) + 'Z';
    }
}

function createHAR(address, title, startTime, resources)
{
    var entries = [];

    resources.forEach(function (resource) {
        var request = resource.request,
            startReply = resource.startReply,
            endReply = resource.endReply;

        if (!request || !startReply || !endReply) {
            return;
        }

        entries.push({
            startedDateTime: request.time.toISOString(),
            time: endReply.time - request.time,
            request: {
                method: request.method,
                url: request.url,
                httpVersion: "HTTP/1.1",
                cookies: [],
                headers: request.headers,
                queryString: [],
                headersSize: -1,
                bodySize: -1
            },
            response: {
                status: endReply.status,
                statusText: endReply.statusText,
                httpVersion: "HTTP/1.1",
                cookies: [],
                headers: endReply.headers,
                redirectURL: "",
                headersSize: -1,
                bodySize: startReply.bodySize,
                content: {
                    size: startReply.bodySize,
                    mimeType: endReply.contentType
                }
            },
            cache: {},
            timings: {
                blocked: 0,
                dns: -1,
                connect: -1,
                send: 0,
                wait: startReply.time - request.time,
                receive: endReply.time - startReply.time,
                ssl: -1
            },
            pageref: address
        });
    });

    return {
        log: {
            version: '1.2',
            creator: {
                name: "PhantomJS",
                version: phantom.version.major + '.' + phantom.version.minor +
                    '.' + phantom.version.patch
            },
            pages: [{
                startedDateTime: startTime.toISOString(),
                id: address,
                title: title,
                pageTimings: {
                    onLoad: page.endTime - page.startTime
                }
            }],
            entries: entries
        }
    };
}


/*
//UQ Custom stuff
//Override onLoadStarted
page.resources = [];
var fs = require('fs');
var harcnt = 0;

if(page.onLoadStarted)
{
	//console.log("Yep - " + page.onLoadStarted);
	var origonLoadStarted = page.onLoadStarted;
	page.onLoadStarted = function() {
		origonLoadStarted();
		page.startTime = new Date();
	}
	//console.log("Yep - " + page.onLoadStarted);
}
else
{
	page.onLoadStarted = function() {
		page.startTime = new Date();
	}
}

var harFinish = function () {
	    page.endTime = new Date();
	    page.title = page.evaluate(function () {
		return document.title;
	    });
	    
	    harcnt ++;
	    
	    har = createHAR(page.address, page.title, page.startTime, page.resources);
	    //console.log(JSON.stringify(har, undefined, 4));
	    fs.write('../htdocs/screenshots/'+run_id+'_'+harcnt+'.har', JSON.stringify(har, undefined, 4), "w");
	    page.resources = [];
    };



if(page.onLoadFinished)
{
	var origonLoadFinished = page.onLoadFinished;
	page.onLoadFinished = function() {
		origonLoadFinished();
		harFinish();
	}
}
else
{
	page.onLoadFinished = function() {
		harFinish();
	};
}

//No safety on these ones
page.onResourceRequested = function (req) {
page.resources[req.id] = {
    request: req,
    startReply: null,
    endReply: null
};
};

page.onResourceReceived = function (res) {
if (res.stage === 'start') {
    page.resources[res.id].startReply = res;
}
if (res.stage === 'end') {
    page.resources[res.id].endReply = res;
}
};
*/
