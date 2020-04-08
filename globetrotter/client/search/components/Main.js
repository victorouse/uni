var React = require('react/addons');
var SearchPage = require('./SearchPage');
var SearchCollection = require('../../collections/SearchCollection');

var backboneMixin = require('backbone-react-component');


var Main = React.createClass({
  mixin: backboneMixin,

  getInitialState: function(props) {
    props = props || this.props;

    return {
      query: props.query,
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
        <SearchPage mapElement={this.state.mapElement} query={this.state.query} collection={new SearchCollection.Collections.Videos()} />
      )
    } else {
      return  (
        <SearchPage query={this.state.query} collection={new SearchCollection.Collections.Videos()} />
      )
    }
  }
})

module.exports = Main;
