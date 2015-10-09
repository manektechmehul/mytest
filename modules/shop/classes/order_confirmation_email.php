<?php

	class OrderConfirmationEmail {

		static function SendConfirmationEmailTest( $orderId ) {
			global $smarty;
			global $base_path;
			if ( isset( $smarty ) ) {
				// echo ' smarty is set';
			} else {
				require $base_path . '/php/smarty/Smarty.class.php';
				$smarty              = new Smarty;
				$smarty->compile_dir = $base_path . '/templates/templates_c';
				require_once $smarty->_get_plugin_filepath( 'modifier', 'format_date' );
			}
			$orderDetails = self::GetOrderDetails( $orderId );
			// var_dump($orderDetails);
			$smarty->assign( 'orderDetails', $orderDetails );
			$message['to'] = 'glenlockhart@gmail.com';
			// $message['bcc'] = SITE_CONTACT_EMAIL;
			// echo ' site contact email is ' .  SITE_CONTACT_EMAIL;
			// die();
			$message['body']    = $smarty->fetch( $base_path . '/modules/shop/templates/confirmation_email.tpl' );
			$message['subject'] = SITE_NAME . ': Your order number ' . $orderId;
			//
			var_dump( $message );
			die();
			//  self::SendEmail($message);
		}

		static function SendDispatchEmail( $orderId ) {
			global $smarty;
			global $base_path;
			if ( isset( $smarty ) ) {
				// echo ' smarty is set';
			} else {
				require $base_path . '/php/smarty/Smarty.class.php';
				$smarty              = new Smarty;
				$smarty->compile_dir = $base_path . '/templates/templates_c';
				require_once $smarty->_get_plugin_filepath( 'modifier', 'format_date' );
			}
			$orderDetails = self::GetOrderDetails( $orderId );
			// because we use this function from the front of the site and the admin - we need to look up the config data when using the function from admin
			include_once( $base_path . '/php/databaseconnection.php' );
			$shop_show_vat = db_get_single_value( "SELECT value FROM configuration WHERE `name` = 'SHOP_SHOW_VAT'" );
			$shop_vat_rate = db_get_single_value( "SELECT value FROM configuration WHERE `name` = 'SHOP_VAT_RATE'" );
			$smarty->assign( 'shop_show_vat', $shop_show_vat );
			$smarty->assign( 'shop_vat_rate', $shop_vat_rate );
			$smarty->assign( 'orderDetails', $orderDetails );
			$message['to']      = $orderDetails['customer']['email'];
			$message['bcc']     = SITE_CONTACT_EMAIL;
			$message['body']    = $smarty->fetch( $base_path . '/modules/shop/templates/dispatch_email.tpl' );
			$message['subject'] = SITE_NAME . ': Your order number ' . $orderId;
			self::SendEmail( $message );
		}


		static function SendConfirmationEmailRecurringLotteryPurchase($futurePayId, $bcc = SITE_CONTACT_EMAIL){
			// when a recurring payment is generated automatically by WorldPay send an email to the customer and the admin
			global $smarty;
			global $base_path;
			if ( isset( $smarty ) ) {
				// echo ' smarty is set';
			} else {
				require $base_path . '/php/smarty/Smarty.class.php';
				$smarty              = new Smarty;
				$smarty->compile_dir = $base_path . '/templates/templates_c';
				require_once $smarty->_get_plugin_filepath( 'modifier', 'format_date' );
			}

			// get order id fom the futurePayId
			$sql = "SELECT soi.order_id FROM shop_order_item_lottery soil
INNER JOIN shop_order_item soi ON soi.id = soil.`shop_order_item_id`
WHERE soil.`futurePayId` = " . $futurePayId;
			$orderId =  db_get_single_value($sql);






			$orderDetails = self::GetOrderRecord( $orderId );
			$orderDetails['gross']    = $orderDetails['total_price'];
			$orderDetails['customer'] = self::GetCustomerRecord( $orderDetails['customer_id'] );
			$orderDetails['items']    = self::GetOrderItemByFuturePayId( $futurePayId );


			// because we use this function from the front of the site and the admin - we need to look up the config data when using the function from admin
			include_once( $base_path . '/php/databaseconnection.php' );
			$shop_show_vat = db_get_single_value( "SELECT value FROM configuration WHERE `name` = 'SHOP_SHOW_VAT'" );
			$shop_vat_rate = db_get_single_value( "SELECT value FROM configuration WHERE `name` = 'SHOP_VAT_RATE'" );
			$smarty->assign( 'shop_show_vat', $shop_show_vat );
			$smarty->assign( 'shop_vat_rate', $shop_vat_rate );
			$smarty->assign( 'orderDetails', $orderDetails );
			$message['to'] = $orderDetails['customer']['email'];
			$message['body'] = $smarty->fetch( $base_path . '/modules/shop/templates/confirmation_email_recurring_lottery.tpl' );
			// $message['body'] = $smarty->fetch($base_path . '/modules/shop/templates/simple_mail.tpl');
			// echo $message['body'];
			$message['subject'] = SITE_NAME . ': Your order number ' . $orderId;
			self::SendEmail( $message, $bcc );


		}


        static function SendDonationEmail( $orderId ) {
            global $smarty;
            global $base_path;
            if ( isset( $smarty ) ) {
                // echo ' smarty is set';
            } else {
                require $base_path . '/php/smarty/Smarty.class.php';
                $smarty              = new Smarty;
                $smarty->compile_dir = $base_path . '/templates/templates_c';
                require_once $smarty->_get_plugin_filepath( 'modifier', 'format_date' );
            }
            $orderDetails = self::GetOrderDetails( $orderId );
            // because we use this function from the front of the site and the admin - we need to look up the config data when using the function from admin
            include_once( $base_path . '/php/databaseconnection.php' );
            $shop_show_vat = db_get_single_value( "SELECT value FROM configuration WHERE `name` = 'SHOP_SHOW_VAT'" );
            $shop_vat_rate = db_get_single_value( "SELECT value FROM configuration WHERE `name` = 'SHOP_VAT_RATE'" );
            $smarty->assign( 'shop_show_vat', $shop_show_vat );
            $smarty->assign( 'shop_vat_rate', $shop_vat_rate );
            $smarty->assign( 'orderDetails', $orderDetails );
            $message['to'] = $orderDetails['customer']['email'];
            //$message['bcc'] = $bcc;
            //  echo ' site contact email is ' .  SITE_CONTACT_EMAIL;
            $message['body'] = $smarty->fetch( $base_path . '/modules/shop/templates/donation_email.tpl' );
            // $message['body'] = $smarty->fetch($base_path . '/modules/shop/templates/simple_mail.tpl');
            // echo $message['body'];
            $message['subject'] = SITE_NAME . ': Your order number ' . $orderId;
            self::SendEmail( $message);
        }


		/*** copy in the site_contact_email for shop email, unless overridden for section leads with say events, donates, lotto etc **/
		static function SendConfirmationEmail( $orderId, $bcc = SITE_CONTACT_EMAIL ) {
			global $smarty;
			global $base_path;
			if ( isset( $smarty ) ) {
				// echo ' smarty is set';
			} else {
				require $base_path . '/php/smarty/Smarty.class.php';
				$smarty              = new Smarty;
				$smarty->compile_dir = $base_path . '/templates/templates_c';
				require_once $smarty->_get_plugin_filepath( 'modifier', 'format_date' );
			}
			$orderDetails = self::GetOrderDetails( $orderId );
			// because we use this function from the front of the site and the admin - we need to look up the config data when using the function from admin
			include_once( $base_path . '/php/databaseconnection.php' );
			$shop_show_vat = db_get_single_value( "SELECT value FROM configuration WHERE `name` = 'SHOP_SHOW_VAT'" );
			$shop_vat_rate = db_get_single_value( "SELECT value FROM configuration WHERE `name` = 'SHOP_VAT_RATE'" );
			$smarty->assign( 'shop_show_vat', $shop_show_vat );
			$smarty->assign( 'shop_vat_rate', $shop_vat_rate );
			$smarty->assign( 'orderDetails', $orderDetails );
			$message['to'] = $orderDetails['customer']['email'];
			//$message['bcc'] = $bcc;
			//  echo ' site contact email is ' .  SITE_CONTACT_EMAIL;
			$message['body'] = $smarty->fetch( $base_path . '/modules/shop/templates/confirmation_email.tpl' );
			// $message['body'] = $smarty->fetch($base_path . '/modules/shop/templates/simple_mail.tpl');
			// echo $message['body'];
			$message['subject'] = SITE_NAME . ': Your order number ' . $orderId;
			self::SendEmail( $message, $bcc );
		}

		static function SendEmail( $message, $bcc = "" ) {
			global $base_path;
			include_once $base_path . "/php/phpmailer/class.phpmailer.php";
			$mail = new PHPMailer();
			$mail->IsMail();
			if ( $bcc != "" ) {
				if ( is_array( $bcc ) ) {
					foreach ( $bcc as $email ) {
						$mail->AddBCC( $email );
					}
				} else {
					$mail->AddBCC( $bcc );
				}
			}
			$mail->From     = SITE_CONTACT_EMAIL;
			$mail->FromName = SITE_NAME;
			$mail->AddAddress( $message['to'] );
			//$mail->AddAddress(SITE_CONTACT_EMAIL);
			$mail->AddReplyTo( SITE_CONTACT_EMAIL, SITE_NAME );
			$mail->WordWrap = 50;                                 // set word wrap to 50 characters
			$mail->IsHTML( true );                                  // set email format to HTML
			$mail->Subject = $message['subject'];
			$mail->Body    = $message['body'];
			$mail->AltBody = strip_tags( $message['body'] );
			$mail->Send();
		}

		static function GetOrderDetails( $orderId ) {
			$orderDetails = self::GetOrderRecord( $orderId );
			$orderDetails['gross']    = $orderDetails['total_price'];
			$orderDetails['customer'] = self::GetCustomerRecord( $orderDetails['customer_id'] );
			$orderDetails['items']    = self::GetOrderItems( $orderId );
			return $orderDetails;
		}

		static function GetOrderRecord( $orderId ) {
			$sql = 'SELECT `id`, `customer_id`, UNIX_TIMESTAMP(`time_made`) AS \'time_made\', `tender_type`, `total_price`, `tax`, `status`, `delivery`, `trade_id`, collection ' .
			       ' FROM shop_order WHERE id = ' . $orderId;
			return db_get_single_row( $sql );
		}

		static function GetCustomerRecord( $customerId ) {
			$sql = 'SELECT * FROM shop_customer WHERE id = ' . $customerId;
			return db_get_single_row( $sql );
		}

		static function GetOrderItemByFuturePayId($futurePayId){
			$sql = "SELECT soil.timestamp, soi.*, ccd.name AS colour_name
				FROM shop_order_item soi
				INNER JOIN shop_order_item_lottery soil ON soil.`shop_order_item_id` = soi.id
				LEFT JOIN shop_product sp ON soi.product_id = sp.id
				LEFT JOIN colour_colour_details ccd ON ccd.id = colour_id
				WHERE futurePayId = '{$futurePayId}' ORDER BY 1 DESC LIMIT 1 ";
			return db_get_rows( $sql );
		}

		static function GetOrderItems( $orderId ) {
			// probably drop the sp.* - so we great the right description field
			$sql = 'SELECT soi.*, ccd.name AS colour_name
				FROM shop_order_item soi 
				LEFT JOIN shop_product sp ON soi.product_id = sp.id
				LEFT JOIN colour_colour_details ccd ON ccd.id = colour_id
				WHERE order_id = ' . $orderId;
			return db_get_rows( $sql );
		}
	}