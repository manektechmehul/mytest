<?php

	class bulk_discount {

		function updated_discounts( $product_id, $discount_product_ids, $discounts ) {
			$errors            = "";
			$discount_group_id = db_get_single_value( 'SELECT bulk_discount_id FROM shop_product WHERE id =' . $product_id, 'bulk_discount_id' );
			// if group = 0 then create a new group ....
			// echo $product_id . ' >> '  .  $discount_product_ids . ' >> '  .  $discounts . ' >> ' . $discount_group_id . '<br>' ;
			if ( $discount_group_id == 0 ) {
				// go create one ...
				$discounts = explode( ",", $discounts );
				$sql       = "insert shop_bulk_discount SET items1 = $discounts[0], dis1 = $discounts[1], items2= $discounts[2] , dis2 = $discounts[3],
			items3 = $discounts[4], dis3 = $discounts[5] ";
				$result    = mysql_query( $sql );
				if ( ! $result ) {
					$errors .= mysql_error();
				}
				$discount_group_id = db_get_single_value( 'SELECT LAST_INSERT_ID() as bulk_discount_id ', 'bulk_discount_id' );
			} else {
				// if exists -update the discount values
				$discounts = explode( ",", $discounts );
				$sql       = "UPDATE shop_bulk_discount SET items1 = $discounts[0], dis1 = $discounts[1], items2= $discounts[2] , dis2 = $discounts[3],
			items3 = $discounts[4], dis3 = $discounts[5] WHERE id = " . $discount_group_id;
				$result    = mysql_query( $sql );
				if ( ! $result ) {
					$errors .= mysql_error();
				}
			}
			// then update all the products in the group - first remove all from the group then re-add ?
			$sql = "UPDATE shop_product SET bulk_discount_id = 0 WHERE bulk_discount_id = " . $discount_group_id;
			//echo $sql;
			$result = mysql_query( $sql );
			if ( ! $result ) {
				$errors .= mysql_error();
			}
			$sql    = "UPDATE shop_product SET bulk_discount_id = $discount_group_id WHERE id IN ($discount_product_ids)";
			$result = mysql_query( $sql );
			if ( ! $result ) {
				$errors .= mysql_error();
			}
			if ( $error != '' ) {
				echo $error;
				die();
			}
		}

		function getGroupProducts( $product_id ) {
			// get all the items that share the group with our current product
			$discount_group_id = db_get_single_value( 'SELECT bulk_discount_id FROM shop_product WHERE id =' . $product_id, 'bulk_discount_id' );
			if ( $discount_group_id == 0 ) {
				return array( '[]', '[]', 0 );
			}
			$sql      = "SELECT id, `name` FROM shop_product WHERE bulk_discount_id = " . $discount_group_id . " order by name asc ";
			$just_ids = "";
			$s        = ' [';
			$result   = mysql_query( $sql );
			while ( $row = mysql_fetch_array( $result ) ) {
				$s .= "[" . $row['id'] . ",'" . str_replace( "'", "`", $row['name'] ) . "'],";
				$just_ids .= $row['id'] . ",";
			}
			// knock final comma
			$s        = substr( $s, 0, strlen( $s ) - 1 );
			$just_ids = substr( $just_ids, 0, strlen( $just_ids ) - 1 );
			// add closing bracket, but only if we found some items
			if ( $s != ' ' ) {
				$s .= ']';
			}
			return array( $s, $just_ids, $discount_group_id );
		}

		function getDiscountGroupDetails( $group_id ) {
			$result = mysql_query( "SELECT  items1, dis1, items2, dis2,items3, dis3 FROM shop_bulk_discount where id =  " . $group_id );
			if ( ! $result ) {
				// failed -  return empty array
				return array();
			}
			$row = mysql_fetch_row( $result );
			if ( $row != '' ) {
				$details = implode( ",", $row );
			} else {
				$details = "0,0,0,0,0,0";
			}
			// implode to csv
			return $details;
		}

		function getAllActiveProducts() {
			// purposely left i items that are not online, if they are about to go live
			$sql    = "SELECT id, `name` FROM shop_product ORDER BY  `name` ASC ";
			$s      = ' [';
			$result = mysql_query( $sql );
			while ( $row = mysql_fetch_array( $result ) ) {
				$s .= "[" . $row['id'] . ",'" . str_replace( "'", "`", $row['name'] ) . "'],";
			}
			// knock final comma
			$s = substr( $s, 0, strlen( $s ) - 1 );
			// add closing bracket, but only if we found some items
			if ( $s != ' ' ) {
				$s .= ']';
			}
			return $s;
		}

	}// end class
?>