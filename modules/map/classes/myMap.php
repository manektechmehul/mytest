<?php
	/**
	 * Created by PhpStorm.
	 * User: glen
	 * Date: 19/05/2015
	 * Time: 13:59
	 */
	global $base_path;
	require $base_path . '/modules/map/classes/GoogleMap.php';
	require $base_path . '/modules/map/classes/JSMin.php';

	class myMap {
		var $enableClustering;
		var $show_you_are_here;
		var $customstyle;
		var $default_pin;
		var $second_pin;
		var $partner_pin;
		var $limit_markers;
		var $mymap;

		function __construct() {
			$this->enableClustering  = false;
			$this->show_you_are_here = false;
			$this->customstyle       = false;
			$this->default_pin       = "/images/map/pin_normal.png";
			$this->second_pin        = "/images/map/pin_hospice.png"; // hospice
			$this->partner_pin       = "/images/map/pin_shop.png"; // shop
			$this->limit_markers     = 'limit 1000';
			$this->mymap             = new GoogleMapAPI();
			$this->mymap->_minify_js = true;  //isset( $_REQUEST["min"] ) ? false : true;
		}

		function printOnLoad(){
			return $this->mymap->printOnLoad();
		}

		function printMap() {
			return $this->mymap->printMap();
		}

		function printSidebar(){
			return $this->mymap->printSidebar();
		}

		function drawMap() {
			/**
			 * Google Map options here could be
			 *
			 * $mymap->window_trigger = 'mouseover'; // click or mouseover
			 * $mymap->setMapType = 'satellite'; // - doesn't seem to work  HYBRID,SATELLITE,TERRAIN,ROADMAP
			 */
			// Add markers to the map
			$this->addMarkerstoMap();
			if ( $this->customstyle ) {
				$this->mymap->setMapStyles( '[ { "featureType": "landscape.natural", "stylers": [ { "hue": "#dd00ff" } ] },{ "featureType": "landscape.man_made", "stylers": [ { "hue": "#002bff" }, { "saturation": 3 }, { "lightness": 1 } ] },{ "featureType": "water", "stylers": [ { "hue": "#2a00ff" } ] },{ "featureType": "road", "stylers": [ { "hue": "#ff00cc" } ] },{ "featureType": "poi.park", "stylers": [ { "hue": "#2bff00" } ] },{ },{ } ]' );
			}
			// get approx current location but ip
			// $coords = getCurrentLocation($base_path);
			$current_lon = '-1.4869194'; // $coords[0];
			$current_lat = '53.3513802'; //$coords[1];
			if ( $this->show_you_are_here ) {
				$this->mymap->setCenterCoords( $current_lon, $current_lat );
				$this->mymap->addMarkerByCoords( $current_lon, $current_lat, 'You are somewhere near here.', $html, $tooltip, $default_pin );
			}
			// clustering reduces the number of pins on a busy map and groups them together
			if ( $this->enableClustering ) {
				//Enable Marker Clustering
				$this->mymap->enableClustering();
				//Set options (passing nothing to set defaults, just demonstrating usage
				$this->mymap->setClusterOptions();
				//Set MarkerCluster library location
				$this->mymap->setClusterLocation( "/modules/maps/lib/markerclusterer_compiled.js" );
			}
			// add these to the header - hijacking the title element here ! - Yes - it is horrible
			$output = $this->mymap->getHeaderJS() . $this->mymap->getMapJS();// .
			if ( $this->show_you_are_here ) {
				$output .= "<script>current_lon ='" . $current_lon . "'  ; current_lat = '" . $current_lat . "';</script>";
			}
                        
			// in a full page system this want to land in the header
			return $output;
		}

		/***
		 * look up map locations from database and generate the list of coordinated and pin text as required
		 ***/
		function addMarkerstoMap() {
			$sql    = 'SELECT * FROM map where published= 1  ' . $this->limit_markers;
			$result = mysql_query( $sql );
			while ( $row = mysql_fetch_array( $result ) ) {
				$address = trim( $row['mapping_address1'] ) . ',<br>' . trim( $row['mapping_address2'] ) . ',<br>' . trim( $row['mapping_address3'] )
				           . ',<br>' . trim( $row['mapping_postalcode'] );
				$address = str_replace( ',<br>,<br>', ',<br>', $address );
				if ( $row['mapping_email'] != '' ) {
					$address .= '<br><a href="mailto:' . $row['mapping_email'] . '" >' . $row['mapping_email'] . '</a>';
				}
				$title = $row['title'];
				$html  = '<div class=\'infowindow\'><h2>' . $title . '</h2><b>' . ucfirst( trim( $row['firstname'] ) ) . ' ' .
				         ucfirst( trim( $row['surname'] ) ) . ' </b> <div class=address>' . $address . '</div>';
				$html .= '<div><a href="/locations/' . $row['page_name'] . '">See Site Details</a></div>';
				$pin = $this->default_pin;
				// hospice
				if ( $row['map_business_type'] == '1' ) {
					$pin = $this->second_pin;
				}
				// shop
				if ( $row['map_business_type'] == '2' ) {
					$pin = $this->partner_pin;
				}
				$html .= '</div>';
				$tooltip = $row['business_name'];
				$lon     = $row['lon'];
				$lat     = $row['lat'];

				if ( $lon != '' ) {
					$this->mymap->addMarkerByCoords( $lon, $lat, $title, $html, $tooltip, $pin );
				}
			} // end while
		}
	}