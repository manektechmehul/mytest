<?php


	include_once $base_path . '/modules/map/classes/myMap.php';

/**** CENTERING ICON IMAGE ISSUES ???
 edit the GoogleMap.php > setMarkerIconKey - fiddle ! ***/

	// $map_height// set map height and width in the page holding div
	// set to low value on debug - leave empty on live
	// the main map page
       
	if ( $name_parts[1] == "" ) {
                
		// Define the main attributes of the map
		$myMap  = new MyMap();
		$output = $myMap->drawMap();
		// in the map module, we overload the page title to inject the code at the top of the page                
		$title  = $output;               
	} else {
		// Show profile of each of the map locations
           
		global $smarty;
		global $name_parts;
		global $base_path;
		$template_file = "map_content.htm";
		$item_sql      = "SELECT * from map WHERE page_name = '{$name_parts[1]}'";
		$item          = db_get_single_row( $item_sql );
		$s             = $item["opening_times"];
		$bits          = explode( "\n", $s );
		$newstring     = "<ul class='opening_times' >";
		foreach ( $bits as $bit ) {
			$newstring .= "<li>" . $bit . "</li>";
		}
		$newstring .= "</ul>";
		$item["opening_times"] = $newstring;
		$title                 = $item['title'];
		$smarty->assign( 'singleitem', $item );
		$content_template_file = "$base_path/modules/map/templates/single.tpl";
		$smarty->display( "file:" . $content_template_file );
		
		// to push map image into page_image function in header
		global $filters;
		global $promo_image;
		$promo_image = "/php/thumbimage.php?img=/UserFiles/Image/" . $item["thumb"] . "&size=50";
		$filters['promo_image'] = array(
		 'search_string' => '/<!-- CS page image start -->(.*)<!-- CS page image end -->/s',
		 'replace_string' => '{$promo_image}'
		);
	}



	/*** these are the older helper functions that attempted to look through all addresses in the database and find a lat and lon
	 The api for this functionality is no longer available
	 ***/
	// go through all items in the database with a geostatus 0 - new
	// try to find a lat/lon for them
	// or mark them as a 2(failed geo)
	function updateGeo( $mymap ) {
		// get all users for now
		$sql      = 'SELECT * FROM member_user where geo_status = 0 limit 50';
		$result   = mysql_query( $sql );
		$num_rows = mysql_num_rows( $result );
		echo ' ' . $num_rows . ' Geo Locations to Update <br>';
		while ( $row = mysql_fetch_array( $result ) ) {
			$member_id = $row['id'];
			$address   = $row['mapping_address1'] . ',' . $row['mapping_address2'] . ',' . $row['mapping_address3'] . ',' . $row['mapping_postalcode'];
			$lon       = $row['lon'];
			$lat       = $row['lat'];
			// get the lon lat for new entry
			$_coords = $mymap->geoGetCoords( $address );
			//var_dump($_coords);
			if ( $_coords != false ) {
				$lon = $_coords['lon'];
				$lat = $_coords['lat'];
				// update database row for next time
				$updatesql = "update member_user set lon = $lon, lat = $lat , geo_status = 1 where id = $member_id";
				$r         = mysql_query( $updatesql );
				echo '<br> Geo Location updated Successfully. ID [' . $member_id . ']';
			} else {
				// can't geocode address - flag it some how
				$updatesql = "update member_user set  geo_status = 2 where id = $member_id";
				$r         = mysql_query( $updatesql );
				echo '<br> Geo Location updated Failed. ID [' . $member_id . ']';
				echo '<br> ' . $address;
			}
		} // end while
	}

	/* view all the failed geos in the database */
	function getFailedGeos() {
		$sql      = 'SELECT * FROM member_user where geo_status = 2 limit 100';
		$result   = mysql_query( $sql );
		$num_rows = mysql_num_rows( $result );
		echo ' First ' . $num_rows . ' of failed geos <br>';
		while ( $row = mysql_fetch_array( $result ) ) {
			$member_id = $row['id'];
			$address   = $row['billing_address1'] . ',' . $row['billing_address2'] . ',' . $row['billing_address3'] . ',' . $row['billing_postalcode'];
			$address   = str_replace( ',,', ',', $address );
			$lon       = $row['lon'];
			$lat       = $row['lat'];
			echo '<hr> member id : ' . $member_id . ' Name:' . $row['firstname'] . ' ' . $row['surname'];
			echo '<br>' . $address;
		}
	}

	// ~This service seems to have stopped
	//  gets an approximate long/lat from the user IP
	function getCurrentLocation( $base_path ) {
		include_once $base_path . '/modules/map/classes/ip2locationlite.class.php';
		//Load the class
		$ipLite = new ip2location_lite;
		$ipLite->setKey( '86c7b4be7d2745def22a0f224ae492df51ab495156444e90e2107286f00b6acd' );
		// echo $_SERVER['REMOTE_ADDR'];
		//Get errors and locations
		$locations = $ipLite->getCity( $_SERVER['REMOTE_ADDR'] ); // '94.194.116.10'
		$errors    = $ipLite->getError();
		$lat       = '';
		$lon       = '';
		if ( ! empty( $locations ) && is_array( $locations ) ) {
			foreach ( $locations as $field => $val ) {
				//echo $field . ' : ' . $val . "<br />\n";
				if ( $field == 'latitude' ) {
					$lat = $val;
				}
				if ( $field == 'longitude' ) {
					$lon = $val;
				}
			}
		}
		return array( $lon, $lat );
	}

?>
