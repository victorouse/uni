var React = require('react/addons');
var SearchPage = React.createFactory(require('../../client/search/components/Main'));

exports.index = function(req, res) {
  var markup = React.renderToString(SearchPage());
  var initialState = '';

  if (req.query.q) {
    var initialState = { query: req.query.q };
  }

  res.render('search', {
    title: 'Search',
    markup: markup,
    state: initialState
  });
}
