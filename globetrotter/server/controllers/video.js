var React = require('react/addons');
var VideoPage = React.createFactory(require('../../client/video/components/Main'));

var api = require('./api');
var secrets = require('../../config/secrets');

/**
 * GET /video/:id
 * Permalink video page.
 */
exports.index = function(req, res) {
  var markup = React.renderToString(VideoPage());
  var initialState = '';

  var videoId = req.params.id;

  api.getVideoById(videoId).then(function(result) {
    var initialState = { video: result.video };

    res.render('video', {
      title: initialState.video._source.title,
      markup: markup,
      state: initialState
    });
  });
};
