var React = require('react/addons');
var HomePage = React.createFactory(require('../../client/home/components/Main'));
var api = require('./api');

/**
 * GET /
 * Home page.
 */
exports.index = function(req, res) {
  var markup = React.renderToString(HomePage());
  var initialState = { recommended: [] };

  res.render('home', {
    title: 'Home',
    markup: markup,
    state: initialState
  });

};
