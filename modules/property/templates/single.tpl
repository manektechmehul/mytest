{literal}   
   
    <style type="text/css">
    div#tipDiv {
        font-size:12px; line-height:1.3;
        color:#000; background-color:#E1E5F1; 
        border:1px solid #667295; 
    }

    div#tipDiv div.topBar {
        background-color:#0D2878;
        min-height:25px; /* for bg color if no caption (close.gif is 14px) */
        text-align:center;  
        }

    /* for ie < 7 */
    * html div#tipDiv div.topBar {
        height:14px; /* ie will expand if needed  */
        }

    div#tipDiv span.caption {
        color:#fff; font-weight:bold; font-size:11px;
        }
    div#tipDiv div.XtipContent {
        padding:4px;
        }

    div#tipDiv p {
        margin:0 0 .7em 0;
        }

    /* used in dw_Tooltip.wrapImageOverText */
    div#tipDiv div.img { 
        text-align:center; 
        margin:4px 0;
        }
    div#tipDiv div.txt { 
        text-align:center; 
        margin:4px 0;
        }
    </style>
    <script src="../js/dw_tooltip_c.js" type="text/javascript"></script>
    <script type="text/javascript">

    dw_Tooltip.defaultProps = {
        klass: 'tooltip2',
        activateOnClick: true,
        supportTouch: true, // set false by default
        sticky: true,
        showCloseBox: true,
        closeBoxImage: '../images/close-box.gif',
        positionFn: dw_Tooltip.positionWindowCenter,
        wrapFn: dw_Tooltip.wrapToWidth
    }

    // Problems, errors? See http://www.dyn-web.com/tutorials/obj_lit.php#syntax

    dw_Tooltip.content_vars = {        
        L2:  {
            img: '../UserFiles/floorplan/map_1443676935?1443676967718',
            w: 250
        },
        L3: {
            caption: 'Yin-Yang Mandala<br>&pound; 300 per week',
            img: '../UserFiles/floorplan/map_1443676935?1443676967718',            
            w: 250
        }
    }
    </script>
{/literal}
<div id="socialmediafloat">
    {twitter link="`$site_address``$pageName`" image="/images/share-single-tw.gif" class="socialmedia"}
    {facebook link="`$site_address``$pageName`" text=$singleitem.title image="/images/share-single-fb.gif" class="socialmedia"}
</div>

<div class="clearfix"></div>

<div class="{$listName}singleitem">

<div class="propertyinfo">
  <div class="{$listName}status status{$singleitem.property_status_id}"></div>
  <p>Location ::</p>
  <h3>{$singleitem.location}</h3>
  <!--<p>Type ::</p>
  <h3>{$singleitem.type}</h3>-->
  <p>Price ::</p>
  <h3>&pound;{$singleitem.price} per week</h3>
  <p>Bedrooms ::</p>
  <h3>{$singleitem.bedroom}</h3>
  <p>Address ::</p>
  <h3>{$singleitem.address|nl2br}<br>
  {$singleitem.postcode}</h3>
  <p class="csbutton" style="margin:0; bottom:30px; left:25px;"><a href="/UserFiles/File/{$singleitem.attachment}" target="_blank">Download Brochure</a></p>
</div>

<div class="">
<div>
  <!--
   <div id="slides">
    <div class="slide">{show_thumb filename=$singleitem.thumb crop=crop size=420x281 alt="alt=\"Slide\" width=420 height=281"}</div>
    {if $singleitem.image_1 != '' }
        <div class="slide">{show_thumb filename=$singleitem.image_1 crop=crop size=420x281 alt="alt=\"Slide\" width=420 height=281"}</div>
    {/if}
    {if $singleitem.image_2 != '' }
        <div class="slide">{show_thumb filename=$singleitem.image_2 crop=crop size=420x281 alt="alt=\"Slide\" width=420 height=281"}</div>
    {/if}
    {if $singleitem.image_3 != '' }
        <div class="slide">{show_thumb filename=$singleitem.image_3 crop=crop size=420x281 alt="alt=\"Slide\" width=420 height=281"}</div>
    {/if}
    {if $singleitem.image_4 != '' }
        <div class="slide">{show_thumb filename=$singleitem.image_4 crop=crop size=420x281 alt="alt=\"Slide\" width=420 height=281"}</div>
    {/if}
    {if $singleitem.image_5 != '' }
        <div class="slide">{show_thumb filename=$singleitem.image_5 crop=crop size=420x281 alt="alt=\"Slide\" width=420 height=281"}</div>
    {/if}
  </div>
   -->  
   <ul class="bxslider">
    <li>{show_thumb filename=$singleitem.thumb crop=crop size=420x281 alt="alt=\"Slide\" width=420 height=281"}</li>
    {if $singleitem.image_1 != '' }
        <li>{show_thumb filename=$singleitem.image_1 crop=crop size=420x281 alt="alt=\"Slide\" width=420 height=281"}</li>
    {/if}
    {if $singleitem.image_2 != '' }
        <li>{show_thumb filename=$singleitem.image_2 crop=crop size=420x281 alt="alt=\"Slide\" width=420 height=281"}</li>
    {/if}
    {if $singleitem.image_3 != '' }
    <li>{show_thumb filename=$singleitem.image_3 crop=crop size=420x281 alt="alt=\"Slide\" width=420 height=281"}</li>
    {/if}
    {if $singleitem.image_4 != '' }
        <li>{show_thumb filename=$singleitem.image_4 crop=crop size=420x281 alt="alt=\"Slide\" width=420 height=281"}</li>
    {/if}
    {if $singleitem.image_5 != '' }
        <li>{show_thumb filename=$singleitem.image_5 crop=crop size=420x281 alt="alt=\"Slide\" width=420 height=281"}</li>
    {/if}
   </ul>
        
  <div id="bx-pager">
   <a data-slide-index="0" href="">{show_thumb filename=$singleitem.thumb size=150x64 alt="alt=\"thumbnail\" height=64"}</a>
     {if $singleitem.image_1 != '' }
      <a data-slide-index="1" href="">{show_thumb filename=$singleitem.image_1 size=150x64 alt="alt=\"thumbnail\" height=64"}</a>
     {/if}
{if $singleitem.image_2 != '' }
      <a data-slide-index="2" href="">{show_thumb filename=$singleitem.image_2 size=150x64 alt="alt=\"thumbnail\" height=64"}</a>
     {/if}
{if $singleitem.image_3 != '' }
      <a data-slide-index="3" href="">{show_thumb filename=$singleitem.image_3 size=150x64 alt="alt=\"thumbnail\" height=64"}</a>
     {/if}
{if $singleitem.image_4 != '' }
      <a data-slide-index="4" href="">{show_thumb filename=$singleitem.image_4 size=150x64 alt="alt=\"thumbnail\" height=64"}</a>
     {/if}
{if $singleitem.image_5 != '' }
      <a data-slide-index="5" href="">{show_thumb filename=$singleitem.image_5 size=150x64 alt="alt=\"thumbnail\" height=64"}</a>
     {/if}
   
  </div>
