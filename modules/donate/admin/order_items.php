<?php

	include '../../../admin/classes/template.php';

	class order_items extends template {

		function order_items() {
			$this->template();
			$this->table          = 'shop_order_item';
			$this->group_name     = 'Donations';
			$this->single_name    = 'Donation';
			$this->singular       = 'an';
			$this->max_items      = - 1;
			$order_id             = $_GET['order'];
			$this->parent_id      = $order_id;
			$this->parent_field   = 'order_id';
			$this->parent_id_name = 'order';
			$this->ToolbarSet   = 'Default';
			$this->debug_log    = false;
			$this->where_clause = "where product_type = 1 ";
			$this->buttons = array(
				'edit' => array( 'text' => 'add', 'type' => 'standard_edit' ),
				'delete' => array( 'text' => 'delete', 'type' => 'standard_delete' ),
			);
			$this->fields  = array(
				'donation_location' => array( 'name' => 'Page Donation was made on page :: ', 'formtype' => 'lookup', 'function' => 'donation_location' ),
				'description' => array( 'name' => 'Product Description', 'formtype' => 'text', 'list' => true ),
				'price' => array( 'name' => 'Donation', 'formtype' => 'text' ),
				'quantity' => array( 'name' => 'Quantity', 'formtype' => 'text', 'list' => true, 'list_prefix' => ' x ', 'required' => true ),
				'gift_aid' => array( 'name' => 'Gift Aid', 'formtype' => 'checkbox', 'formtype' => 'lookup', 'function' => 'donation_giftaid' ),
			);
		}

		function donation_giftaid() {
			$ga = db_get_single_value( "SELECT  `gift_aid` FROM  `shop_order_item_donate` WHERE shop_order_item_id =" . $_GET['id'] );
			return $ga;
		}

		function donation_location() {
			$loc = db_get_single_value( "SELECT  `donation_location` FROM  `shop_order_item_donate` WHERE shop_order_item_id =" . $_GET['id'] );
			return $loc;
		}

		function get_crumbs() {
			return "<a href='orders.php'>Donations Admin</a> > <b>{$this->single_name} Admin</b>";
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
