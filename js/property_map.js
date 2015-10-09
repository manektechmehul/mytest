var address = '{/literal}{$singleitem.address|strip} {$singleitem.postcode}{literal}';       
var contentstr = '<div class="infowindow"><h6>{/literal}{$singleitem.title}{literal}</h6> <div class="address"><b>{/literal}{$singleitem.address|strip}{literal} <br> {/literal}{$singleitem.postcode}{literal}</b></div></div>';
var map = new google.maps.Map(document.getElementById('map'), { 
    mapTypeId: google.maps.MapTypeId.ROADMAP,
    zoom: 13
});

var geocoder = new google.maps.Geocoder();
geocoder.geocode({
   'address': address
}, 
function(results, status) {
   if(status == google.maps.GeocoderStatus.OK) {
      var marker =  new google.maps.Marker({
         position: results[0].geometry.location,
         map: map
      });
      map.setCenter(results[0].geometry.location);
      var infowindow = new google.maps.InfoWindow({
        content: contentstr
      });
      marker.addListener('click', function() {
        infowindow.open(map, marker);
      });              
   }
});