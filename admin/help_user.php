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
<html>
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

   ?>
<h1>User Admin </h1>
<p>Your User Administration System allows you to create two different types of users.</p>
<blockquote>
  <p><strong>Admin User:</strong></p>
  <p> Admin Users have the responsibility of managing the content of your web site. Admin users can add, remove or hide pages, change content, add images and upload documents. They can also create new user accounts for other individuals. </p>
  <p><strong> Member User: </strong></p>
  <p> Member users can  be given permission to edit content within certain pages of the site. </p>
</blockquote>
<p><br>
The following instructions will guide you in using the User Administration System. </p>
<ul>
  <li><a href="#introduction">Introduction</a></li>
  <li><a href="#adding_a_new_user">Adding a new user</a></li>
  <li><a href="#editing_a_user">Editing a user</a> </li>
  <li><a href="#deleting_a_user">Deleting a user</a></li>
  <li><a href="#user_active">Making a user active</a></li>
  <li>   <a href="#user_inactive">Making a user inactive</a></li>
</ul>
<h3><br>
  <a name="introduction"></a>Introduction </h3>
<p>To manage users click on the 'User Admin' tab</p>
<p align="center"> <img src="images/user_admin_shot1.gif"></p>
<p> From this page you can </p>
<ul>
  <li> add a new user </li>
  <li>edit an existing user's details </li>
  <li> delete a user </li>
  <li> make a user's account active or inactive </li>
</ul>
<p></p>
<h3><br>
  <a name="adding_a_new_user"></a>Adding a new user:</h3>
<table border="0" cellspacing="0" cellpadding="4">
  <tr>
    <td>1. Click on the &ldquo;create a new user&rdquo; button. </td>
    <td><img src="images/user_admin_create_user.gif"></td>
  </tr>
</table>
<p>All fields marked with a * must have details entered. </p>
<p><em> Personal details section: </em></p>
<p> 2. Enter First Name, Surname and Email address in the appropriate fields<br>
    <br>
    <br>
	<!--
	<strong>
ANDY USE MODULE CODE TO DECIDE WHETHER TO DISPLAY THESE NEXT 2 BITS!!!</strong></p>
<p> 3. If the user is happy for others to see their email address viewed by other users the then the &ldquo;Show my email address to other users&rdquo; can be ticked </p>
<p> When a comment is made on a blog entry then the system can email the author of the entry. This is useful where the comment is a question about the entry and the author would like to respond. </p>
<p> 4. Click &ldquo;receive email when someone comments on my posts&rdquo; for the system to send emails to user when comments are entered about any of their posts. </p>
<p><em> <br>
-->
    <em>Login Details Section: </em></p>
<p> 3. Enter the username and the password and confirm the password by entering it again in the Confirm Password field. </p>
<p> 4. If the account is to be active immediately the Account Status box should be ticked. This box may be ticked later by editing the user at another time. </p>
<p> 5. Select the type of user, either Admin or Member. </p>
<p> 6. For Member users check &ldquo;Content management System&rdquo; if the user is to be allowed to add change and delete pages. <br>
Note that the member user will still need access granted to them for each page of the content that they are allowed to change. Admin users are able to change the content on every page. </p>
<p> 7. Click Submit to complete adding the user. </p>
<p>&nbsp;</p>
<h3> <a name="editing_a_user"></a>Editing a user:</h3>
<table border="0" cellspacing="0" cellpadding="4">
  <tr>
    <td>1. Click on the &ldquo;Edit user&rdquo; button to the right of the users name. </td>
    <td><img src="images/user_admin_edit_user.gif"></td>
  </tr>
</table>
<p> Please note, if you wish to cancel the changes you have been making then you can click on the user admin tab to cancel the current changes. However once you have clicked the Submit button you will need to correct any changes manually by re-editing the user. </p>
<p> 2. Make changes as appropriate. </p>
<p> 3. Click Submit to complete editing the user. </p>
<h3>&nbsp; </h3>
<h3><a name="deleting_a_user"></a>Deleting a user:</h3>
<table border="0" cellspacing="0" cellpadding="4">
  <tr>
    <td>1. Click on the &ldquo;Delete user&rdquo; button to the right of the users name. </td>
    <td><img src="images/user_admin_delete_user.gif"></td>
  </tr>
</table>
<p> You will now be asked to confirm that you wish to delete the user. </p>
<p> 2. Click &ldquo;Yes&rdquo; to confirm or &ldquo;No&rdquo; to cancel. </p>
<p>&nbsp;</p>
<p>
<h3><a name="user_active"></a>Making  a user active:</h3>
<p> If the account is was not made active when the user was created the user can be made active as follows: </p>
<p> 1. Click on the &ldquo;Edit user&rdquo; button to the right of the users name. </p>
<p> 2. Tick the Account Status box by clicking on it. </p>
<p> 3. Click the Submit button </p>
<p> <br>
<h3><a name="user_inactive"></a>Making a user inactive:</h3>
<p> A user account can be made inactive as follows: </p>
<p> 1. Click on the &ldquo;Edit user&rdquo; button to the right of the users name. </p>
<p> 2. Tick the Account Status box by clicking on it. </p>
<p> 3. Click the Submit button </p>
<p><br>


    <?php

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


