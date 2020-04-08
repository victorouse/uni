var React = require('react/addons');
var HomePage = require('./HomePage');

var Main = React.createClass({

  getInitialState: function(props) {
    props = props || this.props;

    return {
      recommended: [],
      mapElement: props.children
    }
  },

  componentDidMount: function() {

    this.setState({
      mapElement: this.props.children
    });
  },

  componentWillReceiveProps: function(newProps, oldProps) {
    this.setState(this.getInitialState(newProps));
  },

  render: function() {

    if (this.state.mapElement) {
      return (
        <div>
          <HomePage mapElement={this.state.mapElement} recommended={this.state.recommended} />
        </div>
      )
    }

    return (
      <div>
        <HomePage recommended={this.state.recommended} />
      </div>
    )
  }
})

module.exports = Main;

