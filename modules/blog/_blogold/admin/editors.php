<?php
session_cache_limiter('must-revalidate');
session_start();


// Changes to remove need for register globals and avoid warnings
//  -- start
// general variable declarations
if (!isset($ordered))
    $ordered = false;

if (!isset($hidable))
    $hidable = false;

$bgcolor = "";
$PHP_SELF = $_SERVER['PHP_SELF'];
$template->page_self = $PHP_SELF;

// Get Session variables
$session_user_id = (isset($_SESSION['session_user_id'])) ? $_SESSION['session_user_id'] : "";
$session_user_type_id = (isset($_SESSION['session_user_type_id'])) ? $_SESSION['session_user_type_id'] : "";
$session_access_to_cms = (isset($_SESSION['session_access_to_cms'])) ? $_SESSION['session_access_to_cms'] : "";

// post, get or file variable declarations

$id = "";
$confirm_delete = "";
$submit_edit = "";
$status = "";
$delete_item = "";
$edit_item = "";
$parent_id = "";

// Get get and post variables 
if (isset($_REQUEST['id']))
    $id = $_REQUEST['id'];
if (isset($_REQUEST[$template->parent_id_name]))
    $parent_id = $_REQUEST[$template->parent_id_name];
if (isset($_REQUEST['confirm_delete']))
    $confirm_delete = $_REQUEST['confirm_delete'];
if (isset($_REQUEST['delete_item']))
    $delete_item = $_REQUEST['delete_item'];
if (isset($_REQUEST['edit_item']))
    $edit_item = $_REQUEST['edit_item'];
if (isset($_REQUEST['submit_edit']))
    $submit_edit = $_REQUEST['submit_edit'];

$custom_action = '';
if (is_array($template->actions)) {
    foreach ($template->actions as $action) {
        if (isset($_REQUEST[$action['querystring']])) {
            $custom_action = $_REQUEST[$action['querystring']];
            $custom_action_method = $action['method'];
        }
    }
}


$id = (isset($_REQUEST['id'])) ? $_REQUEST['id'] : "";
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
        <title>Administration</title>

        <link href="/admin/adminstylesheet.css" rel="stylesheet" type="text/css" />
        <?php
        $base_path = $_SERVER['DOCUMENT_ROOT'];
        include_once ("$base_path/php/javascript_rollover_inc.php");
        ?>

        <script src="/admin/js/template.js" ></script>

    </head>

    <body>

        <?php
        unset($_SESSION["session_section_id"]);
        unset($session_section_id);

        $path_prefix = "..";

        include_once ("$base_path/php/databaseconnection.php");
        include_once ("$base_path/php/read_config.php");
        include_once ("$base_path/admin/cms_functions_inc.php");

        if (!isset($admin_tab))
            $admin_tab = "content_admin";

        $use_admin_header = "1";
        include_once ("$base_path/admin/process_login_inc.php");

        include_once ("$base_path/admin/admin_header_inc.php");
        $uploaddir = "../uploaded_files";

        include_once ("$base_path/php/thumbnails_inc.php");
        ?>

        <?php
        if (($session_user_id) && ($session_access_to_cms)) {

//	printf ("<h1>Content Administration</h1><p>");
            printf("<h1>$group_name</h1><p>");

            // CONTENT STARTS HERE    
            printf("<h2><a href='content_admin.php'>Content Administration</a> > <b>{$template->single_name} Admin</b></h2>");

            if (($session_access_to_cms) || ($session_user_type_id == "1")) {
                $blog_id = $_REQUEST['blog'];
                $action = $_POST['submit'];
                if ($action == 'submit') {
                    $editor_list = '0';
                    $nofified_list = '0';
                    if (is_array($_POST['edit']))
                        $editor_list = implode(',', array_keys($_POST['edit']));
                    if (is_array($_POST['notify']))
                        $nofified_list = implode(',', array_keys($_POST['notify']));

                    $update_sql = "update blog_editors set status = ( 0 + (user_id in ($editor_list)) + (user_id in ($nofified_list))) where blog_id = $blog_id";
                    $update_result = mysql_query($update_sql);
                    echo $update_sql;

                    $insert_sql = "insert into blog_editors (user_id, blog_id, status) select id, $blog_id,	(0 + (id in ($editor_list)) + (id in ($nofified_list))) " .
                            "from user where id > 1 and id not in (select user_id from blog_editors where blog_id = $blog_id)";
                    $insert_result = mysql_query($insert_sql);
                    echo $insert_sql;
                }
                else {
                    $sql = 'select u.id, concat(firstname," ", surname) as name, user_type_id, coalesce(be.status,0) status ' .
                            'from user u left outer join blog_editors be on u.id = user_id ' .
                            'where u.id > 1 and account_status = 1';
                    $result = mysql_query($sql);
                    if (mysql_num_rows($result) > 0) {
                        echo '<form method="post" action="">';
                        echo '<table>';
                        echo '<tr><th>Name</th><th>Can Edit</th><th>Receive Notification</th></tr>';
                        while ($row = mysql_fetch_array($result)) {
                            $can_edit = ($row['status'] > 0) ? ' selected="selected" ' : '';
                            $notified = ($row['status'] > 1) ? ' selected="selected" ' : '';
                            printf('<tr><td>%s</td><td><input type="checkbox" name="edit[%s]" %s /></td><td><input type="checkbox" name="notify[%s]" %s/></td></tr>', $row['name'], $row['id'], $can_edit, $row['id'], $notified);
                        }
                        echo '</table>';
                        echo '<input type="submit" name="submit" value="submit">';
                        echo '</form>';
                    }
                }
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
				
