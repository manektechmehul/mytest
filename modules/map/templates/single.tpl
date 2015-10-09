{*This is the Profile view or the map entries*}
<div class="container-within-container">
<div class="row">
  <div class="col-lg-6 col-md-5 col-sm-12 col-xs-12">
    <h2 class="maptitle">About this location</h2>
    {$singleitem.description}
    <p class="csbutton"><a href="/locations">Back to map</a></p>
  </div>
  <div class="col-lg-3 col-md-4 col-sm-7 col-xs-12">
    <h2 class="maptitle">Address</h2>
    <p><span class="large"><strong>{$singleitem.title}</strong></span><br>
    {if $singleitem.mapping_address1 != ""}{$singleitem.mapping_address1}<br>{/if}
    {if $singleitem.mapping_address2 != ""}{$singleitem.mapping_address2}<br>{/if}
    {if $singleitem.mapping_address3 != ""}{$singleitem.mapping_address3}<br>{/if}
    {if $singleitem.mapping_postalcode != ""}{$singleitem.mapping_postalcode}{/if}
    </p>
    <p>
    {if $singleitem.phone != ""}<span class="large"><strong>{$singleitem.phone}</strong></span><br>{/if}
    {if $singleitem.mapping_email != ""}<a href="mailto:{$singleitem.mapping_email}">{$singleitem.mapping_email}</a>{/if}
    </p>
  </div>
  <div class="col-md-3 col-sm-5 col-xs-12 openingtimes">
    <h2 class="maptitle">Opening times</h2>
    {$singleitem.opening_times}
  </div>
  <div class="clearfix"></div>
</div>
</div>
<div class="container-within-container" style="margin-top:20px;">
<div class="row">
  <div class="col-sm-8 col-xs-12 map-canvas-container">
    <div id="map-canvas"></div>
  </div>
  <div class="col-sm-4 col-xs-12 map-image-container">
    <div class="map-image" style="background-image:url({show_thumb_minimal filename=$singleitem.thumb size='800x1500'});"></div>
  </div>
</div>
</div>
{literal}
<script src="https://maps.googleapis.com/maps/api/js"></script>
<script>
var mapCanvas;
var map = null, marker = null;
function initialize() {
    mapCanvas = document.getElementById('map-canvas');
	var myLatlng = new google.maps.LatLng({/literal}{$singleitem.lat},{$singleitem.lon}{literal});
	var mapOptions = {
		center: myLatlng,
		zoom: 14,
		mapTypeId: google.maps.MapTypeId.ROADMAP
	}
	map = new google.maps.Map(mapCanvas, mapOptions)

	{/literal}
	{if  $singleitem.map_business_type == '0'}
			var map_pin = "/images/map/pin_normal.png"; // other
	{/if}
	{if  $singleitem.map_business_type == '1'}
			var map_pin = "/images/map/pin_hospice.png"; // hospice
	{/if}
	{if  $singleitem.map_business_type == '2'}
			var map_pin = "/images/map/pin_shop.png"; // shop
	{/if}
{literal}
	marker = new google.maps.Marker({
	position: myLatlng,
    animation: google.maps.Animation.DROP,
	map: map,
	icon: map_pin,
	title: '{/literal}{$singleitem.title}{literal}'
	});
        map.setCenter(myLatlng.getCenter());
}

        google.maps.event.addDomListener(window, 'load', initialize);
</script>
{/literal}