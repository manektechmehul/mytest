<?php

	include '../../../admin/classes/template.php';

	class order_items extends template {

		function order_items() {
			$this->template();
			$this->table       = 'shop_order_item_lottery';
			$this->group_name  = 'Lottery Items';
			$this->single_name = 'Lottery ticket';
			$this->singular    = 'an';
			$this->max_items   = - 1;
			$fpid = $_GET['fpid'];
			//	$this->parent_id = $order_id;
			//		$this->parent_field = 'order_id';
			//	$this->parent_id_name = 'order';
			//$this->list_top_text = sprintf ("<a href=\"categories.php\" onMouseOut=\"MM_swapImgRestore()\" onMouseOver=\"MM_swapImage('defaultcategories','','/admin/images/buttons/cmsbutton-Categories-over.gif',0)\"><img style='border:none' src='./images/buttons/cmsbutton-Categories-off.gif' name='defaultcategories'></a>", $PHP_SELF);
			$this->ToolbarSet   = 'Default';
			$this->where_clause = " where futurePayId =" . $fpid;
			$this->debug_log       = false;
			$this->custom_list_sql = "SELECT soil.*, soi.description FROM shop_order_item soi  INNER JOIN shop_order so ON so.id = soi.`order_id`
  INNER JOIN shop_order_item_lottery soil
    ON soil.`shop_order_item_id` = soi.`id`
WHERE soi.product_type = 3
  AND soi.product_id = 2
  AND soil.futurePayId = {$fpid} order by 1 desc";
			$this->buttons = array(
				'edit' => array( 'text' => 'add', 'type' => 'standard_edit' ),
				'delete' => array( 'text' => 'delete', 'type' => 'standard_delete' ),
			);
			$this->fields  = array(
				'timestamp' => array( 'name' => 'Sale Date', 'formtype' => 'text', 'list' => true, ),
				'tickets' => array( 'name' => 'Tickets', 'formtype' => 'text', 'list' => false, ),
				'weeks' => array( 'name' => 'weeks', 'formtype' => 'text', 'list' => false, ),
				'transaction_details' => array( 'name' => 'Transaction Details::', 'formtype' => 'lookup', 'function' => 'transaction_details' ),
			);
			//$this->links = array( 'category' => array('link_table' => 'case_study_category', 'table' => 'category', 'name' => 'title') );
		}

		function transaction_details() {
			$o   = "";
			$row = db_get_single_row( "SELECT  * FROM  `shop_order_item_lottery` WHERE  id =" . $_GET['id'] );
			$o .= "Future Pay ID :" . $row['futurePayId'] . "<br>";
			$o .= "Transaction ID :" . $row['transId'] . "<br>";
			$o .= "Post Data :" . $row['post_data'] . "<br>";
			$o .= "Time Stamp :" . $row['timestamp'] . "<br>";
			return $o;
		}

		function onload() {
			// echo 'constants sre ' .  USE_TRADE_PRODUCTS . USE_COLOURS . USE_GENDER . USE_SIZE ;
			$this->list_top_text .= "<h3>These items all have the futurePayId: <strong>" . $_GET['fpid'] . "</strong>";
			$sql = "SELECT
    `shop_customer`.*
    , `shop_order_item_lottery`.`futurePayId`
FROM
    `RHospice`.`shop_order`
    INNER JOIN `RHospice`.`shop_order_item`
        ON (`shop_order`.`id` = `shop_order_item`.`order_id`)
    INNER JOIN `RHospice`.`shop_customer`
        ON (`shop_order`.`customer_id` = `shop_customer`.`id`)
    INNER JOIN `RHospice`.`shop_order_item_lottery`
        ON (`shop_order_item_lottery`.`shop_order_item_id` = `shop_order_item`.`id`)
WHERE (`shop_order_item_lottery`.`futurePayId` = " . $_GET['fpid'] . ");";
			$row = db_get_single_row( $sql );
			$this->list_top_text .= "   Customer is: <strong>" . $row['firstname'] . " " . $row['surname'] . "</strong>";
			$this->list_top_text .= "   Address: <strong>" . $row['billing_address1'] . "," . $row['billing_address2'] . " </strong> </h3>";
			//    if(SHOP_USE_COLOURS){
			//   $this->fields['colours'] = array('name' => 'Colour', 'formtype' => 'lookup', 'list' => true, 'list_prefix' => ' ', 'required' => false, 'function' => 'get_colour_desc', 'titlefunction' => 'get_colour_desc');
			//     }
		}

		function get_crumbs() {
			return "<a href='main.php'>Lottery Admin</a> > <b>{$this->single_name} Admin</b>";
		}

		function get_product_desc( $product_id ) {
			return db_get_single_value( "select name from shop_product where id = '$product_id'", 'name' );
		}

		// TODO :: don't do a look up as name may have changed .. pul back data from order line
		function get_colour_desc( $colour_id ) {
			// echo "select name from colour_colour_details where id = '$colour_id'";
			return db_get_single_value( "select colour_name from colour_colour_details where id = '$colour_id'", 'name' );
		}

		function get_size_desc( $id ) {
			// echo "select name from colour_colour_details where id = '$colour_id'";
			return db_get_single_value( "select title from shop_size where id = '$id'", 'title' );
		}

		function get_gender_desc( $id ) {
			// echo "select name from colour_colour_details where id = '$colour_id'";
			return db_get_single_value( "select title from shop_gender where id = '$id'", 'title' );
		}
	}

	$template         = new order_items();
	$main_page        = 'index.php';
	$main_title       = 'Return to main page';
	$admin_tab        = "shop";
	$second_admin_tab = "orders";
	include 'second_level_navigation.php';
	include( "../../../admin/template.php" );
?>
