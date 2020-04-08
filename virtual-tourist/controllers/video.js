var secrets = require('../config/secrets');
var async = require('async');
var request = require('request');
var _ = require('lodash');
var ejs = require('elastic.js');
var esq = require('esq');
var qs = require('qs');
var client = require('../client');

/*
 * More docs: http://victorouse.github.io/globetrotter-docs/video/#rest-endpoints
 */

/**
 * Get a video by ID.
 * 
 * @param id - video id (query param)
 * @returns JSON video object
 */
exports.getVideoById = function(req, res) {
  client.search({
    index: 'vtvideo',
    type: 'vtvideo',
    body: ejs.Request()
      .query(
          ejs.IdsQuery(req.params.id))
  }).then(function (resp) {
    var hits = resp.hits.hits;
    res.send(hits);
  }, function (err) {
    console.trace(err.message);
  });
};

/**
 * Get video by custom query.
 *
 * @param q - text query (query body)
 * @param gq - geo query (query body)
 */
exports.getVideoByQuery = function(req, res) {
  // Extract query string parameters
  var textQuery = req.body.q;
  var geoQuery = req.body.gq;
  var likes = likes ? req.body.likes.split(','): '';

  // Start elastic query
  var body = new esq();

  // Build query
  if (textQuery) {
    body.query('query', 'function_score', 'query',  'filtered', 'query', 'multi_match', 'query', textQuery);
    body.query('query', 'function_score', 'query',  'filtered', 'query', 'multi_match', 'type', 'most_fields');
    body.query('query', 'function_score', 'query',  'filtered', 'query', 'multi_match', 'fields', ['title', 'description']);
  } else {
    body.query('query', 'function_score', 'query',  'filtered', 'query', { match_all: {} });
  }

  // Make sure coords exist
  body.query('query', 'function_score', 'query',  'filtered', 'filter', 'and', ['filters'], 'exists', { field: 'coords'});

  if (geoQuery) {
    for (var i = 0; i < geoQuery.length; i++) {
      // Get each geo query paramaters
      var lon = parseFloat(geoQuery[i].lon);
      var lat = parseFloat(geoQuery[i].lat);
      var dist = parseFloat(geoQuery[i].radius);

      // Override user location
      if (geoQuery.length > 1) {
        body.query('query', 'function_score', 'query',  'filtered', 'filter', 'or', ['filters'], 
          { geo_distance: { coords: [lat, lon], distance: dist }});
      } else {
        // User location
        body.query('query', 'function_score', 'query',  'filtered', 'filter', 'and', ['filters'], 
          { geo_distance: { coords: [lat, lon], distance: 500000 }});

        // Scale by distance
        body.query('query', 'function_score', ['functions'], 'gauss', 'coords', { origin: [lat, lon], offset: '250000', scale: '250000' });
      }
    }
  }

  // Add likes
  for (var i = 0; i < likes.length; i++) {
    var like = likes[i].toLowerCase();
    body.query('query', 'function_score', ['functions'], { filter: { term: { 'title': like }}, weight: 2 });
    body.query('query', 'function_score', ['functions'], { filter: { term: { 'description': like }}, weight: 2 });
  }

  // Result return size
  body.query('size', 50);

  // DEBUG
  console.log(JSON.stringify(body.getQuery(), null, 2));

  // Make request
  client.search({
    index: 'vtvideo',
    type: 'vtvideo',
    body: body.getQuery()
  }).then(function (resp) {
    var hits = resp.hits.hits;
    res.send(hits);
  }, function (err) {
    console.trace(err.message);
  });
};

/** DEPRECATED **/
exports.getVideoByGeoDistance = function(req, res) {
  // Extract query string parameters
  var lon = parseFloat(req.params.lon);
  var lat = parseFloat(req.params.lat);
  var dist = parseInt(req.params.dist);

  // Make request
  client.search({
    index: 'vtvideo',
    type: 'vtvideo',
    // TODO: switch to esq
    body: ejs.Request()
      .query(
        ejs.FilteredQuery(
          ejs.MatchAllQuery(),
          ejs.GeoDistanceFilter('coords')
            .point(ejs.GeoPoint([lon, lat]))
            .distance(dist)
            .unit('km')))
  }).then(function (resp) {
    var hits = resp.hits.hits;
    res.send(hits);
  }, function (err) {
    console.trace(err.message);
  });
};

/**
 * Get recommended videos. 
 *
 * @param lon - text query (query param)
 * @param lat - geo query (query param)
 */
exports.getRecommendedVideos = function(req, res) {
  // Extract query string parameters
  var lon = parseFloat(req.query.lon);
  var lat = parseFloat(req.query.lat);
  var likes = req.query.likes.split(',');

  // Start elastic query
  var recommended = new esq();

  // Match all records
  recommended.query('query', 'function_score', 'query',  'filtered', 'query', { match_all: {} });

  // User location
  recommended.query('query', 'function_score', 'query',  'filtered', 'filter', 'and', ['filters'], { geo_distance: { coords: [lat, lon], distance: 500000 }});

  // Make sure coords exists
  recommended.query('query', 'function_score', 'query',  'filtered', 'filter', 'and', ['filters'], 'exists', { field: 'coords'});

  // Add likes
  likes.forEach(function(like) {
    like = like.toLowerCase();
    recommended.query('query', 'function_score', ['functions'], { filter: { term: { 'title': like }}, weight: 2 });
    recommended.query('query', 'function_score', ['functions'], { filter: { term: { 'description': like }}, weight: 2 });
  });

  // Scale by distance
  recommended.query('query', 'function_score', ['functions'], 'gauss', 'coords', { origin: [lat, lon], offset: '250000', scale: '250000' });

  // Results return size
  recommended.query('size', 12);

  // DEBUG
  console.log(JSON.stringify(recommended.getQuery(), null, 2));

  // Make request
  client.search({
    index: 'vtvideo',
    type: 'vtvideo',
    body: recommended.getQuery()
  }).then(function (resp) {
    var hits = resp.hits.hits;
    res.send(hits);
  }, function (err) {
    console.trace(err.message);
  });
};
