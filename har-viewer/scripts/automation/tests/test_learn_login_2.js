var x = require('casper').selectXPath;
var casper = require('casper').create();
casper.options.viewportSize = {width: 1920, height: 921};
casper.start('https://learn.uq.edu.au/');
casper.then(function() {
    this.mouse.down(910, 271);
    this.mouse.move(1015, 272);
    this.mouse.up(1015, 272);
});
casper.waitForSelector(x("//*[contains(text(), 'Learn.UQ')]"),
    function success() {
        this.test.assertExists(x("//*[contains(text(), 'Learn.UQ')]"));
      },
    function fail() {
        this.test.assertExists(x("//*[contains(text(), 'Learn.UQ')]"));
});
casper.waitForSelector("form[name=login] input[name='user_id']",
    function success() {
        this.test.assertExists("form[name=login] input[name='user_id']");
        this.click("form[name=login] input[name='user_id']");
    },
    function fail() {
        this.test.assertExists("form[name=login] input[name='user_id']");
});
casper.waitForSelector("input[name='user_id']",
    function success() {
        this.sendKeys("input[name='user_id']", "s4264246");
    },
    function fail() {
        this.test.assertExists("input[name='user_id']");
});
casper.waitForSelector("form[name=login] input[name='password']",
    function success() {
        this.test.assertExists("form[name=login] input[name='password']");
        this.click("form[name=login] input[name='password']");
    },
    function fail() {
        this.test.assertExists("form[name=login] input[name='password']");
});
casper.waitForSelector("input[name='password']",
    function success() {
        this.sendKeys("input[name='password']", "Maddogg22
");
    },
    function fail() {
        this.test.assertExists("input[name='password']");
});
casper.waitForSelector("form[name=login] input[type=submit][value='Login']",
    function success() {
        this.test.assertExists("form[name=login] input[type=submit][value='Login']");
        this.click("form[name=login] input[type=submit][value='Login']");
    },
    function fail() {
        this.test.assertExists("form[name=login] input[type=submit][value='Login']");
});

casper.run(function() {this.test.renderResults(true);});
