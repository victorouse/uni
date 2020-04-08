// Instantiate
var casper = require('casper').create();
var page = require('webpage').create();
var x = require('casper').selectXPath;
var fs = require('fs');
var harCount = 0;
page.resources = [];
casper.options.viewportSize = {width: 640, height: 480};

// Include code for HAR files
phantom.injectJs('generic_har.js');

// Setup args
if (casper.cli.args.length === 0) {
    casper.echo('Usage: casperjs generate_har.js path_to_script').exit();
} else {
    var path = casper.cli.get(0);
}

// Start page load time
casper.on('load.started', function() {
    casper.echo('load started');
    page.startTime = new Date();
    
});

// Populate resources
casper.on('resource.requested', function(resource) {
    // casper.echo('resource requested');
    page.resources[resource.id] = {
        request: resource,
        startReply: null,
        endReply: null
    };
});

// Setup resources
casper.on('resource.received', function(resource) {
    // casper.echo('resource received');
    if (resource.stage === 'start') {
        page.resources[resource.id].startReply = resource;
    }

    if (resource.stage === 'end') {
        page.resources[resource.id].endReply = resource;
    }
});

// Generate HAR file when page is finished loading
casper.on('load.finished', function() {
    casper.echo('load finished');

    // Setup HAR information
    page.endTime = new Date(); 
    page.title = casper.getTitle();
    page.address = casper.getCurrentUrl();
    harCount++;

    // Create HAR
    har = createHAR(page.address, page.title, page.startTime, page.resources);
    json_har = JSON.stringify(har, undefined, 4);
    file = '../../har_files/'+page.endTime.toISOString()+'_'+harCount+'.har';
    fs.write(file, json_har, 'w');
    casper.echo('wrote HAR file: ' + file);

    // Take screenshot
    casper.capture('../../screens/'+page.endTime.toISOString()+'_'+harCount+'.png');
    casper.echo('took screenshot');
});

// Run script
phantom.injectJs(path);

casper.then(function() {
    casper.test.renderResults(true, 0, '../../test_logs/'+page.endTime.toISOString()+'_'+harCount+'.xml');
});

// Run main
casper.run(function() {
    casper.exit();
});
