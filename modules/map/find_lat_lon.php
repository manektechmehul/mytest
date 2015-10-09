<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
  <head>
    <meta http-equiv="content-type" content="text/html; charset=UTF-8"/>
      	<title>Find latitude and longitude with Google Maps</title>
    <script src="http://maps.google.com/maps?file=api&amp;v=2&amp;key=AIzaSyBmB8KWTaiKHUGxHOqXhs7O_uOlNlZbJkE"
      type="text/javascript"></script>
    <script type="text/javascript">

// Passed in vars from the $_get
$cur_lon = <?
if($_GET['lon']){
    echo $_GET['lon'];
}else{
    echo '-1.47009';
}
?>;
$cur_lat = <?
if($_GET['lat']){
    echo $_GET['lat'];
}else{
    echo '53.38113';
}
?>;

if($cur_lon == ''){
    $cur_lon = '1';
}
if($cur_lat == ''){
    $cur_lat = '-1';
}



 function load() {
      if (GBrowserIsCompatible()) {
        var map = new GMap2(document.getElementById("map"));
        map.addControl(new GSmallMapControl());
        map.addControl(new GMapTypeControl());
        var center = new GLatLng($cur_lat,$cur_lon);
        map.setCenter(center, 15);
        geocoder = new GClientGeocoder();
        var marker = new GMarker(center, {draggable: true});  
        map.addOverlay(marker);
        document.getElementById("lat").innerHTML = center.lat().toFixed(5);
        document.getElementById("lng").innerHTML = center.lng().toFixed(5);
        document.getElementById("mylat").value = center.lat().toFixed(5);
        document.getElementById("mylon").value = center.lng().toFixed(5);

	GEvent.addListener(marker, "dragend", function() {
        var point = marker.getPoint();
	map.panTo(point);
        document.getElementById("lat").innerHTML = point.lat().toFixed(5);        
        document.getElementById("lng").innerHTML = point.lng().toFixed(5);
        document.getElementById("mylat").value = point.lat().toFixed(5);  
        document.getElementById("mylon").value = point.lng().toFixed(5);
   
              

        });


	 GEvent.addListener(map, "moveend", function() {
            map.clearOverlays();
            var center = map.getCenter();
            var marker = new GMarker(center, {draggable: true});
            map.addOverlay(marker);
            document.getElementById("lat").innerHTML = center.lat().toFixed(5);
	    document.getElementById("lng").innerHTML = center.lng().toFixed(5);
            document.getElementById("mylat").value = center.lat().toFixed(5);
            document.getElementById("mylon").value = center.lng().toFixed(5);

	 GEvent.addListener(marker, "dragend", function() {
      var point =marker.getPoint();
	     map.panTo(point);
             document.getElementById("lat").innerHTML = point.lat().toFixed(5);
	     document.getElementById("lng").innerHTML = point.lng().toFixed(5);
             document.getElementById("mylat").value = point.lat().toFixed(5);
             document.getElementById("mylon").value = point.lng().toFixed(5);
             
        });
 
        });

      }
    }

	   function showAddress(address) {
	   var map = new GMap2(document.getElementById("map"));
       map.addControl(new GSmallMapControl());
       map.addControl(new GMapTypeControl());
       if (geocoder) {
        geocoder.getLatLng(
          address,
          function(point) {
            if (!point) {
              alert(address + " not found");
            } else {
		  document.getElementById("lat").innerHTML = point.lat().toFixed(5);
                  document.getElementById("lng").innerHTML = point.lng().toFixed(5);
                   document.getElementById("mylat").value = point.lat().toFixed(5);
                    document.getElementById("mylon").value = point.lng().toFixed(5);
           
		 map.clearOverlays()
			map.setCenter(point, 14);
   var marker = new GMarker(point, {draggable: true});  
		 map.addOverlay(marker);

		GEvent.addListener(marker, "dragend", function() {
      var pt = marker.getPoint();
	     map.panTo(pt);
      document.getElementById("lat").innerHTML = pt.lat().toFixed(5);
	     document.getElementById("lng").innerHTML = pt.lng().toFixed(5);
              document.getElementById("mylat").value = pt.lat().toFixed(5);
             document.getElementById("mylon").value = pt.lng().toFixed(5);
             
              // my vars
                lon =  pt.lng().toFixed(5);
                lat = pt.lng().toFixed(5);
        });


	 GEvent.addListener(map, "moveend", function() {
		  map.clearOverlays();
    var center = map.getCenter();
		  var marker = new GMarker(center, {draggable: true});
		  map.addOverlay(marker);
		  document.getElementById("lat").innerHTML = center.lat().toFixed(5);
	   document.getElementById("lng").innerHTML = center.lng().toFixed(5);
                      document.getElementById("mylat").value = point.lat().toFixed(5);
                    document.getElementById("mylon").value = point.lng().toFixed(5);

	 GEvent.addListener(marker, "dragend", function() {
     var pt = marker.getPoint();
	    map.panTo(pt);
            document.getElementById("lat").innerHTML = pt.lat().toFixed(5);
	   document.getElementById("lng").innerHTML = pt.lng().toFixed(5);
                         document.getElementById("mylat").value = pt.lat().toFixed(5);
             document.getElementById("mylon").value = pt.lng().toFixed(5);
           
           
            // my vars
                lon = point.lng().toFixed(5);
                lat = point.lat().toFixed(5);
        });
 
        });

            }
          }
        );
      }
    }
    
    
    function returnLocation(){
    //alert('do it');
    lon = document.getElementById("mylon").value;
    lat = document.getElementById("mylat").value
    //alert(lon + lat);

    window.opener.getLocation(lon,lat);
    window.close();
    }
    
    </script>
  <script type="text/javascript">
