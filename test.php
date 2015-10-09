<?php 
        $address = 'New Delhi, India';
        $url='http://maps.googleapis.com/maps/api/geocode/json?address=' . urlencode($address) . '&sensor=true';        
        $coordinates = file_get_contents($url);        
        $coordinates = json_decode($coordinates);
        echo 'Latitude:' . $coordinates->results[0]->geometry->location->lat;
	echo 'Longitude:' . $coordinates->results[0]->geometry->location->lng;       
	$lat = $coordinates->results[0]->geometry->location->lat;
	$lng = $coordinates->results[0]->geometry->location->lng;
        exit;
?>