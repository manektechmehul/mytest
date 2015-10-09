<?php
session_cache_limiter('must-revalidate');
session_start();

// Changes to remove need for register globals and avoid warnings
//  -- start

// general variable declarations
$use_admin_header = 1	;

// Get Session variables
$session_user_id =  "";
$session_user_type_id = "";
$session_access_to_cms = "";
$session_section_id = "";


// end --

session_unset();
session_destroy();

//setcookie("PHPSESSID","","","/","");
setcookie("PHPSESSID","");
?>

<html>
<head>
<title>Administration</title>

<link href="./css/adminstylesheet.css" rel="stylesheet" type="text/css" />
<link href="http://fonts.googleapis.com/css?family=Gafata" rel="stylesheet" type="text/css">
</head>
<body >

<?php

$path_prefix = ".";

include ("../php/databaseconnection.php");

include ("./admin_header_inc.php"); 

?>

<div id='admin-page-content'>
<p><strong>You have been logged out.</strong></p>
<a href='/admin/index.php'>Login</a><br />
<a href='/'>Website</a>
</div>
<?php 

include ("./admin_footer_inc.php"); 


mysql_close ( $link );
?>
