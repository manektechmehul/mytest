<?php
session_cache_limiter('must-revalidate');
session_start();
$base_path = $_SERVER['DOCUMENT_ROOT'];
// Get Session variables
$session_user_id =  (isset($_SESSION['session_user_id'])) ? $_SESSION['session_user_id'] : "";
$session_user_type_id = (isset($_SESSION['session_user_type_id'])) ? $_SESSION['session_user_type_id'] : "";
$session_access_to_cms = (isset($_SESSION['session_access_to_cms'])) ? $_SESSION['session_access_to_cms'] : "";
include_once ("$base_path/php/databaseconnection.php");
if (($session_user_id) && (($session_access_to_cms) || ($session_user_type_id == "1")))
{
    if ($_REQUEST['submit_run'])
    {
        $all_categories = $_REQUEST['all_categories']; 
        $cats = $_REQUEST['categories'];
        if (!$all_categories)
            $cats_str = implode(',', $cats);
        $detail =  $_REQUEST['detail_level']; 
        $start_date  = $_REQUEST['start-date']; 
        $start_date = substr($start_date, 6, 4) .'-'.substr($start_date, 3, 2) .'-'.substr($start_date, 0, 2);
        $end_date = $_REQUEST['end-date']; 
        $end_date = substr($end_date, 6, 4) .'-'.substr($end_date, 3, 2) .'-'.substr($end_date, 0, 2);
        
        $report_name = trim($_REQUEST['report_name']); 
        $report_id = db_get_single_value("select id from shop_sales_report where `Report Name` = '$report_name'", 'id');
        if ($report_id )
        {
                $delete_sql = "delete from shop_sales_report_categories where report_id = '$report_id'";
                $result = mysql_query($delete_sql);
                $update_sql = "update shop_sales_report set `Detailed` = '$detail',  ".
                    " `All Categories` = '$all_categories',  `Start Date` = '$start_date', `End Date` = '$end_date' ".
                    " where id = '$report_id'";
                $result = mysql_query($update_sql);
        }
        else
        {
            $insert_sql = "insert into shop_sales_report (`Report Name`, `Detailed`, `All Categories` ".
                "`Start Date`, `End Date`) values ('$report_name', '$detail', '$all_categories', '$start_date', '$end_date')";
            $insert_result = mysql_query($insert_sql);
            $report_id = mysql_insert_id();
        }
        if (!$all_categories)
        {
            $insert_sql = "insert into shop_sales_report_categories (report_id, category_id) select $report_id, id from shop_category".
                " where id in ($cats_str)";
            $insert_result = mysql_query($insert_sql);
        }
        $format =  $_REQUEST['report_format']; 
        if ($format == 0)        
            header("Location: /modules/shop/admin/reports/sales_report_$report_id.pdf");
        else
            header("Location: /modules/shop/admin/reports/sales_xls.php?report_id=$report_id");
    }           
}
// post, get or file variable declarations
$id = 0;
$admin_tab = "shop";
$second_admin_tab = "reports";
// Get get and post variables 
if (isset($_REQUEST['id'])) $id = $_REQUEST['id'];
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Administration</title>
<link href="/admin/css/adminstylesheet.css" rel="stylesheet" type="text/css" />
<link href="/admin/css/datePicker.css" rel="stylesheet" type="text/css" />
<script src="/admin/js/template.js" ></script>
<script src="/admin/js/stock.js" ></script>
<!-- jQuery -->
<script type="text/javascript" src="//ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.min.js"></script><!-- specific jQuery -->
<!-- required plugins -->
<script type="text/javascript" src="/admin/js/date.js"></script>
<!--[if IE]><script type="text/javascript" src="jquery.bgiframe.js"></script><![endif]-->
<!-- jquery.datePicker.js -->
<script type="text/javascript" src="/admin/js/jquery.datePicker.js"></script>
<script type="text/javascript" src="/modules/shop/admin/js/sales_report.js"></script>
<style>
a.dp-choose-date {
    float: left;
    width: 16px;
    height: 16px;
    padding: 0;
    margin: 5px 3px 0;
    display: block;
    text-indent: -2000px;
    overflow: hidden;
    background: url(/admin/images/calendar.png) no-repeat; 
}
a.dp-choose-date.dp-disabled {
    background-position: 0 -20px;
    cursor: default;
}
/* makes the input field shorter once the date picker code
 * has run (to allow space for the calendar icon
 */
input.dp-applied {
    width: 140px;
    float: left;
}
</style>         
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
if ($session_user_id)
{
    if (($session_access_to_cms) || ($session_user_type_id == "1")) 
    {
        printf ("<h2><a href=\"reports.php\">Reports</a> > <b>Sales Report</b></h2>");
        echo '<div id="admin-page-content">';
        echo "<h4>Saved Reports</h4>";
        echo "<select id='saved_report_name' name='saved_report_name' size=5 style='width: 300px'>";
        $sql = 'select * from shop_sales_report where `Report Name` != \'\'';
        $result = mysql_query($sql);
        while ($row = mysql_fetch_array($result))
        {
            echo "<option value='{$row['id']}'>{$row['Report Name']}</option>";
        }
        echo "</select><br /><button onclick='load_report()'>load report</button> <button onclick='delete_report()'>delete report</button>";
?>
<form action="" method="post" onsubmit="return validate_sales_report_form();"> 
<h4>Dates</h4>
<div>
                            <label for="start-date">Start date:</label>
                            <input name="start-date" id="start-date" class="date-pick" />
</div>
<br clear="all">
<div>
                            <label for="end-date">End date:</label>
                            <input name="end-date" id="end-date" class="date-pick" />
</div>
<br clear="all">
<h4>Categories</h4>
<input type="checkbox" id="all_categories" name="all_categories" value="1" onclick="setAllCategories()">All Categories<br />
<br />
<?php
        $sql = 'select * from shop_category where special = 0';
        $result = mysql_query($sql);
        echo "<div style='width: 500px'>";
        while ($row = mysql_fetch_array($result))
        {
            echo "<div style='float:left; width:125px'>";
            $row_id = "cat_".$row['id'];
            echo "<input type=\"checkbox\" id=\"$row_id\" class=\"category_checkbox\" name=\"categories[]\" value=\"{$row['id']}\">";
            echo "<label for='$row_id'>{$row['name']}</label>";
            echo "</div>";
        }
        echo "<br clear=\"all\"></div>";
      
?>
    <div>
        <h4>Detail Level</h4>
        <input type="radio" name="detail_level" value="1" id="detail_radio" checked="checked"><label for="detail_radio">detail</label><br />    
        <input type="radio" name="detail_level" value="0" id="summary_radio"><label for="summary_radio">summary</label>       
        <br />    
        <br />    
    </div>
    <div>
        <h4>Format</h4>
        <input type="radio" name="report_format" value="1" id="excel_radio" checked="checked"><label for="detail_radio">Excel</label><br />    
        <input type="radio" name="report_format" value="0" id="pdf_radio"><label for="summary_radio">PDF</label>       
        <br />    
        <br />    
    </div>    
    <h4>Save Report</h4>
    <label for="report_name">Report Name </label><input type="text" name="report_name" id="report_name"><br />
    <br />
    <input type="submit" name="submit_run" value="Run Report">
<br />    
<br />    
</form>
<?php
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
