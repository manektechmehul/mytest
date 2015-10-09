$(document).ready(function () {
    // wire up button click
    $('#consultantnearme').click(function () {
        // test for presence of geolocation
        showFadeInmessage('Please Click the `Share Location` or `Allow Location Sharing` to pass us your location, Then wait please.',undefined,8000);
        if (navigator && navigator.geolocation) {
           // hideFadeInmessage();
           // showFadeInmessage('Just looking you up ... ');
             navigator.geolocation.getCurrentPosition(geo_success, geo_error);
        } else {
            error('Sorry, Geolocation is not supported on your browser. Please type a location in to the search instead.');
        }
    });
});
 
function geo_success(position) {
    printLatLong(position.coords.latitude, position.coords.longitude);
}
 
// The PositionError object returned contains the following attributes:
// code: a numeric response code
// PERMISSION_DENIED = 1
// POSITION_UNAVAILABLE = 2
// TIMEOUT = 3
// message: Primarily for debugging. It's recommended not to show this error
// to users.
function geo_error(err) {
    if (err.code == 1) {
        error('The user denied the request for location information.')
    } else if (err.code == 2) {
        error('Sorry, Your location information is unavailable. Please type a location in to the search instead.')
    } else if (err.code == 3) {
        error('The request to get your location timed out.')
    } else {
        error('An unknown error occurred while requesting your location.')
    }
}
 
// output lat and long
function printLatLong(lat, lon) {
    hideFadeInmessage();
    showFadeInmessage('We have your location, Redrawing the map');
    //console.log('lat = ' + lat);
    //console.log('lon = ' + lon);
    zoomandcentre(lat, lon);
}
 
function error(msg) {
   // alert(msg);
    hideFadeInmessage();
    showFadeInmessage(msg,undefined,12000);

}
