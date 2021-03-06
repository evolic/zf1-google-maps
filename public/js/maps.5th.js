function initialize() {
  var mapOptions = {
    center: new google.maps.LatLng(-34.397, 150.644),
    zoom: 8,
    mapTypeId: google.maps.MapTypeId.ROADMAP
  };

  var map = new google.maps.Map(document.getElementById('map-canvas'),
    mapOptions);

  var drawingManager = new google.maps.drawing.DrawingManager({
    drawingMode: google.maps.drawing.OverlayType.MARKER,
    drawingControl: true,
    drawingControlOptions: {
      position: google.maps.ControlPosition.TOP_CENTER,
      drawingModes: [
        google.maps.drawing.OverlayType.MARKER,
        google.maps.drawing.OverlayType.POLYGON,
        google.maps.drawing.OverlayType.POLYLINE
      ]
    },
    markerOptions: {
//      icon: 'https://developers.google.com/maps/documentation/javascript/examples/images/beachflag.png'
    },
    circleOptions: {
      fillColor: '#ffff00',
      fillOpacity: 1,
      strokeWeight: 5,
      clickable: false,
      editable: true,
      zIndex: 1
    },
    polylineOptions: {
      strokeColor: "#A52A2A",
      strokeOpacity: 1.0,
      strokeWeight: 2,
      clickable: false,
      editable: true,
      zIndex: 1
    },
    polygonOption: {
      strokeColor: "#191970",
      strokeOpacity: 0.8,
      strokeWeight: 2,
      fillColor: "#6495ED",
      fillOpacity: 0.35
    }
  });
  drawingManager.setMap(map);
}

google.maps.event.addDomListener(window, 'load', initialize);
