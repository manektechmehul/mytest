<?php

session_cache_limiter('must-revalidate');
session_start();
// Changes to remove need for register globals and avoid warnings
//  -- start
// general variable declarations
$admin_tab = "settings";
// Get Session variables
$session_user_id =  (isset($_SESSION['session_user_id'])) ? $_SESSION['session_user_id'] : "";
$session_user_type_id = (isset($_SESSION['session_user_type_id'])) ? $_SESSION['session_user_type_id'] : "";
$session_access_to_cms = (isset($_SESSION['session_access_to_cms'])) ? $_SESSION['session_access_to_cms'] : "";
// end --
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>Administration</title>
<script type="text/javascript" src="//ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.min.js"></script><!-- specific jQuery -->
<script src="/js/jquery-ui.js" ></script>
<link type="text/css" href="/css/ui-lightness/jquery-ui.css" rel="stylesheet" />
<link href="./css/adminstylesheet.css" rel="stylesheet" type="text/css" />
<link href="http://fonts.googleapis.com/css?family=Gafata" rel="stylesheet" type="text/css">
<style>
.error {
	color: #880000;
}
.good {
	color: #008800;
}
h3 {
	
}

#main-content {
	padding-left:6px;
}
#notesbox { display: none; }
</style>

<script>
function config_toogle(elem)
{
	config_name = elem.id;
	new_value = (elem.checked == true) ? '1' : '0';
    // Create new JS element
    var jsel = document.createElement('SCRIPT');
    jsel.type = 'text/javascript';
    jsel.src = 'ajax_functions.php?action=config_change&name='+config_name+'&value='+new_value+'&callback=checkit';

    // Append JS element (thereby executing the 'AJAX' call)
    document.body.appendChild (jsel);
    //document.body.removeChild(jsel);
}

function checkit()
{	
}

function config_change_value(config_name, new_value)
{
    // Create new JS element
    var jsel = document.createElement('SCRIPT');
    jsel.type = 'text/javascript';
    jsel.src = 'ajax_functions.php?action=config_change&name='+config_name+'&value='+escape(new_value)+'&callback=test';

    // Append JS element (thereby executing the 'AJAX' call)
    document.body.appendChild (jsel);
    //document.body.removeChild(jsel);
}

function config_change_text(elemid)
{
	elem = document.getElementById(elemid);
	config_name = elem.id;
	new_value = elem.value;
	config_change_value(config_name, new_value);

	btnelem = document.getElementById(elemid+'_btn');
	btnelem.disabled = true;
}

function config_change_option(elem)
{
	config_name = elem.id;
	new_value = elem.value;
	config_change_value(config_name, new_value);
}

function test()
{
	//alert('test');
}

function enable_btn(btn)
{
	elem = document.getElementById(btn);
	elem.disabled = false;
}
$(document).ready( function () {
	$('#shownotes').click( function() {
		if ($(this).text() == 'show') {
			$('#notesbox').show();
			$(this).text('hide');
		} else {
			$('#notesbox').hide();
			$(this).text('show');
		}
	});
});
</script>

</head>
<body>
<?php
unset($_SESSION["session_section_id"]);
unset ($session_section_id);
$path_prefix = "..";
include ($path_prefix . "/php/databaseconnection.php");
$use_admin_header = "1";
include ("../php/read_config.php");
include ("./process_login_inc.php");
include ("./admin_header_inc.php");

function showConfig($sm_admin)
{
    echo "<table class='check-results'>\n";
    

    $conf_sql = "select c.*, cg.name groupname from configuration c ".
        "join configuration_group cg on `group` = cg.id ".
        "where c.sm_admin_only = '$sm_admin' and cg.sm_admin_only = '$sm_admin' order by order_num,c.id";
    
    
    $conf_result = mysql_query($conf_sql);
    $current_group_name = '';
    while ($conf_row = mysql_fetch_array($conf_result))
    {
        $group_name = $conf_row['groupname'];
        if ($group_name !== $current_group_name)
        {
            echo "<tr>\n<td width='200' colspan=2><h3>$group_name</h3><hr></td></tr>";
            $current_group_name = $group_name;
        }
        
        echo "<tr>\n<td width='200' title='{$conf_row['description']}'>{$conf_row['title']}</td><td>\n";
        switch ($conf_row['type'])
        {
            case '0':
                $checked = ($conf_row['value']== '1') ? 'checked=\'checked\'' : '';
				$value = htmlentities($conf_row['value'], ENT_QUOTES);
                echo "<input type='text' id='{$conf_row['name']}' name='{$conf_row['name']}' value='$value' onkeyup='enable_btn(\"{$conf_row['name']}_btn\")' size='60'/>";
                echo "<button id='{$conf_row['name']}_btn' onclick='config_change_text(\"{$conf_row['name']}\")' disabled=disabled>set</button>";
                break;
            case '1':
                $checked = ($conf_row['value']== '1') ? 'checked=\'checked\'' : '';
                echo "<input type='checkbox' $checked id='{$conf_row['name']}' name='{$conf_row['name']}' onclick='config_toogle(this)'/></td></tr>";
                break;
            case '2':
                $checked = ($conf_row['value']== '1') ? 'checked=\'checked\'' : '';
                echo "<select id='{$conf_row['name']}' name='{$conf_row['name']}' onchange='config_change_option(this)'>";
                $options = explode(',', $conf_row['options']);
                foreach ($options as $option)
                {
                    $selected = ($conf_row['value'] == $option) ? 'selected=\'selected\'' : '';
                    echo "<option $selected value='$option'/>$option</option>";
                }
                echo "</select>";
                break;
            case '3':
                $checked = ($conf_row['value']== '1') ? 'checked=\'checked\'' : '';
				$value = htmlentities($conf_row['value'], ENT_QUOTES);
                echo "<textarea id='{$conf_row['name']}' name='{$conf_row['name']}' rows='5' cols='50' onkeyup='enable_btn(\"{$conf_row['name']}_btn\")' />{$value}</textarea>";
                echo "<button id='{$conf_row['name']}_btn' onclick='config_change_text(\"{$conf_row['name']}\")' disabled=disabled>set</button>";
                break;
        }
        echo "</td></tr>";
                
    }


    echo "</table>\n";
    
}