</div>
</div>
 
<div class="clearfix"></div>



{$singleitem.body}
 <!--
{if $singleitem.map_url != ''}
<div style="margin-top:20px;">
	<iframe width="710" scrolling="no" height="480" frameborder="0" marginheight="0" marginwidth="0" src="{$singleitem.map_url}&output=embed" marginwidth="0" marginheight="0"></iframe><br />
	<div class="small" style="text-align:right;"><a href="{$singleitem.map_url}" target="_blank">View larger map on Google Maps</a></div>
</div>
{/if} -->
 <div id="map" style="width: 99%; height: 300px;"></div>

</div>
<div>
    <p><br>Floor Plan ::</p>
    <img src="http://localhost/UserFiles/floorplan/map_1443676935?1443676967718" width="" height="" name="usaMap" usemap="#m_usaMap" alt="" />
    <map id="m_usaMap" name="m_usaMap"><area shape="rect" alt="" title="" coords="28,88,177,205" href="" target="" class="showTip L2" /><area shape="rect" alt="" title="" coords="20,415,180,599" href="" target="" class="showTip L3" /><area shape="rect" alt="" title="" coords="331,19,480,174" href="" target="" class="showTip L2" /></map>
</div>    

<p class="csbutton"  style="margin-top:20px;"><a {if $button_link == "do_js_back"} onclick="javascript:history.back(-1);" href="#" {else}  href='{$button_link}' {/if} >{$button_text}</a></p>
<div class="clearfix"></div>
{literal}
    <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
    <script type="text/javascript" src="../js/imageMapResizer.min.js"></script>
    <script src="http://maps.google.com/maps/api/js?sensor=false" type="text/javascript"></script> 
    <script type="text/javascript">
	$('map').imageMapResize();
    </script>
    <script type="text/javascript">
    $(document).ready(function(){
        $('.bxslider').bxSlider({
          pagerCustom: '#bx-pager'
        });
    });
    </script>    
    <script type="text/javascript">         
        var lat1 = "";
        var long1 = "";
        var geocoder1 =  new google.maps.Geocoder();
        var address1 = '{/literal}{$singleitem.address|strip} {$singleitem.postcode}{literal}';
        geocoder1.geocode( { 'address': address1}, function(results, status) {
          if (status == google.maps.GeocoderStatus.OK) {
            lat1 = results[0].geometry.location.lat();
            long1 = results[0].geometry.location.lng(); 
          } else {

          }
        });
        
      /* for multiple markers */ 
        var delay = 100;
        var infowindow = new google.maps.InfoWindow();
        //var latlng = new google.maps.LatLng('{/literal}{$lat}{literal}', '{/literal}{$lng}{literal}');  
        var geocoder;
        var map; 
        var bounds;
        var mapOptions;
        setTimeout(function(){  
            var latlng = new google.maps.LatLng(lat1, long1);            
            mapOptions = {
              zoom: 15,
              center: latlng,
              mapTypeId: google.maps.MapTypeId.ROADMAP
            }
            geocoder = new google.maps.Geocoder(); 
            map = new google.maps.Map(document.getElementById("map"), mapOptions);
            bounds = new google.maps.LatLngBounds();
        }, 2100);
        
        function geocodeAddress(address, next, head1) {
          geocoder.geocode({address:address}, function (results,status)
            { 
               if (status == google.maps.GeocoderStatus.OK) {
                var p = results[0].geometry.location;
                var lat=p.lat();
                var lng=p.lng();
                createMarker(address,lat,lng,head1);
              }
              else {
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
       function createMarker(add,lat,lng,heading) {
         var contentString = "<h6>"+heading+"</h6>"+add;
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
        var nextAddress = 0;
        function theNext() {
          if (nextAddress < locations.length) {
            setTimeout('geocodeAddress("'+locations[nextAddress]+'",theNext,"'+locations_head[nextAddress]+'")', delay);
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