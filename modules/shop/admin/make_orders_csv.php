<?

session_cache_limiter('must-revalidate');
session_start();
$base_path = $_SERVER['DOCUMENT_ROOT'];
// Get Session variables
// var_dump($_POST);
$session_user_id = (isset($_SESSION['session_user_id'])) ? $_SESSION['session_user_id'] : "";
$session_user_type_id = (isset($_SESSION['session_user_type_id'])) ? $_SESSION['session_user_type_id'] : "";
$session_access_to_cms = (isset($_SESSION['session_access_to_cms'])) ? $_SESSION['session_access_to_cms'] : "";
include_once ("$base_path/php/databaseconnection.php");

if (true) {
    $filename = "file.csv";
    header("Expires: 0");
    header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
    header("Content-Disposition: attachment;filename=$filename");
    header("Content-Transfer-Encoding: binary ");
}
$data = getOrderData();
// create table titles

echo 'shop order id' . ',	' . 'time made formated' . ',	' . 'total price' . ',	' . 'tax' . ',	' . 'delivery' . ',	'
 . 'shop order status' . ',	' . 'customer id' . ',	' . 'firstname' . ',	' . 'surname' . ',	' . 'billing address1'
 . ',	' . 'billing address2' . ',	' . 'billing address3' . ',	' . 'billing postalcode' . ',	' . 'phone' . ',	' . 'email'
 . ',	' . 'delivery address1' . ',	' . 'delivery address2' .
 ',	' . 'delivery address3' . ',	' . 'delivery postalcode' . ',	' . 'billing country' . ',	' . 'delivery country';

echo "\n";
// output data
foreach ($data as $item) {
    // for each item in array
	
	$field_count = 0;
    foreach ($item as $field) {
		
		//var_dump($field);
		if($field_count == 1){ // format unix time to human friendly
			echo date('d/m/Y G:i:s',$field) . ",\t";
		}else{        
			echo $field . ",\t"; // . $v['id'] . "\t" . $v->surname . "\t";
		}
		$field_count++;
    }
	
    echo "\n";
}
exit;

function getOrderData(){
    $where = " where ";
    $first = true;
    if ($_GET['status'] != '-1') {
        if ($_GET['status'] != '') {
            $where .= " shop_order.status=" . $_GET['status'] . " ";
            $first = false;
        }
    }
    if ($_GET['id'] != '') {
        if (!$first) {
            $where .= " and ";
        }
        $where .= " id=" . $_GET['id'] . " ";
        $first = false;
    }
    if ($_GET['start_date'] != '') {
        if (!$first) {
            $where .= " and ";
        }
        $where .= " time_made > '" . date("Y-m-d", strtotime($_GET['start_date'])) . "' ";
        $first = false;
    }
    if ($_GET['end_date'] != '') {
        if (!$first) {
            $where .= " and ";
        }
        $where .= " time_made < '" . date("Y-m-d", strtotime($_GET['end_date'])) . "' ";
        $first = false;
    }
    if ($_GET['email'] != '') {
        if (!$first) {
            $where .= " and ";
        }
        $where .= " email = '" . $_GET['email'] . "' ";
        $first = false;
    }
    if ($_GET['firstname'] != '') {
        if (!$first) {
            $where .= " and ";
        }
        $where .= " firstname = '" . $_GET['firstname'] . "' ";
        $first = false;
    }
    if ($_GET['surname'] != '') {
        if (!$first) {
            $where .= " and ";
        }
        $where .= " surname = '" . $_GET['surname'] . "' ";
        $first = false;
    }
    if ($where == " where ") {
        $where = '';
    }

    if (isset($_GET['order_by'])) {
        $order = " order by ";
        if ($_GET['order_by'] == "0") {
            $order = " ORDER BY shop_order.status ASC, time_made DESC ";
        }
        if ($_GET['order_by'] == "1") {
            $order = " ORDER BY time_made ASC ";
        }
        if ($_GET['order_by'] == "2") {
            $order = " ORDER BY time_made DESC";
        }
        if ($_GET['order_by'] == "3") {
            $order = " ORDER BY surname ASC";
        }
        if ($_GET['order_by'] == "4") {
            $order = " ORDER BY surname DESC";
        }
    }else{
        $order = " ORDER BY shop_order.status, time_made ";
    }

    $sql = " SELECT shop_order.id,UNIX_TIMESTAMP(shop_order.time_made) AS time_made_formated , shop_order.total_price,
 shop_order.`tax`, shop_order.`delivery`, shop_order_status.`name`,shop_customer.id AS `customer_id`, shop_customer.`firstname`, shop_customer.`surname`, shop_customer.`billing_address1`, shop_customer.`billing_address2`, shop_customer.`billing_address3`, shop_customer.`billing_postalcode`, shop_customer.`phone`, shop_customer.`email`, shop_customer.`delivery_address1`, shop_customer.`delivery_address2`, shop_customer.`delivery_address3`, shop_customer.`delivery_postalcode`, shop_customer.`billing_country`, shop_customer.`delivery_country`
FROM shop_order 
INNER JOIN shop_customer ON shop_customer.`id` = shop_order.`customer_id` 
INNER JOIN shop_order_status ON shop_order.`status` = shop_order_status.id
 " . $where . $order;
    return db_get_rows($sql);
}

?>