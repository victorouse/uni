/**
 * Taken from: https://groups.google.com/forum/#!topic/google-maps-js-api-v3/hDRO4oHVSeM
 * Provides a rough estimation of the meters per pixel
 */
var MapZoomRatio = function(zoomLevel) {
  var metersPerPixel = [];

  metersPerPixel[0] = 156543.03392;
  metersPerPixel[1] = 78271.51696;
  metersPerPixel[2] = 39135.75848;
  metersPerPixel[3] = 19567.87924;
  metersPerPixel[4] = 9783.93962;
  metersPerPixel[5] = 4891.96981;
  metersPerPixel[6] = 2445.98490;
  metersPerPixel[7] = 1222.99245;
  metersPerPixel[8] = 611.49622;
  metersPerPixel[9] = 305.74811;
  metersPerPixel[10] = 152.87405;
  metersPerPixel[11] = 76.43702;
  metersPerPixel[12] = 38.21851;
  metersPerPixel[13] = 19.10925;
  metersPerPixel[14] = 9.55462;
  metersPerPixel[15] = 4.77731;
  metersPerPixel[16] = 2.38865;
  metersPerPixel[17] = 1.19432;
  metersPerPixel[18] = 0.59716;
  metersPerPixel[19] = 0.29858;

  return metersPerPixel[zoomLevel];
}

module.exports = MapZoomRatio;
