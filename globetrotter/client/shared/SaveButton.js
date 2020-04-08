var React = require('react/addons');

var SaveButton = React.createClass({

  handleClick: function() {
    // TODO: save to mongodb
  },

  render: function() {

    var className = this.props.isSaved ? 'fa fa-heart is-saved' : 'fa fa-heart';

    return (
      <div className='save-button'>
        <div className='btn btn-primary btn-circle'>
          <i className={className} onClick={this.handleClick}></i>
        </div>
      </div>
    );
  }

});

module.exports = SaveButton;
