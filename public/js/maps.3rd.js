var map, markers = [];

function initialize() {
  var mapOptions = {
    center: new google.maps.LatLng(53.13111803493987, 23.168792724609375),
    zoom: 10,
    mapTypeId: google.maps.MapTypeId.ROADMAP
  };
  map = new google.maps.Map(document.getElementById("map-canvas"), mapOptions);
}
google.maps.event.addDomListener(window, 'load', initialize);

// geocoding service
var geocoder = new google.maps.Geocoder();

function codeAddress() {
  // In this case it gets the address from an element on the page,
  // but obviously you  could just pass it to the method instead
  var address = document.getElementById("address").value;

  geocoder.geocode({'address': address}, function(results, status) {
    if (status == google.maps.GeocoderStatus.OK) {
      // In this case it creates a marker, but you can get the lat and lng
      // from the location.LatLng
      map.setCenter(results[0].geometry.location);
      map.setZoom(Math.max(15, map.getZoom()));
//      var bounds = new google.maps.LatLngBounds();
      var marker = new google.maps.Marker({
        map: map, 
        position: results[0].geometry.location,
        zoom: 14
      });
//      map.fitBounds(bounds); 
    } else {
      alert("Geocode was not successful for the following reason: " + status);
    }
  });
}