<?php

session_cache_limiter('must-revalidate');

session_start();

// Changes to remove need for register globals and avoid warnings
//  -- start

// general variable declarations
$acc = 0;
$admin_tab = "";
$PHP_SELF = $_SERVER['PHP_SELF'];

// Get Session variables
$session_user_id =  (isset($_SESSION['session_user_id'])) ? $_SESSION['session_user_id'] : "";
$session_user_type_id = (isset($_SESSION['session_user_type_id'])) ? $_SESSION['session_user_type_id'] : "";
$session_access_to_cms = (isset($_SESSION['session_access_to_cms'])) ? $_SESSION['session_access_to_cms'] : "";

// post, get or file variable declarations
$delete_user = "";
$update = "";
$action = "";
$search_by = "";
$user_id = "";
$confirm_delete_user = "";

// Get get and post variables 
if (isset($_REQUEST['delete_user'])) $delete_user = $_REQUEST['delete_user'];
if (isset($_REQUEST['update'])) $update = $_REQUEST['update'];
if (isset($_REQUEST['action'])) $action = $_REQUEST['action'];
if (isset($_REQUEST['search_by'])) $search_by = $_REQUEST['search_by'];
if (isset($_REQUEST['user_id'])) $user_id = $_REQUEST['user_id'];
if (isset($_REQUEST['confirm_delete_user'])) $confirm_delete_user = $_REQUEST['confirm_delete_user'];


function show_button($href, $img, $name)
{
	global $acc;
	$img_over = "/admin/images/$img-over.gif";
	$img_off = "/admin/images/$img-off.gif";
	echo "<td width=0>";
	echo "<a href=\"$href\" onmouseout=\"MM_swapImgRestore()\" onmouseover=\"MM_swapImage('$name$acc','','$img_over',0)\">";
	echo "<img src=\"$img_off\" alt=\"edit user\" name=\"$name$acc\" height=32 border=0  />";
	echo "</a></td>";
}


// end --

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />

<title>Administration</title>

<link href="./css/adminstylesheet.css" rel="stylesheet" type="text/css" />
<link href="http://fonts.googleapis.com/css?family=Gafata" rel="stylesheet" type="text/css">
</head>

<body >

<?php 

unset($_SESSION["session_section_id"]);

unset ($session_section_id);

$path_prefix = "..";

include ("../php/databaseconnection.php");
include ("../php/read_config.php");
include ("./cms_functions_inc.php");

$admin_tab = "user_admin";

$use_admin_header = "1";
include ("./process_login_inc.php");
include ("./admin_header_inc.php");


