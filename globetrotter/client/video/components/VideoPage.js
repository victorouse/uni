var React = require('react/addons');
var VideoPlayer = require('../../shared/VideoPlayer');
var SaveButton = require('../../shared/SaveButton');
var StarRating = require('../../shared/StarRating');

var VideoPage = React.createClass({

  getInitialState: function() {
    return {
      videoTitle: '',
      videoDescription: ''
    }
  },

  componentDidMount: function() {
    this.setState({
      video: this.props.video,
      videoTitle: this.props.video.title,
      videoDescription: this.props.video.description
    })
  },

  render: function() {

    return (
      <div>
        <div className='row video-heading'>
          <h1>{this.state.videoTitle}</h1>
          <hr />
        </div>
        <div className='row video-player'>
          <VideoPlayer video={this.state.video} />
        </div>
        <div className='row video-details'>
          <hr />
          <p className='lead'><strong>Description</strong></p>
          <p className='description'>
            {this.state.videoDescription ? this.state.videoDescription
              : 'No description available.'}
          </p>
        </div>
      </div>
    );
  }

});

module.exports = VideoPage;
