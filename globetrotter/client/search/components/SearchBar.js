var React = require('react/addons');
var Input = require('react-bootstrap').Input;

var SearchBar = React.createClass({

  getInitialState: function() {
    return {
      inputValue: this.props.initialInput
    }
  },

  handleChange: function() {
    var query = this.refs.query.getValue();

    this.setState({
      inputValue: query
    });

    this.props.onUserInput(query);
  },

  render: function() {

    return (
      <div>
        <Input type='search' value={this.state.inputValue} placeholder='Enter your search query here.' ref='query' onChange={this.handleChange} autoFocus />
      </div>
    );
  }

});

module.exports = SearchBar;
