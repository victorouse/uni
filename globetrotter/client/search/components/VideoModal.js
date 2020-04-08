var React = require('react/addons');
var Modal = require('./Modal');
var VideoPlayer = require('../../shared/VideoPlayer');
var StarRating = require('../../shared/StarRating');
var SaveButton = require('../../shared/SaveButton');

var VideoModal = React.createClass({

  getInitialState: function() {
    return {
      videoData: {}
    }
  },

  handleShow: function(result) {
    this.setState({
      videoData: result
    })

    this.refs.videoModal.show();
  },

  handleDisposeVideoPlayer: function() {
    this.refs.videoPlayer.disposePlayer();
  },

  render: function() {

    return (
      <Modal className='videoModal' ref='videoModal'
        videoId={this.state.videoData._id}
        title={this.state.videoData.title}
        afterClose={this.handleDisposeVideoPlayer}>

        <VideoPlayer ref='videoPlayer' video={this.state.videoData} />
        <div className='video-details'>
          <hr />
          <p><strong>Description</strong></p>
          <p className='description'>
            {this.state.videoData.description ?
              this.state.videoData.description : 'No description available.'}
          </p>
        </div>

      </Modal>
    );

  }

});

module.exports = VideoModal;
