<?php
	// need to kill the persistant cookie
	persistLogin::DeleteKeptSession($_SESSION["session_member_id"]);

	$_SESSION["session_member_id"] = '';
	$session_member_id             = '';

	// shop
	unset( $_SESSION["isTrade"] ); //  = '';
	unset( $_SESSION["session_member_name"] );
	unset( $_SESSION["session_member_details"] );
	$_SESSION['form_id'] = '';

	if ( SITE_HAS_SECURE_SECTIONS ) {

		header( 'Location: /' );
	}

?><p>You have been logged out.</p>