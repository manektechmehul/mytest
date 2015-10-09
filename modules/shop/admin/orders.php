<?php

include '../../../admin/classes/template.php';
 
class customer extends template {

    function customer() {
        $this->template();
        $this->table = 'shop_customer';
        $this->fields = array(
            'firstname' => array('name' => 'First Name', 'formtype' => 'text', 'list' => true, 'required' => true, 'primary' => true),
            'surname' => array('name' => 'Surname', 'formtype' => 'text', 'list' => true, 'required' => false),
            'phone' => array('name' => 'phone', 'formtype' => 'shorttext', 'list' => false, 'required' => false),
            'email' => array('name' => 'email', 'formtype' => 'shorttext', 'list' => false, 'required' => false),
            'billing_address1' => array('name' => 'Billing Address', 'formtype' => 'text', 'list' => false, 'required' => false),
            'billing_address2' => array('name' => ' ', 'formtype' => 'text', 'list' => false, 'required' => false),
            'billing_address3' => array('name' => ' ', 'formtype' => 'text', 'list' => false, 'required' => false),
            'billing_postalcode' => array('name' => 'Billing Postcode', 'formtype' => 'shorttext', 'list' => false, 'required' => false),
            'billing_country' => array('name' => 'Billing Country', 'formtype' => 'shorttext', 'list' => false, 'required' => false),
            'delivery_address1' => array('name' => 'Delivery Address', 'formtype' => 'text', 'list' => false, 'required' => false),
            'delivery_address2' => array('name' => 'Delivery Address', 'formtype' => 'text', 'list' => false, 'required' => false),
            'delivery_address3' => array('name' => 'Delivery Address', 'formtype' => 'text', 'list' => false, 'required' => false),
            'delivery_postalcode' => array('name' => 'Delivery Postcode', 'formtype' => 'shorttext', 'list' => false, 'required' => false),
            'delivery_country' => array('name' => 'Billing Country', 'formtype' => 'shorttext', 'list' => false, 'required' => false),
                //'instructions' => array('name' => 'Special Instructions', 'formtype' => 'textarea', 'list' => false, 'rows' => 3, 'cols' => 60, 'required' => true),
        );
    }

}
 

class orders extends template {

