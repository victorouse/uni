var React = require('react/addons');
var GoogleMaps = require('react-googlemaps');

var MapToolBar = require('./MapToolBar');
var MapZoomRatio = require('../utils/MapZoomRatio');

var GoogleMapsAPI = window.google.maps;
var GoogleMap = GoogleMaps.Map;

var Marker = GoogleMaps.Marker;
var Circle = GoogleMaps.Circle;

var Map = React.createClass({
  getInitialState: function() {
    return {
      map: this.props.map
    }
  },

  handleRemove: function(key) {
    var oldTools = this.state.map.activeTools.slice();
    var newTools = [];

    for (var i = 0; i < oldTools.length; i++) {
      if (oldTools[i].key != key) {
        newTools.push(oldTools[i]);
      }
    }

    var newMap = this.state.map;
    newMap.activeTools = newTools;

    this.setState({
      map: newMap
    });
  },

  handleClick: function(e, map) {
    var activeTool = this.refs.mapToolBar.state.activeTool;
    var key = this.state.map.activeTools.length;

    var oldTools = this.state.map.activeTools;
    var newTools = oldTools.slice();

    if (activeTool == 'marker') {
      newTools.push(<Marker key={key} onRightClick={this.handleRemove.bind(this, key)} position={e.latLng} />);
    } else if (activeTool == 'circle') {
      var metresPerPixel = MapZoomRatio(map.zoom);
      var initialRadius = metresPerPixel * 50;

      newTools.push(<Circle key={key} onRightClick={this.handleRemove.bind(this, key)} editable={true} draggable={true} initialRadius={initialRadius} initialCenter={e.latLng} />);
    }

    var newMap = this.state.map;
    newMap.activeTools = newTools;

    this.setState({
      map: newMap
    });
  },

  render: function() {

    if (this.state.map.tools) {
      return (
        <div className='map'>
          <hr />
          <MapToolBar ref='mapToolBar' />
          <GoogleMap className='map-canvas'
            initialZoom={this.state.map.initialZoom}
            initialCenter={this.state.map.initialCenter}
            onClick={this.handleClick}>
              <Marker
                position={this.state.map.initialCenter} />
              {this.state.map.activeTools}
          </GoogleMap>
        </div>
      )
    }

    return (
      <div className='map'>
        <GoogleMap
          initialZoom={10}
          initialCenter={this.state.map.initialCenter}
          onClick={this.handleClick}>
            <Marker
              position={this.state.map.initialCenter} />
        </GoogleMap>
      </div>
    )
  }
});

module.exports = Map;

