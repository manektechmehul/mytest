<?php

	_l( 'Starting Paypal IPN processing' );
	// this is where paypal give us responses from payments.
	// CONFIG: Enable debug mode. This means we'll log requests into 'ipn.log' in the same directory.
	// Especially useful if you encounter network errors or other intermittent problems with IPN (validation).
	// Set this to 0 once you go live or don't require logging.
	//  define("PAYPAL_IPN_DEBUG", 1);   -- now in db
	// Set to 0 once you're ready to go live
	//  define("PAYPAL_IPN_USE_SANDBOX", 1);    -- now in db
	// Read POST data
	// reading posted data directly from $_POST causes serialization
	// issues with array data in POST. Reading raw POST data from input stream instead.
	$raw_post_data  = file_get_contents( 'php://input' );
	$raw_post_array = explode( '&', $raw_post_data );
	$myPost         = array();
	foreach ( $raw_post_array as $keyval ) {
		$keyval = explode( '=', $keyval );
		if ( count( $keyval ) == 2 ) {
			$myPost[$keyval[0]] = urldecode( $keyval[1] );
		}
	}
	// read the post from PayPal system and add 'cmd'
	$req = 'cmd=_notify-validate';
	if ( function_exists( 'get_magic_quotes_gpc' ) ) {
		$get_magic_quotes_exists = true;
	}
	foreach ( $myPost as $key => $value ) {
		if ( $get_magic_quotes_exists == true && get_magic_quotes_gpc() == 1 ) {
			$value = urlencode( stripslashes( $value ) );
		} else {
			$value = urlencode( $value );
		}
		$req .= "&$key=$value";
	}
	_l( "req:" . $req );
	// Post IPN data back to PayPal to validate the IPN data is genuine
	// Without this step anyone can fake IPN data
	if ( PAYPAL_IPN_USE_SANDBOX == true ) {
		$paypal_url = "https://www.sandbox.paypal.com/cgi-bin/webscr";
	} else {
		$paypal_url = "https://www.paypal.com/cgi-bin/webscr";
	}
	$ch = curl_init( $paypal_url );
	if ( $ch == false ) {
		return false;
	}
	curl_setopt( $ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1 );
	curl_setopt( $ch, CURLOPT_POST, 1 );
	curl_setopt( $ch, CURLOPT_RETURNTRANSFER, 1 );
	curl_setopt( $ch, CURLOPT_POSTFIELDS, $req );
	curl_setopt( $ch, CURLOPT_SSL_VERIFYPEER, 1 );
	curl_setopt( $ch, CURLOPT_SSL_VERIFYHOST, 2 );
	curl_setopt( $ch, CURLOPT_FORBID_REUSE, 1 );
	if ( PAYPAL_IPN_DEBUG == true ) {
		curl_setopt( $ch, CURLOPT_HEADER, 1 );
		curl_setopt( $ch, CURLINFO_HEADER_OUT, 1 );
	}
	// CONFIG: Optional proxy configuration
	//curl_setopt($ch, CURLOPT_PROXY, $proxy);
	//curl_setopt($ch, CURLOPT_HTTPPROXYTUNNEL, 1);
	// Set TCP timeout to 30 seconds
	curl_setopt( $ch, CURLOPT_CONNECTTIMEOUT, 30 );
	curl_setopt( $ch, CURLOPT_HTTPHEADER, array( 'Connection: Close' ) );
	_l( 'curl url :: ' . $paypal_url );
	// CONFIG: Please download 'cacert.pem' from "http://curl.haxx.se/docs/caextract.html" and set the directory path
	// of the certificate as shown below. Ensure the file is readable by the webserver.
	// This is mandatory for some environments.
	//$cert = __DIR__ . "./cacert.pem";
	//curl_setopt($ch, CURLOPT_CAINFO, $cert);
	$res = curl_exec( $ch );
	_l( 'curl error :: ' . curl_errno( $ch ) );
	if ( curl_errno( $ch ) != 0 ) { // cURL error
		if ( PAYPAL_IPN_DEBUG == true ) {
			_l( date( '[Y-m-d H:i e] ' ) . "Can't connect to PayPal to validate IPN message: " . curl_error( $ch ) . PHP_EOL );
		}
		curl_close( $ch );
		exit;
	} else {
		// Log the entire HTTP response if debug is switched on.
		if ( PAYPAL_IPN_DEBUG == true ) {
			_l( date( '[Y-m-d H:i e] ' ) . "HTTP request of validation request:" . curl_getinfo( $ch, CURLINFO_HEADER_OUT ) . " for IPN payload: $req" . PHP_EOL );
			// Split response headers and payload
			list( $headers, $res ) = explode( "\r\n\r\n", $res, 2 );
		}
		curl_close( $ch );
	}
	// when in the sandbox I think we must need to escape the header data differently - so here is a single line fix just to find the response.
	// get the last 8 letters of res
	$res = substr( $res, - 8 );
	// Inspect IPN validation result and act accordingly
	_l( 'res :: ' . $res );
	if ( strcmp( $res, "VERIFIED" ) == 0 ) {
		// check whether the payment_status is Completed
		// check that txn_id has not been previously processed
		// check that receiver_email is your PayPal email
		// check that payment_amount/payment_currency are correct
		// process payment and mark item as paid.
		// assign posted variables to local variables
		// see log dump for all returned vars
		$order_id       = $_POST['invoice'];
		$payment_status = $_POST['payment_status'];
		$payment_amount = $_POST['mc_gross'];
		_l( "payment successful: Order No:" . $order_id . " payment status:" . $payment_status . " payment amount:" . $payment_amount );
		if ( $payment_status == 'Completed' ) {
			// look up order and just check they have paid the right amount
			$order_req_payment = db_get_single_value( "SELECT total_price FROM shop_order WHERE id =" . $order_id );
			if ( $order_req_payment == $payment_amount ) {
				// All good !!! update order etc !!!
				_l( "* Payment successful: Order No:" . $order_id . " payment status:" . $payment_status . " payment amount:" . $payment_amount );
				$update_sql    = "update shop_order set status = 1,  security_key='" . $values['txn_id'] . "' where id = '$order_id'";
				$update_result = mysql_query( $update_sql );
				OrderConfirmationEmail::SendConfirmationEmail( $order_id );
				if ( SHOP_USE_STOCK_CONTROL ) {
					// massive cavat is that this only work on a shop where you don't have colour, sizes or other attributes
					$update_stock_sql    = "UPDATE shop_product p JOIN shop_order_item oi ON p.id = oi.product_id SET stock_level = stock_level - quantity WHERE order_id = $order_id";
					$update_stock_result = mysql_query( $update_stock_sql );
				}
			} else {
				_l( "ORDER mismatch ::  Payment NOT successful: Order No:" . $order_id . " payment status:" . $payment_status . " payment amount:" . $payment_amount );
			}
		} else {
			_l( "Update: Payment status:" . $payment_status . " for  Order No:" . $order_id . " payment status:" . $payment_status . " payment amount:" . $payment_amount );
		}
		if ( PAYPAL_IPN_DEBUG == true ) {
			_l( date( '[Y-m-d H:i e] ' ) . "Verified IPN: $req " . PHP_EOL );
		}
	} else if ( strcmp( $res, "INVALID" ) == 0 ) {
		// log for manual investigation
		// Add business logic here which deals with invalid IPN messages
		if ( PAYPAL_IPN_DEBUG == true ) {
			_l( date( '[Y-m-d H:i e] ' ) . "Invalid IPN: $req" . PHP_EOL );
		}
	} else {
		$order_id       = $_POST['invoice'];
		$payment_status = $_POST['payment_status'];
		$payment_amount = $_POST['mc_gross'];
		_l( " not VERIFIED !  :: res:" . strcmp( $res, "VERIFIED" ) . " -  Payment NOT successful: Order No:" . $order_id . " payment status:" . $payment_status . " payment amount:" . $payment_amount );
	}

	function _l( $m ) {
		$sql = "INSERT INTO `log` (`log`) VALUES ( '{$m}'  );";
		mysql_query( $sql );
	}

?>