    function orders() {
        $this->template();
        $this->table = 'shop_order';
        $this->group_name = 'Orders';
        $this->single_name = 'Order';
        $this->singular = 'an';
        $this->hideable = true;
        $this->order_clause = 'datestamp desc';
        $this->max_items = -1;
        $this->javascript_file = '/modules/shop/admin/js/orders.js';
    //    $this->grouping = "status";
  //      $this->grouping_name_function = 'group_name';
        //$this->delete_field = 'status';
       $this->customer = new customer();
    //    $this->grouping = 'order_status';
    //    $this->grouping_name_function = 'get_status_name';
        $this->ToolbarSet = 'Default';
        $this->customer_filter_bar = true;
        $this->custom_filter_bar_include_file = "orders_filter_bar.php";
        
        $this->php_debug = true;
        if ($_GET['incomplete'] == 'yes'){
            $status_clause = ' = 0 ';
            $this->single_name = 'Incomplete Order';
            $this->list_top_text = sprintf("<a href=\"orders.php\" onMouseOut=\"MM_swapImgRestore()\" onMouseOver=\"MM_swapImage('defaultorders','','/admin/images/buttons/cmsbutton-Orders-over.gif',0)\"><img style='border:none' src='/admin/images/buttons/cmsbutton-Orders-off.gif' name='defaultorders'></a>", $PHP_SELF);
            $this->list_top_text .= '<br /><br />The following orders may be in-process, abandoned by the customer or have a payment issue';
        }else{
            $status_clause = ' > 0 ';
          //  $this->list_top_text = sprintf("<a href=\"orders.php?incomplete=yes\" onMouseOut=\"MM_swapImgRestore()\" onMouseOver=\"MM_swapImage('defaultorders','','/admin/images/buttons/cmsbutton-Incomplete_Orders-over.gif',0)\"><img style='border:none' src='/admin/images/buttons/cmsbutton-Incomplete_Orders-off.gif' name='defaultorders'></a>", $PHP_SELF);
        }
        $this->custom_list_sql = 'select c.*, o.id, o.status as order_status, unix_timestamp(o.time_made) as time_made from shop_order o join shop_customer c on customer_id = c.id where o.status ' . $status_clause . ' order by o.status, o.time_made';
        $this->buttons = array(
            // 'edit' => array( 'text' => 'edit/process', 'type' => 'standard_edit'),
            //'current_status' => array( 'type' =>'function', 'function' => 'get_current_status'),
            // 'pattern' => '/modules/shop/admin/orders.php?edit_customer=%s'
        //   'edit_customer' => array('text' => 'edit customer details', 'type' => 'function','function'=>'set_customer_id' ),
            
            'edit_customer' => array('text' => 'edit customer details', 'type' => 'function','function'=>'set_customer_id' ),
            'items' => array('text' => 'edit order items', 'type' => 'button', 'pattern' => '/modules/shop/admin/order_items.php?order=%s'),
            'progress_order' => array('text' => 'change status', 'type' => 'button', 'pattern' => '/modules/shop/admin/orders.php?change_status=%s'),
            'resend_email' => array('text' => 'resend email', 'type' => 'jsbutton', 'pattern' => '#', 'js' => "onclick='return resend_email(%s)'"),
            'delete' => array('text' => 'delete', 'type' => 'standard_delete'),
	        'collection' => array('text' => 'Collection Only', 'type' => 'function','function'=>'collection' ),
           
        );
        $this->fields = array(
       
            // this is actually showing the customer id !!! - is it then passin in customer id to the other functions ?
            'id' => array('name' => 'Order ID', 'formtype' => 'text', 'list' => true, 'required' => true, 'primary' => true, 'list_prefix' => 'Order:'),
            'firstname' => array('name' => 'First Name', 'formtype' => 'text', 'list' => true, 'required' => true, 'primary' => true, 'list_prefix' => '-'),
            'surname' => array('name' => 'Surname', 'formtype' => 'text', 'list' => true, 'required' => false),
            'time_made_formated' => array('name' => 'Date', 'formtype' => 'staticdate', 'list' => true, 'required' => false, 'list_prefix' => ' on '),
            'phone' => array('name' => 'phone', 'formtype' => 'shorttext', 'list' => false, 'required' => false),
            'email' => array('name' => 'email', 'formtype' => 'shorttext', 'list' => false, 'required' => false),
            'billing_address' => array('name' => 'Billing Address', 'formtype' => 'address', 'list' => false, 'required' => false),
            'billing_postcode' => array('name' => 'Billing Postcode', 'formtype' => 'shorttext', 'list' => false, 'required' => false),
            'delivery_address' => array('name' => 'Delivery Address', 'formtype' => 'address', 'list' => false, 'required' => false),
            'delivery_postcode' => array('name' => 'Delivery Postcode', 'formtype' => 'shorttext', 'list' => false, 'required' => false),
                //'instructions' => array('name' => 'Special Instructions', 'formtype' => 'textarea', 'list' => false, 'rows' => 3, 'cols' => 60, 'required' => true),
        );
        //$this->links = array( 'category' => array('link_table' => 'case_study_category', 'table' => 'category', 'name' => 'title') );
        $this->actions = array(
                'change_status' => array('title' => 'Change Status', 'pagequerystring' => 'change_status',
                'pagemethod' => 'change_status', 'actionquerystring' => 'process_change_status', 'actionmethod' => 'process_change_status'),
                'edit_customer' => array('title' => 'Edit Customer Details', 'pagequerystring' => 'edit_customer',
                'pagemethod' => 'edit_customer', 'actionquerystring' => 'process_customer', 'actionmethod' => 'process_customer'),
        );
    }

