var React = require('react');

var Modal = React.createClass({

    propTypes: {
        videoId: React.PropTypes.string,
        title: React.PropTypes.string,
        showOverlay: React.PropTypes.bool,
        beforeOpen: React.PropTypes.func,
        afterOpen: React.PropTypes.func,
        beforeClose: React.PropTypes.func,
        afterClose: React.PropTypes.func
    },

    getDefaultProps: function () {
        return {
            title: '',
            showOverlay: true
        }
    },

    getInitialState: function () {
        return {
            isVisible: false
        };
    },

    show: function () {
      this.setState({isVisible: true});
    },

    hide: function () {
      this.setState({isVisible: false});
    },

    componentWillUpdate: function (nextProps, nextState) {
      if (nextState.isVisible && this.props.beforeOpen) {
          this.props.beforeOpen();
      }

      if (!nextState.isVisible && this.props.beforeClose) {
          this.props.beforeClose();
      }
    },

    componentDidUpdate: function (prevProps, prevState) {
      if (!prevState.isVisible && this.props.afterOpen) {
          this.props.afterOpen();
      }

      if (prevState.isVisible && this.props.afterClose) {
          this.props.afterClose();
      }
    },

    render: function () {

      var visible = { display: this.state.isVisible ? 'block' : 'none' };
      var modal = this.state.isVisible ? 'modal fade in' : 'modal fade';
      var backdrop = this.state.isVisible ? 'modal-backdrop fade in' : 'modal-backdrop fade';
      var permalink = '/video/' + this.props.videoId;

      return (
        <div className={modal} style={visible}>
          <div className={backdrop} onClick={this.hide}></div>
          <div className='modal-dialog'>
            <div className='modal-content'>
              <div className='modal-header'>
                <h4 className='modal-title'>{this.props.title}</h4>
              </div>
              <div className='modal-body'>
                {this.props.children}
              </div>
              <div className='modal-footer'>
                <button type='button' className='btn btn-info btn-sm btn-circle pull-left'>
                  <a href={permalink} target='_blank'>
                    <i className='fa fa-link' />
                  </a>
                </button>
                <button type='button' onClick={this.hide} className='btn btn-default btn-sm'>Close</button>
              </div>
            </div>
          </div>
        </div>
      )
    }
});

module.exports = Modal;
