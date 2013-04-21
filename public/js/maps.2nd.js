var map, markers = [];

function initialize() {
  var mapOptions = {
    center: new google.maps.LatLng(54.15922, 21.68152),
    zoom: 10,
    scaleControl: true,
    scaleControlOptions: {
      position: google.maps.ControlPosition.BOTTOM_LEFT
    },
    mapTypeId: google.maps.MapTypeId.ROADMAP
  };
  map = new google.maps.Map(document.getElementById("map-canvas"), mapOptions);

  var marker = new google.maps.Marker({
    position: new google.maps.LatLng(54.15922, 21.68152),
    map: map,
    title: 'Click to zoom'
  });

//  google.maps.event.addListener(map, 'center_changed', function() {
//    // 3 seconds after the center of the map has changed, pan back to the
//    // marker.
//    window.setTimeout(function() {
//      map.panTo(marker.getPosition());
//    }, 3000);
//  });

  google.maps.event.addListener(map, 'click', function(event) {
    placeMarker(event.latLng);
  });


  google.maps.event.addListener(marker, 'click', function() {
    map.setZoom(10);
    map.setCenter(marker.getPosition());
  });
}

function placeMarker(location) {
  var marker = new google.maps.Marker({
    position: location,
    map: map,
    draggable: true
  });
  markers[markers.length] = marker;

  google.maps.event.addListener(marker, 'dragend', function(event) {
    console.log(marker, 'marker');
    console.log(event, 'event');
    $('#lat').text(marker.position.lat().toFixed(5));
    $('#lng').text(marker.position.lng().toFixed(5));
    for (var i in markers) {
      if (markers[i] === marker) {
        $('#marker').text(i);
      }
    }
  });

//  map.setCenter(location);
  $('#lat-lng').text(location.toString());
  $('#lat').text(location.lat().toFixed(5));
  $('#lng').text(location.lng().toFixed(5));
}

google.maps.event.addDomListener(window, 'load', initialize);
