//var graph = require('fbgraph');
var agent = require('superagent-promise');
var request = require('superagent');
var Promise = require('bluebird');
var qs = require('qs');
var _ = require('lodash');
var graph = require('fbgraph');
var async = require('async');

var secrets = require('../../config/secrets.js');

/**
 * Get a video by ID.
 *
 * GET <ELASTIC_REST>/video/:id
 */
exports.getVideoById = function(id) {
  return new Promise(function(resolve) {
    var searchType = '/video/id/';
    var uri = secrets.elasticRestEndpoint + searchType + id;

    request.get(uri).end(function(error, res) {
      resolve({
        video: res.body[0]
      });
    });

  });
};

/**
 * Get a recommended videos.
 *
 * GET <ELASTIC_REST>/video/recommended
 */
exports.getRecommendedVideos = function(req, res, next) {
  return new Promise(function(resolve) {
    var searchType = '/video/recommended';
    var uri = secrets.elasticRestEndpoint + searchType;

    request.get(uri).end(function(error, res) {
      resolve({
        video: res.body
      });
    });

  });
};

/**
 * Get a video by text query (full text search).
 *
 * GET <ELASTIC_REST>/video/q?video=<query>
 */
exports.getVideoByQuery = function(req, res) {
  var queryString = qs.parse(req.query.query);

  var textQuery = queryString.q ? queryString.q : '';
  var geoQuery = queryString.gq ? qs.parse(queryString.gq) : '';

  var query = {
    q: textQuery,
    gq: geoQuery.gq,
    likes: req.query.likes
  };

  console.log(query);

  var searchType = '/video';
  var uri = secrets.elasticRestEndpoint + searchType;

  agent('GET', uri)
    .send(query)
    .end()
    .then(function(result) {
      res.send(result.body);
    });
};

/**
 * Get a video by coordinates (longitude, latitude)
 * and distance from this point.
 *
 * GET <ELASTIC_REST>/video/:lon/:lat/:dist
 */
exports.getVideoByMap = function(req, res) {
  var lon = req.params.lon;
  var lat = req.params.lat;
  var dist = req.params.dist;
  var query = [lon, lat, dist];


  var searchType = '/video/map/';
  var uri = secrets.elasticRestEndpoint + searchType + query.join('/');

  agent('GET', uri).end()
    .then(function(result) {
      res.send(result.body);
    });
};

/**
 * GET /api/facebook/likes
 */
exports.getFacebookLikes = function(req, res, next) {
  var token = _.find(req.user.tokens, { kind: 'facebook' });
  graph.setAccessToken(token.accessToken);

  async.parallel({
    getMe: function(done) {
      graph.get(req.user.facebook, function(err, me) {
        done(err, me);
      });
    },
    getMyLikes: function(done) {
      graph.get(req.user.facebook + '/likes', function(err, likes) {
        done(err, likes.data);
      });
    }
  },
  function(err, results) {
    if (err) return next(err);
    var likes = results.getMyLikes;
    res.send(likes);
  });
};

