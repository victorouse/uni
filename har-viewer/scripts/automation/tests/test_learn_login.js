casper.start('https://learn.uq.edu.au/');
casper.waitForSelector("input[name='user_id']",
    function success() {
        this.sendKeys("input[name='user_id']", "s4264246");
    },
    function fail() {
        this.test.assertExists("input[name='user_id']");
});
casper.waitForSelector("input[name='password']",
    function success() {
        this.sendKeys("input[name='password']", "Maddogg22");
    },
    function fail() {
        this.test.assertExists("input[name='password']");
});
casper.waitForSelector(x("//*[contains(text(), 'Learn.UQ')]"),
    function success() {
        this.test.assertExists(x("//*[contains(text(), 'Learn.UQ')]"));
      },
    function fail() {
        this.test.assertExists(x("//*[contains(text(), 'Learn.UQ')]"));
});
casper.waitForSelector("form[name=login] input[type=submit][value='Login']",
    function success() {
        this.test.assertExists("form[name=login] input[type=submit][value='Login']");
        this.click("form[name=login] input[type=submit][value='Login']");
    },
    function fail() {
        this.test.assertExists("form[name=login] input[type=submit][value='Login']");
});

casper.then(function() {
    casper.test.done(2);
    // seems to not load next page without a then.. weird
});