if (($session_user_id) && ($session_access_to_cms))
{
	echo "<h1>Admin Control Panel</h1>";
	
	echo "<div id='main-content'>";
	if ($session_user_id == 1)
	{
		printf ("<p><br><p>CS-Admin Control Panel Version 2.6.1</p>");
		$ip=$_SERVER['REMOTE_ADDR']; 
		printf ("<p>Your IP address is: $ip</p>");

		// Folders + permissions	
		echo "<div id='folders'><h3>Folders</h3><hr>";
		$install_files = array ('install' => 'SECURITY RISK - this should be deleted', 
            'php/databaseconnection.template' => 'SECURITY RISK - this file should be deleted');
		$upload_folders = array ('admin/images/buttons/cache', 'images/buttons/cache', 'modules/twitter/twittercache', 'modules/forms/cache','php/securimage/image_data', 'UserFiles/Image', 'UserFiles/File', 'UserFiles/Thumbnails', 'templates/templates_c');
		$errors = 0;
		
		echo "<table class='check-results'>\n";
		foreach ($install_files as $item => $message)
		{
			if (file_exists('../'.$item))
			{
    			echo "<tr>\n<td width='200'>$item</td>\n";
                echo "<td class='error'>$message</td>";
                $errors++;
                echo "</tr>";
			}
		}
		foreach ($upload_folders as $fldr)
		{
			echo "<tr>\n<td width='200'>$fldr</td>\n";
			if (file_exists('../'.$fldr))
			{
				if (substr(sprintf('%o', fileperms('../'.$fldr)), -4) == '0777') 
				{
					echo "<td class='good'>Good - writeable</td>";
				}
				else
				{
					echo "<td class='error'>Error - write permissions required</td>";
					$errors++;
				}
			}
			else
			{
				echo "<td class='error'>Error - folder missing</td>";
				$errors++;
			}
			echo "</tr>";
		}
		echo "</table></div>";
		

		//Database	
		echo "<h4>Notes</h4>";
		ob_start();
		include './php/databaseconnection.php';
		ob_clean();
		$dbok = true;
		echo "<table class='check-results'>\n";
		echo "<tr>\n<td width='200'>Notes</td>\n<td>";

		$notesSql = 'select value from configuration where name = \'SITE_NOTES\'';
		$notes = db_get_single_value($notesSql, 'value');

		$value = htmlentities($notes, ENT_QUOTES);
		echo "<button id='shownotes'>show</button>";
		echo "<div id=\"notesbox\">";
		echo "<textarea id='SITE_NOTES' name='SITE_NOTES' rows='15' cols='80' onkeyup='enable_btn(\"SITE_NOTES_btn\")' />{$value}</textarea>";
		echo "<button id='SITE_NOTES_btn' onclick='config_change_text(\"SITE_NOTES\")' disabled=disabled>set</button>";
		echo "<div id=\"notesbox\">";
			
		echo "</td></tr></table>\n";

		// Config	
		if ($dbok) {
			echo "<h1>Config</h1>";
            showConfig(0);
            showConfig(1);
        }
		
//Summary
		echo "<h4>Summary</h4>";
		echo "<p class='check-results'>There are $errors errors</p>";
	}
	else
	{
		printf ("<p><br><p>Welcome to your Website's Admin Control Panel.
			 <p>
			 Please click on the tabs to access the Admin functions.
			<br><p>");
            showConfig(0);
	}
}
else 
{
		if ($login)
			echo $login_error;
		else
			include ("./login_inc.php"); 
	}

mysql_close ($link);

include ("./admin_footer_inc.php");


