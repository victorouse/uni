var React = require('react/addons');
var Main = React.createFactory(require('./components/Main'));

var initialState = JSON.parse(document.getElementById('initial-state').innerHTML);
var mountNode = document.getElementById('video-main');

React.render(new Main({'video': initialState.video._source}), mountNode);
