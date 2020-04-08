var React = require('react/addons');
var MapTool = require('./MapTool');

var SearchToolBar = React.createClass({
  
  getInitialState: function() {
    return {
      mapElement: this.props.mapElement
    }
  },

  componentDidMount: function() {
    this.setState({
      mapElement: this.props.mapElement
    });
  },

  handleData: function(mapTool) {
    this.props.onData(this);
  },

  render: function() {

    return (
      <div>
        <MapTool ref='mapTool' mapElement={this.state.mapElement} onSearchFilter={this.handleData}/>
      </div>
    );
  }

});

module.exports = SearchToolBar;