	function collection($id, $row) {
		$sql = "SELECT collection FROM `shop_order` where id =  '$id'";
		$collection = db_get_single_value($sql);
		$href= '#';
		if($collection == 1) {
			$this->cms_admin_button( $href, 'contentbutton', "collection only", $href );
		}

	}

      function get_current_status($id, $row) {
        $sql = "SELECT sos.name FROM `shop_order` so JOIN shop_order_status sos ON so.status = sos.id  WHERE so.id =  '$id'";
        $statusName = db_get_single_value($sql);
        $href= '#';
        $statusName = $statusName;
        $this->cms_admin_button($href, 'contentbutton', $statusName, $href);
        
         
    }
     function show_row_title($content_row, $row_level, $row_title_class)
    {
        parent::show_row_title($content_row, $row_level, $row_title_class);
        $sql = "SELECT sos.name FROM `shop_order` so JOIN shop_order_status sos ON so.status = sos.id  WHERE so.id ={$content_row['id']}";
        $statusName = db_get_single_value($sql);
        echo '<div class="contentfieldactive">Status:'.$statusName .'</div>';
    }
    
    function set_customer_id($id, $row){      
        $customer_id = db_get_single_value("SELECT customer_id FROM shop_order WHERE id ={$id}");
        $href= '/modules/shop/admin/orders.php?edit_customer=' . $customer_id;
        $this->cms_admin_button($href, 'contentbutton', 'edit customer details', $href);     
    }
      
    
    function get_status_name($id, $row) {
        $sql = "SELECT sos.name FROM `shop_order` so JOIN shop_order_status sos ON so.status = sos.id  WHERE so.id =  '$id'";
        $statusName = db_get_single_value($sql);        
        return $statusName;
    }

    function change_status() {
        $order_id = $_REQUEST['change_status'];
        $status = db_get_single_value("select status from shop_order where id = '$order_id'", 'status');
        $sql = "select * from shop_order_status";
        $result = mysql_query($sql);
        echo '<div id="admin-page-content">';
        echo "<form action='' method=''post>\n";
        echo "<input type='hidden' name='order_id' value='$order_id' />";
        echo "<input type='hidden' name='process_change_status' value='process_change_status' />";
        echo "Order status: <select name='order_status'>";
        while ($row = mysql_fetch_array($result)) {
            $selected = ($status == $row['id']) ? ' selected="selected" ' : '';
            echo "<option value='{$row['id']}'$selected>{$row['name']}</option>\n";
        }
        echo '</select><br />';
        echo '<input type="submit" value="submit" />';
        echo '</form></div>';
    }

    function process_change_status() {
        $status = $_REQUEST['order_status'];
        $order_id = $_REQUEST['order_id'];
        $sql = "update shop_order set status = '$status' where id = '$order_id'";
        $result = mysql_query($sql);
        /// order complete - send completin email to sutomer
        if($status =='4'){
            global $base_path;
            // send email to customer
              include_once  $base_path.'/modules/shop/classes/order_confirmation_email.php';
              // echo 'alert("' . $base_path.'/modules/shop/classes/order_confirmation_email.php' .   '");';
              $o = new OrderConfirmationEmail();        
              $o->SendDispatchEmail($order_id);
        }
        
    }

    function edit_customer() {
        $customer_id = $_REQUEST['edit_customer'];
       // $customer_id = db_get_single_value("select customer_id from shop_order where id = $order_id", 'customer_id');
        $this->customer->parent_id_name = 'process_customer';
        $this->customer->show_edit($customer_id, $customer_id);
    }

    function process_customer() {
        $customer_id = $_REQUEST['process_customer'];
        $this->customer->get_form_data();
        return $this->customer->process_submit($customer_id);
    }

