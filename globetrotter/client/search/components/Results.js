var React = require('react/addons');
var Table = require('react-bootstrap').Table;
var Result = require('./Result');

var Results = React.createClass({

  handleVideoTrigger: function(result) {
    this.props.onResultsTrigger(result);
  },

  render: function() {
    var self = this;
    var results = [];

    if (this.props.data) {
      this.props.data.forEach(function(data, i) {
        results.push(data.attributes);
      });
    }

    var renderResults = results.map(function(result) {
      return <Result key={result._id} data={result} onVideoTrigger={self.handleVideoTrigger} />
    });

    return (
      <div>
        <Table striped>
          <tbody>
            {renderResults}
          </tbody>
        </Table>
      </div>
    );
  }

});

module.exports = Results;
