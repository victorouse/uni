var x = require('casper').selectXPath;
var casper = require('casper').create();
casper.options.viewportSize = {width: 1920, height: 921};
casper.start('https://www.google.com/');
casper.waitForSelector(x("//img[@src='/images/srpr/logo4w.png']"),
    function success() {
        this.test.assertExists(x("//img[@src='/images/srpr/logo4w.png']"));
      },
    function fail() {
        this.test.assertExists(x("//img[@src='/images/srpr/logo4w.png']"));
});
casper.waitForSelector("form[name=gbqf] input[name='q']",
    function success() {
        this.test.assertExists("form[name=gbqf] input[name='q']");
        this.click("form[name=gbqf] input[name='q']");
    },
    function fail() {
        this.test.assertExists("form[name=gbqf] input[name='q']");
});
casper.waitForSelector("input[name='q']",
    function success() {
        this.sendKeys("input[name='q']", "testing");
    },
    function fail() {
        this.test.assertExists("input[name='q']");
});
casper.waitForSelector(x("//*[contains(text(), 'Software testing')]"),
    function success() {
        this.test.assertExists(x("//*[contains(text(), 'Software testing')]"));
      },
    function fail() {
        this.test.assertExists(x("//*[contains(text(), 'Software testing')]"));
});
casper.then(function() {
    this.mouse.down(202, 143);
    this.mouse.move(411, 145);
    this.mouse.up(411, 145);
});
casper.waitForSelector(x("//*[contains(text(), 'From Wikipedia, the free encyclopedia')]"),
    function success() {
        this.test.assertExists(x("//*[contains(text(), 'From Wikipedia, the free encyclopedia')]"));
      },
    function fail() {
        this.test.assertExists(x("//*[contains(text(), 'From Wikipedia, the free encyclopedia')]"));
});

casper.run(function() {this.test.renderResults(true);});
