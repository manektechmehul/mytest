{* Echo out the onload created by the google map *}
{$output}{literal}
<script type="text/javascript">
    $(document).ready(function () {
        onLoadmap();
        var map = mapmap;
        //map = new google.maps.Map(document.getElementById("map"));
        // var autocomplete = new google.maps.places.Autocomplete(document.getElementById('autocomplete'));
        //autocomplete.bindTo('bounds',map);
        google.maps.event.addListener(autocomplete, 'place_changed', function () {

            try {
                var place = autocomplete.getPlace();

                if (place.geometry.viewport) {
                    map.fitBounds(place.geometry.viewport);
                } else {
                    map.setCenter(place.geometry.location);
                    map.setZoom(8);
                }
                marker.setPosition(place.geometry.location);



            } catch (err) {
                //console.log(err.message);
                if (err.message == 'place.geometry is undefined') {
                    showFadeInmessage('Do not recogise `' + $("#autocomplete").val() + '`, Please select a location from list.');
                } else {
                    // alert(err.description);
                }
            }
        });
    });

    function zoomandcentre(lat, lon) {
        // this is used to find items close to me
        mapmap.setZoom(10);
        var latlng = new google.maps.LatLng(lat, lon);
        mapmap.setCenter(latlng.getCenter());
    }
</script>
{/literal}