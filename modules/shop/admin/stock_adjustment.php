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
$second_admin_tab = "stock_adjust";
// Get get and post variables 
if (isset($_REQUEST['id'])) $id = $_REQUEST['id'];
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Administration</title>
<link href="http://fonts.googleapis.com/css?family=Gafata" rel="stylesheet" type="text/css">
<link href="/admin/css/adminstylesheet.css" rel="stylesheet" type="text/css" />
<link href="/modules/shop/admin/css/stylesheet.css" rel="stylesheet" type="text/css" />
<!-- jQuery -->
<script type="text/javascript" src="//ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.min.js"></script><!-- specific jQuery -->
<script src="/admin/js/template.js" ></script>
<script src="/modules/shop/admin/js/stock.js" ></script>                                 
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

if (($session_access_to_cms) || ($session_user_type_id == "1")) 
    include 'second_level_navigation.php';
include_once ("$base_path/admin/admin_header_inc.php");
$uploaddir = "../UserFiles";
include_once ("$base_path/html_editor/fckeditor.php");    
include_once ("$base_path/php/thumbnails_inc.php");

if (($session_user_id) && ($session_access_to_cms))
{     
    if (($session_access_to_cms) || ($session_user_type_id == "1")) 
    {        
        printf ("<h1>Stock Adjustments</h1>");
        ?>
        <div id="admin-page-content">
        <br />        
        <?php
        echo "<table class='stock_table' width=60%>";
        echo "<tr><td style='width:150px'>Product Id</td><td   >Product Name</td><td 'style=width:50px'>Stock Level</td><td style='width:150px'>Update Stock Level</td></tr>";
        $stock_sql = 'select * from shop_product order by order_num';
        $stock_result = mysql_query($stock_sql);
        $maker_name = "";
        while ($stock_row = mysql_fetch_array($stock_result))
        {             
            printf('<tr class="stock_row"><td >%s</td><td>%s</td><td  id="level_product_id_%s" align="right">%s</td><td><form action="" method="post" onsubmit="adjust_stock(this); return false" style="float:left">'.
                '<input type="text" name="qty" value="%s" size="3" onkeypress="enable_button(\'submit_%s\')" />'.
                '<input type="hidden" name="product_id" value="%s"  />'.
                '<input type="submit" id="submit_%s" name="submit" value="Set" disabled="disabled" /></form></td></tr>', 
                $stock_row['id'],
                $stock_row['name'],
                $stock_row['id'],
                $stock_row['stock_level'],
                $stock_row['stock_level'],
                $stock_row['id'],
                $stock_row['id'],
                $stock_row['id']);
        }
        echo '</table>';
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


