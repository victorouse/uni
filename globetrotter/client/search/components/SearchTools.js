var React = require('react/addons');
var SearchToolBar = require('./SearchToolBar');

var SearchTools = React.createClass({

  getInitialState: function() {
    return {
      mapElement: this.props.mapElement,
      resized: false
    }
  },

  componentWillReceiveProps: function(nextProps) {
    if (nextProps) {
      this.setState({
        mapElement: this.props.mapElement
      });
    }
  },

  handleData: function(searchToolBar) {
    this.props.onData(this);
  },

  render: function() {

    if (this.state.mapElement) {
      return (
        <div>
          <SearchToolBar mapElement={this.state.mapElement} onData={this.handleData} />
        </div>
      );
    }

    return (
      <div>
        <p>Map not available.</p>
      </div>
    );
  }
});

module.exports = SearchTools;
