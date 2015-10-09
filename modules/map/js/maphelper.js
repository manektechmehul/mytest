// These functons are used in the web [member-edit] and admin end, so tred carefully


// check if the mapp fields are displayed, if so add the lat/lon chooser option
 function openMapTool(){
     lat = '';
     lon = '';
     addr =  $('[name=mapping_address1]').val() + ' '+ 
          $('[name=mapping_address2]').val() + ' ' +
     $('[name=mapping_address3]').val() + ' ' +
     $('[name=mapping_postalcode]').val() + ' ' +
    $('[name=mapping_country] option:selected').text();
     
     // make addr safe
     
     window.open('/modules/map/find_lat_lon.php?lat=' + $('[name=lat]').val() + '&lon=' + $('[name=lon]').val() + '&addr=' + addr);
 }
 
 
 function updategeocode_primer(){

    addr =  $('[name=mapping_address1]').val() + ' '+ 
    $('[name=mapping_address2]').val() + ' ' +
    $('[name=mapping_address3]').val() + ' ' +
    $('[name=mapping_postalcode]').val() + ' ' +
    $('[name=mapping_country] option:selected').text();

    coords = updategeocode(addr);
    // alert('coords=' +  coords);
   // if(coords != false){
      //  $('[name=lat]').val(coords.lat);
      //  $('[name=lon]').val(coords.lon);
   // }else{
       // alert('Sorry, we could not find you on the map.');
   // }
             
 }
 
 function getLocation(lon, lat){
    
     // alert('lat is' + lat + 'lon is ' + lon );
    
     $('[name=lat]').val(lat);
     $('[name=lon]').val(lon);
     
     alert('Location Updated - Please submit this page to save your new location');
 }
 /* added to js file
function updategeocode_primer(){

    addr =  $('[name=mapping_address1]').val() + ' '+ 
    $('[name=mapping_address2]').val() + ' ' +
    $('[name=mapping_address3]').val() + ' ' +
    $('[name=mapping_postalcode]').val() + ' ' +
    $('[name=mapping_country] option:selected').text();

    coords = updategeocode(addr);
    // alert('coords=' +  coords);
   // if(coords != false){
      //  $('[name=lat]').val(coords.lat);
      //  $('[name=lon]').val(coords.lon);
   // }else{
       // alert('Sorry, we could not find you on the map.');
   // }
             
 } 
 */
 
function updategeocode(addr){   

    // ajax to look up lon and lat from addr
    url ='/modules/map/ajax/getGeocode.php';
    query = '?addr=' + addr;
    
    // you will need to start with response = $.ajax .. then return responcse at the end !!!!    
    coords =   $.ajax({
        url: url + query,
        type: "addr",
        data: query,
        dataType: 'json',
        success: function (data) {
            // alert(data);
            for (var x = 0; x < data.length; x++) {
                lat = data[x].lat;                
                lon = data[x].lon;                         
           }
           
           // if lat and lon not empty populate and give a success prompt - else give warning 
           if(lat != ''){                            
               $('[name=lat]').val(lat);
               $('[name=lon]').val(lon);
               alert('Your Location has been updated.'); 
               return data[x];
           }else{
               alert('Sorry, we could not find you on the map.');
               return false;
           }
           
                 
           
        },
         error: function (xhr, desc, er) {
                        // add whatever debug you want here.
			alert("an error occurred" + er);
                    }
    });    
    
    
    return coords;
    
}
