{*<h2>Featured Commercial Property</h2>*}
{literal}
    <script>
        var page_url_parts="{/literal}{$page_url_parts[0]}{literal}";
        var listname='{/literal}{$listName}{literal}';
    </script> 
{/literal}

{include file="$pr_filter"}
<div id="Container">  
{section name=item loop=$itemList}
  <div class="{$listName}listitem" >
    <div class="{$listName}status status{$itemList[item].property_status_id}"></div>
    <div class="{$listName}left">
      {assign var="alt" value="alt='`$itemList[item].title`'"}
      <a href="/{$page_url_parts[0]}/{$itemList[item].page_name}">{show_thumb filename=$itemList[item].thumb size='180x600' alt=$alt border="0" class="class=left"}</a>
    </div>
    <div class="{$listName}right">
      <a href="/{$page_url_parts[0]}/{$itemList[item].page_name}">{$itemList[item].address}</a>
      <p>&pound;{$itemList[item].price} per week</p>
      <p>{$itemList[item].bedroom} bedrooms</p>
      <p class="csbutton"><a href="/{$page_url_parts[0]}/{$itemList[item].page_name}" style="border-top-left-radius:0;">View property details</a></p>
	</div>
    <div class="clearfix"></div>
  </div>   
  
  {if $smarty.section.item.last}
    <div id="pagination">{$pagination}</div>
  {/if}    
{sectionelse}
    <p>There are currently no {$pluralName}.</p>
{/section}
</div>
<div id="map" style="width: 99%; height: 300px;"></div>


{literal}
<script src="http://maps.google.com/maps/api/js?sensor=false" type="text/javascript"></script> 
<script type="text/javascript">
    var lat1 = "";
    var long1 = "";
    var geocoder1 =  new google.maps.Geocoder();
    var address1 = '{/literal}{$itemList[0].address|strip} {$itemList[0].postcode}{literal}';
    geocoder1.geocode( { 'address': address1}, function(results, status) {
        if (status == google.maps.GeocoderStatus.OK) {
            lat1 = results[0].geometry.location.lat();
            long1 = results[0].geometry.location.lng(); 
        }
    });

    /* for multiple markers */
    var delay = 100;
    var infowindow = new google.maps.InfoWindow();
    var geocoder;
    var map; 
    var bounds;
    var mapOptions;
    setTimeout(function(){  
        var latlng = new google.maps.LatLng(lat1, long1);            
        mapOptions = {
            zoom: 4,
            center: latlng,
            mapTypeId: google.maps.MapTypeId.ROADMAP
        }    
        geocoder = new google.maps.Geocoder(); 
        map = new google.maps.Map(document.getElementById("map"), mapOptions);
        bounds = new google.maps.LatLngBounds();
    }, 2100);
        
    function geocodeAddress(address, next, head1, page1) {
        geocoder.geocode({address:address}, function (results,status)
        { 
            if (status == google.maps.GeocoderStatus.OK) {
                var p = results[0].geometry.location;
                var lat=p.lat();
                var lng=p.lng();
                createMarker(address,lat,lng,head1,page1);
            } else {
                if (status == google.maps.GeocoderStatus.OVER_QUERY_LIMIT) {
                    nextAddress--;
                    delay++;
                } else {
                }   
            }
            next();
        }
      );
    }
        
    function createMarker(add,lat,lng,heading,pagename) {
        var contentString = "<h6>"+heading+"</h6>"+add+"<br><a href='/{/literal}{$page_url_parts[0]}{literal}/"+pagename+"'>See Details</a>";
        var marker = new google.maps.Marker({
          position: new google.maps.LatLng(lat,lng),
          map: map,
          icon: '../images/map/villa.png'
        });

        google.maps.event.addListener(marker, 'click', function() {
            infowindow.setContent(contentString); 
            infowindow.open(map,marker);
        });
        bounds.extend(marker.position);
    }
        
    var locations = {/literal}{$location_str|strip}{literal}       
    var locations_head = {/literal}{$location_head|strip}{literal}
    var locations_page = {/literal}{$location_page|strip}{literal}
    var nextAddress = 0;
    function theNext() {
        if (nextAddress < locations.length) {
            setTimeout('geocodeAddress("'+locations[nextAddress]+'",theNext,"'+locations_head[nextAddress]+'","'+locations_page[nextAddress]+'")', delay);
            nextAddress++;
        } else {
            //map.fitBounds(bounds);
        }
    }
    setTimeout(function(){ 
        theNext();
    }, 2500);   
    </script> 
{/literal}