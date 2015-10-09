<?php
session_cache_limiter('must-revalidate');
session_start();

// Changes to remove need for register globals and avoid warnings
//  -- start


// general variable declarations
$PHP_SELF = $_SERVER['PHP_SELF'];

// get session variable 
$session_section_id = (isset($_SESSION['session_section_id'])) ? $_SESSION['session_section_id'] : "";

// post, get or file variable declarations
$submit = "";

// Get get and post variables 
if (isset($_REQUEST['submit'])) $submit = $_REQUEST['submit'];
if (isset($_REQUEST['email'])) $email = $_REQUEST['email'];

// end --

?>
<html>
<head>
<title>Administration</title>

<link href="./css/adminstylesheet.css" rel="stylesheet" type="text/css" />
<link href="http://fonts.googleapis.com/css?family=Gafata" rel="stylesheet" type="text/css">
</head>
<body>

<?php

$path_prefix = ".";

include ("../php/databaseconnection.php");
include ("../php/read_config.php");
include ("./cms_functions_inc.php");
include ("./admin_header_inc.php");

?>

<!-- CONTENT STARTS HERE -->

<?php

if ($submit != "") {

	$user_sql = "SELECT * FROM user WHERE email='$email'";
	$user_result = mysql_query($user_sql);

	if (mysql_num_rows($user_result) > 0 ) {

		$user_row = mysql_fetch_array($user_result);

		if ($user_row["account_status"] == "1" ) {

			$to_email_address = $user_row["email"];
			$subject = "Your " . SITE_NAME . " username and password";
		  	$from_email_address = SITE_CONTACT_EMAIL;

			
			$message = "Here is the " . SITE_NAME . " username and password you requested." . "\n\n";
	
			$message .= "Your username = " . $user_row["username"]  . "\n\n";
			$message .= "Your password = " . $user_row["password"]  . "\n\n";
	

			mail($to_email_address,$subject,$message,'FROM: '.$from_email_address); 

			printf ("<p><b>Forgotten my password</b>");
			printf ("<p>Your username and password have been sent to %s", $email);
			}
			else {
				printf ("<p><b>Forgotten my password</b>
				<p>Sorry, the account associated with your email address is not currently active.");

			     }

		}
		else {
			printf ("<p><b>Forgotten my password</b>
				 <p>Sorry, the email address you entered is not in our database.");
			printf ("<p>Please click on the <b>Back</b> button if you wish to enter your email address again.");
		      	printf ("<p><INPUT TYPE=\"button\" VALUE=\"<< Back\" onClick=\"history.go(-1)\">");	      
		     }

	}
	else {
		printf ("<p><b>Forgotten my password</b>
		<p>Please enter your email address so we can send you your username and password.");
		
		printf ("<form method=\"post\" action=\"%s\">", $PHP_SELF);
		?>

	        <table cellpadding="4">
        	  <tr>
	       	    <td>Email Address</td>
       		    <td>
		      <input type="Text" name="email" >
		       </td>
	         </tr>
        	  <tr>       	    
	            <td>&nbsp;</td>
       		    <td>
		      <input type="Submit" name="submit" value="Submit" >
		    </td>
	          </tr>

        
        	</table>

		</form>

	  	<?php
	     }

?>

<!-- CONTENT ENDS HERE -->


<?php

include ("./admin_footer_inc.php");

mysql_close ( $link );
?>
