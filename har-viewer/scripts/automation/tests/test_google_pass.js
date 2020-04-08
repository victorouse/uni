casper.start('http://www.google.com/', function() {
    this.test.assertTitle('Google', 'google homepage title is the one expected');
    this.test.assertExists('form[action="/search"]', 'main form is found');
    this.fill('form[action="/search"]', {
        q: 'foo'
    }, true);
});

casper.then(function() {
    this.test.assertTitle('foo - Google Search', 'google title is ok');
    this.test.assertUrlMatch(/q=foo/, 'search term has been submitted');
    this.test.assertEval(function() {
        return __utils__.findAll('h3.r').length >= 10;
    }, 'google search for "foo" retrieves 10 or more results');
});

casper.then(function() {
    casper.test.done(5);
    casper.test.renderResults(true, 0, "../../../test_logs/google.xml");
});
