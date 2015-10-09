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
$session_user_id =  (isset($_SESSION['session_user_id'])) ? $_SESSION['session_user_id'] : "";
$session_user_type_id = (isset($_SESSION['session_user_type_id'])) ? $_SESSION['session_user_type_id'] : "";
$session_access_to_cms = (isset($_SESSION['session_access_to_cms'])) ? $_SESSION['session_access_to_cms'] : "";

include_once ("$base_path/php/databaseconnection.php");

if (method_exists($template, 'is_authorised') && !$template->is_authorised())
{
    header("Location: {$template->fail_auth_location}");
    exit();
}

// post, get or file variable declarations
$id = "";
$confirm_delete = "";
$submit_edit = "";
$status = "";
$delete_item = "";
$edit_item = "";
$parent_id = false;

	// Get get and post variables 
if (isset($_REQUEST['id'])) $id = $_REQUEST['id'];
if (isset($_REQUEST[$template->parent_id_name])) $parent_id = $_REQUEST[$template->parent_id_name];

if (isset($_REQUEST['edit_item'])) $page = 'edit_item';

if (isset($_REQUEST['submit_edit'])) $action = 'submit_edit';
if (isset($_REQUEST['delete_item'])) $action = 'delete_item';

$custom_action = '';

if (is_array($template->actions))
{
	foreach ($template->actions as $tpl_action)
	{
		if (isset($_REQUEST[$tpl_action['pagequerystring']])) 
		{
            $page = 'custom';
            $custom_page_value = $_REQUEST[$tpl_action['pagequerystring']];
            $custom_title = $tpl_action['title'];
			$custom_page_method = $tpl_action['pagemethod'];
		}
        if (isset($_REQUEST[$tpl_action['actionquerystring']])) 
        {
            $action = 'custom';
            $custom_action_value = $_REQUEST[$tpl_action['actionquerystring']];
            $custom_title = $tpl_action['title'];
            $custom_action_method = $tpl_action['actionmethod'];
        }
	}
}


$id = (isset($_REQUEST['id'])) ? $_REQUEST['id'] : "";


?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>Administration</title>

<link href="/admin/css/adminstylesheet.css" rel="stylesheet" type="text/css" />
<link href="http://fonts.googleapis.com/css?family=Gafata" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="//ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.min.js"></script><!-- specific jQuery -->
<script src="/admin/js/template.js" ></script>
<?php 
if ($template->css_file) {
    if (is_array($template->css_file)) {
        foreach ($template->css_file as $cssFile)
            echo '<link href="'.$cssFile.'" rel="stylesheet" type="text/css" />';
    }
    else
        echo '<link href="'.$template->css_file.'" rel="stylesheet" type="text/css" />';
}
            
if ($template->javascript_file) {
    if (is_array($template->javascript_file)) {
        foreach ($template->javascript_file as $javascriptFile)
            echo '<script src="'.$javascriptFile.'"></script>';
    }
    else
        echo '<script src="'.$template->javascript_file.'"></script>';
}





?>
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

include_once ("$base_path/admin/admin_header_inc.php");
$uploaddir = "../UserFiles";
include_once ("$base_path/html_editor/fckeditor.php");	
include_once ("$base_path/php/thumbnails_inc.php");

if (($session_user_id) && ($session_access_to_cms))
{
  
	printf ("<h1>$group_name</h1>");

	if (($session_access_to_cms) || ($session_user_type_id == "1")) 
	{
		if (($parent_id) && ($template->parent_child == false))
		{
			if ($template->parent_child == true) 
			{
                $block_template = $template->child;
				//$template->child->page_self = $template->page_self;
			}
			else
			{
                $block_template = $template;
				echo "<div id='adminboxbuttons'>";
				echo $template->top_text;
				echo "</div>";
			}
		}
		else
		{   
			if (($parent_id) && ($template->parent_child == true))
                $block_template = $template->child;
			else
				$block_template = $template;
			echo "<div id='adminboxbuttons'>";
			echo $template->top_text;
			echo "</div>";
		}
        
        $block_template->get_form_data();
		$block_template->page_self = $PHP_SELF;

        switch ($action) 
        {
            case 'submit_edit':
                $message = $block_template->process_submit($id, $parent_id);
                break;
            case 'delete_item':
                $message = $block_template->delete_page($id, $confirm_delete, $parent_id);
                break;
            case 'hide_show_item':
                $message = $block_template->show_hide($id, $hide_show_item, $parent_id);
                break;
            case 'custom':
                $message = $block_template->$custom_action_method($id, $custom_action, $parent_id);
                break;
        }
        
        $title = '';
        if ($page == 'custom')
            $title = $custom_title;
        else
        {
            if ($page == 'edit_item')
                $title = $id ? 'Edit' : 'Add';
        }
        
        if (!$message)
        {
            $crumbs = $template->get_crumbs($title);
            printf ("<h2>$crumbs</h2>");
            switch ($page) 
            {
                case 'edit_item': 
                    $block_template->show_edit($id, $parent_id);
                    break;
                case 'custom':
                    $block_template->$custom_page_method($id, $custom_action, $parent_id);
                    break;
                default:
                    if ($template->parent_child)
                        $parent_id = false;
                    $template->show_list($parent_id);  // this is the template level not block_template
            }
        }
        else
        {
            if ($page == 'edit_item')
                $title = 'Edit';            
            else
                $title = $custom_title;  
            $crumbs = $template->get_crumbs($title);
            printf ("<h2>$crumbs</h2>");
            echo "<div id='admin-page-content'>$message<input class='admin-back-button' type=\"button\" value=\"Back\" onClick=\"history.go(-1)\"></div>";
        }
	}
	else {
		printf ("You do not have the appropriate type of login account to view this page.
				 <P>Please <a href=\"../logout.php\">logout</a> then login again using an Admin account.");
	}
}
else {
	$page = "";
	include_once ("$base_path/admin/login_inc.php"); 
}
?>  
<!-- CONTENT ENDS HERE -->
<?php
if ($template->body_javascript_file) {
    if (is_array($template->body_javascript_file)) {
        foreach ($template->body_javascript_file as $javascriptFile)
            echo '<script src="'.$javascriptFile.'"></script>';
    }
    else
        echo '<script src="'.$template->body_javascript_file.'"></script>';
}

mysql_close ( $link );

include_once ("$base_path/admin/admin_footer_inc.php");
?>