var React = require('react/addons');
var VideoPage = require('./VideoPage');

var Main = React.createClass({

  getInitialState: function(props) {
    props = props || this.props;

    return {
      video: props.video
    }
  },

  componentWillReceiveProps: function(newProps, oldProps) {
    this.setState(this.getInitialState(newProps));
  },

  render: function() {

    return (
      <VideoPage video={this.state.video} />
    )
  }
})

module.exports = Main;

