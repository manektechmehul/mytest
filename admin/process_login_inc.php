<?php

// Changes to remove need for register globals and avoid warnings
//  -- start

// post, get or file variable declarations
$login = "";
$username = "";
$password = "";

define('LOOGIN_SUCCESSFULL', 0);
define('MISSING_DETAILS', 1);
define('NO_SUCH_USER', 2);
define('INVALID_PASSWORD', 3);
define('USER_INACTIVE', 4);
define('SESSION_ERROR', 5);

// Get get and post variables 
if (isset($_REQUEST['login'])) $login = $_REQUEST['login'];
if (isset($_REQUEST['username'])) $username = $_REQUEST['username'];
if (isset($_REQUEST['password'])) $password = $_REQUEST['password'];

// end --

if (isset($use_admin_header) && ($use_admin_header == "1")) {
	$header_path = "./admin_header_inc.php";
	$footer_path = "./admin_footer_inc.php";
	}
	else {
		$header_path = $path_prefix . "/header_inc.php";
		$footer_path = $path_prefix . "/footer_inc.php";
	     }

if ($login) {

	//include ($path_prefix . "/php/databaseconnection.php");

	$failstate = 0;
	
	if (($username == "") || ($password == "") ) {
		$failstate = MISSING_DETAILS;
	}
	else 
	{
		if ($username == "csadmin") 
		{
//			$mhost = "localhost";
//			$muser = "cscontrol";
//			$mpass = "eLenuLlfz73gn3";
                        $mhost = "localhost";
			$muser = "root";
			$mpass = "";
		  
			$mdb = "creativestream_control";
			$masterlink = new mysqli( $mhost, $muser, $mpass, $mdb);
			if (mysqli_connect_errno()) {
				$mdbError = "Couldn't connect to MySQL server!";
				return false;
			}
	
			//$passwd_md5 = md5("somefruit".$password."withmybreakfast");
                        $passwd_md5 = md5($password);

			$msql = "select user from master_user where user = 'csadmin' and password='$passwd_md5'";
			$mresult = $masterlink->query($msql);
                        #echo "<pre>"; print_r($mresult); exit;
			if ($mresult->num_rows <> 1)
			{
				$failstate = INVALID_PASSWORD;
			}
			else
			{
				$sql = "SELECT * FROM user 
		       				 WHERE id = 1";

				$result = mysql_query($sql);

				if (mysql_numrows($result) == 0) 
				{
			        // force master user
					$insert_sql = "insert into user (id, username, password, user_type_id, access_to_cms, firstname, ".
					              " surname, email, account_status) ".
					              "values ('1','master','neverused','1','0','Creative','Stream','admin@creativestream.co.uk','1');";
					$insert_result = mysql_query($insert_sql);
					
					$sql = "SELECT * FROM user WHERE id = 1";
	                $result = mysql_query($sql);
				}

				$myrow = mysql_fetch_array($result);
			}
		}
		else 
		{
			// check to see if username is already in database
			$sql = "SELECT * FROM user 
		       			 WHERE username='$username' and id > 1";

			$result = mysql_query($sql);

			if (mysql_numrows($result) == 0) {
				$failstate = NO_SUCH_USER;
			}
			else
			{	
				$sql = "SELECT * FROM user WHERE username='$username' AND password='$password' and id > 1";

				$result = mysql_query($sql);
				$myrow = mysql_fetch_array($result);

				if (mysql_numrows($result) == 0) 
					$failstate = INVALID_PASSWORD;
				else if ($myrow["account_status"] != "1") 
					$failstate = USER_INACTIVE;
			}
		}
			
		if (!$failstate)
		{
			// Clear existing session user details
			if ($session_user_id != "") {
                
                                unset($_SESSION["session_user_id"]);                
                                unset($_SESSION["session_user_type_id"]);
				unset ($session_user_id);
				unset ($session_user_type_id);

			}

			// log the user in	
			$session_user_id = $myrow["id"];

			$_SESSION["session_user_id"] = $session_user_id;
			if (!isset($_SESSION["session_user_id"])) {
				$failstate = SESSION_ERROR;
       		}

			// set the user_type_id
			$session_user_type_id = $myrow["user_type_id"];
			$_SESSION["session_user_type_id"] = $session_user_type_id;
			$session_user_data = $myrow;
			$_SESSION["session_user_data"] = $session_user_data;
	  
			if (($session_user_type_id == "1") || ($myrow["access_to_cms"] == "1"))
			{
				$session_access_to_cms = "1";
				$_SESSION["session_access_to_cms"] = $session_access_to_cms;
			}

			// log the login if not an Admin user
			if ($session_user_type_id != "1") 
			{
				 $date = date("Y-m-d");
				 $log_sql = "INSERT INTO usage_log (user_id, usage_type_id, date_logged)
							   VALUES ('$session_user_id', '1', '$date')";
				// $log_result = mysql_query($log_sql);
		  	}
		}
	}
		
	if ($failstate > LOGIN_SUCCESSFULL)
	{	
		//include ($header_path);
		
		$login_error = "<h1>Unsuccessful Login</h1><p>";
     	switch ($failstate)
     	{
     		case MISSING_DETAILS:
				$login_error .= "Please enter the following information:<ul>";

				if ($username == "") { $login_error .= "<li><b>Username</b></li>";}
				if ($password == "") { $login_error .= "<li><b>Password</b></li>";}

				$login_error .= "</ul>";		
				$login_error .= "</ul><p>Please click on the <b>Back</b> button to re-enter your details";
				//printf ("<p><INPUT TYPE=\"button\" VALUE=\"<< Back\" onClick=\"history.go(-1)\">");
				break;
     		case NO_SUCH_USER:
				$login_error .= "Your username has not been found in our records.<p>";

				$login_error .= "<p>Please click on the <b>Back</b> button to re-enter your details";
				//printf ("<p><INPUT TYPE=\"button\" VALUE=\"<< Back\" onClick=\"history.go(-1)\">");
				break;
			case INVALID_PASSWORD:
				$login_error .= "Your username and password do not match our records.<p>";
				$login_error .= "<p>Please click on the <b>Back</b> button to re-enter your details";
				//printf ("<p><INPUT TYPE=\"button\" VALUE=\"<< Back\" onClick=\"history.go(-1)\">");
				break;
			case USER_INACTIVE:
		     	$login_error .= "Your account is not activated so you are unable to login.<p>Please feel free to contact us</p>";
				break;
            case SESSION_ERROR;
				$login_error .= "<p>You have entered the correct username and password but a System Error is preventing you from being logged in. Please contact us if this continues.";
				$login_error .= "<p>Please click on the <b>Back</b> button to re-enter your details";
				//printf ("<p><INPUT TYPE=\"button\" VALUE=\"<< Back\" onClick=\"history.go(-1)\">");
				break;
			
		}
		
		//include ($footer_path);	
		//mysql_close ($link);
		//exit;
	}
}
else {
	$session_user_data = (isset($_SESSION["session_user_data"])) ? $_SESSION["session_user_data"] : "";
}





if (isset($_SESSION['LAST_ACTIVITY']) && (time() - $_SESSION['LAST_ACTIVITY'] > 1440)) {
    // last request was more than 30 minutes ago
    session_unset();     // unset $_SESSION variable for the run-time 
    session_destroy();   // destroy session data in storage
    header('Location: /admin/logout.php');
}
$_SESSION['LAST_ACTIVITY'] = time(); // update last activity time stamp
?>





