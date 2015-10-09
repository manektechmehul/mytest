<?php
	include '../../../admin/classes/template.php';
	class main extends template {
		function main() {
			$this->template();
			$this->debug_log = false;
			$this->php_debug = false;
			/* #module specific */
			$this->table = 'booking_product';
			/* #module specific */
			$this->group_name = 'Event Products';
			/* #module specific */
			$this->single_name     = 'Product';
			$this->ordered         = true;
			$this->order_clause    = ' order_num desc ';
			$this->singular        = 'a';
			$this->hideable        = true;
			$this->list_top_text   = "<h3><a href='main.php' ><< Back to Event List</a></h3>";
			$this->javascript_file = array( '/modules/booking/admin/js/admin.js');
			$this->ToolbarSet      = 'Default';
			$this->has_page_name   = true;
			// This will allow us to recieve a parent id into the this admin area and pass it around
			$parent_id             = $_GET['parent_id']; //get the parent id
			$this->parent_id       = $parent_id;
			$this->parent_field    = 'booking_id'; //this is the table column name
			$this->parent_id_name  = 'parent_id'; // url parameter name
			$this->custom_list_sql = 'SELECT * from booking_product where booking_id =' . $_GET['parent_id'] . ' order by order_num ';
			$this->buttons         = array(
				'edit'   => array( 'text' => 'add', 'type' => 'standard_edit' ),
				'hide'   => array( 'text' => 'hide', 'type' => 'standard_hide' ),
				'delete' => array( 'text' => 'delete', 'type' => 'standard_delete' ),
				'move'   => array( 'text' => 'move', 'type' => 'standard_move' ),
			);
			$this->fields     = array(
				'title'       => array( 'name' => 'Title', 'formtype' => 'text', 'list' => true, 'required' => true, 'primary' => true ),
				'description' => array( 'name' => 'Notes', 'formtype' => 'textarea', 'list' => false, 'rows' => 3, 'cols' => 60, 'required' => false ),
				'thumb'       => array( 'name' => 'Thumbnail', 'formtype' => 'image', 'list' => false, 'required' => false, 'size' => 2 ),
				'price'       => array( 'name' => 'Price (inc Vat and Delivery Costs)', 'formtype' => 'shorttext', 'required' => true ),
			);
		}

	function onload()
	{
		$this->list_top_text = "These items are for  event ::  " .   db_get_single_value("select title from booking where id = " . $_GET['parent_id'] . ' ');
	}


		function get_crumbs( $page ) {
			if ( $page == '' ) {
				return "<b>{$this->single_name} Admin</b>";
			} else {
				return "<a href='main.php'>{$this->single_name} Admin</a> > <b>$page</b>";
			}
		}
	}

	$template   = new main();
	$main_page  = 'index.php';
	$main_title = 'Return to main page';
	/* #module specific */
	$admin_tab = "booking";
	/* #module specific */
	$second_admin_tab = "booking";
	include 'second_level_navigation.php';
	include( "../../../admin/template.php" );
?>