if (($session_user_id) && ($session_access_to_cms))
{

	if  (($user_id == 1) && ($session_user_id > 1))
		$user_id = "";

	printf ("<h1>User Administration</h1><p>");

	if ($delete_user) {

		$user_sql = "select * from user WHERE id = '$user_id'";
		$user_result = mysql_query($user_sql);		
		$user_row = mysql_fetch_array($user_result);


		if ($user_row["user_type_id"] == "1") {
			// IF THIS USER IS AN ADMIN  USER - MAKE SURE THEY AREN'T THE LAST ADMIN USER

			$admin_user_sql = "select * from user WHERE user_type_id = '1' AND account_status = '1' ";
			$admin_user_result = mysql_query($admin_user_sql);	
			if (mysql_num_rows($admin_user_result) == "1") {
				printf ("<p>Unfortunately this user cannot be deleted as they are the only Admin user with an active account.");
				mysql_close ($link);
				include ("./footer_inc.php"); 
				exit;
				}
			}

		if ($confirm_delete_user) {
			$sql = "UPDATE user SET account_status = '2'
					       WHERE id = '$user_id'";

			$result = mysql_query($sql);
			printf ("<p>The user has been successfully deleted.");
			printf ("<p><a href=\"%s?action=list&search_by=all\">Return to the list of users</a>", $PHP_SELF);
			}
			else {
				printf ("<p>Please confirm that you wish to delete %s %s", $user_row["firstname"], $user_row["surname"]);
				printf ("<p><a href=\"%s?delete_user=yes&confirm_delete_user=yes&user_id=%s\">Yes</a> - I confirm that I wish to delete this user", $PHP_SELF, $user_id);
				printf ("<p><a href=\"%s?action=list&search_by=all\">No</a> - I do not wish to delete this user", $PHP_SELF);
			     }
		}

        else if ($update != "") {
				
		$user_sql="select * from user WHERE account_status != '2' and id > 1 ";
		$user_result = mysql_query($user_sql);
		
		while ($user_row = mysql_fetch_array($user_result)) {

			$user_id = $user_row["id"];			
			
			$blogs_to_join = "";
			$account_status = "";										
				
			$account_status_checkbox = $user_id . "account_status";
			if ($$account_status_checkbox == "on") {
				$account_status = "1";

				// check to see if account was previously de-activated
				if ($user_row["account_status"] != "1") {

										// send the user an email informing them that their account is now activated
					$to_email_address = $user_row["email"];

					$subject = "Your " . SITE_NAME . " web site account is now activated";
				  	$from_email_address = SITE_CONTACT_EMAIL;

				  	$message ="Your " . SITE_NAME . " web site account is now activated:\n\n";
					  
				  	$message .="Your username is: " . $user_row["username"] . "\n\n";

					$message .="Your password is: " . $user_row["password"] . "\n\n";

					$message .= "Click here to access the " . SITE_NAME . " web site - " . BLOG_WEB_PAGE_ADDRESS . "\n";

					//$message .="\n\n\n\nNetwork Solutions - bringing people together with Community Blogging\n";
					//$message .="http://www.netsol.uk.com\n";

					mail($to_email_address,$subject,$message,'FROM: '.$from_email_address); 
					}
				}
			

				$sql = "UPDATE user SET account_status = '$account_status'
					       WHERE id='$user_id'";

				$result = mysql_query($sql);
				
			}

		printf ("<p>The users details have been updated.
			 <p><a href=\"user.php\">Return to User Admin</a>");
	
 	    }
	    else {
		if ($session_user_type_id == "1") {

		    	if (($action == "list") && ($search_by == "all")) {

				?>
				  <table width="100%" border="0" cellspacing="0" cellpadding="0">
				    <tr>
				      <td width="0">
					  <a href="/admin/user_details.php?create_user=yes" onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('createanewuser','','/admin/images/user-createnew-over.gif',0)">
					  <img src="/admin/images/user-createnew-off.gif" alt="create a new user" name="createanewuser" width="246" height="32" border="0" id="createanewuser" />
					  </a></td>
				      <td width="100%" ></td>
				    </tr>
				  </table>
				<?php

				// list all users


				$user_type_sql="select * from user_type";
				$user_type_result = mysql_query($user_type_sql);		
								
				
				while ($user_type_row = mysql_fetch_array($user_type_result)) 
				{
					$user_type_id = $user_type_row['id'];
					$user_sql="select * from user WHERE account_status != '2' and id > 1 and user_type_id = '$user_type_id' ORDER BY user_type_id, firstname";
					$user_result = mysql_query($user_sql);		

					if (mysql_num_rows($user_result) > 0)
					{
						$user_type_name = $user_type_row["name"];
					?>
						  <table width="100%" border="0" cellspacing="0" cellpadding="0">
						    <tr>
							<td colspan=7  valign='bottom' style="background: transparent url(/admin/images/lightbox-tall-bk.gif) repeat; width: 100%;" >
							<br/>
							<span class='heading-blue' style='font-family:Verdana,Arial,Helvetica,sans-serif;font-size:13px'><?php echo $user_type_name; ?></span><br/>
							<img src="/admin/images/user-field-names.gif" alt="email address, account type, account status" border="0" />
							</td>
						    </tr>
					<?php
					
						while ($user_row = mysql_fetch_array($user_result)) 
						{
		
							$acc = $acc + 1;
							$account_active = $user_row["account_status"] == "1";
							
							if ($account_active) 
							{
								$account_status_class = "useron";
								$userfield_class = "userfieldactive";
							}
							else 
							{
								$account_status_class = "useroff";
								$userfield_class = "userfieldinactive";
							}


								
							    echo '<tr>';

								printf ("<td width=280><div class=\"%s\">%s %s</div></td>", $userfield_class, $user_row["firstname"], $user_row["surname"]);
								printf ("<td width=259><div class=\"userfieldemail\"><a href=\"mailto:%s\" style=\"text-decoration:none; color:#0b69ac;\">%s</a></div></td>", $user_row["email"], $user_row["email"]);
								//printf ("<td width=78><div class=\"userfieldaccounttype\">%s</div></td>", $user_type_name);
								//printf ("<td width=23><div class=\"%s\"></div></td>", $account_status_class);

								if ($account_active) 
								{
									$href = sprintf('/admin/user_details.php?edit_login_details=yes&user_id=%s', $user_row["id"]);
									show_button($href, 'buttons/showcontent-deactivate_user', 'usermaindeactivate');
								}
								else
								{
									$href = sprintf('/admin/user_details.php?edit_login_details=yes&user_id=%s', $user_row["id"]);
									show_button($href, 'buttons/showcontent-activate_user', 'usermainactivate');
								}
									
								$href = sprintf('/admin/user_details.php?edit_login_details=yes&user_id=%s', $user_row["id"]);
								show_button($href, 'userbutton-edituser', 'usermainedituser');

								$href = sprintf('%s?delete_user=1&user_id=%s', $PHP_SELF, $user_row["id"]);
								show_button($href, 'userbutton-deleteuser', 'usermaindeleteuser');


							      	echo "<td width=100% style=\"background:url(/admin/images/main-bk.gif);\">";
								//printf ("<div align=right><a href=\"./help.php#user\"><img src=\"/admin/images/help-icon.gif\" alt=\"help\" width=25 height=32 border=0 /></a></div>");
								echo "</td>";
								echo "</tr>";
				
						}
						echo '</table>';
					}						
					
				}
				echo '<br />';
	   		}
			else {
				printf ("Sorry, you are not permitted to view this page");
			     }			
		   }
	   	}
	}
	else {
		if ($login)
			echo $login_error;
		else
			include ("./login_inc.php"); 
	}

  mysql_close ( $link );

  ?>
       
<?php include ("./admin_footer_inc.php"); ?>
   

