var Backbone = require('backbone');

var SearchCollection = {
  Models: {},
  Collections: {},
  Views: {},
  Constants: {
    apiUrl: '/api/video'
  }
}

SearchCollection.Models.Video = Backbone.Model.extend({});

SearchCollection.Collections.Videos = Backbone.Collection.extend({
  model: SearchCollection.Models.Video,
  url: SearchCollection.Constants.apiUrl
});

module.exports = SearchCollection;
