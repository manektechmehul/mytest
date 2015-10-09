<?php

session_cache_limiter('must-revalidate');
session_start();

// Changes to remove need for register globals and avoid warnings
//  -- start

// general variable declarations
$acc = 0;
$use_admin_header=1;
$admin_tab = "";
$original_username = "";
$PHP_SELF = $_SERVER['PHP_SELF'];

// Get Session variables
$session_user_id =  (isset($_SESSION['session_user_id'])) ? $_SESSION['session_user_id'] : "";
$session_user_type_id = (isset($_SESSION['session_user_type_id'])) ? $_SESSION['session_user_type_id'] : "";
$session_access_to_cms = (isset($_SESSION['session_access_to_cms'])) ? $_SESSION['session_access_to_cms'] : "";
$session_section_id = (isset($_SESSION['session_section_id'])) ? $_SESSION['session_section_id'] : "";

// post, get or file variable declarations
$create_user = "";
$user_id = "";
$Submit_login_details = "";
$edit_login_details = "";
$blog_to_join = "";
$user_id = "";
$create_user = "";
$first_name = "";
$surname = "";
$email = "";
$share_email_address = "";
$receive_comment_email = "";
$username = "";
$password = "";
$confirm_password = "";
$account_status = "";
$user_type_id = "";
$access_to_cms = "";
$access_to_msg_forum = "";
$access_to_uploaded_documents = "";
$Submit_login_details = "";

// Get get and post variables 
if (isset($_REQUEST['access_to_cms'])) $access_to_cms = $_REQUEST['access_to_cms'];
if (isset($_REQUEST['access_to_msg_forum'])) $access_to_msg_forum = $_REQUEST['access_to_msg_forum'];
if (isset($_REQUEST['access_to_uploaded_documents'])) $access_to_uploaded_documents = $_REQUEST['access_to_uploaded_documents'];
if (isset($_REQUEST['account_status'])) $account_status = $_REQUEST['account_status'];
if (isset($_REQUEST['events_access'])) $events_access = $_REQUEST['events_access'];
if (isset($_REQUEST['news_access'])) $news_access = $_REQUEST['news_access'];
if (isset($_REQUEST['blog_to_join'])) $blog_to_join = $_REQUEST['blog_to_join'];
if (isset($_REQUEST['create_user'])) $create_user = $_REQUEST['create_user'];
if (isset($_REQUEST['confirm_password'])) $confirm_password = $_REQUEST['confirm_password'];
if (isset($_REQUEST['edit_login_details'])) $edit_login_details = $_REQUEST['edit_login_details'];
if (isset($_REQUEST['email'])) $email = $_REQUEST['email'];
if (isset($_REQUEST['first_name'])) $first_name = $_REQUEST['first_name'];
if (isset($_REQUEST['password'])) $password = $_REQUEST['password'];
if (isset($_REQUEST['receive_comment_email'])) $receive_comment_email = $_REQUEST['receive_comment_email'];
if (isset($_REQUEST['share_email_address'])) $share_email_address = $_REQUEST['share_email_address'];
if (isset($_REQUEST['Submit_login_details'])) $Submit_login_details = $_REQUEST['Submit_login_details'];
if (isset($_REQUEST['surname'])) $surname = $_REQUEST['surname'];
if (isset($_REQUEST['user_id'])) $user_id = $_REQUEST['user_id'];
if (isset($_REQUEST['username'])) $username = $_REQUEST['username'];
if (isset($_REQUEST['user_type_id'])) $user_type_id = $_REQUEST['user_type_id'];



$user_row = array(
  'firstname' => $first_name,
  'surname' => $surname,
  'email' => $email,
  'username' => $username,
  'password' => $password,
  'user_type_id' => $user_type_id,
  'share_email_address' => $share_email_address,
  'receive_comment_email' => $receive_comment_email,
  'account_status' => $account_status,
  'events_access' => $events_access,
  'news_access' => $news_access,
  'access_to_cms' => $access_to_cms,
  'access_to_msg_forum' => $access_to_msg_forum,
  'access_to_uploaded_documents' => $access_to_uploaded_documents,
  );


