<?php

	include '../../../admin/classes/template.php';

	class order_items extends template {
		function order_items() {
			$this->template();
			$this->table       = 'shop_order_item';
			$this->group_name  = 'Booking and Event Tickets Only';
			$this->single_name = 'Tickets';
			$this->singular    = 'an';
			$this->max_items   = - 1;
			$order_id             = $_GET['order'];
			$this->parent_id      = $order_id;
			$this->parent_field   = 'order_id';
			$this->parent_id_name = 'order';
			//$this->list_top_text = sprintf ("<a href=\"categories.php\" onMouseOut=\"MM_swapImgRestore()\" onMouseOver=\"MM_swapImage('defaultcategories','','/admin/images/buttons/cmsbutton-Categories-over.gif',0)\"><img style='border:none' src='./images/buttons/cmsbutton-Categories-off.gif' name='defaultcategories'></a>", $PHP_SELF);
			$this->ToolbarSet   = 'Default';
		//	$this->where_clause = " where product_type = 2 and booking";
			$this->debug_log = true;
			/// product_type = 2 AND
			 $this->custom_list_sql = "SELECT * FROM shop_order_item WHERE  product_id IN (
			SELECT id FROM booking_ticket WHERE booking_id = " . $_GET['parent_id'] . ")";
			$this->buttons = array(
				'edit'   => array( 'text' => 'add', 'type' => 'standard_edit' ),
				'delete' => array( 'text' => 'delete', 'type' => 'standard_delete' ),
			);
			$this->fields  = array(
				'description' => array( 'name' => 'Product Description', 'formtype' => 'text', 'list' => true, ),
				// todo: add these as optional fields
				//   'gender_name' => array('name' => 'Gender', 'list' => true),
				//  'size_name' => array('name' => 'Size', 'list' => true),
				//  'colour_name' => array('name' => 'Colour', 'list' => true),
				'quantity'    => array( 'name' => 'Quantity', 'formtype' => 'text', 'list' => true, 'list_prefix' => ' x ', 'required' => true )
			);
			//$this->links = array( 'category' => array('link_table' => 'case_study_category', 'table' => 'category', 'name' => 'title') );
		}
		function onload() {
			// echo 'constants sre ' .  USE_TRADE_PRODUCTS . USE_COLOURS . USE_GENDER . USE_SIZE ;
			//    if(SHOP_USE_COLOURS){
			//   $this->fields['colours'] = array('name' => 'Colour', 'formtype' => 'lookup', 'list' => true, 'list_prefix' => ' ', 'required' => false, 'function' => 'get_colour_desc', 'titlefunction' => 'get_colour_desc');
			//     }
		}
		function get_crumbs() {
			return "<a href='orders.php'>Booking and Event Sales Admin</a> > <b>{$this->single_name} Admin</b>";
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
	$admin_tab        = "booking";
	$second_admin_tab = "orders";
	include 'second_level_navigation.php';
	include( "../../../admin/template.php" );
?>
