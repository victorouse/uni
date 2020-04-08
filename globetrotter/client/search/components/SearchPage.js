var React = require('react/addons');
var SearchBar = require('./SearchBar');
var SearchTools = require('./SearchTools');
var Results = require('./Results');
var VideoModal = require('./VideoModal');
var request = require('superagent');

var backboneMixin = require('backbone-react-component');

var SearchPage = React.createClass({
  mixins: [backboneMixin],

  getInitialState: function() {
    return {
      query: this.props.query,
      likes: '', 
      mapElement: false
    }
  },

  handleSearch: function (query) {
    /*
    this.getCollection().fetch({
      data: { q: query },
      processData: true
    });
    */
  },

  handleGeoSearch: function (textQuery, geoQuery) {
    var query = {};
    var coords = this.props.mapElement.element.props.map.initialCenter;

    if (textQuery) {
      query.q = textQuery;
    }

    if (geoQuery) {
      query.gq = geoQuery;
    } else {
      var current = [{
        type: 'geo_query',
        lat: coords.A,
        lon: coords.F,
        radius: 1000000
      }];

      var geoData = {gq: current};
      var geoParam = jQuery.param(geoData);

      query.gq = geoParam; 
    }

    this.getCollection().fetch({
      data: { 
        query: query,
        likes: this.state.likes
      },
      processData: true
    });
  },

  handleSearchTools: function() {
    var activeTools = this.props.mapElement.element.props.map.activeTools;
    var searchText = this.refs.searchBar.state.inputValue;

    gQuery = [];

    for (var i = 0; i < activeTools.length; i++) {
      var lat = activeTools[i].props.initialCenter.lat();
      var lon = activeTools[i].props.initialCenter.lng();
      var radius = activeTools[i].props.initialRadius;

      var geoQuery = {
        type: 'geo_query',
        lat: lat,
        lon: lon,
        radius: radius
      };

      gQuery.push(geoQuery);
    }

    var geoData = {gq: gQuery};
    var geoParam = jQuery.param(geoData);

    this.handleGeoSearch(searchText, geoParam);
  },

  componentDidMount: function () {
    var self = this;
    var artificialLikes = [
      'Beer', 
      'Football', 
      'FIFA World Cup', 
      'Wine',
      'Sports',
      'Music',
      'Music Festival',
      'Pubs'
    ];

    if (this.state.query != '') {
      this.getCollection().fetch({
        data: { q: this.state.query },
        processData: true
      });
    }

    self.setState({
      mapElement: this.props.mapElement
    });

    request
      .get('api/facebook/likes')
      .end(function(err, likeResult) {
        var likeResult = JSON.parse(likeResult.text);
        var likes = [];

        likeResult.forEach(function(like) {
          likes.push(like.name);
        });

        likes = likes.concat(artificialLikes);
        likes = likes.join(',');

        self.setState({
          likes: likes
        });
      });
  },

  handleResultsTrigger: function(result) {
    this.refs.videoModal.handleShow(result);
  },

  render: function() {

    if (this.state.mapElement) {
      return (
        <div>
          <VideoModal ref='videoModal' />
          <div className='col-lg-8 col-lg-offset-2'>
            <div className='row search-bar'>
              <div id='greeting'>
                <h1 className='text-center'>Where are we going?</h1>
                <hr />
              </div>

              <SearchBar ref='searchBar' initialInput={this.state.query} onUserInput={this.handleSearch} />
            </div>
            <div className='row search-tools'>
              <SearchTools mapElement={this.state.mapElement} onData={this.handleSearchTools} />
              <hr />
            </div>
            <div className='row search-results'>
              <Results onResultsTrigger={this.handleResultsTrigger} data={this.props.collection} />
            </div>
          </div>
        </div>
      );
    }

    return (
      <div>
        <VideoModal ref='videoModal' />
        <div className='col-lg-8 col-lg-offset-2'>
          <div className='row'>
            <div id='greeting'>
              <h1 className='text-center'>Where are we going?</h1>
              <hr />
            </div>

            <SearchBar ref='searchBar' initialInput={this.state.query} onUserInput={this.handleUserInput} />
            <hr />
          </div>
          <div className='row'>
            <hr />
          </div>
          <div className='row'>
            <Results onResultsTrigger={this.handleResultsTrigger} data={this.props.collection} />
          </div>
        </div>
      </div>
    );

  }
});

module.exports = SearchPage;
