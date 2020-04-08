var x = require('casper').selectXPath;
var casper = require('casper').create();
casper.options.viewportSize = {width: 1920, height: 921};
casper.start('https://learn.uq.edu.au/');
casper.then(function() {
    this.mouse.click(1010, 275);
});
casper.waitForSelector(x("//*[contains(text(), 'Learn.UQ')]"),
    function success() {
        this.test.assertExists(x("//*[contains(text(), 'Learn.UQ')]"));
      },
    function fail() {
        this.test.assertExists(x("//*[contains(text(), 'Learn.UQ')]"));
});

casper.then(function() {
    // weird
});
