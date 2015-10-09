<?php
session_cache_limiter('must-revalidate');
session_start();
$base_path = $_SERVER['DOCUMENT_ROOT'];
// Get Session variables
$session_user_id = (isset($_SESSION['session_user_id'])) ? $_SESSION['session_user_id'] : "";
$session_user_type_id = (isset($_SESSION['session_user_type_id'])) ? $_SESSION['session_user_type_id'] : "";
$session_access_to_cms = (isset($_SESSION['session_access_to_cms'])) ? $_SESSION['session_access_to_cms'] : "";
include_once ("$base_path/php/databaseconnection.php");
// post, get or file variable declarations
$id = 0;
$admin_tab = "shop";
$second_admin_tab = "reports";
// Get get and post variables 
if (isset($_REQUEST['id']))
    $id = $_REQUEST['id'];
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
        <title>Administration</title>
<link href="http://fonts.googleapis.com/css?family=Gafata" rel="stylesheet" type="text/css">
        <link href="/admin/css/adminstylesheet.css" rel="stylesheet" type="text/css" />
        <link href="/admin/css/datePicker.css" rel="stylesheet" type="text/css" />
        <script src="/admin/js/template.js" ></script>
        <script src="/admin/js/stock.js" ></script>
        <!-- jQuery -->
        <script type="text/javascript" src="/js/jquery.js"></script>
    </head>
    <body>
        <?php
	        unset($_SESSION["session_section_id"]);
        unset($session_section_id);
        $path_prefix = "..";
        include_once ("$base_path/php/read_config.php");
        include_once ("$base_path/admin/cms_functions_inc.php");
        if (!isset($admin_tab))
            $admin_tab = "content_admin";
        $use_admin_header = "1";
        include_once ("$base_path/admin/process_login_inc.php");
        include 'second_level_navigation.php';
        include_once ("$base_path/admin/admin_header_inc.php");
        $uploaddir = "../UserFiles";
        include_once ("$base_path/html_editor/fckeditor.php");
        include_once ("$base_path/php/thumbnails_inc.php");

        function admin_button($report_file, $button_name, $button_title, $target = '') {
            if ($target)
                $target = " target=\"$target\"";
            printf("<a href=\"%s\" $target onMouseOut=\"MM_swapImgRestore()\" onMouseOver=\"MM_swapImage('%s','','/admin/images/buttons/cmsbutton-%s-over.gif',0)\"><img style='border:none' src='/admin/images/buttons/cmsbutton-%s-off.gif' name='%s'></a><br /><br />", $report_file, $button_name, $button_title, $button_title, $button_name);
        }

        if (($session_user_id) && ($session_access_to_cms)) {
            if (($session_access_to_cms) || ($session_user_type_id == "1")) {
                printf("<h2><b>Reports</b></h2>");
                echo '<div id="admin-page-content">';
                admin_button('reports/pick_list_report.php', 'defaultpicklist', 'Pick_List');
             //   admin_button('r_sales_report.php', 'defaultsalesreport', 'Sales_Report');
                
               
                echo '</div>';
            } else {
                printf("You do not have the appropriate type of login account to view this page.
                 <P>Please <a href=\"../logout.php\">logout</a> then login again using an Admin account.");
            }
        } else {
            include_once ("$base_path/admin/login_inc.php");
        }
        ?>  
        <!-- CONTENT ENDS HERE -->
        <?php
        mysql_close($link);
        include_once ("$base_path/admin/admin_footer_inc.php");
        ?>
