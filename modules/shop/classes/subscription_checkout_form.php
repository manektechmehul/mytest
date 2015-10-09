<?php

	include_once './php/classes/form_template.php';

// include_once $base_path . '/modules/shop/classes/protx.php';
	class subscription_checkout_form extends form_template {

		var $not_yet_registered;
		var $basket;
		var $confirm_fields;

		function subscription_checkout_form() {
			global $session_member_id;
			$delivery_details_message = "<p><strong>Error: Missing Delivery Details</strong></p>" .
			                            "<p>Your enquiry has not yet been submitted because you have not entered valid Delivery Details.</p>" .
			                            "<p>Please tick \"deliver to billing address\" or fill in both the delivery address and delivery postcode fields.</p>" .
			                            "<p>Please click on the <b>Back</b> button to re-enter the Delivery Details.</p>";
			$this->form_template();
			$this->fields                     = array();
			$this->fields['name']             = array( 'name' => 'First Name', 'formtype' => 'text', 'required' => true );
			$this->fields['surname']          = array( 'name' => 'Surname', 'formtype' => 'text', 'required' => true );
			$this->fields['emailaddress']     = array( 'name' => 'E mail', 'formtype' => 'text', 'required' => true, 'validation' => 'validate_email' );
			$this->fields['billing_address1'] = array( 'name' => 'Billing Address Line 1', 'formtype' => 'text', 'required' => true );
			$this->fields['billing_address2'] = array( 'name' => 'Billing Address Line 2', 'formtype' => 'text', 'required' => false );
			$this->fields['billing_address3'] = array( 'name' => 'Billing Town/City', 'formtype' => 'text', 'required' => true );
			$this->fields['billing_postcode'] = array( 'name' => 'Billing Postcode', 'formtype' => 'text', 'required' => true );
			// new validation //
			$this->fields['VAT_Territory']       = array( 'name' => 'Billing Country', 'formtype' => 'lookup', 'function' => 'getVAT_territory', 'required' => false );
			$this->fields['billing_state']       = array( 'name' => 'US Billing State', 'formtype' => 'lookup', 'function' => 'getbillingstate', 'validation' => 'validate_billing_country', 'required' => false );
			$this->fields['VAT_Territory_value'] = array( 'name' => 'VAT_Territory_value', 'formtype' => 'hidden', 'required' => false );
			$this->fields['telephone']           = array( 'name' => 'Telephone', 'formtype' => 'text', 'required' => false );
			$this->fields['agree_terms']         = array( 'name' => 'By checking this box you agree to our <a href="/terms" target="_blank"> payment terms </a>', 'formtype' => 'checkbox', 'not_field' => true,
				'required' => true, 'invalid_message' => 'You must agree to our payment terms before proceeding to the checkout.' );
			// add postage
			if ( $session_member_id ) {
				$this->AddDefaultsFromMemberDetails( $session_member_id );
			}
			// $this->javascript_file = '/modules/shop/js/checkout.js';
			//  $this->before_form_message = BEFORE_CHECKOUT_MESSAGE;
			//   $this->fields['mobile'] = array('name' => 'Mobile', 'formtype' => 'text', 'required' => false);
			$this->submit_button = '<input type="submit" value="Submit" name="Submit" onclick="updateValues();" >';
			//  $this->submit_button = '<input type="hidden" name="Submit" value="Submit" /><input id="submitbtn"  style="padding-left:  0px; "onclick="updateValues();" type="image"  name="book-tickets" src="/images/shop/continue.gif" value="Continue >> "/>';
		}

		function AddDefaultsFromMemberDetails( $memberId ) {
			if ( is_numeric( $memberId ) ) {
				$data                                             = db_get_single_row( "select * from shop_member_user where id = $memberId" );
				$this->fields['name']['defaultValue']             = $data['firstname'];
				$this->fields['surname']['defaultValue']          = $data['surname'];
				$this->fields['emailaddress']['defaultValue']     = $data['email'];
				$this->fields['billing_address1']['defaultValue'] = $data['billing_address1'];
				$this->fields['billing_address2']['defaultValue'] = $data['billing_address2'];
				$this->fields['billing_address3']['defaultValue'] = $data['billing_address3'];
				$this->fields['billing_postcode']['defaultValue'] = $data['billing_postalcode'];
				$this->fields['VAT_Territory']['defaultValue']    = $data['billing_country_id'];
				//  $this->fields['deliver_to_billing_address']['defaultValue'] = $data['deliver_to_billing_address'];
				//  $this->fields['delivery_address1']['defaultValue'] = $data['delivery_address1'];
				// $this->fields['delivery_address2']['defaultValue'] = $data['delivery_address2'];
				//  $this->fields['delivery_address3']['defaultValue'] = $data['delivery_address3'];
				//   $this->fields['delivery_postcode']['defaultValue'] = $data['delivery_postalcode'];
				//  $this->fields['dt']['defaultValue'] = $data['delivery_country_id'];
				//   $this->fields['dtv']['defaultValue'] = $data['delivery_country_id'];
				$this->fields['VAT_Territory_value']['defaultValue'] = $data['billing_country_id'];
				$this->fields['telephone']['defaultValue']           = $data['phone'];
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

		// build_confirm_fields
		private function build_f( $name, $value ) {
			$this->confirm_fields[$name] = array( 'name' => $name, 'value' => $value );
		}

		function process_data( $base_page_name = 'shop' ) {
			$billing_country_id = $this->data['VAT_Territory_value'];
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
			}
			$del = 0; // $_SESSION['deliverycost'];
			// get details of the subscription from the form post from the lottery page
			$subscription = $_SESSION['subscription'];
			// look up the price for the product .... NEVER ACCEPT THE PRICE FROM A POST ! avoiding dodging injection attempt
			// for lottery I have it in a const
			$item_price = LOTTERY_TICKET_COST;
			$qty        = $subscription['qty'];
			$weeks      = $subscription['weeks'];
			//  var_dump($_SESSION['subscription']);
			//$total = $basket->total + $del; // pay vat on delivery
			$total               = $item_price * $qty * $weeks;
			$vat                 = $total * ( $_SESSION['vatrate'] / 100 );
			$total               = sprintf( "%01.2f", $total );
			$insert_order_sql    = sprintf( "insert into shop_order (customer_id, time_made, tender_type, status, total_price, tax, delivery, trade_id) " .
			                                "values (%s, now(), %s, %s, %s, %s, '%s', %s)", $customerid, 0, 0, $total, $vat, $del, $tradeId );
			$insert_order_result = mysql_query( $insert_order_sql );
			$orderid             = mysql_insert_id();
			// this is normally dealth with in the basket - but we are bypassing the basket here
			$_SESSION['billing_country'] = db_get_single_value( "select name from shop_country where id = " . $_POST['VAT_territory'] );
			// need to create an orderline here to reference the first payment
			$product_type     = 3; // lotto
			$product_id       = 2; // a subscription ticket
			$product_sub_type = 1; // for recurring
			$description      = "Recurring Lottery Tickets: " . $weeks . " Weeks";
			$price            = $total / $qty;
			$comm_tax         = $total * ( $_SESSION['vatrate'] / 100 );
			// as we can only have one item in this pseudo basket - we can resue the total and vat values from above
			$insert_order_item_sql = 'insert into shop_order_item (order_id, product_id, product_type, product_sub_type, description, quantity,   comm_tax, price  ) ' .
			                         "values ('$orderid', '$product_id','$product_type', '$product_sub_type','$description', '$qty',   '$comm_tax',  '$price')";
			$insert_order_item_result = mysql_query( $insert_order_item_sql );
			$weeks                    = $_SESSION['weeks'];
			$subscription             = 1;
			$d_sql                    = " insert into shop_order_item_lottery (shop_order_item_id, weeks,tickets, subscription )
 values (LAST_INSERT_ID(), '$weeks', '$qty' , '$subscription' ) ";
			$result                   = mysql_query( $d_sql );
			// $basket->add_to_order($orderid);
			$this->customer_details[] = array( 'name' => 'First Name', 'value' => $this->data['name'] );
			$this->customer_details[] = array( 'name' => 'Surname', 'value' => $this->data['surname'] );
			$this->customer_details[] = array( 'name' => 'E mail', 'value' => $this->data['emailaddress'] );
			$this->customer_details[] = array( 'name' => 'Billing Address Line 1', 'value' => $this->data['billing_address1'] );
			$this->customer_details[] = array( 'name' => 'Billing Address Line 2', 'value' => $this->data['billing_address2'] );
			$this->customer_details[] = array( 'name' => 'Billing Town/City', 'value' => $this->data['billing_address3'] );
			$this->customer_details[] = array( 'name' => 'Billing Postcode', 'value' => $this->data['billing_postcode'] );
			$this->customer_details[] = array( 'name' => 'Billing Country', 'value' => $_SESSION['billing_country'] );
			$this->customer_details[] = array( 'name' => 'Billing State', 'value' => $this->data['billing_state'] );
			$this->customer_details[] = array( 'name' => 'Telephone', 'value' => $this->data['telephone'] );
			if ( PAYMENT_PROVIDER == 'WORLDPAY' ) {
				$this->_build_worldpay_subscription_details( $total, $orderid, $subscription );
			}
		}

		public function _build_worldpay_subscription_details( $total, $orderid, $subscription ) {
			// http://www.worldpay.com/support/kb/bg/testandgolive/tgl.html
			$shop_status = WORLDPAY_SHOP_STATUS;
			//echo ' testmode is ' . $testmode;
			if ( $shop_status != 'LIVE' ) {
				$strPurchaseURL = "https://secure-test.wp3.rbsworldpay.com/wcc/purchase";
				// need to add the form element called testMode and value = 100
			} else {
				$strPurchaseURL = "https://secure.wp3.rbsworldpay.com/wcc/purchase";
			}
			echo $strPurchaseURL;
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
			$amount                                  = $total;
			$currency                                = 'GBP';
			$fieldsCode                              = "$secret;$signatureFields;$instId;$amount;$currency;$cartId";
			$signature                               = md5( $fieldsCode );
			$this->confirm_fields['instId']          = array( 'name' => 'instId', 'value' => $instId );
			$this->confirm_fields['cartId']          = array( 'name' => 'cartId', 'value' => $cartId );
			$this->confirm_fields['amount']          = array( 'name' => 'amount', 'value' => $amount );
			$this->confirm_fields['currency']        = array( 'name' => 'currency', 'value' => $currency );
			$this->confirm_fields['signatureFields'] = array( 'name' => 'signatureFields', 'value' => $signatureFields );
			$this->confirm_fields['signature']       = array( 'name' => 'signature', 'value' => $signature );
			$this->confirm_fields['name']            = array( 'name' => 'name', 'value' => $this->customer_details[0]['value'] . ' ' . $this->customer_details[1]['value'] );
			$this->confirm_fields['address']         = array( 'name' => 'address', 'value' => $this->customer_details[3]['value'] . ' ' . $this->customer_details[4]['value'] . ' ' . $this->customer_details[5]['value'] );
			$this->confirm_fields['country']         = array( 'name' => 'country', 'value' => $this->customer_details[7]['value'] );
			$this->confirm_fields['email']           = array( 'name' => 'E mail', 'value' => $this->customer_details[2]['value'] );
			$this->confirm_fields['phone']           = array( 'name' => 'phone', 'value' => $this->customer_details[9]['value'] );
			$this->confirm_fields['postcode']        = array( 'name' => 'postcode', 'value' => $this->customer_details[6]['value'] );
			$this->confirm_fields['normalAmount']    = array( 'name' => 'normalAmount', 'value' => $amount );
			$this->confirm_fields['MC_action']       = array( 'name' => 'MC_action', 'value' => "1" );
			$this->confirm_fields['futurePayType']   = array( 'name' => 'futurePayType', 'value' => "regular" );
			$this->confirm_fields['option']          = array( 'name' => 'option', 'value' => "1" );
			$this->confirm_fields['noOfPayments']    = array( 'name' => 'noOfPayments', 'value' => "0" );
			/* set these values on depending when the recurring payments will happen */
			// number of weeks between payments
			$this->confirm_fields['startDelayMult'] = array( 'name' => 'startDelayMult', 'value' => $_SESSION['subscription']['weeks'] );
			// startDelayUnit 2 - indicates weeks
			$this->confirm_fields['startDelayUnit'] = array( 'name' => 'startDelayUnit', 'value' => "2" );
			// number of weeks between payments
			$this->confirm_fields['intervalMult'] = array( 'name' => 'intervalMult', 'value' => $_SESSION['subscription']['weeks'] );
			// intervalUnit 2 - indicates weeks
			$this->confirm_fields['intervalUnit'] = array( 'name' => 'intervalUnit', 'value' => "2" );
			//	var_dump($_POST);
			//	var_dump($this->customer_details);
			//	var_dump($subscription);
			//	var_dump($this->confirm_fields);
			//  die();
		}

		function getdeliverystate( $id ) {
			$sql    = 'SELECT * FROM shop_us_states order by name';
			$result = mysql_query( $sql );
			$out    = '<select id="delivery_state" name="delivery_state"  >';
			$out .= '<option  value="-1" selected > US States </option>';
			while ( $row = mysql_fetch_array( $result ) ) {
				//$selected = ('AL' == $row['code']) ? ' selected="selected"' : '';
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
				//$selected = ('AL' == $row['code']) ? ' selected="selected"' : '';
				$out .= '<option ' . $selected . ' value="' . $row['code'] . '"  >' . $row['name'] . '</option>';
			}
			$out .= '</select>';
			return $out;
		}

		function getVAT_territory( $id ) {
			// need to make  set_location_value_name()
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
			//$out .= '<input type=text name="delivery_territory_val" id="delivery_territory_val" >';
			return $out;
		}

		function has_errors() {
			return form_template::has_errors();
		}
	}
