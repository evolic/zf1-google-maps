$(document).ready(function() {
  // Handler for .ready() called.
  $('#panel input[type="radio"]').change(function() {
    var mode = parseInt($('#panel input[type="radio"]:checked').val());
    console.log(mode, 'mode');

    var drawingModes = [];
    switch (mode) {
      case 1: // single point
        drawingModes[0] = google.maps.drawing.OverlayType.MARKER;
        break;
      case 2: // a few points
        drawingModes[0] = google.maps.drawing.OverlayType.POLYLINE;
        break;
      case 4: // area
        drawingModes[0] = google.maps.drawing.OverlayType.POLYGON;
        break;
    }
    console.log(drawingModes, 'drawingModes');

    // clear previous drawing
    deleteSelectedShape();
    // change mode
    drawingManager.setOptions({
      drawingControlOptions: {
        drawingModes: drawingModes
      }
    });
    drawingManager.setDrawingMode(drawingModes[0]);
  });
});