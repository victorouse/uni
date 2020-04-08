var x = require('casper').selectXPath;
var casper = require('casper').create();
casper.options.viewportSize = {width: 1920, height: 921};
casper.start('http://en.wikipedia.org/wiki/Test_(assessment)');
casper.wait(1000);
casper.then(function() {
    this.captureSelector("screenshot1.png", "html");
});
casper.waitForSelector(x("//*[contains(text(), 'Test (assessment)')]"),
    function success() {
        this.test.assertExists(x("//*[contains(text(), 'Test (assessment)')]"));
      },
    function fail() {
        this.test.assertExists(x("//*[contains(text(), 'Test (assessment)')]"));
});
casper.waitForSelector(x("//a[normalize-space(text())='assessment']"),
    function success() {
        this.test.assertExists(x("//a[normalize-space(text())='assessment']"));
        this.click(x("//a[normalize-space(text())='assessment']"));
    },
    function fail() {
        this.test.assertExists(x("//a[normalize-space(text())='assessment']"));
});
casper.wait(1000);
casper.then(function() {
    this.captureSelector("screenshot2.png", "html");
});
casper.waitForSelector(x("//*[contains(text(), 'Educational assessment')]"),
    function success() {
        this.test.assertExists(x("//*[contains(text(), 'Educational assessment')]"));
      },
    function fail() {
        this.test.assertExists(x("//*[contains(text(), 'Educational assessment')]"));
});
casper.waitForSelector(x("//a[normalize-space(text())='documenting']"),
    function success() {
        this.test.assertExists(x("//a[normalize-space(text())='documenting']"));
        this.click(x("//a[normalize-space(text())='documenting']"));
    },
    function fail() {
        this.test.assertExists(x("//a[normalize-space(text())='documenting']"));
});
casper.wait(1000);
casper.then(function() {
    this.captureSelector("screenshot3.png", "html");
});
casper.waitForSelector(x("//*[contains(text(), 'What is a document?')]"),
    function success() {
        this.test.assertExists(x("//*[contains(text(), 'What is a document?')]"));
      },
    function fail() {
        this.test.assertExists(x("//*[contains(text(), 'What is a document?')]"));
});
casper.waitForSelector("form#searchform input[name='search']",
    function success() {
        this.test.assertExists("form#searchform input[name='search']");
        this.click("form#searchform input[name='search']");
    },
    function fail() {
        this.test.assertExists("form#searchform input[name='search']");
});
casper.waitForSelector("input[name='search']",
    function success() {
        this.sendKeys("input[name='search']", "google");
    },
    function fail() {
        this.test.assertExists("input[name='search']");
});
casper.wait(1000);
casper.then(function() {
    this.captureSelector("screenshot4.png", "html");
});
casper.waitForSelector("form#searchform input[name='search']",
    function success() {
        this.test.assertExists("form#searchform input[name='search']");
        this.click("form#searchform input[name='search']");
    },
    function fail() {
        this.test.assertExists("form#searchform input[name='search']");
});
casper.waitForSelector("input[name='search']",
    function success() {
        this.sendKeys("input[name='search']", "
");
    },
    function fail() {
        this.test.assertExists("input[name='search']");
});
casper.waitForSelector("form#searchform button[name='button']",
    function success() {
        this.test.assertExists("form#searchform button[name='button']");
        this.click("form#searchform button[name='button']");
    },
    function fail() {
        this.test.assertExists("form#searchform button[name='button']");
});
casper.wait(1000);
casper.then(function() {
    this.captureSelector("screenshot5.png", "html");
});
casper.waitForSelector(x("//*[contains(text(), 'Google Inc.')]"),
    function success() {
        this.test.assertExists(x("//*[contains(text(), 'Google Inc.')]"));
      },
    function fail() {
        this.test.assertExists(x("//*[contains(text(), 'Google Inc.')]"));
});
casper.wait(1000);
casper.then(function() {
    this.captureSelector("screenshot6.png", "html");
});

casper.run(function() {this.test.renderResults(true);});
