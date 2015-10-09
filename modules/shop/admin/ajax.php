<?php
session_cache_limiter('must-revalidate');
session_start();
$session_user_id =  (isset($_SESSION['session_user_id'])) ? $_SESSION['session_user_id'] : "";
$session_user_type_id =  (isset($_SESSION['session_user_type_id'])) ? $_SESSION['session_user_type_id'] : "";
$session_access_to_cms =  (isset($_SESSION['session_access_to_cms'])) ? $_SESSION['session_access_to_cms'] : "";
$base_path = str_replace('\\', '/', $_SERVER['DOCUMENT_ROOT']);
include $base_path.'/php/databaseconnection.php';
include $base_path.'/php/read_config.php';
include $base_path.'/admin/cms_functions_inc.php';
        
function set_stock_level()
{
    $id = $_GET['id'];
    $level = $_GET['level'];
    $sql = "update shop_product set stock_level = '$level' where id = '$id'";
    $result = mysql_query($sql);
    $level = db_get_single_value("select stock_level from shop_product where id = '$id'", 'stock_level');
    echo "show_stock_level('$id', '$level')";
}

function set_category_online()
{
    $id = $_GET['id'];
    $online = $_GET['value'];
    $sql = "update shop_category set online = $online where id = $id";
    $result = mysql_query($sql);
}


function set_featured()
{
    $id = $_GET['id'];
    $featured = $_GET['value'];
    $sql = "update shop_product set featured = $featured where id = $id";
    $result = mysql_query($sql);
}
    
function set_shop_featured(){
    $id = $_GET['id'];
    $featured = $_GET['shop_featured'];
    $sql = "update shop_product set shop_featured = $featured where id = $id";
    $result = mysql_query($sql);
}    
    
function change_stock_level()
{
    $id = $_GET['id'];
    $change = $_GET['change'];
    $sql = "update shop_stock set level = level + '$change' where product_id = '$id'";
    $result = mysql_query($sql);
    $level = db_get_single_value("select level from shop_stock where product_id = '$id'", 'level');
    echo "show_stock_level('$id', '$level')";
}
function load_report()
{
    $id = $_GET['id'];
    $change = $_GET['change'];
    $report_sql = "select * from shop_sales_report where id = '$id'";
    $report_result = mysql_query($report_sql);
    $report = mysql_fetch_array($report_result);
    $detailed = $report['Detailed'];
    $maker = $report['Maker Id'];
    $report_name = $report['Report Name'];
    $all_categories = $report['All Categories'];
    $categories_sql = "select * from shop_sales_report_categories where report_id = '$id'";
    $categories_result = mysql_query($categories_sql);
    $cats = '';
    while ($row = mysql_fetch_array($categories_result))
    {
        if ($cats != '')    
            $cats .= ', ';
        $cats .= '"'.$row[category_id].'"';
    }
    echo "set_report_fields($detailed, $maker, $all_categories, '$report_name', new Array($cats))";
}
function delete_report()
{
    $id = $_GET['id'];
    $report_sql = "delete from shop_sales_report where id = '$id'";
    $report_result = mysql_query($report_sql);
    $categories_sql = "delete from shop_sales_report_categories where report_id = '$id'";
    $categories_result = mysql_query($categories_sql);
    echo 'remove_report_from_list()';
}
function resend_email()
{
    global $base_path;
    $order_id = $_GET['id'];
    ini_set('display_errors', '1');
    ini_set('html_errors', 'on');
    ini_set('error_reporting', '-1');    
    try{
        include_once  $base_path.'/modules/shop/classes/order_confirmation_email.php';
       //echo 'alert("' . $base_path.'/modules/shop/classes/order_confirmation_email.php' .   '");';
       $o = new OrderConfirmationEmail();        
       $o->SendConfirmationEmail($order_id);
      // $o->SendConfirmationEmailTest($order_id); 
    }catch(Exception $e){
        
       echo 'alert("' . $e->getMessage() .   '");';
    }
  echo 'alert("Email sent");';
}
if (($session_user_id) && (($session_access_to_cms) || ($session_user_type_id == "1")))
{
    $action = (isset($_GET['action'])) ? $_GET['action'] : ''; 
    switch ($action)
    {
        
        case 'set_stock_level':
            set_stock_level();
            break;
        case 'set_featured':
            set_featured();
            break;
        case 'set_shop_featured':
            set_shop_featured();
            break;
        case 'change_stock_level':
            change_stock_level();
            break;
        case 'load_report':
            load_report();
            break;
        case 'delete_report':
            delete_report();
            break;            
        case 'resend_email':
            resend_email();
            break;            
        case 'set_category_online':
            set_category_online();
            break;                
    }
}
?>
