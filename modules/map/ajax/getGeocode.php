<?php
include_once '../classes/GoogleMap.php'; 


ini_set('display_errors', '1');
include_once '../../../php/databaseconnection.php';
include_once '../../../php/functions_inc.php';

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */




    $address  = rawurldecode($_REQUEST['addr']);   
    $map = new GoogleMapAPI();    
    $coords = $map->geoGetCoords($address,0);
    
    //var_dump($coords);
    $json = '[{"lat":"' . $coords['lat'] . '","lon":"' . $coords['lon']  . '"}]';
    echo $json;
     

    // as json
            
            
    
    
    
    


?>