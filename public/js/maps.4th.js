var map, marker;

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

  marker = new google.maps.Marker({
    position: new google.maps.LatLng(54.15922, 21.68152),
    map: map,
    title: 'Click to zoom'
  });

  google.maps.event.addListener(marker, 'click', function() {
    map.setZoom(10);
    map.setCenter(marker.getPosition());
  });
}

google.maps.event.addDomListener(window, 'load', initialize);


function showLocalizationByCoords() {
  var coords_input = $('#coords');
  var coords = eval($(coords_input).val().replace('(', '[').replace(')', ']'));
  console.log(coords, 'coords');
  if (typeof(coords) === 'object' && coords.length === 2) {
    var location = new google.maps.LatLng(coords[0], coords[1]);
    map.setCenter(location);
  };
}