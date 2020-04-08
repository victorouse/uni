var React = require('react/addons');

var Map = React.createFactory(require('../shared/Map'));
var Main = React.createFactory(require('./components/Main'));

var mountNode = document.getElementById('home-main');
var initialMainState = JSON.parse(document.getElementById('initial-state').innerHTML);

var location = '';

var initialMapState = {
  initialCenter: location
};

var MapElement;

if (sessionStorage.latitude && sessionStorage.longitude) {
  location = new google.maps.LatLng(sessionStorage.latitude, sessionStorage.longitude);
  initialMapState.initialCenter = location;

  MapElement = <Map map={initialMapState} />;
  React.render(new Main(initialMainState, {'element': MapElement}), mountNode);
} else {
  navigator.geolocation.getCurrentPosition(function(position) {
    location = new google.maps.LatLng(position.coords.latitude, position.coords.longitude);
    initialMapState.initialCenter = location;
    
    MapElement = <Map map={initialMapState} />;

    sessionStorage.latitude = position.coords.latitude;
    sessionStorage.longitude = position.coords.longitude;

    React.render(new Main(initialMainState, {'element': MapElement}), mountNode);
  }, function(err) {
    console.log('Error getting geolocation.', err)
  }, {maximumAge: 60000});
}


