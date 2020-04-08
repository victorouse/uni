var React = require('react/addons');
var moment = require('moment');
var Button = require('react-bootstrap').Button;

var Result = React.createClass({
  handleClick: function() {
    this.props.onVideoTrigger(this.props.data._source);
  },

  render: function() {

    return (
      <tr className='search-result'>
        <td className='col-lg-3'>
          <img className='url_cover img-thumbnail' src={this.props.data._source.url_cover} />
        </td>
        <td className='col-lg-9'>
          <div className='details'>
            <p className='title' onClick={this.handleClick.bind(null, this)}>
              {this.props.data._source.title}
            </p>
            <p className='uploaded'>{moment(this.props.data._source.upTime).format('LL')}</p>
          </div>
          <hr />
          <div className='description'>
            <p>{this.props.data._source.description ? this.props.data._source.description : 'No description available.'}</p>
          </div>
        </td>
      </tr>
    );
  }

});

module.exports = Result;