// end --


?>

<html>
<head>

<title>Administration</title>

<meta http-equiv="Pragma" content="no-cache">
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">


<link href="./css/adminstylesheet.css" rel="stylesheet" type="text/css" />
<link href="http://fonts.googleapis.com/css?family=Gafata" rel="stylesheet" type="text/css">
</head>
<body >

<?php


$path_prefix = "..";

include ("../php/databaseconnection.php");
include ("../php/read_config.php");
include ("./cms_functions_inc.php");


/*if ((!$user_id) && (!$create_user)) {
	include ("../header_inc.php");
	include ("../php/process_login_inc.php");
	}
	else {
	     }
	*/

$admin_tab = "user_admin";
$use_admin_header = "1";
include ("./process_login_inc.php");
include ("./admin_header_inc.php");		

unset($_SESSION["session_section_id"]);
unset ($session_section_id);


if (($session_user_id) && ($session_access_to_cms))
{

	printf ("<h1>User Administration</h1><p>");

	if ($user_id != 1) {

		// include ("../blog_functions_inc.php"); 

		
		if ($Submit_login_details) {  

			if ($create_user == "") {
				// editing an existing user	

				if ($user_id != "") {  
					$orig_username_sql = "SELECT * FROM user WHERE id='$user_id' and id > 1";
					}
					else {
						$orig_username_sql = "SELECT * FROM user WHERE id = '$session_user_id'";
					     }
	  
				$orig_username_result = mysql_query($orig_username_sql);
				$orig_username_row = mysql_fetch_array($orig_username_result);
				$original_username = $orig_username_row["username"];
				}

			  $duplicate_username_sql = "SELECT * FROM user WHERE username='$username'";
			  $duplicate_username_result = mysql_query($duplicate_username_sql);


			  if (($first_name == "") || ($surname == "") || ($email == "") || ($username == "") || ($password == "") || ($user_type_id == "")) {	
		
		  		echo "<b>Error: Missing Information</b>";
				echo "<P>The following fields were left blank in your form:";
	      			echo "<ul>";

	       			if (!$first_name) {echo "<li>First Name</li>";}
				if (!$surname) {echo "<li>Surname</li>";}
				if (!$email) {echo "<li>Email Address</li>";}
	       			if (!$username) {echo "<li>Username</li>";}
	       			if (!$password) {echo "<li>Password</li>";}
 	       			if (!$user_type_id) {echo "<li>Type of Account</li>";}
		       
	      			echo "</ul><br>";
	      			echo "Your account has not yet been updated because the above information is missing.<p>";
	     			printf ("<p>Please click on the <b>Back</b> button to enter the details.");
	      			printf ("<p><INPUT TYPE=\"button\" VALUE=\"<< Back\" onClick=\"history.go(-1)\">");
	      			}	 
				else if ((strstr($username, " ")) || (strstr($password, " "))){

					printf ("<p>The account has not yet been created because the username or password contains at least one space.");
					printf ("<p>Please click on the <b>Back</b> button to remove the space(s).");
					printf ("<p><INPUT TYPE=\"button\" VALUE=\"<< Back\" onClick=\"history.go(-1)\">");
					}

				else if (!ereg('^[-!#$%&\'*+\\./0-9=?A-Z^_`a-z{|}~]+'.
				    '@'.
				    '[-!#$%&\'*+\\/0-9=?A-Z^_`a-z{|}~]+\.'.
				    '[-!#$%&\'*+\\./0-9=?A-Z^_`a-z{|}~]+$', $email)) {

				      echo "<b>Error: Invalid Email Address</b>";
		
				      echo "<P>Your account has not yet been updated because you have entered an invalid email address.";
				      printf ("<p>Please click on the <b>Back</b> button to re-enter your email address.");
				      printf ("<p><INPUT TYPE=\"button\" VALUE=\"<< Back\" onClick=\"history.go(-1)\">");
				      } 

			
				else if (($original_username != $username) && (mysql_numrows($duplicate_username_result) > "0")){
					printf ("<p><b>Your account has not yet been updated.</b>");
					printf ("<p>The username you entered is already being used by someone else. Please enter another username.");
					printf ("</ul><p>Please click on the <b>Back</b> button to complete the details");
					printf ("<P><INPUT TYPE=\"button\" VALUE=\"<< Back\" onClick=\"history.go(-1)\">");			
					}

				else if (strlen($password) < 8) {

					printf ("<p>Your account has not yet been updated because your password contains less than 8 characters.");
					printf ("<P>Please click on the <b>Back</b> button to enter another password.");
					printf ("<P><INPUT TYPE=\"button\" VALUE=\"<< Back\" onClick=\"history.go(-1)\">");
					}

				else if ($password <> $confirm_password) {
					printf ("<p>Your account has not yet been updated because the two passwords are different.");
					printf ("<P>Please click on the <b>Back</b> button to re-enter your passwords.");
					printf ("<P><INPUT TYPE=\"button\" VALUE=\"<< Back\" onClick=\"history.go(-1)\">");
					}
		
				else {	

					// process form - insert data into database and send email		
				
					if ($create_user) {
						// insert record if creating a new user
						$create_sql="INSERT INTO user (username) 
									VALUES ('$username')";
		
						$create_result = mysql_query($create_sql);								

						// retrieve id of new record and assign it to $user_id

						$new_user_sql = "SELECT * FROM user WHERE username = '$username'";
						$new_user_result = mysql_query($new_user_sql);
						$new_user_row = mysql_fetch_array($new_user_result);
						$user_id = $new_user_row["id"];




						}


					if ($session_user_type_id != "1") {

						if (!$user_id) {
							$user_id = $session_user_id;
							}

						$user_sql = "SELECT * FROM user WHERE id='$user_id'";
						$user_result = mysql_query($user_sql);
						$user_row = mysql_fetch_array($user_result);

						$account_status = $user_row["account_status"];
						$user_type_id = $user_row["user_type_id"];

						$access_to_cms = $user_row["access_to_cms"];
						$access_to_msg_forum =  $user_row["access_to_msg_forum"];
						$access_to_uploaded_documents =  $user_row["access_to_uploaded_documents"];

						}	


					if ($user_id) {
						// check to see if account was previously de-activated			

						if ($account_status == "1") {				
							$user_sql = "SELECT * FROM user WHERE id='$user_id'";
							$user_result = mysql_query($user_sql);
							$user_row = mysql_fetch_array($user_result);

							if ($user_row["account_status"] != "1") {

								// send the user an email informing them that their account is now activated
								$to_email_address = $email;
			
								$subject = "Your " . SITE_NAME . " web site account is now activated";
						  		$from_email_address = SITE_CONTACT_EMAIL;
		
						  		$message ="Your " . SITE_NAME . " web site account is now activated:\n\n";
						  
						  		$message .="Your username is: " . $username . "\n\n";
		
								$message .="Your password is: " . $password . "\n\n";
		
								$message .= "Click here to access the " . SITE_NAME . " web site - " . SITE_ADDRESS . "\n";
		
		
								mail($to_email_address,$subject,$message,'FROM: '.$from_email_address); 
								}
							}
						}


			 		$sql = "UPDATE user SET firstname = '$first_name',
								surname = '$surname',
								email = '$email',
								share_email_address = '$share_email_address',
								receive_comment_email = '$receive_comment_email',
								username = '$username',
								password = '$password',
								user_type_id = '$user_type_id',
								access_to_cms = '$access_to_cms',
								access_to_msg_forum = '$access_to_msg_forum',
								access_to_uploaded_documents = '$access_to_uploaded_documents',
								account_status = '$account_status',
								events_access = '$events_access',
								news_access = '$news_access'
						";

					if ($user_id == "") {
						$sql = $sql . " WHERE id='$session_user_id'";
						}
						else {
							$sql = $sql . " WHERE id='$user_id'";
						     }

					$result = mysql_query($sql);
  			
					if ($create_user == "") {
						//update_username_and_password_in_phorum ($original_username, $username, $password, $email);
						}

					if ($user_id == "") {
						echo "<b><b>My Account Details</b></b>";
						}
						else {
							echo "<b>User's details</b>";
						     }

					printf ("<p>Your changes have been successfully submitted.");

					if ($user_id) {
			
						if (($access_to_cms == "1") && ($session_user_type_id == "1") && ($user_type_id != "1") ) {
							printf ("<P>Remember you need to specify which pages the user can edit in the <a href=\"./content_admin.php\">Content Management System</a>");
							}
						printf ("<p><a href=\"/admin/user.php?action=list&search_by=all\"><b>Please click here to return to the User Admin pages</b></a>");	  	
				     		}
						else {
							printf ("<p><a href=\"/\">Return to Home Page</a>");
						     }
				     }

			
			}
		        else if (($edit_login_details != "") || ($create_user != "")) {
		
				// check to see if a non-admin user is trying to edit a user or create a new user
				if (($session_user_type_id != "1") && (($user_id) || ($create_user != "")) ) {
					printf ("<p>Sorry, you do not have the appropriate access rights to view this page");
					mysql_close($link);
					include ("../footer_inc.php");
					exit;
					}
			
				if ($edit_login_details != "") {
					if ($user_id) {
						$user_sql = "SELECT * FROM user WHERE id = '$user_id'";
						}
						else if ($edit_login_details != "") {
							$user_sql = "SELECT * FROM user WHERE id = '$session_user_id'";
				     			}	
		
					$user_result = mysql_query($user_sql);
					$user_row = mysql_fetch_array($user_result);
					}

				if (($user_id != "") || ($create_user != "")){
					echo "<b>User's details</b>";
					}
					else {
						echo "<b>My Account Details</b>";
					     }
				?>

				<form method="post" action="<?php echo $PHP_SELF?>">

                		<input type="hidden" name="blog_to_join" value="<?php echo "$blog_to_join"?>">
				<input type="hidden" name="user_id" value="<?php echo "$user_id"?>">
			        <input type="hidden" name="create_user" value="<?php echo "$create_user"?>">

	                        <table  border="0" cellspacing="0" cellpadding="3" >
	                        <tr valign="middle"> 
	                        <td width="22%"> 
	                          <div align="right"><b>Personal Details</b></div>
	                        </td>
	                        <td>&nbsp;</td>
	                        </tr>

	                        <tr valign="middle"> 
	                        <td width="22%"> 
	                          <div align="right">First Name</div>
	                        </td>
	                        <td> 
	                          <input type="text" name="first_name" maxlength="25" value="<?php printf ("%s", $user_row["firstname"]);?>">
	                          * </td>
	                        </tr>
	                        <tr valign="middle"> 
	                        <td width="22%"> 
	                          <div align="right">Surname</div>
	                        </td>
	                        <td> 
	                          <input type="text" name="surname" maxlength="40" value="<?php printf ("%s", $user_row["surname"]);?>">
	                          * </td>
	                        </tr>
	                        <tr valign="middle"> 
	                        <td width="22%"> 
	                          <div align="right">Email Address</div>
	                        </td>
	                        <td> 
	                          <input type="text" name="email" maxlength="255" value="<?php printf ("%s", $user_row["email"]);?>">
	                          * </td>
	                        </tr>

	      <?php
	        // Only include where blog present
	        if (defined('SITE_HAS_BLOG') && (SITE_HAS_BLOG == 1)) {
	      ?>
			    
				<tr valign="middle"> 
	                        <td width="22%"> 
	                        </td>
	                        <td> 
				<?php
				  $checked = "";
				  if ($user_row["share_email_address"] == "on") {
					$checked = "checked";
					}

				  printf ("<input type=\"checkbox\" name=\"share_email_address\" $checked value=on>");
	                          printf ("Show my email address to other users (<i>tick if you are happy for others to see it</i>)");
				  ?>
	                          </td>
	                      </tr>

			      <tr valign="middle"> 
	                        <td width="22%"> 
	                        </td>
	                        <td> 
				<?php
				  $checked = "";
				  if ($user_row["receive_comment_email"] == "on") {
					$checked = "checked";
					}

				  printf ("<input type=\"checkbox\" name=\"receive_comment_email\" $checked value=on>");
	                          printf ("Receive email when someone comments on my posts");
				  ?>
	                          </td>
	                      </tr>
	      <?php
	        }
	      ?>



	                        <tr valign="middle"> 
	                        <td width="22%">&nbsp;</td>
	                        <td>&nbsp;</td>
	                        </tr>
	                 
	                        <tr valign="middle"> 
	                        <td width="22%"> 
	                          <div align="right"><b>Login Details</b></div>
	                        </td>
	                        <td>&nbsp;</td>
	                        </tr>
	                        <tr valign="middle"> 
	                        <td width="22%"> 
	                          <div align="right">Username</div>
	                        </td>
	                        <td> 
	                          <input type="text" name="username" maxlength="255" value="<?php printf ("%s", $user_row["username"]);?>">
	                          * </td>
	                        </tr>
	                        <tr valign="middle"> 
	                        <td width="22%"> 
	                          <div align="right">Password</div>
	                        </td>
	                        <td> 
	                          <input type="password" name="password" maxlength="255" value="<?php printf ("%s", $user_row["password"]);?>">
	                          * <i>(min 8 characters)</i></td>
	                        </tr>
	                        <tr valign="middle"> 
	                        <td width="22%"> 
	                          <div align="right">Confirm Password</div>
	                        </td>
	                        <td> 
	                          <input type="password" name="confirm_password" maxlength="255" value="<?php printf ("%s", $user_row["password"]);?>">
	                          * </td>
	                        </tr>

		   		<?php
		   		if (($session_user_type_id == "1") && (($user_id) || ($create_user)) ) {
					?>
<?php // Account Status ?>
			     		<tr valign="middle"> 
        		                <td width="22%">
					  <div align="right">Account Status</div>
                        		</td>
		                        <td> 
					<?php
					  $checked = "";
					  if ($user_row["account_status"] == "1") {
						$checked = "checked";
						}
					  printf ("<input type=\"checkbox\" name=\"account_status\" $checked value=1>");
					  ?>
					  (<i>tick to activate</i>)
		                          </td>
        		                </tr>

<?php // Account type ?>

					<tr valign="middle"> 
		                         <td width="22%">
					  <div align="right">Account Type</div>
                		         </td>
                        		 <td> 
		
					  <?php
					  $user_type_sql = "SELECT * FROM user_type";
				
					  $user_type_result = mysql_query($user_type_sql);		
				
					  printf ("<select name=\"user_type_id\">");

					  printf ("<option value=\"\" ");
		
					  if ($user_row["user_type_id"] == "") {
						  printf (" selected ");
						  }
				
					  printf (" >Please select</option>");
					
					  while ($user_type_row = mysql_fetch_array($user_type_result)) {
			
						printf ("<option value=\"%s\" ", $user_type_row["id"]);
		
						if ($user_row["user_type_id"] == $user_type_row["id"]) {
							printf (" selected ");
							}
		
						printf (" >%s</option>", $user_type_row["name"]);
						}

				          echo "</select>";
					 ?> *
                		         </td>
                        		</tr>
			    

	 				<tr valign="middle"> 
                		        <td width="22%">&nbsp;</td>
                        		<td>&nbsp;</td>
		                        </tr>

        		                <tr valign="middle"> 
                		        <td width="22%"> 
                        		  <div align="right"><b>Access to:</b></div>
		                        </td>
        		                <td>&nbsp;</td>
                		        </tr>

					<tr valign="middle"> 
                		        <td width="22%">
					  <div align="right">Content Admin</div>
		                        </td>
        		                <td> 
					<?php
					  $checked = "";
					  if ($user_row["access_to_cms"] == "1") {
						$checked = "checked";
						}
					  printf ("<input type=\"checkbox\" name=\"access_to_cms\" $checked value=1>");
					  ?>				 
                		          </td>
                        		</tr>
<?php 
// News access control 
	if (SITE_HAS_NEWS == "1") { ?>
			     		<tr valign="middle">
        		                <td width="22%">
					  <div align="right">News Admin</div>
                        		</td>
		                        <td>
					<?php
					  $checked = "";
					  if ($user_row["news_access"] == "1") {
						$checked = "checked";
						}
					  printf ("<input type=\"checkbox\" name=\"news_access\" $checked value=1>");
					  ?>
						</td>
        		                </tr>
<?php 
	};
// Events access control 
	if (SITE_HAS_EVENTS == "1") { ?>
			     		<tr valign="middle">
        		                <td width="22%">
					  <div align="right">Events Admin</div>
                        		</td>
		                        <td>
					<?php
					  $checked = "";
					  if ($user_row["events_access"] == "1") {
						$checked = "checked";
						}
					  printf ("<input type=\"checkbox\" name=\"events_access\" $checked value=1>");
					  ?>
						</td>
        		                </tr>
<?php 
	};
	          // Only include forum where relevant 
	          if (defined('SITE_HAS_FORUM') && (SITE_HAS_FORUM == 1)) {
	        ?>
					<tr valign="middle"> 
                		        <td width="22%">
					  <div align="right">Discussion Forum</div>
		                        </td>
        		                <td> 
					<?php
					  $checked = "";
					  if ($user_row["access_to_msg_forum"] == "1") {
						$checked = "checked";
						}
					  printf ("<input type=\"checkbox\" name=\"access_to_msg_forum\" $checked value=1>");
					  ?>				 
                		          </td>
                        		</tr>
	         <?php
	           }
	         ?>

			<!--
					<tr valign="middle"> 
                		        <td width="22%">
					  <div align="right">Uploaded Documents</div>
		                        </td>
        		                <td> 
			-->
					<?php
			//		  $checked = "";
			//		  if ($user_row["access_to_uploaded_documents"] == "1") {
			//			$checked = "checked";
			//			}
			//		  printf ("<input type=\"checkbox\" name=\"access_to_uploaded_documents\" $checked  value=1>");
					  ?>				 
	                <!--	          </td>
                        		</tr>
			-->



					<?php
					}
					
					?>


	                        <tr valign="middle"> 
	                        <td width="22%">&nbsp;</td>
	                        <td><i>Please note that an * indicates a mandatory field</i> 
	                        </td>
	                        </tr>
	                        <tr valign="middle"> 
	                        <td width="22%">&nbsp;</td>
	                        <td> 
	                          <input type="submit" name="Submit_login_details" value="Submit">
	                        </td>
	                        </tr>
	                        </table>
                  		</form>
				<?php
				}
				else {
					$user_sql = "SELECT * FROM user WHERE id = '$session_user_id'";			
					$user_result = mysql_query($user_sql);
					$user_row = mysql_fetch_array($user_result);

					printf ("<p>Hello %s %s", $user_row["firstname"], $user_row["surname"]);

					printf ("<p><a href=\"/admin/user_details.php?edit_login_details=yes\">Edit my personal and login details</a>");

				     }
		}
		else
			printf ("<p>Can't edit master user</p>");
				
	}
	else {
		if ($login)
			echo $login_error;
		else
			include ("./login_inc.php"); 
	     }



include ("./admin_footer_inc.php"); 

mysql_close($link);
?>
