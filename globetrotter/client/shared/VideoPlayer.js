var React = require('react/addons');

var VideoPlayer = React.createClass({

  shouldComponentUpdate: function(nextProps) {
    return nextProps.video !== this.props.video;
  },

  componentDidMount: function() {
    if(!(_.isEmpty(this.props.video))) {
      this.initPlayer();
    }
  },

  initPlayer: function() {
    var videoDiv = jQuery('<video></video>')
      .attr('id', this.props.video._id)
      .attr('height', '300px')
      .attr('width', '100%')
      .attr('controls', '')
      .attr('preload', 'auto')
      .addClass('video-js vjs-default-skin vjs-big-play-centered');

    var sourceDiv = jQuery('<source></source>')
      .attr('src', this.props.video.url_mp4);

    var extensionPattern = /\.[0-9a-z]+$/i;
    var sourceType = (this.props.video.url_mp4).match(extensionPattern)[0];

    if (sourceType == '.mp4') {
      jQuery(sourceDiv).attr('type', 'video/mp4');
    } else if (sourceType == '.flv') {
      jQuery(sourceDiv).attr('type', 'video/flv');
    }

    jQuery(videoDiv).append(sourceDiv);
    jQuery('.video-player').append(videoDiv);
    videojs(this.props.video._id);
  },

  componentDidUpdate: function() {
    console.log(this.props);
    if (jQuery('.video-player').children().length > 0) {
      jQuery('.video-player').empty();
    }

    this.initPlayer();
  },

  disposePlayer: function() {
    jQuery('.video-player').empty();
  },

  render: function() {

    return (
        <div className='video-player'></div>
    );

  }
});

module.exports = VideoPlayer;
