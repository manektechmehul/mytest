<?php



	_d( 'going through process login' );
	include dirname( __FILE__ ) . '/password/PasswordHelper.php';
	include_once "./php/classes/persistLogin.php";
	$persistLogin = new persistLogin();
// Changes to remove need for register globals and avoid warnings
//  -- start
// post, get or file variable declarations
// Get Session variables
	$session_member_id      = ( isset( $_SESSION['session_member_id'] ) ) ? $_SESSION['session_member_id'] : "";
	$session_member_details = ( isset( $_SESSION['session_member_details'] ) ) ? $_SESSION['session_member_details'] : "";

	/* refresh session if we have stored a cookie */
	persistLogin::GetLoginSession();


	$login    = "";
	$username = "";
	$password = "";
	define( 'LOGIN_SUCCESSFULL', 0 );
	define( 'MISSING_DETAILS', 1 );
	define( 'NO_SUCH_USER', 2 );
	define( 'INVALID_PASSWORD', 3 );
	define( 'USER_INACTIVE', 4 );
	define( 'SESSION_ERROR', 5 );
// Get get and post variables




	if ( isset( $_REQUEST['login'] ) ) {
		$login = $_REQUEST['login'];
	}
	if ( isset( $_REQUEST['email'] ) ) {
		$email = $_REQUEST['email'];
	}
	if ( isset( $_REQUEST['password'] ) ) {
		$password = $_REQUEST['password'];
	}
	$stayLoggedIn = 0;
	if ( isset( $_REQUEST['stayLoggedIn'] ) ) {
		$stayLoggedIn = $_REQUEST['stayLoggedIn'];
	}
// end --
	if ( isset( $use_admin_header ) && ( $use_admin_header == "1" ) ) {
		$header_path = "./admin_header_inc.php";
		$footer_path = "./admin_footer_inc.php";
	} else {
		$header_path = $path_prefix . "/header_inc.php";
		$footer_path = $path_prefix . "/footer_inc.php";
	}




	if ( $login ) {
		$failstate = 0;
		if ( ( $email == "" ) || ( $password == "" ) ) {
			$failstate = MISSING_DETAILS;
		} else {
			// check to see if username is already in database
			$sql = "SELECT * FROM member_user WHERE email='$email' order by status desc limit 1";
			if ( SITE_HAS_SHOP ) {
				// if we are using shop member system switch table
				$sql = "SELECT * FROM shop_member_user WHERE email='$email' order by status desc limit 1";
			}
			_d( "member login look up query ::" . $sql );
			$result = mysql_query( $sql );
			if ( mysql_numrows( $result ) == 0 ) {
				$failstate = NO_SUCH_USER;
			} else {
				$myrow = mysql_fetch_array( $result );
				$pass  = new PasswordHelper();
				if ( $myrow["status"] != "1" ) {
					$failstate = USER_INACTIVE;
				} else if ( ! $pass->compareToHash( $password, $myrow['password'] ) ) {
					$failstate = INVALID_PASSWORD;
				}
			}
			if ( ! $failstate ) {
				// Clear existing session user details
				if ( $session_member_id != "" ) {
					unset( $_SESSION["session_member_id"] );
					unset( $session_member_id );
					unset( $_SESSION['isTrade'] );
				}
				// log the user in
				$session_member_id                  = $myrow["id"];
				$_SESSION["session_member_id"]      = $session_member_id;
				$_SESSION["session_member_name"]    = "{$myrow['firstname']} {$myrow['surname']}";
				$_SESSION["session_member_details"] = $myrow;
				if ( SITE_HAS_SHOP ) {
					// so here we are infurring that if you are logged in you are a trade user when trade poducts are enabled
					if ( SHOP_USE_TRADE_PRODUCTS ) {
						$_SESSION['isTrade'] = 1;
					}
				}
				if ( ! isset( $_SESSION["session_member_id"] ) ) {
					$failstate = SESSION_ERROR;
				}
				if ( $stayLoggedIn == 1 ) {
					$persistLogin->SaveKeepLoginSession( $session_member_id );
				}
				$date    = date( "Y-m-d" );
				$log_sql = "INSERT INTO usage_log (user_id, usage_type_id, date_logged)
						   VALUES ('$session_member_id', '2', '$date')";
			}
		}
		if ( $failstate > LOGIN_SUCCESSFULL ) {
			//include ($header_path);
			$login_error = "<p><strong>Unsuccessful Login</strong><p>";
			switch ( $failstate ) {
				case MISSING_DETAILS:
					$login_error .= "Please enter the following information:<ul>";
					if ( $username == "" ) {
						$login_error .= "<li><b>email</b></li>";
					}
					if ( $password == "" ) {
						$login_error .= "<li><b>Password</b></li>";
					}
					break;
				case NO_SUCH_USER:
					$login_error .= "Your username has not been found in our records.<p>";
					break;
				case INVALID_PASSWORD:
					$login_error .= "Your username and password do not match our records.<p>";
					break;
				case USER_INACTIVE:
					$login_error .= "Your account is not activated so you are unable to login.<p>Please feel free to contact us</p>";
					break;
				case SESSION_ERROR;
					$login_error .= "<p>You have entered the correct username and password but a System Error is preventing you from being logged in. Please contact us if this continues.";
					break;
			}
		}else{
			if ( SITE_HAS_SECURE_SECTIONS ) {

				header( 'Location: /' .  $ff_url );
			}
		}
	}





	/***
	 * Secure site functionality
	 *
	 * This chunk will check if secure sections are switched on in the config flag - SITE_HAS_SECURE_SECTIONS
	 * Then check section flag, to see if it public or private	 *
	 * If you are not in the logon area (assumed to be 51 for now)
	 * Then redirect to the login area
	 *
	 */

	if ( SITE_HAS_SECURE_SECTIONS ) {
		if (! isset( $_SESSION['session_member_id'] ) ||  $_SESSION['session_member_id'] == "" ) {
			// check if this section is secure
			$public = db_get_single_value( "select public from content_public where section_id =" . $session_section_id );
			// just in case it is not set at all
			if ( $public == 1 ) {
				// echo "<h1>Public page - carry on</h1>";
			} else {
				// Section 51 should be the member login area
				// if not a logon page

			//	var_dump($_REQUEST);
			//	die();

				if($session_section_id != 51 ) {
					//if ( SITE_HAS_SHOP ) {
						// redirect to login
					//	header( 'Location: ' . '/shop-members?forced-redirect=' . urlencode( $_SERVER[REQUEST_URI] ) );
					//}else{
						// redirect to login
						header( 'Location: ' . '/members');
					//}
				}
			}
		}
	}