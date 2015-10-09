<?php
	include_once './php/classes/form_template.php';

	class checkout_form extends form_template {

		var $not_yet_registered;
		var $basket;
		var $confirm_fields;

		function checkout_form() {
			global $session_member_id;
			$delivery_details_message = "<p><strong>Error: Missing Delivery Details</strong></p>" .
			                            "<p>Your enquiry has not yet been submitted because you have not entered valid Delivery Details.</p>" .
			                            "<p>Please tick \"deliver to billing address\" or fill in both the delivery address and delivery postcode fields.</p>" .
			                            "<p>Please click on the <b>Back</b> button to re-enter the Delivery Details.</p>";
			$this->form_template();
			$this->fields                               = array();
			$this->fields['name']                       = array( 'name' => 'First Name', 'formtype' => 'text', 'required' => true );
			$this->fields['surname']                    = array( 'name' => 'Surname', 'formtype' => 'text', 'required' => true );
			$this->fields['emailaddress']               = array( 'name' => 'E mail', 'formtype' => 'text', 'required' => true, 'validation' => 'validate_email' );
			$this->fields['billing_address1']           = array( 'name' => 'Billing Address Line 1', 'formtype' => 'text', 'required' => true );
			$this->fields['billing_address2']           = array( 'name' => 'Billing Address Line 2', 'formtype' => 'text', 'required' => false );
			$this->fields['billing_address3']           = array( 'name' => 'Billing Town/City', 'formtype' => 'text', 'required' => true );
			$this->fields['billing_postcode']           = array( 'name' => 'Billing Postcode', 'formtype' => 'text', 'required' => true );
			$this->fields['VAT_Territory']              = array( 'name' => 'Billing Country', 'formtype' => 'lookup', 'function' => 'getVAT_territory', 'required' => false );
			$this->fields['billing_state']              = array( 'name' => 'US Billing State', 'formtype' => 'lookup', 'function' => 'getbillingstate', 'validation' => 'validate_billing_country', 'required' => false );
			$this->fields['deliver_to_billing_address'] = array( 'name' => 'Deliver to Billing Address', 'formtype' => 'checkbox',
				'required' => false, 'onclick' => 'toggle_delivery(\'this\')', 'validation' => 'validate_delivery_address', 'invalid_message' => $delivery_details_message );
			$this->fields['delivery_address1']          = array( 'name' => 'Delivery Address Line 1', 'formtype' => 'text', 'required' => false );
			$this->fields['delivery_address2']          = array( 'name' => 'Delivery Address Line 2', 'formtype' => 'text', 'required' => false );
			$this->fields['delivery_address3']          = array( 'name' => 'Delivery Town/City', 'formtype' => 'text', 'required' => false );
			$this->fields['delivery_postcode']          = array( 'name' => 'Delivery Postcode', 'formtype' => 'text', 'required' => false );
			$this->fields['dt']                         = array( 'name' => 'Delivery Country', 'formtype' => 'lookup', 'function' => 'getDelivery_territory', 'required' => false );
			$this->fields['dtv']                        = array( 'name' => 'delivery_Territory_value', 'formtype' => 'hidden', 'required' => false );
			$this->fields['VAT_Territory_value']        = array( 'name' => 'VAT_Territory_value', 'formtype' => 'hidden', 'required' => false );
			$this->fields['delivery_state']             = array( 'name' => 'US Delivery State ', 'formtype' => 'lookup', 'function' => 'getdeliverystate', 'validation' => 'validate_delivery_country', 'required' => false );
			$this->fields['telephone']                  = array( 'name' => 'Telephone', 'formtype' => 'text', 'required' => false );
			$this->fields['agree_terms']                = array( 'name' => 'By checking this box you agree to our <a href="/terms" target="_blank"> payment terms </a>', 'formtype' => 'checkbox', 'not_field' => true,
				'required' => true, 'invalid_message' => 'You must agree to our payment terms before proceeding to the checkout.' );
			if ( $session_member_id ) {
				$this->AddDefaultsFromMemberDetails( $session_member_id );
			}
			// $this->javascript_file = '/modules/shop/js/checkout.js';
			$this->before_form_message = BEFORE_CHECKOUT_MESSAGE;
			$this->submit_button       = '<input type="submit" value="Submit" name="Submit" onclick="updateValues();" >';
		}

		function AddDefaultsFromMemberDetails( $memberId ) {
			if ( is_numeric( $memberId ) ) {
				$data                                                       = db_get_single_row( "select * from shop_member_user where id = $memberId" );
				$this->fields['name']['defaultValue']                       = $data['firstname'];
				$this->fields['surname']['defaultValue']                    = $data['surname'];
				$this->fields['emailaddress']['defaultValue']               = $data['email'];
				$this->fields['billing_address1']['defaultValue']           = $data['billing_address1'];
				$this->fields['billing_address2']['defaultValue']           = $data['billing_address2'];
				$this->fields['billing_address3']['defaultValue']           = $data['billing_address3'];
				$this->fields['billing_postcode']['defaultValue']           = $data['billing_postalcode'];
				$this->fields['VAT_Territory']['defaultValue']              = $data['billing_country_id'];
				$this->fields['deliver_to_billing_address']['defaultValue'] = $data['deliver_to_billing_address'];
				$this->fields['delivery_address1']['defaultValue']          = $data['delivery_address1'];
				$this->fields['delivery_address2']['defaultValue']          = $data['delivery_address2'];
				$this->fields['delivery_address3']['defaultValue']          = $data['delivery_address3'];
				$this->fields['delivery_postcode']['defaultValue']          = $data['delivery_postalcode'];
				$this->fields['dt']['defaultValue']                         = $data['delivery_country_id'];
				$this->fields['dtv']['defaultValue']                        = $data['delivery_country_id'];
				$this->fields['VAT_Territory_value']['defaultValue']        = $data['billing_country_id'];
				$this->fields['telephone']['defaultValue']                  = $data['phone'];
			}
		}

		function validate_billing_country( $value ) {
			if ( $_POST['VAT_territory'] == "234" ) {
				if ( $_POST['billing_state'] == "-1" ) {
					$this->invalid[] = array( 'name' => $this->namedFields['billing_state']->name, 'message' => 'US Customers must choose a billing state.' );
				} else {
					return true;
				}
			} else {
				return true;
			}
		}

		function validate_delivery_country( $value ) {
			if ( $_POST['dt'] == "234" ) {
				if ( $_POST['delivery_state'] == "-1" ) {
					$this->invalid[] = array( 'name' => $this->namedFields['delivery_state']->name, 'message' => 'US Customers must choose a delivery state.' );
				} else {
					return true;
				}
			} else {
				return true;
			}
		}

		function validate_delivery_address( $value ) {
			return ( ( $value == 1 ) || ( ( $this->data['delivery_address1'][0] != '' ) && ( $this->data['delivery_postcode'] != '' ) ) );
		}

		public function _build_protx_details( $basket, $orderid ) {

			$site_address          = SITE_ADDRESS;
			$subject               = SITE_NAME . " enquiry from " . $site_address;
			$from_email_address    = SITE_CONTACT_EMAIL;
			$strConnectTo          = PROTX_STATUS;
			$strVirtualDir         = "VSPForm-Kit"; //Change if you have created a Virtual Directory in IIS with a different name
			$strYourSiteFQDN       = SITE_ADDRESS . "/";
			$strVSPVendorName      = PROTX_VENDOR_NAME;
			$strEncryptionPassword = PROTX_ENCRYPTION_PASSWORD;
			$strCurrency           = "GBP";
			$strVendorEMail        = PROTX_VENDOR_EMAIL;
			$strTransactionType    = PROTX_TRANSACTION_TYPE;
			$strProtocol           = "3.00";
			$output                = "";
			if ( $strConnectTo == "LIVE" ) {
				$strPurchaseURL = "https://live.sagepay.com/gateway/service/vspform-register.vsp";
			} elseif ( $strConnectTo == "TEST" ) {
				$strPurchaseURL = "https://test.sagepay.com/gateway/service/vspform-register.vsp";
			} else // Simulator
			{
				$strPurchaseURL = "https://test.sagepay.com/simulator/VSPFormGateway.asp";
			}
			/** Okay, build the crypt field for VSP Form using the information in our session **
			 * ** First we need to generate a unique VendorTxCode for this transaction **
			 * ** We're using VendorName, time stamp and a random element.  You can use different methods if you wish **
			 * ** but the VendorTxCode MUST be unique for each transaction you send to VSP Server * */
			$sngTotal        = 0.0;
			$strThisEntry    = $strCart;
			$strBasket       = "";
			$total           = $basket->total;
			$del             = $_SESSION['deliverycost'];
			$del             = $del;
			$total           = $total + $del;
			$sngTotal        = $total;
			$sngTax          = $basket->tax_total;
			$strBasket       = $basket->get_basket_str();
			$strVendorTxCode = $strVSPVendorName . $orderid;
			// Now to build the VSP Form crypt field.  For more details see the VSP Form Protocol 2.22
			$strPost = "VendorTxCode=" . $strVendorTxCode;
			/** As generated above * */
			$strPost = $strPost . "&Amount=" . number_format( $sngTotal, 2 ); // Formatted to 2 decimal places with leading digit
			$strPost = $strPost . "&Currency=" . $strCurrency;
			// Up to 100 chars of free format description
			$strPost        = $strPost . "&Description=" . PROTX_SHOP_DESCRIPTION;
			$base_page_name = 'shop';
			$strPost        = $strPost . "&SuccessURL=" . $strYourSiteFQDN . "$base_page_name/success";
			$strPost        = $strPost . "&FailureURL=" . $strYourSiteFQDN . "$base_page_name/failed";
			/* Optional setting.
			 * * 0 = Do not send either customer or vendor e-mails,
			 * * 1 = Send customer and vendor e-mails if address(es) are provided(DEFAULT).
			 * * 2 = Send Vendor Email but not Customer Email. If you do not supply this field, 1 is assumed and e-mails are sent if addresses are provided. * */
			$bSendEMail = 1;
			$strPost    = $strPost . "&SendEMail=" . $bSendEMail;
			$strPost    = $strPost . "&CustomerName=" . $this->data['name'] . ' ' . $this->data['surname'];
			$strPost    = $strPost . "&CustomerEMail=" . $this->data['emailaddress'];
			//  if ($strVendorEMail>"[your e-mail address]")
			$strPost = $strPost . "&VendorEMail=" . PROTX_VENDOR_EMAIL;
			/* You can specify any custom message to send to your customers in their confirmation e-mail here
			 * * The field can contain HTML if you wish, and be different for each order.  The field is optional */
			$strPost = $strPost . "&eMailMessage=" . PROTX_EMAIL_MESSAGE;
			$strPost = $strPost . "&BillingFirstnames=" . $this->data['name'];
			$strPost = $strPost . "&BillingSurname=" . $this->data['surname'];
			$b0      = $this->data['billing_address1'];
			$b1      = $this->data['billing_address2'];
			$b2      = $this->data['billing_address3'];
			if ( trim( $b0 ) == "" ) {
				$b0 = ".";
			}
			if ( trim( $b1 ) == "" ) {
				$b1 = ".";
			}
			if ( trim( $b2 ) == "" ) {
				$b2 = ".";
			}
			$strPost = $strPost . "&BillingAddress1=" . $b0;
			$strPost = $strPost . "&BillingAddress2=" . $b1;
			$strPost = $strPost . "&BillingCity=" . $b2;
			$strPost = $strPost . "&BillingPostCode=" . $this->data['billing_postcode'];
			$strPost = $strPost . "&BillingCountry=" . $_SESSION['billing_country_code'];
			if ( $_SESSION['billing_country_code'] == 'US' ) {
				$strPost = $strPost . "&BillingState=" . $this->data['billing_state'];
			}
			if ( $this->data['deliver_to_billing_address'] ) {
				$strPost = $strPost . "&DeliveryFirstnames=" . $this->data['name'];
				$strPost = $strPost . "&DeliverySurname=" . $this->data['surname'];
				$strPost = $strPost . "&DeliveryAddress1=" . $b0; // $this->data['billing_address'][0];
				$strPost = $strPost . "&DeliveryAddress2=" . $b1; //  $this->data['billing_address'][1];
				$strPost = $strPost . "&DeliveryCity=" . $b2; //$this->data['billing_address'][2];
				$strPost = $strPost . "&DeliveryPostCode=" . $this->data['billing_postcode'];
				$strPost = $strPost . "&DeliveryCountry=" . $_SESSION['billing_country_code'];
				if ( $_SESSION['billing_country_code'] == 'US' ) {
					$strPost = $strPost . "&DeliveryState=" . $this->data['billing_state'];
				}
			} else {
				$strPost = $strPost . "&DeliveryFirstnames=" . $this->data['name'];
				$strPost = $strPost . "&DeliverySurname=" . $this->data['surname'];
				$strPost = $strPost . "&DeliveryAddress1=" . $this->data['delivery_address1'];
				$strPost = $strPost . "&DeliveryAddress2=" . $this->data['delivery_address2'];
				$strPost = $strPost . "&DeliveryCity=" . $this->data['delivery_address3'];
				$strPost = $strPost . "&DeliveryPostCode=" . $this->data['delivery_postcode'];
				$strPost = $strPost . "&DeliveryCountry=" . $_SESSION['delivery_country_code'];
				if ( $_SESSION['delivery_country_code'] == 'US' ) {
					$strPost = $strPost . "&DeliveryState=" . $this->data['delivery_state'];
				}
			}
			// Optionally add the contact numbers, if they are present
			$strPost = $strPost . "&ContactNumber=" . $this->data['telephone'];
			//   $strPost=$strPost . "&ContactFax=" . $strContactFax;
			$strPost = $strPost . "&Basket=" . $strBasket; // As created above
			// For charities registered for Gift Aid, set to 1 to display the Gift Aid check box on the payment pages
			$strPost = $strPost . "&AllowGiftAid=0";
			/* Allow fine control over AVS/CV2 checks and rules by changing this value. 0 is Default
			 * * It can be changed dynamically, per transaction, if you wish.  See the VSP Server Protocol document */
			if ( $strTransactionType !== "AUTHENTICATE" ) {
				$strPost = $strPost . "&ApplyAVSCV2=0";
			}
			/* Allow fine control over 3D-Secure checks and rules by changing this value. 0 is Default
			 * * It can be changed dynamically, per transaction, if you wish.  See the VSP Server Protocol document */
			$strPost = $strPost . "&Apply3DSecure=0";
			//  echo strlen($strPost);
			//  echo $strPost;
			// die();
			// Encrypt the plaintext string for inclusion in the hidden field
			// This assumes version 3.00
			$strCrypt = $this->encryptAndEncode( $strPost, $strEncryptionPassword );
			$this->purchase_URL                       = $strPurchaseURL;
			$this->form                               = array(
				'method' => 'POST',
				'name' => 'VSPForm',
				'id' => 'VSPForm'
			);
			$this->confirm_fields                     = array();
			$this->confirm_fields['vps_protocol']     = array( 'name' => 'VPSProtocol', 'value' => $strProtocol );
			$this->confirm_fields['transaction_type'] = array( 'name' => 'TxType', 'value' => $strTransactionType );
			$this->confirm_fields['vendor_name']      = array( 'name' => 'Vendor', 'value' => $strVSPVendorName );
			$this->confirm_fields['crypt']            = array( 'name' => 'Crypt', 'value' => $strCrypt );
		}


		// ******************************************************
		// New Sage Pay functions //
		// ******************************************************
		// see main.php for the decode versions
		protected function encryptAndEncode( $strIn, $encryptPassword ) {
			$strIn = $this->pkcs5_pad( $strIn, 16 );
			return "@" . bin2hex( mcrypt_encrypt( MCRYPT_RIJNDAEL_128, $encryptPassword, $strIn, MCRYPT_MODE_CBC, $encryptPassword ) );
		}

		protected function pkcs5_pad( $text, $blocksize ) {
			$pad = $blocksize - ( strlen( $text ) % $blocksize );
			return $text . str_repeat( chr( $pad ), $pad );
		}
		// ******************************************************
		// build_confirm_fields
		private function build_f( $name, $value ) {
			$this->confirm_fields[$name] = array( 'name' => $name, 'value' => $value );
		}

		private function _build_paypal_details( $basket, $order_id ) {
			$b = new basket( true );
			global $base_path;
			// need to look up shop url
			$returnURL = SITE_ADDRESS . '/shop/success';
			$cancelURL = SITE_ADDRESS . '/shop/cancel';
			$notifyURL = SITE_ADDRESS . '/shop/notify';
			if ( PAYPAL_ENV == 'LIVE' ) {
				$postURL = "https://www.paypal.com";
			} else {
				$postURL = "https://www.sandbox.paypal.com";
			}
			$this->purchase_URL = "{$postURL}/cgi-bin/webscr";
			$this->form         = array(
				'method' => 'POST',
				'name' => 'paypalform',
				'id' => 'paypalform'
			);
			// this is the list of hidden items passed to paypal for payment
			$this->confirm_fields = array();
			$this->build_f( 'invoice', $order_id );
			$this->build_f( 'paymentaction', 'sale' );
			$this->build_f( 'cmd', '_cart' );
			$this->build_f( 'upload', '1' );
			$this->build_f( 'business', PAYPAL_EMAIL_ADDRESS ); //
			$this->build_f( 'charset', 'UTF-8' );
			$this->build_f( 'currency_code', 'GBP' );
			$this->build_f( 'return', htmlspecialchars( $returnURL ) );
			$this->build_f( 'rm', '2' ); // return method - 2. POST back from pay pal to our success page
			$this->build_f( 'cancel_return', htmlspecialchars( $cancelURL ) );
			$this->build_f( 'notify_url', htmlspecialchars( $notifyURL ) );
			$this->build_f( 'cbt', 'Return to ' . SITE_NAME );
			$this->build_f( 'custom', 'Paypal for ' . SITE_NAME );
			$this->build_f( 'first_name', $this->data['name'] );
			$this->build_f( 'last_name', $this->data['surname'] );
			$this->build_f( 'address1', $this->data['billing_address1'] );
			$this->build_f( 'address2', $this->data['billing_address2'] );
			$this->build_f( 'city', $this->data['billing_address3'] );
			$this->build_f( 'country', 'GB' );
			$this->build_f( 'zip', $this->data['billing_postcode'] );
			$this->build_f( 'night_phone_b', $this->data['telephone'] );
			$this->build_f( 'email', $this->data['emailaddress'] );
			$this->build_f( 'lc', 'GB' );
			// for some reason paypal is never readiing the first item in the cart - so i have put in a dummy one until i can fix the issue
			$this->build_f( "item_name", urlencode( 'dummy item' ) );
			$this->build_f( "amount", '0' );
			$this->build_f( "quantity", '1' );
			//
			$basket_count = 1;
			foreach ( $basket->items as $item ) {
				// $ext = "";
				// if($basket_count > 0){
				$ext = "_" . $basket_count;
				//  }
				$this->build_f( "item_name" . $ext, $b->format_item_name( $item ) );
				$this->build_f( "amount" . $ext, number_format( $item['price'], 2 ) );
				$this->build_f( "quantity" . $ext, $item['qty'] );
				$basket_count ++;
			}
			// if shipping cost
			if ( $basket->deliverycost > 0 ) {
				$this->build_f( "item_name" . "_" . $basket_count, 'shipping' );
				$this->build_f( "amount" . "_" . $basket_count, number_format( $basket->deliverycost, 2 ) );
				$this->build_f( "quantity" . "_" . $basket_count, '1' );
			}
		}

		function process_data( $base_page_name = 'shop' ) {
			$basket       = new basket( true );
			$this->basket = $basket;
			if ( $_GET['fastforward'] == 'true' ) {
				$this->data = $_SESSION['user_checkout_fields'];
			} else {
				$_SESSION['user_checkout_fields'] = $this->data;
			}
			$billing_country_id  = $this->data['VAT_Territory_value'];
			$delivery_country_id = $this->data['dtv'];
			$this->basket->setVAT_territory( $billing_country_id );
			$this->basket->setBilllingCountryCode( $billing_country_id );
			// set delivery session here
			$this->basket->setDelivery_territory( $delivery_country_id ); // postage costs
			$this->basket->setDeliveryCountryCode( $delivery_country_id );
			if ( $this->data['deliver_to_billing_address'] ) {
				$delivery_address1 = $this->data['billing_address1'];
				$delivery_address2 = $this->data['billing_address2'];
				$delivery_address3 = $this->data['billing_address3'];
				$delivery_postcode = $this->data['billing_postcode'];
			} else {
				$delivery_address1 = $this->data['delivery_address1'];
				$delivery_address2 = $this->data['billing_address2'];
				$delivery_address3 = $this->data['billing_address3'];
				$delivery_postcode = $this->data['delivery_postcode'];
			}
			$insert_customer_sql    = sprintf( "insert into shop_customer " .
			                                   "(firstname, surname, billing_address1, billing_address2, billing_address3, billing_postalcode, " .
			                                   "phone, email, delivery_address1, delivery_address2, delivery_address3, delivery_postalcode,status, date_joined, " .
			                                   "billing_country, billing_country_id, delivery_country, delivery_country_id" .
			                                   ") values ('%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', now()" .
			                                   ",'%s', '%s', '%s', '%s'" .
			                                   ")", $this->data['name'], $this->data['surname'], $this->data['billing_address1'], $this->data['billing_address2'], $this->data['billing_address3'], $this->data['billing_postcode'], $this->data['telephone'], $this->data['emailaddress'], $delivery_address1, $delivery_address2, $delivery_address3, $delivery_postcode, 1, $_SESSION['billing_country'], $billing_country_id, $_SESSION['delivery_country'], $delivery_country_id
			);
			$insert_customer_result = mysql_query( $insert_customer_sql );
			$customerid             = mysql_insert_id();
			$tradeId                = $_SESSION["session_member_id"];
			if ( $tradeId == "" ) {
				$tradeId = 0;
				//echo 'here';
			}
			$del                 = $_SESSION['deliverycost'];
			$total               = $basket->total + $del; // pay vat on delivery
			$vat                 = $total * ( $_SESSION['vatrate'] / 100 );
			$total               = sprintf( "%01.2f", $total );
			$collection = 0;
			if(isset($_SESSION['collection'])){
				$collection = 1;
			}
			$insert_order_sql    = sprintf( "insert into shop_order (customer_id, time_made, tender_type, status, total_price, tax, delivery, trade_id, collection) " .
			                                "values (%s, now(), %s, %s, %s, %s, '%s', %s, %s)", $customerid, 0, 0, $total, $vat, $del, $tradeId, $collection );



			$insert_order_result = mysql_query( $insert_order_sql );
			$orderid             = mysql_insert_id();
			$basket->add_to_order( $orderid );
			$this->customer_details[] = array( 'name' => 'First Name', 'value' => $this->data['name'] );
			$this->customer_details[] = array( 'name' => 'Surname', 'value' => $this->data['surname'] );
			$this->customer_details[] = array( 'name' => 'E mail', 'value' => $this->data['emailaddress'] );
			$this->customer_details[] = array( 'name' => 'Billing Address Line 1', 'value' => $this->data['billing_address1'] );
			$this->customer_details[] = array( 'name' => 'Billing Address Line 2', 'value' => $this->data['billing_address2'] );
			$this->customer_details[] = array( 'name' => 'Billing Town/City', 'value' => $this->data['billing_address3'] );
			$this->customer_details[] = array( 'name' => 'Billing Postcode', 'value' => $this->data['billing_postcode'] );
			$this->customer_details[] = array( 'name' => 'Billing Country', 'value' => $_SESSION['billing_country'] );
			$this->customer_details[] = array( 'name' => 'Billing State', 'value' => $this->data['billing_state'] );
			if ( $this->data['deliver_to_billing_address'] ) {
				$this->customer_details[] = array( 'name' => 'Delivery Address Line 1', 'value' => $this->data['billing_address1'] );
				$this->customer_details[] = array( 'name' => 'Delivery Address Line 2', 'value' => $this->data['billing_address2'] );
				$this->customer_details[] = array( 'name' => 'Delivery Town/City', 'value' => $this->data['billing_address3'] );
				$this->customer_details[] = array( 'name' => 'Delivery Postcode', 'value' => $this->data['billing_postcode'] );
			} else {
				$this->customer_details[] = array( 'name' => 'Delivery Address Line 1', 'value' => $this->data['delivery_address1'] );
				$this->customer_details[] = array( 'name' => 'Delivery Address Line 2', 'value' => $this->data['delivery_address2'] );
				$this->customer_details[] = array( 'name' => 'Town/City', 'value' => $this->data['delivery_address3'] );
				$this->customer_details[] = array( 'name' => 'Delivery Postcode', 'value' => $this->data['delivery_postcode'] );
			}
			$this->customer_details[] = array( 'name' => 'Delivery Country', 'value' => $_SESSION['delivery_country'] );
			$this->customer_details[] = array( 'name' => 'Telephone', 'value' => $this->data['telephone'] );
			if ( $this->data['delivery_state'] != '' ) {
				$this->customer_details[] = array( 'name' => 'Delivery State', 'value' => $this->data['delivery_state'] );
			}
			if ( PAYMENT_PROVIDER == 'PROTX' ) {
				$this->_build_protx_details( $basket, $orderid );
			}
			if ( PAYMENT_PROVIDER == 'PAYPAL' ) {
				$this->_build_paypal_details( $basket, $orderid );
			}
			if ( PAYMENT_PROVIDER == 'WORLDPAY' ) {
				$this->_build_worldpay_details( $basket, $orderid );
			}
		}

		/***
		 *
		 * build the worldpay basket
		 *
		 * See http://www.worldpay.com/support/kb/bg/testandgolive/tgl.html for help
		 *
		 * @param $basket
		 * @param $orderid
		 *
		 */
		public function _build_worldpay_details( $basket, $orderid ) {
			$shop_status = WORLDPAY_SHOP_STATUS;
			if ( $shop_status != 'LIVE' ) {
				$strPurchaseURL = "https://secure-test.wp3.rbsworldpay.com/wcc/purchase";
				// need to add the form element called testMode and value = 100
			} else {
				$strPurchaseURL = "https://secure.wp3.rbsworldpay.com/wcc/purchase";
			}
			$this->purchase_URL   = $strPurchaseURL;
			$this->form           = array(
				'method' => 'POST',
				'name' => 'worldpayform',
				'id' => 'worldpayformid'
			);
			$this->confirm_fields = array();
			if ( $shop_status != 'LIVE' ) {
				$this->confirm_fields['testMode'] = array( 'name' => 'testMode', 'value' => '100' );
			}
			$secret                                  = WORLDPAY_PAYMENT_SECRET;
			$signatureFields                         = "instId:amount:currency:cartId";
			$instId                                  = WORLDPAY_PAYMENT_INSTALLATION_ID;
			$cartId                                  = 'shop_' . $orderid;
			$amount                                  = $basket->total;
			$currency                                = 'GBP';
			$fieldsCode                              = "$secret;$signatureFields;$instId;$amount;$currency;$cartId";
			$signature                               = md5( $fieldsCode );


			$this->confirm_fields['name'] = array('name'=> 'name' , 'value' => $this->data['name'] . ' ' . $this->data['surname']);
			$this->confirm_fields['address1'] = array('name'=> 'address1' , 'value' => $this->data['billing_address1']);
			$this->confirm_fields['address2'] = array('name'=> 'address2' , 'value' => $this->data['billing_address2']);
			$this->confirm_fields['town'] = array('name'=> 'town' , 'value' => $this->data['billing_address3']);
			//$this->confirm_fields['town'] = array('name'=> 'town' , 'value' => $this->data['billing_state']);
			$this->confirm_fields['country'] = array('name'=> 'country' , 'value' => 'GB');
			$this->confirm_fields['postcode'] = array('name'=> 'postcode' , 'value' => $this->data['billing_postcode']);
			$this->confirm_fields['tel'] = array('name'=> 'tel' , 'value' => $this->data['telephone']);
			$this->confirm_fields['email'] = array('name'=> 'email' , 'value' => $this->data['emailaddress']);

			$this->confirm_fields['instId']          = array( 'name' => 'instId', 'value' => $instId );
			$this->confirm_fields['cartId']          = array( 'name' => 'cartId', 'value' => $cartId );
			$this->confirm_fields['amount']          = array( 'name' => 'amount', 'value' => $amount );
			$this->confirm_fields['currency']        = array( 'name' => 'currency', 'value' => $currency );
			$this->confirm_fields['signatureFields'] = array( 'name' => 'signatureFields', 'value' => $signatureFields );
			$this->confirm_fields['signature']       = array( 'name' => 'signature', 'value' => $signature );
		}

		function getdeliverystate( $id ) {
			$sql    = 'SELECT * FROM shop_us_states order by name';
			$result = mysql_query( $sql );
			$out    = '<select id="delivery_state" name="delivery_state"  >';
			$out .= '<option  value="-1" selected > US States </option>';
			while ( $row = mysql_fetch_array( $result ) ) {
				$out .= '<option ' . $selected . ' value="' . $row['code'] . '"  >' . $row['name'] . '</option>';
			}
			$out .= '</select>';
			return $out;
		}

		function getbillingstate( $id ) {
			$sql    = 'SELECT * FROM shop_us_states order by name';
			$result = mysql_query( $sql );
			$out    = '<select id="billing_state" name="billing_state" onchange="updatedbillingstate();" >';
			$out .= '<option  value="-1" selected > US States </option>';
			while ( $row = mysql_fetch_array( $result ) ) {
				$out .= '<option ' . $selected . ' value="' . $row['code'] . '"  >' . $row['name'] . '</option>';
			}
			$out .= '</select>';
			return $out;
		}

		function getVAT_territory( $id ) {
			// need to set_location_value_name()
			$sql    = 'SELECT id, name FROM shop_country';
			$result = mysql_query( $sql );
			$out    = '<select id="VAT_territory" name="VAT_territory" onchange="showhidebillingstate();"  >';
			while ( $row = mysql_fetch_array( $result ) ) {
				$selected = ( $id == $row['id'] ) ? ' selected="selected"' : '';
				$out .= '<option ' . $selected . ' value="' . $row['id'] . '"  >' . $row['name'] . '</option>';
			}
			$out .= '</select>';
			return $out;
		}

		function getDelivery_territory( $id ) {
			// need to make  set_location_value_name()
			$sql    = 'SELECT id, name FROM shop_country';
			$result = mysql_query( $sql );
			$out    = '<select id="dt" name="dt"  onchange="showhidedeliverystate();"  >';
			while ( $row = mysql_fetch_array( $result ) ) {
				$selected = ( $id == $row[id] ) ? ' selected="selected"' : '';
				$out .= '<option ' . $selected . ' value="' . $row[id] . '"  >' . $row[name] . '</option>';
			}
			$out .= '</select>';
			return $out;
		}

		function has_errors() {
			return form_template::has_errors();
		}
	}
