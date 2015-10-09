<?php

session_cache_limiter('must-revalidate');
session_start();

$base_path = $_SERVER['DOCUMENT_ROOT'];
// Get Session variables
$session_user_id =  (isset($_SESSION['session_user_id'])) ? $_SESSION['session_user_id'] : "";
$session_user_type_id = (isset($_SESSION['session_user_type_id'])) ? $_SESSION['session_user_type_id'] : "";
$session_access_to_cms = (isset($_SESSION['session_access_to_cms'])) ? $_SESSION['session_access_to_cms'] : "";

include_once ("$base_path/php/databaseconnection.php");

// post, get or file variable declarations
$id = 0;
$admin_tab = "shop";
$second_admin_tab = "shipping";



// Get get and post variables 
if (isset($_REQUEST['id'])) $id = $_REQUEST['id'];




?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Administration</title>

<link href="/admin/css/adminstylesheet.css" rel="stylesheet" type="text/css" />

<script src="/admin/js/template.js" ></script>
<script src="/admin/js/stock.js" ></script>

                                       
</head>

<body>

<?php
	unset($_SESSION["session_section_id"]);
unset ($session_section_id);

$path_prefix = "..";

include_once ("$base_path/php/read_config.php");
include_once ("$base_path/admin/cms_functions_inc.php");

if (!isset($admin_tab ))
    $admin_tab = "content_admin";

$use_admin_header = "1";
include_once ("$base_path/admin/process_login_inc.php");

include 'second_level_navigation.php';
include_once ("$base_path/admin/admin_header_inc.php");
$uploaddir = "../UserFiles";
include_once ("$base_path/html_editor/fckeditor.php");    
include_once ("$base_path/php/thumbnails_inc.php");

           

if (($session_user_id) && ($session_access_to_cms))
{
    if (($session_access_to_cms) || ($session_user_type_id == "1")) 
    {
        
        $add_rate = isset($_REQUEST['add_rate']) ? $_REQUEST['add_rate'] : false;
        $change_rate = isset($_REQUEST['change_rate']) ? $_REQUEST['change_rate'] : false;
        $delete_id= isset($_REQUEST['delete_id']) ? $_REQUEST['delete_id'] : false;
        
        if ($add_rate)                                                                    
        {
              $add_quantity = isset($_REQUEST['add_quantity']) ? $_REQUEST['add_quantity'] : 0;
              $sql = 'replace into shop_shipping_rates (quantity,rate) values'."('$add_quantity', '$add_rate')";
              $result = mysql_query($sql);
        }

        if ($change_rate)
        {
              $change_id = isset($_REQUEST['id']) ? $_REQUEST['id'] : 0;
              $sql = "update shop_shipping_rates set rate = '$change_rate' where id = '$id'";
              $result = mysql_query($sql);
        }

        if ($delete_id)
        {
              $sql = "delete from shop_shipping_rates where id = '$delete_id'";
              $result = mysql_query($sql);
        }
        
        printf ("<h1>Shipping</h1>");
        ?>
        <div id="admin-page-content">

        <br />
            

        <?php

        echo "<table>";
        echo "<tr><th>Units</th><th>Rate</th></tr>";
        $shipping_sql = 'select * from shop_shipping_rates order by quantity';
        $shipping_result = mysql_query($shipping_sql);

        while ($shipping_row = mysql_fetch_array($shipping_result))
        {
            printf('<tr><td>%s</td><td><form action="" style="float:left" method="post" onsubmit="adjust_shipping(this); return false">'.
                '<input type="text" name="change_rate" value="%0.2f" size="3"/>'.
                '<input type="hidden" name="id" value="%s" />'.
                '<input type="submit" name="submit" value="Set" /></form>'.
                '<form style="float:left" action="" method="post" onsubmit="delete_shipping(this); return false">'.
                '<input type="hidden" name="delete_id" value="%s" /><input type="submit" name="delete_submit" value="Delete" /></form></td></tr>', 
                $shipping_row['quantity'],
                $shipping_row['rate'],
                $shipping_row['id'],
                $shipping_row['id']);
        }                                                     
        echo '</table>';                                        
        
        echo '<p>Add a new rate</p>';
        echo '<form action="" method="post" onsubmit="add_shipping(this); return false">';
        echo '<div><label for="add_quantity">Quantity </label><input type="text" id="add_quantity"  name="add_quantity" value="" size="3" /></div>';
        echo '<div><label for="add_rate">Rate </label><input type="text" name="add_rate" value="" size="3" /></div>';
        echo '<input type="submit" name="submit_add" value="Add" />';
        echo '</form>';
        echo '</div>';
      
    }
    else {
        printf ("You do not have the appropriate type of login account to view this page.
                 <P>Please <a href=\"../logout.php\">logout</a> then login again using an Admin account.");
    }
}
else {
    include_once ("$base_path/admin/login_inc.php"); 
}
?>  
<!-- CONTENT ENDS HERE -->
<?php

mysql_close ( $link );



include_once ("$base_path/admin/admin_footer_inc.php");
?>