    function process_submit($id) {
        template::process_submit($id);
        $cust_id = db_get_single_value("select last_insert_id() as id", 'id');
        $sql = "insert into shop_order (customer_id, time_made, tender_type) values ($cust_id, now(), 4)";
        $result = mysql_query($sql);
    }

    function get_crumbs($page) {
        if ($page == '')
            return "<b>{$this->single_name} Admin</b>";
        else
            return "<a href='orders.php'>{$this->single_name} Admin</a> > <b>$page</b>";
    }
    
    function get_customer_filter_bar_custom_sql(){
        $where = " where ";
        $first = true;
			
        if($_GET['status'] != '-1'){
            if($_GET['status']!=''){
                $where .= " shop_order.status=". $_GET['status'] . " ";
                $first =false;
            }else{
                $where .= " shop_order.status != 0 ";
                $first =false;
            }
        }else{
            $where .= " shop_order.status != 0 ";
            $first =false;
        }
        if($_GET['id'] != ''){
              if(!$first){
                $where .= " and ";  
              }
                $where .= "  shop_order.id=". $_GET['id'] . " ";
                $first =false;
        }      
        if($_GET['start_date'] != ''){
              if(!$first){
                $where .= " and ";  
              }
                $where .= " time_made > '".  date("Y-m-d", strtotime($_GET['start_date'])) . "' ";
                $first =false;
        }        
        if($_GET['end_date'] != ''){
              if(!$first){
                $where .= " and ";  
              }
                $where .= " time_made < '".  date("Y-m-d", strtotime($_GET['end_date'])) . "' ";
                $first =false;
        }      
        if($_GET['email'] != ''){
              if(!$first){
                $where .= " and ";  
              }
                $where .= " email = '".   $_GET['email']  . "' ";
                $first =false;
        }         
        if($_GET['firstname'] != ''){
              if(!$first){
                $where .= " and ";  
              }
                $where .= " firstname = '".   $_GET['firstname']  . "' ";
                $first =false;
        }
        if($_GET['surname'] != ''){
              if(!$first){
                $where .= " and ";  
              }
                $where .= " surname = '".   $_GET['surname']  . "' ";
                $first =false;
        }                
        if($where==" where "){
            $where = '';
        }
        
		
		if(isset($_GET['order_by'])){
			$order = " order by ";
			if($_GET['order_by'] == "0"){
				$order = " ORDER BY  time_made DESC ";
			}
			if($_GET['order_by'] == "1"){
				$order = " ORDER BY  time_made ASC ";
			}
			if($_GET['order_by'] == "2"){
				$order = " ORDER BY time_made DESC";
			}
			if($_GET['order_by'] == "3"){
				$order = " ORDER BY surname ASC";
			}
			if($_GET['order_by'] == "4"){
				$order = " ORDER BY  surname DESC";
			}
			
			
		}else{
			 
			$order = " ORDER BY  time_made desc "; // shop_order.status,
		}	
		
		// select c.*, o.id, o.status as order_status, unix_timestamp(o.time_made) as time_made from shop_order o join shop_customer c on customer_id = c.id
                
                
                //INNER JOIN shop_customer ON shop_customer.`id` = shop_order.`customer_id`
        $sql = "SELECT shop_customer.firstname, shop_customer.surname, shop_order.*, unix_timestamp(shop_order.time_made) as time_made_formated  FROM shop_order INNER JOIN shop_customer ON shop_customer.`id` = shop_order.`customer_id`  " . $where  .  $order;
        
		//$sql = "select c.*, o.id, o.status as order_status, unix_timestamp(o.time_made) as time_made from shop_order o join shop_customer c on customer_id = c.id ";
		
		// echo $sql;
        return $sql;
    }

}

$template = new orders();
$main_page = 'index.php';
$main_title = 'Return to main page';
$admin_tab = "shop";
$second_admin_tab = "orders";
include 'second_level_navigation.php';
include ("../../../admin/template.php");
?>
