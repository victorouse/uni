var React = require('react/addons');

var MapToolBar = React.createClass({
  getInitialState: function() {
    return {
      activeTool: false,
    }
  },

  handleMarker: function(e) {
    this.setState({
      activeTool: 'marker'
    });
  },

  handleCircle: function(e) {
    this.setState({
      activeTool: 'circle'
    });
  },

  render: function() {
    var selectedClass = "btn btn-danger btn-sm btn-tool";
    var defaultClass = "btn btn-primary btn-sm btn-tool";

    return (
      <div className='map-toolbar'>
        <button className={this.state.activeTool == 'marker' ? selectedClass : defaultClass} onClick={this.handleMarker}>
          <i className='fa fa-map-marker'></i>
          Marker
        </button>

        <button className={this.state.activeTool == 'circle' ? selectedClass : defaultClass} onClick={this.handleCircle}>
          <i className='fa fa-circle-o'></i>
          Circle
        </button>
      </div>
    )
  }
});

module.exports = MapToolBar;
