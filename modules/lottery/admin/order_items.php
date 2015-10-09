<?php

include '../../../admin/classes/template.php';

class order_items extends template {

    function order_items() {
        $this->template();
        $this->table = 'shop_order_item';
        $this->group_name = 'Lottery Items';
        $this->single_name = 'Lottery ticket';
        $this->singular = 'an';
        $this->max_items = -1;
		
        $order_id = $_GET['order'];
        $this->parent_id = $order_id;
        $this->parent_field = 'order_id';
        $this->parent_id_name = 'order';
        //$this->list_top_text = sprintf ("<a href=\"categories.php\" onMouseOut=\"MM_swapImgRestore()\" onMouseOver=\"MM_swapImage('defaultcategories','','/admin/images/buttons/cmsbutton-Categories-over.gif',0)\"><img style='border:none' src='./images/buttons/cmsbutton-Categories-off.gif' name='defaultcategories'></a>", $PHP_SELF);
        $this->ToolbarSet = 'Default';
	    $this->where_clause = " where product_type = 3 ";
		
	$this->debug_log = false;
	//$this->custom_list_sql = "SELECT * FROM shop_order_item where order_id = {$order_id} order by 1 ";
		
        $this->buttons = array(
            'edit' => array('text' => 'add', 'type' => 'standard_edit'),
            'delete' => array('text' => 'delete', 'type' => 'standard_delete'),
        );
        $this->fields = array(
            'description' => array('name' => 'Product Description', 'formtype' => 'text', 'list' => true,),
         
            // todo: add these as optional fields 
            
         //   'gender_name' => array('name' => 'Gender', 'list' => true),
          //  'size_name' => array('name' => 'Size', 'list' => true),
          //  'colour_name' => array('name' => 'Colour', 'list' => true),
            
            
            
            
            'quantity' => array('name' => 'Quantity', 'formtype' => 'text', 'list' => true, 'list_prefix' => ' x ', 'required' => true),

	        'donation_location' => array( 'name' => 'Transaction Details::', 'formtype' => 'lookup', 'function' => 'transaction_details' ),

        );
        //$this->links = array( 'category' => array('link_table' => 'case_study_category', 'table' => 'category', 'name' => 'title') );
    }


function transaction_details(){
	$o = "";
	$row = db_get_single_row( "SELECT  * FROM  `shop_order_item_lottery` WHERE shop_order_item_id =" . $_GET['id'] );
	$o .= "Future Pay ID :" . $row['futurePayId'] . "<br>";
	$o .= "Transaction ID :" . $row['transId'] . "<br>";
	$o .= "Post Data :" . $row['post_data'] . "<br>";



	return $o;
}

  function onload(){
       // echo 'constants sre ' .  USE_TRADE_PRODUCTS . USE_COLOURS . USE_GENDER . USE_SIZE ;
        
     
    //    if(SHOP_USE_COLOURS){
    
			//   $this->fields['colours'] = array('name' => 'Colour', 'formtype' => 'lookup', 'list' => true, 'list_prefix' => ' ', 'required' => false, 'function' => 'get_colour_desc', 'titlefunction' => 'get_colour_desc');
   //     }
		
  }
    function get_crumbs() {
        return "<a href='main.php'>Lottery Admin</a> > <b>{$this->single_name} Admin</b>";
    }

    function get_product_desc($product_id) {
        return db_get_single_value("select name from shop_product where id = '$product_id'", 'name');
    }

    
    // TODO :: don't do a look up as name may have changed .. pul back data from order line
    function get_colour_desc($colour_id) {
        // echo "select name from colour_colour_details where id = '$colour_id'"; 
        return db_get_single_value("select colour_name from colour_colour_details where id = '$colour_id'", 'name');
    }
    
     function get_size_desc($id) {
        // echo "select name from colour_colour_details where id = '$colour_id'"; 
        return db_get_single_value("select title from shop_size where id = '$id'", 'title');
    }
     function get_gender_desc($id) {
        // echo "select name from colour_colour_details where id = '$colour_id'"; 
        return db_get_single_value("select title from shop_gender where id = '$id'", 'title');
    }
}

$template = new order_items();
$main_page = 'index.php';
$main_title = 'Return to main page';
$admin_tab = "shop";
$second_admin_tab = "orders";
include 'second_level_navigation.php';
include ("../../../admin/template.php");
?>