//<![CDATA[
var gs_d=new Date,DoW=gs_d.getDay();gs_d.setDate(gs_d.getDate()-(DoW+6)%7+3);
var ms=gs_d.valueOf();gs_d.setMonth(0);gs_d.setDate(4);
var gs_r=(Math.round((ms-gs_d.valueOf())/6048E5)+1)*gs_d.getFullYear();
var gs_p = (("https:" == document.location.protocol) ? "https://" : "http://");
document.write(unescape("%3Cscript src='" + gs_p + "s.gstat.orange.fr/lib/gs.js?"+gs_r+"' type='text/javascript'%3E%3C/script%3E"));
//]]>
</script>
</head>

  
<body onload="load()" onunload="GUnload()" >
<input type="hidden" value="" id="mylat">
    <input type="hidden" value="" id="mylon">
    
    
   <p><b> Find coordinates by moving around the map</b></p> <p>1. Drag and drop the map to broad location. <br/>
	2. Zoom in for greater accuracy. <br/>
	3. Drag and drop the marker to pinpoint the place. The coordinates are refreshed at the end of each move.  </p>
  <p><b>Find coordinates using the name and/or address of the place</b></p>
  <p>Submit the full location : number, street, city, country. For big cities and famous places, the country is optional. "Bastille Paris" or "Opera Sydney" will do. <br/>
 </p>

  <form action="#" onsubmit="showAddress(this.address.value); return false">
     <p>        
      <input type="text" size="60" name="address" value="<?=$_GET['addr'] ?>" />
      <input type="submit" value="Search!" />
      </p>
    </form>

 <p align="left">
 
 <table  bgcolor="#FFFFCC" width="600">
  <tr>
    <td><b>Latitude</b></td>
    <td><b>Longitude</b></td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td id="lat"></td>
    <td id="lng"></td>
     <td><a href="#" onclick="returnLocation();"><b>Use This Location for My Location</b></a></td>
  </tr>
</table>
 </p>
  <p>
  <div align="center" id="map" style="width: 600px; height: 400px"><br/></div>
   </p>
  </div>
  <script type="text/javascript">
//<![CDATA[
if (typeof _gstat != "undefined") _gstat.audience('','pagesperso-orange.fr');
//]]>
</script>
</body>

</html>