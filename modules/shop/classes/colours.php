<?php

	class colours {

		// TODO: when editing a colour run it through this function to say if text should be light or dark
		// see delicious book mark tags(colour label) for details and link to js version
		function getContrastYIQ( $hexcolor ) {
			$r   = hexdec( substr( $hexcolor, 0, 2 ) );
			$g   = hexdec( substr( $hexcolor, 2, 2 ) );
			$b   = hexdec( substr( $hexcolor, 4, 2 ) );
			$yiq = ( ( $r * 299 ) + ( $g * 587 ) + ( $b * 114 ) ) / 1000;
			return ( $yiq >= 128 ) ? 'black' : 'white';
		}

		function getColourDetailsFromListAsJSArray( $ids ) {
			// if not new product or errror
			if ( $ids != '' ) {
				// todo: remove distinct
				$sql    = "SELECT distinct d.id, d.name, d.pantone, d.`rgb`  FROM colour_colour_details d
			WHERE d.id in (" . $ids . ")";
				$s      = ' [';
				$result = mysql_query( $sql );
				while ( $row = mysql_fetch_array( $result ) ) {
					$s .= "[" . $row['id'] . "," . $row['dc_id'] . ",'" . $row['name'] . "','" . $row['pantone'] . "','#" . $row['rgb'] . "'," . $row['season_id'] . "," . $row['tone_id'] . "],";
				}
				// knock final comma
				$s = substr( $s, 0, strlen( $s ) - 1 );
				// add closing bracket, but only if we found some items
				if ( $s != ' ' ) {
					$s .= ']';
				}
				return $s;
			} else {
				return '';
			}
		}

		function getWebColourFromId( $id ) {
			if ( $id != '' ) {
				// todo: remove distinct
				$sql    = "SELECT *  FROM colour_colour_details d
	   		WHERE id =" . $id;
				$result = mysql_query( $sql );
				while ( $row = mysql_fetch_array( $result ) ) {
					$colour = $row[3];
				}
				return '#' . $colour;
			} else {
				return '';
			}
		}

		function getProductColoursAsJSArrayNoSeasonTone( $product_id ) {
			if ( $product_id > 0 ) {
				$sql = "SELECT d.id, d.name, d.pantone, d.`rgb` FROM colour_colour_details d
	   		INNER JOIN shop_product_colour pc ON pc.colour_id = d.id
	   		WHERE pc.product_id =" . $product_id . " order by pc.order_no ";
				$s = ' [';
				$result = mysql_query( $sql );
				while ( $row = mysql_fetch_array( $result ) ) {
					$s .= "[" . $row['id'] . ",'" . $row['name'] . "','" . $row['pantone'] . "','#" . $row['rgb'] . "'],";
				}
				// knock final comma
				$s = substr( $s, 0, strlen( $s ) - 1 );
				// add closing bracket, but only if we found some items
				if ( $s != ' ' ) {
					$s .= ']';
				}
				return $s;
			} else {
				return '';
			}
		}

		function getNonColourDetails() {
			// TODO: GL  add to db $NON_COLOUR_ID
			$NON_COLOUR_ID = 200;
			$sql           = " SELECT * FROM colour_colour_details WHERE id = " . $NON_COLOUR_ID;
			$result        = mysql_query( $sql );
			while ( $row = mysql_fetch_array( $result ) ) {
				$s .= "[" . $row['id'] . ",'" . $row['name'] . "','" . $row['pantone'] . "','#" . $row['rgb'] . "'],";
			}
			// knock final comma
			$s = substr( $s, 0, strlen( $s ) - 1 );
			// add closing bracket, but only if we found some items
			if ( trim( $s ) != '' ) {
				//	$s .= ']';
			} else {
				$s .= '[]';
			}
			return $s;
		}

		function getProductColoursAsJSArray( $product_id ) {
			// if not new product or errror
			if ( $product_id > 0 ) {
				$sql    = "SELECT d.id, d.name, d.pantone, d.`rgb` FROM colour_colour_details d
    	 JOIN shop_product_colour pc ON pc.colour_id = d.id
    	WHERE pc.product_id =" . $product_id . "
	and published = 1
    	order by d.id ";
				$s      = ' [';
				$result = mysql_query( $sql );
				while ( $row = mysql_fetch_array( $result ) ) {
					$s .= "[" . $row['id'] . ",'" . $row['name'] . "','" . $row['pantone'] . "','#" . $row['rgb'] . "'],";
				}
				// knock final comma
				$s = substr( $s, 0, strlen( $s ) - 1 );
				// add closing bracket, but only if we found some items
				if ( trim( $s ) != '' ) {
					$s .= ']';
				} else {
					$s .= '[]';
				}
				return $s;
			} else {
				return '[]';
			}
		}

		function getProductColours( $product_id ) {
			$s = '';
			if ( $product_id > 0 ) {
				$sql = "SELECT colour_id from shop_product_colour where product_id = " . $product_id . " order by order_num ";
				$result = mysql_query( $sql );
				while ( $row = mysql_fetch_array( $result ) ) {
					$s .= $row['colour_id'] . ",";
				}
				$s = substr( $s, 0, strlen( $s ) - 1 );
				// echo $s;
			}
			return $s;
		}

		function getAllColoursAsJSArrayNoSeasonTone() {
			$sql    = 'SELECT * FROM colour_colour_details d where published = 1';
			$s      = ' [';
			$result = mysql_query( $sql );
			while ( $row = mysql_fetch_array( $result ) ) {
				$s .= "[" . $row['id'] . ",'" . $row['name'] . "','" . $row['pantone'] . "','#" . $row['rgb'] . "'],";
			}
			// knock final comma
			$s = substr( $s, 0, strlen( $s ) - 1 );
			$s .= ']';
			return $s;
		}

		function getAllColoursAsJSArray() {
			$sql = 'SELECT * FROM colour_colour_details d where published = 1 ORDER BY order_num ASC';
			$s      = ' [';
			$result = mysql_query( $sql );
			while ( $row = mysql_fetch_array( $result ) ) {
				$s .= "[" . $row['id'] . ",'" . $row['name'] . "','" . $row['pantone'] . "','#" . $row['rgb'] . "','" . $row['filename'] . "'],";
			}
			// knock final comma
			$s = substr( $s, 0, strlen( $s ) - 1 );
			$s .= ']';
			return $s;
		}
	}

?>
