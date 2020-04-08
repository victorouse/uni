/**
 * Module dependencies.
 */
var express = require('express');
var cookieParser = require('cookie-parser');
var bodyParser = require('body-parser');
var logger = require('morgan');
var errorHandler = require('errorhandler');
var methodOverride = require('method-override');
var cors = require('cors');

var _ = require('lodash');
var path = require('path');

/**
 * Elasticsearch client
 */
var client = require('./client');

/**
 * Controllers (route handlers).
 */
var videoController = require('./controllers/video');

/**
 * API keys and Passport configuration.
 */
var secrets = require('./config/secrets');

/**
 * Create Express server.
 */
var app = express();

app.use(cors({credentials: true}));

/**
 * Express configuration.
 */
app.set('port', process.env.PORT || 3001);
app.use(logger('dev'));
app.use(bodyParser.json());
app.use(bodyParser.urlencoded({ extended: true }));
app.use(methodOverride());
app.use(cookieParser());

/**
 * Video API routes.
 */
app.get('/video', videoController.getVideoByQuery);
app.get('/video/id/:id', videoController.getVideoById);
app.get('/video/map/:lon/:lat/:dist', videoController.getVideoByGeoDistance); // TODO change to query params?
app.get('/video/recommended', videoController.getRecommendedVideos);

/**
 * Error Handler.
 */
app.use(errorHandler());


/**
 * Start Express server.
 */
app.listen(app.get('port'), function() {
  console.log('Express server listening on port %d in %s mode', app.get('port'), app.get('env'));
});

/**
 * Test elasticsearch is running
 */
client.ping({
  // ping usually has a 100ms timeout
  requestTimeout: 1000,

  // undocumented params are appended to the query string
  hello: "elasticsearch"
}, function (error) {
  if (error) {
      console.trace('Elasticsearch cluster is down!');
  } else {
      console.log('Elasticsearch is running');
  }
});

module.exports = app;
