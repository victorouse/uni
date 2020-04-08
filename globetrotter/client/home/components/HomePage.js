var React = require('react/addons');
var Button = require('react-bootstrap').Button;
var request = require('superagent');

var HomePage = React.createClass({

  getInitialState: function() {
    return {
      recommended: false,
      mapElement: false
    }
  },

  componentDidMount: function() {
    var self = this;
    var recommended = [];
    var coords = this.props.mapElement.element.props.map.initialCenter;
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

    request
      .get('api/facebook/likes')
      .end(function(err, likeResult) {
        var likeResult = JSON.parse(likeResult.text);
        var likes = [];

        likeResult.forEach(function(like) {
          likes.push(like.name);
        });

        likes = likes.concat(artificialLikes);
        console.log(likes);

        request
          .get('http://localhost:3001/video/recommended')
          .query({'lat': coords.A})
          .query({'lon': coords.F})
          .query({'likes': likes})
          .end(function(err, result) {
            recommended = JSON.parse(result.text);

            self.setState({
              recommended: recommended, 
              mapElement: self.props.mapElement
            });
        });
      });
  },

  render: function() {

    if ((this.state.recommended != false) && (this.state.mapElement != false)) {
      var map = this.state.mapElement;

      var items = this.state.recommended.map(function(item) {
        return (
          <li key={item._id}>
            <a href={'/video/' + item._source._id}>
              <img className='video-thumbnail img-thumbnail' src={item._source.url_cover} />
            </a>
          </li>
        )
      });


      return (
        <div>
          <div id='greeting' className='row'>
            <h1 className='text-center'>Start exploring.</h1>
            <a href='/search'>
              <Button bsStyle='primary' bsSize='large'>
                <i className='fa fa-search' />
                Explore
              </Button>
            </a>
          </div>
          <div className='row recommended'>
            <hr />
            <h3>Recommended for you</h3>
            <hr />
            <ul>
              {items}
            </ul>
          </div>
          <div className='row'>
            <div className='col-lg-12'>
              <hr />
              <h3>Your location</h3>
              <hr />
              {map}
            </div>
          </div>
        </div>
      );
    }

    return (
      <div></div>
    );
  }

});

module.exports = HomePage;
