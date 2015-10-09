<?php

session_cache_limiter('must-revalidate');

session_start();


// Changes to remove need for register globals and avoid warnings
//  -- start
$admin_tab = "";

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

<link href="./css/adminstylesheet.css" rel="stylesheet" type="text/css" />
<link href="http://fonts.googleapis.com/css?family=Gafata" rel="stylesheet" type="text/css">
</head>
<body >

<?php
unset($_SESSION["session_section_id"]);
unset ($session_section_id);

$path_prefix = "..";

include ($path_prefix . "/php/databaseconnection.php");
include ($path_prefix . "/php/read_config.php");
include ("./cms_functions_inc.php");

$admin_tab = "help";

$use_admin_header = "1";
include ("./process_login_inc.php");
include ("./admin_header_inc.php");   

if (($session_user_id) && ($session_access_to_cms))
{

	printf ("<h1>Help</h1>");

	if ($session_user_type_id == "1") {
		?>
		<p> The information within these pages will help you to add content to your web site and manage the users who are able to access it:</p>
		<ul>
		  <li><a href="help_cms.php">Using the Content Management System</a> <br><br>
		  </li>
		  <li><a href="help_user.php">User Administration</a> </li>
		</ul>
		<?php
		}
		else if ($session_access_to_cms) {
			?>
			<p> This information will help you to add content to your web site:</p>
			<ul>
			  <li><a href="help_cms.php">Using the Content Management System</a> <br><br>
			  </li>
			</ul>

			<?php
			}

	}
	else {
		if ($login)
			echo $login_error;
		else
			include ("./login_inc.php"); 
    }

mysql_close ($link);

include ("./admin_footer_inc.php");
?>


