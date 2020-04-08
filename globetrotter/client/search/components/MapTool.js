var React = require('react/addons');
var Accordion = require('react-bootstrap').Accordion;
var Panel = require('react-bootstrap').Panel;

var MapTool = React.createClass({
  
  getInitialState: function() {
    return {
      mapElement: this.props.mapElement,
      resized: false
    }
  },

  componentDidMount: function() {
    this.setState({
      mapElement: this.props.mapElement
    });
  },

  handleSearchFilter: function() {
    //var newActiveTools = this.state.mapElement.element.props.map.activeTools;
    this.props.onSearchFilter(this);
  },

  handleSelect: function(selectedKey) {
    this.setState({
      activeKey: selectedKey
    });
  },

  handlePanelToggle: function() {
    if (!this.state.resized) {
      setTimeout(function() {
        window.google.maps.event.trigger(window.gmaps, 'resize');
      }, 1000);

      this.setState({
        resized: true
      });
    }
  },

  render: function() {
    if (this.state.mapElement) {
      return (
        <div> 
          <hr />
          <button className='btn btn-primary btn-block' onClick={this.handleSearchFilter}>
            <i className='fa fa-map-marker'></i>
            Search 
          </button>
          <hr />
          <Accordion> 
            <Panel header='Map' eventKey='1' onClick={this.handlePanelToggle}>
              <div>
                <div className='map-wrapper'>
                  <div className='map-tool'>
                    {this.state.mapElement}
                  </div>
                </div>
              </div>
            </Panel>
          </Accordion>
        </div>
      );
    }

    return (
      <div>
        <button className='btn btn-primary btn-block' onClick={this.handleSearchFilter}>
          <i className='fa fa-map-marker'></i>
          Search 
        </button>
        <Accordion>
          <Panel header='Map' eventKey='1'>
            Map not available.
          </Panel>
        </Accordion>
      </div>
    );
  }

});

module.exports = MapTool;
