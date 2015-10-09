<?php

	$base_path = $_SERVER['DOCUMENT_ROOT'];
	include_once $base_path . '/php/nice_urls.php';
	include_once $base_path . '/php/classes/pagination.php';
	include_once $base_path . '/modules/shop/classes/shop.php';
	include_once $base_path . '/modules/shop/classes/products.php';
	include_once $base_path . '/modules/shop/classes/basket.php';
	// might be worth having a switch here for payment gateway - GATEWAY_PROVIDER
	include_once $base_path . '/modules/shop/classes/checkout_form.php';
	include_once $base_path . '/modules/shop/classes/subscription_checkout_form.php';
	include_once $base_path . '/modules/shop/classes/colours.php';
	include_once $base_path . '/modules/shop/classes/order_confirmation_email.php';

	$shop                = new shop();
	$products            = new products();
	products::$page_name = $page_name;
	$shop_page_type      = 'home';
	$link_str            = '/shop?page=';
	list( $shop_page_type, $cat_page_name, $product_link, $product_id, $product_page_name ) = $shop->getShopPageType( $name_parts );
	$basket = new basket( true );

	switch ( $shop_page_type ) {
		// Listing products
		case "home":
			// I don't think we need this ....
			// the home is in the default section of this case statement
			$cat_page_name = $focus_category_row['page_name'];
			$home_body     = db_get_single_value( 'select body from content where page_name = \'' . $page_name . '\'', 'body' );
			$smarty->assign( 'home_body', $home_body );
			$smarty->assign( 'category_body', $home_body );
			$title         = db_get_single_value( "select name from shop_category where page_name = 'welcome'", 'name' );
			$products->sql = "SELECT SQL_CALC_FOUND_ROWS  p.* , sc1.page_name AS cat_pagename,  sc1.name AS 'cat_name', sc1.isRoot , (
SELECT CONCAT(sc2.name, '|', sc2.page_name)  AS thing FROM shop_category sc2 WHERE sc2.id = sc1.parent_id ) AS cat_parentname_and_pagename
FROM shop_product p INNER JOIN shop_category sc1 ON p.primary_category_id = sc1.id WHERE p.featured = 1";
			$link_str      = "/shop/$cat_page_name?page=";
			//$product_listing = true;
			break;
		case "product":
			// force page template
			$template_file = "shopproduct.htm";
			$title         = $product_item['name'];
			// if(SHOP_USE_COLOURS){ // need to test
			//$c = new colours();
			// $coloursArr = $c->getProductColoursAsJSArray($product_id); //$c->getAllColoursAsJSArray();
			//$coloursArr = $c->getProductColours($product_id);
			$colour_sql = "SELECT colour_id , `name`, rgb  FROM shop_product_colour spc INNER JOIN colour_colour_details  ccd ON spc.colour_id = ccd.id
    	    	WHERE product_id = '$product_id'  AND published = 1 ORDER BY spc.order_no";
			//   need to get rows + push in to template
			$colours = db_get_rows( $colour_sql );
			$smarty->assign( 'colours', $colours );
			//  }
			$product_item = $products->get_product( $product_id );
			// if we are using sex
			if ( SHOP_USE_GENDER ) {
				// gives you all the available options - check the 'available' is not null to enable
				$gender_sql = "SELECT t.id, t.title, IF( l.`shop_gender_id` IS NULL, 0, 1)   as available   FROM shop_gender t
LEFT OUTER JOIN shop_product_gender l ON t.id = l.`shop_gender_id` AND l.`shop_product_id` = '$product_id' 
ORDER BY t.`order_num`";
				$genders    = db_get_rows( $gender_sql );
				$smarty->assign( 'genders', $genders );
			}
			// if we are using size
			if ( SHOP_USE_SIZE ) {
				// gives you all the available options - check the 'available' is not null to enable
				$size_sql = "SELECT t.id, t.title,  IF( l.`shop_size_id` IS NULL, 0, 1)   as available FROM shop_size t
LEFT OUTER JOIN shop_product_size l ON t.id = l.`shop_size_id` AND l.`shop_product_id` = '$product_id'  
ORDER BY t.`order_num` ";
				$sizes    = db_get_rows( $size_sql );
				$smarty->assign( 'sizes', $sizes );
			}
			// might need to assign title to smarty var here
			if ( isset( $_POST['submit_basket_add'] ) ) {
				$showmessage = "showFadeInmessage(' Items Added to Bag ');";
			}
			//  if (!isset($_SESSION['isTrade'])) {
			// ADDING VAT
			// vat rate as a percentage
			//    $vat_rate = db_get_single_value("SELECT vat_rate FROM shop_vat_zone WHERE id = 1", 'vat_rate');
			$product_item['price'] = $product_item['price']; // + ($product_item['price'] * ( $vat_rate / 100 ));
			// }
			$smarty->assign( 'product', $product_item );
			$no_colours        = 0;
			$hide_pantone_grid = 0;
			$nonColoursArr     = 0;
			// with need to add the don't use colours attr from products db here
			if ( trim( $coloursArr ) == '[]' ) {
				// echo 'no colours ';
				$no_colours = true;
				// get no-colour array details
				$nonColoursArr     = $c->getNonColourDetails();
				$hide_pantone_grid = true;
			}
			if ( $product_item['display_as_non_colour'] ) {
				// override colour settings. show pallete but can't add to pallet
				$nonColoursArr     = $c->getNonColourDetails();
				$hide_pantone_grid = 0;
			}
			$smarty->assign( 'hide_pantone_grid', $hide_pantone_grid );
			$smarty->assign( 'nonColoursArr', $nonColoursArr );
			$smarty->assign( 'no_colours', $no_colours );
			$smarty->assign( 'showmessage', $showmessage );
			$smarty->assign( 'coloursArr', $coloursArr );
			$smarty->assign( 'page_name', $page_name );
			$smarty->assign( 'in_basket', $basket->product_qty( $product_id ) );
			$smarty->assign( 'stock_available', $product_item['stock_level'] ); // - $basket->product_qty($product_id));
			$content_template_file = "$base_path/modules/$module_path/templates/shop_single_product.tpl";
			break;
		case "category":
			$cat_row       = db_get_single_row( "select id, name, body from shop_category where page_name = '$cat_page_name'" );
			$title         = $cat_row['name'];
			$category_body = $cat_row['body'];
			$cat_id        = $cat_row['id'];
			$smarty->assign( 'category_body', $category_body );
			$sql   = "SELECT SQL_CALC_FOUND_ROWS p.*, 1 , c.page_name AS cat_pagename,  c.name AS 'cat_name', c.isRoot , (
            SELECT CONCAT(sc2.name, '|', sc2.page_name)  AS thing
            FROM shop_category sc2
            WHERE sc2.id = c.parent_id) AS cat_parentname_and_pagename ,(
SELECT rgb
FROM shop_product_colour spx 
INNER JOIN colour_colour_details ccd ON ccd.`id` = spx.`colour_id`
WHERE spx.`product_id` = spc.`product_id`
ORDER BY spx.order_no  LIMIT 1
 )AS rgb    FROM shop_product p
            LEFT JOIN shop_product_category pc ON p.id = shop_product_id AND shop_category_id = 11
            JOIN shop_category c ON c.id = shop_category_id
            OR primary_category_id = c.id
LEFT JOIN  shop_product_colour spc ON spc.product_id = p.id
WHERE p.published = 1 AND (c.id = " . $cat_id . " OR c.parent_id = "
			         . $cat_id . " OR p.id IN (SELECT shop_product_id FROM shop_product_category WHERE shop_category_id = " . $cat_id . "))
		GROUP BY id	";
			$order = "";
			if ( isset( $_GET['order'] ) ) {
				// prevent injection
				if ( $_GET['order'] == 'asc' ) {
					$order = 'asc ';
				}
				if ( $_GET['order'] == 'desc' ) {
					$order = 'desc ';
				}
				//    if (isset($_SESSION['isTrade'])) {
				//     $orderby = " order by p.trade_price " . $order;
				//   } else {
				$orderby = " order by p.price " . $order;
				//   }
			} else {
				$orderby = ' order by p.order_num  ';
			}
			$products->sql = $sql . $orderby;
			$link_str      = "/shop/category/$cat_page_name?page=";
			break;
		case "search":
			$title = 'Detailed Search';
			$smarty->assign( 'categories', $products->get_categories() );
			$content_template_file = "$base_path/modules/$module_path/templates/shop_search.tpl";
			break;
		case "results":
			$title           = 'Search Results';
			$search_keywords = $_REQUEST['search_keywords'];
			$category        = isset( $_REQUEST['category_id'] ) ? $_REQUEST['category_id'] : - 1;
			if ( $category == '-1' ) {
			/*	$products->sql = "select  SQL_CALC_FOUND_ROWS p.*, c.page_name AS cat_pagename, c.name AS 'cat_name', c.isRoot ,(
                SELECT CONCAT(sc2.name, '|', sc2.page_name)  AS thing
                FROM shop_category sc2
                WHERE sc2.id = c.parent_id
                ) AS cat_parentname_and_pagename, MATCH (p.name,p.description) AGAINST ('$search_keywords' IN BOOLEAN MODE) as score" .
				                 " from shop_product p " .
				                 ' JOIN shop_category c ON c.id = p.primary_category_id ' .
				                 " where p.published = 1 and MATCH (p.name,p.description) AGAINST ('$search_keywords' IN BOOLEAN MODE)" .
				                 " order by score desc, p.id";
			*/
				$products->sql = "SELECT SQL_CALC_FOUND_ROWS p.*, c.page_name AS cat_pagename, c.name AS 'cat_name', c.isRoot ,
( SELECT CONCAT(sc2.name, '|', sc2.page_name) AS thing FROM shop_category sc2 WHERE sc2.id = c.parent_id ) AS cat_parentname_and_pagename

FROM shop_product p
JOIN shop_category c ON c.id = p.primary_category_id
WHERE p.published = 1 AND

      p.name LIKE '%{$search_keywords}%' OR p.description LIKE '%{$search_keywords}%'
 ";





			} else {
				$cat_clause    = " and shop_category_id = '$category' ";
				$products->sql = "select  SQL_CALC_FOUND_ROWS p.*, level, MATCH (p.name,p.description) AGAINST ('$search_keywords' IN BOOLEAN MODE) as score" .
				                 " from shop_product p " .
				                 ' join shop_stock s on s.product_id = p.id ' .
				                 " join shop_product_category pc on shop_product_id = p.id " .
				                 " where p.published = 1  and MATCH (p.name,p.description) AGAINST ('$search_keywords' IN BOOLEAN MODE)" .
				                 " $cat_clause order by score desc, p.id";
			}
			$cat_param       = ( $category == '-1' ) ? '' : "&category_id=$category";
			$search_keywords = urlencode( $search_keywords );
			$link_str        = "/shop/results?search_keywords=$search_keywords$cat_params&page=";
	 
				break;
		case "basket":
			$title = 'Shopping Cart';
			$smarty->assign( 'total_vat', $total_vat );
			// $basket['items'] = $_SESSION['basket'];
			$smarty->assign( 'items', $_SESSION['basket']['items'] );
			$smarty->assign( 'basket', $basket );
			$smarty->assign( 'basket_empty', BASKET_EMPTY_TEXT );
			$content_template_file = "$base_path/modules/$module_path/templates/shop_basket.tpl";
			break;
		case "subscribe":
			// This section is meant to handle WorldPay installation subscriptions
			$title = 'Subscription Checkout';
			$form  = new subscription_checkout_form();
			$form->get_data();
			$submit = ( isset( $_POST['Submit'] ) ) ? $_POST['Submit'] : "";
			if ( $submit ) {
				$form->validate_data();
				if ( $form->has_errors() ) {
					$form->display_errors();
					$smarty->assign( 'form_display', $form_display );
					$content_template_file = "$base_path/modules/$module_path/templates/subscription_checkout.tpl";
				} else {
					$form->process_data( $page_name );
					$_SESSION['form'] = $form;
					header( "location: /$page_name/subscribe_confirm" );
				}
			} else {
				$_SESSION['subscription'] = $_POST;
				$form_display             = $form->display_form( false );
				$smarty->assign( 'form_display', $form_display );
				$content_template_file = "$base_path/modules/$module_path/templates/subscription_checkout.tpl";
			}
			break;
		case "subscribe_confirm";
			$title = "Confirm Subscription Details";
			fixObject( $_SESSION['form'] );
			$form = $_SESSION['form'];
			$smarty->assign( 'total', $form->confirm_fields['amount']['value'] );
			$smarty->assign( 'qty', $_SESSION['subscription']['qty'] );
			$smarty->assign( 'weeks', $_SESSION['subscription']['weeks'] );
			$smarty->assign( 'item_description', $_SESSION['subscription']['item_description'] );
			$smarty->assign( 'confirm', $form );
			$content_template_file = "$base_path/modules/$module_path/templates/subscription_confirm.tpl";
			break;
		case "collection";
			if ( $_GET['clear_collection'] ) {
				unset( $_SESSION['collection'] );
			} else {
				$_SESSION['collection'] = true;
			}
			header( "location: /$page_name/checkout?fastforward=true" );

		case "donate";
			// refresh the basket and fastforward
			header( "location: /$page_name/checkout?fastforward=true" );


		case "checkout":
			// might want to consider having a different template here
			// $template_file = "fullscreen.htm";
			$title = 'Checkout';
			$form  = new checkout_form();
			$form->get_data();
			$submit = ( isset( $_POST['Submit'] ) ) ? $_POST['Submit'] : "";
			if ( $_GET['fastforward'] == 'true' ) {
				$form->process_data( $page_name );
				$_SESSION['form'] = $form;
				header( "location: /$page_name/confirm" );
				$content_template_file = "$base_path/modules/$module_path/templates/confirm.tpl";
			} else {
				if ( $submit ) {
					$form->validate_data();
					if ( $form->has_errors() ) {
						$form->display_errors();
						$smarty->assign( 'form_display', $form_display );
						$content_template_file = "$base_path/modules/$module_path/templates/checkout.tpl";
					} else {
						$form->process_data( $page_name );
						$_SESSION['form'] = $form;
						header( "location: /$page_name/confirm" );
						$content_template_file = "$base_path/modules/$module_path/templates/confirm.tpl";
					}
				} else {
					$form_display = $form->display_form( false );
					$smarty->assign( 'form_display', $form_display );
					$content_template_file = "$base_path/modules/$module_path/templates/checkout.tpl";
				}
			}
			break;
		case "confirm":
			$title = "Confirm Details";
			fixObject( $_SESSION['form'] );
			$form         = $_SESSION['form'];
			$form->basket = $basket;
			$smarty->assign( 'items', $_SESSION['basket'] );
			$smarty->assign( 'confirm', $form );


			$has_donation = false;
			foreach($basket->items as $item){

				if($item['product_type'] == '1'){
					$has_donation = true;
				}
			}
			// $show_donate_option if there isn't a donation in the basket


			$smarty->assign('has_donation', $has_donation);
			$content_template_file = "$base_path/modules/$module_path/templates/confirm.tpl";
			break;
		case "success":
			db_update( "INSERT INTO `log` (`log`,`message`,`file`) values ('" . mysql_real_escape_string( print_r( $_POST, true ) ) . "','shop post vars from success block','" . __FILE__ . "')" );
			$title = 'Order Placed';
			if ( isset( $_GET['message'] ) ) {
				if ( $_GET['message'] == 'success' ) {
					$smarty->assign( 'order_id', $_GET['order_id'] );
					$content_template_file = "$base_path/modules/$module_path/templates/success.tpl";
				} else {
					// fail
					$smarty->assign( 'order_id', $_GET['order_id'] );
					$content_template_file = "$base_path/modules/$module_path/templates/failure.tpl";
				}
				break;
			} else {
				if ( PAYMENT_PROVIDER == 'PROTX' ) {
					$strCrypt = $_REQUEST["crypt"];
					// version 3.0 - You will need the decode functions added near the end of this file
					$values = decode( $strCrypt );
					$smarty->assign( 'order_values', $values );
					$order_id      = substr( $values['VendorTxCode'], strlen( PROTX_VENDOR_NAME ) );
					$update_sql    = "update shop_order set status = 1,  security_key='" . $values['VPSTxId'] . "' where id = '$order_id'";
					$update_result = mysql_query( $update_sql );
					OrderConfirmationEmail::SendConfirmationEmail( $order_id );
					// empty the basket
					unset( $_SESSION['basket'] );
					unset( $_SESSION['deliverycost'] );
					unset( $_SESSION['vatrate'] );
					if ( SHOP_USE_STOCK_CONTROL ) {
						// massive cavat is that this only work on a shop where you don't have colour, sizes or other attributes
						$update_stock_sql    = "UPDATE shop_product p JOIN shop_order_item oi ON p.id = oi.product_id SET stock_level = stock_level - quantity WHERE order_id = $order_id";
						$update_stock_result = mysql_query( $update_stock_sql );
					}
					$smarty->assign( 'order_id', $order_id );
					$content_template_file = "$base_path/modules/$module_path/templates/success.tpl";
				}
				if ( PAYMENT_PROVIDER == 'PAYPAL' ) {
					// Say thanks for order when returning from paypal
					// NOTE: WE DO NOT UPDATE STOCK AND CONFIRM SALE HERE ... WE DO THAT WHEN WE GET THE IPN FROM PAYPAL
					// empty the basket
					unset( $_SESSION['basket'] );
					unset( $_SESSION['deliverycost'] );
					unset( $_SESSION['vatrate'] );
					$smarty->assign( 'order_id', $order_id );
					$content_template_file = "$base_path/modules/$module_path/templates/paypal_success.tpl";
				}
				if ( PAYMENT_PROVIDER == 'WORLDPAY' ) {
					$transStatus = isset( $_POST['transStatus'] ) ? $_POST['transStatus'] : 'N';
					$valid       = ( $transStatus == 'Y' );
					if ( ( $valid ) && ( $_POST['callbackPW'] == WORLDPAY_CALLBACK_PASSWORD ) ) {
						// Update transaction to success
						// we are thinking that a post to here without a valid cardId is a recurring lottery payment
						cs_log( "into worldpay", __FILE__, $_POST );
						$pos = strpos( $_POST['cartId'], "shop_" );
						if ( $pos === false || ! isset( $_POST['cartId'] ) ) {
							//	echo "this is a recurring payment !!!";
							// this is not a regular shop transaction but a recurring payment instead
							cs_log( "insert the recurring lottery payment ", __FILE__, $_POST );
							// if it is bypass the order up date
							// then get the order id from the futurepay id and add a new shop_order_item and possibly new shop_order_item_lottery line too
							// $futurePayId, $transId, $post_data, $serialised_post, $transStatus
							// Set this to a specific futurePayId to test
							$fpid = $_POST['futurePayId'];// '20024134'; //$_POST['futurePayId']
							$shop->insertNewRecurringLotteryPayment( $fpid, $_POST['transId'], mysql_real_escape_string( print_r( $_POST, true ) ), mysql_real_escape_string( serialize( $_POST ) ), $_POST['transStatus'] );
							// send an email
							$bcc[] = SHOP_CONFIRMATION_EMAIL_ADDRESS_LOTTERY;
							OrderConfirmationEmail::SendConfirmationEmailRecurringLotteryPurchase( $fpid, $bcc );
						} else {
							$order_id = str_replace( 'shop_', '', $_POST['cartId'] );
							if ( isset( $_POST['futurePayId'] ) ) {
								/*** if we are dealing with the first of a recurring payment, update the order line with future pay details **/
								$shop_order_item_lottery_id = db_get_single_value( "SELECT soil.id FROM shop_order_item soi
INNER JOIN shop_order_item_lottery soil ON soil.`shop_order_item_id` = soi.`id`
WHERE soi.product_type = 3 AND soi.product_id = 2 AND soi.order_id = " . $order_id );
								$futurePayId                = $_POST['futurePayId'];
								$transId                    = $_POST['transId'];
								$post_data                  = mysql_real_escape_string( print_r( $_POST, true ) );
								$serialised_post            = mysql_real_escape_string( serialize( $_POST ) );
								db_update( "UPDATE shop_order_item_lottery  SET  futurePayId = '{$futurePayId}', transId = '{$transId}',
						 post_data = '{$post_data}', serialised_post = '{$serialised_post}', transStatus = '{$transStatus}'   WHERE id =" . $shop_order_item_lottery_id );
							}
							// update the order as complete
							$update_sql    = "update shop_order set status = 1,  security_key='" . mysql_real_escape_string( $data ) . "' where id = " . $order_id;
							$update_result = mysql_query( $update_sql );
							// depending on the product type - send email to appropriate department
							// if order contains multiple types send multi emails
							// product_type
							// this should return a distinct list of product types in this order
							$csv_product_types = db_get_single_value( "SELECT  GROUP_CONCAT(DISTINCT soi.`product_type`) FROM shop_order so
INNER JOIN shop_order_item soi ON so.id = soi.order_id
WHERE so.id =" . $order_id );
							// 0 - normal shop product SHOP_CONFIRMATION_EMAIL_ADDRESS
							if ( strpos( $csv_product_types, '0' ) !== false ) {
								//$bcc[] = SHOP_CONFIRMATION_EMAIL_ADDRESS;
							}
							// 1 - Donation SHOP_CONFIRMATION_EMAIL_ADDRESS_DONATIONS
							if ( strpos( $csv_product_types, '1' ) !== false ) {
								//$bcc[] = SHOP_CONFIRMATION_EMAIL_ADDRESS_DONATIONS;
                                OrderConfirmationEmail::SendDonationEmail( $order_id );
                            }
							// 2 - Event SHOP_CONFIRMATION_EMAIL_ADDRESS_BOOKINGS
							if ( strpos( $csv_product_types, '2' ) !== false ) {
								//$bcc[] = SHOP_CONFIRMATION_EMAIL_ADDRESS_BOOKINGS;
							}
							// 3 - Lottery Ticket (no subscription) Subscriptions will go through a different mechanism SHOP_CONFIRMATION_EMAIL_ADDRESS_LOTTERY
							if ( strpos( $csv_product_types, '3' ) !== false ) {
								$bcc[] = SHOP_CONFIRMATION_EMAIL_ADDRESS_LOTTERY;
							}
							OrderConfirmationEmail::SendConfirmationEmail( $order_id, $bcc );
							// empty the basket
							unset( $_SESSION['basket'] );
							unset( $_SESSION['deliverycost'] );
							unset( $_SESSION['vatrate'] );
							unset( $_SESSION['collection'] );
							$smarty->assign( 'order_id', $order_id );
							$content_template_file = "$base_path/modules/$module_path/templates/success.tpl";
						}
						echo '<meta http-equiv="refresh" content="0;URL=\'http://www.rotherhamhospice.csprev.com/shop/success?message=success&order_id=' . $order_id . '">';
					} else {
						// Payment failed
						$order_id   = str_replace( 'shop_', '', $_POST['cartId'] );
						$update_sql = "update shop_order set status = 0,  gateway_postback='" . mysql_real_escape_string( $data ) . "' where id = " . $order_id;
						$smarty->assign( 'order_id', $order_id );
						$content_template_file = "$base_path/modules/$module_path/templates/failure.tpl";
						echo '<meta http-equiv="refresh" content="0;URL=\'http://www.rotherhamhospice.csprev.com/shop/success?message=fail&order_id=' . $order_id . '">';
					}
				} // end message
				break;
			}
			break;
		// This is for PAYPAL IPN system. listening for ipn and notify
		case "ipn":
			include_once $base_path . '/modules/shop/classes/paypal_ipn.php';
			break;
		// This is for PAYPAL IPN system. listening for ipn and notify
		case "cancel":
			$content_template_file = "$base_path/modules/$module_path/templates/paypal_cancel.tpl";
			break;
		case "failed":
			$title = 'Order Failed';
			if ( PAYMENT_PROVIDER == 'PROTX' ) {
				$strCrypt = $_REQUEST["crypt"];
				// version 3.0 - You will need the decode functions added near the end of this file
				$values   = decode( $strCrypt );
				$order_id = substr( $values['VendorTxCode'], strlen( PROTX_VENDOR_NAME ) );
				$smarty->assign( 'order_id', $order_id );
				/// status 7 for failed payment
				$update_sql    = "update shop_order set status = 7,  security_key='" . $values['VPSTxId'] . "' where id = '$order_id'";
				$update_result = mysql_query( $update_sql );
				// empty the basket
				unset( $_SESSION['basket'] );
				unset( $_SESSION['deliverycost'] );
				unset( $_SESSION['vatrate'] );
			}
			$content_template_file = "$base_path/modules/$module_path/templates/failure.tpl";
			break;
		case "test":
			OrderConfirmationEmail::SendConfirmationEmailTest( $name_parts[2] );
			$title = 'Email Test Page. Add the required order number after the slash in the page name to get a copy of the test email';
			break;
		default:
			// Homepage
			$page = $_REQUEST['page'];
			$products->pagination->set_page();
			$products->get_products();
			if ( isset( $_GET['order'] ) ) {
				$pag_string = $products->pagination->html_string( $link_str, '&order=' . $_GET['order'] );
			} else {
				$pag_string = $products->pagination->html_string( $link_str );
			}
			$sort_str = $link_str . '1&order=';
			$smarty->assign( 'products', $products->product_list );
			$smarty->assign( 'count', $products->pagination->pages );
			$smarty->assign( 'pag_string', $pag_string );
			$smarty->assign( 'search_keywords', $search_keywords );
			$smarty->assign( 'sort_str', $sort_str );
			$smarty->register_object( 'basket', $basket, null, false );
			$content_template_file = "$base_path/modules/$module_path/templates/homepage_product_list.tpl";
			$shop_featured_items   = db_get_rows( 'SELECT sp.* , (
SELECT rgb
FROM shop_product_colour spx 
INNER JOIN colour_colour_details ccd ON ccd.`id` = spx.`colour_id`
WHERE spx.`product_id` = spc.`product_id`
ORDER BY spx.order_no  LIMIT 1
)AS rgb
FROM shop_product sp
LEFT  JOIN  shop_product_colour spc ON spc.product_id = sp.id
WHERE shop_featured = 1 
GROUP BY id
ORDER BY sp.order_num' );
			$smarty->assign( 'shop_featured_items', $shop_featured_items );
			$home_body = db_get_single_value( 'select body from content where page_name = \'' . $page_name . '\'', 'body' );
			$smarty->assign( 'home_body', $home_body );
			$smarty->assign( 'homepage', true );
			break;
	}

	// Output for categories and search results
	if ( $shop_page_type == "category" || $shop_page_type == "results" ) {
		$page = $_REQUEST['page'];
		$products->pagination->set_page();
		$products->get_products();
		if ( isset( $_GET['order'] ) ) {
			$pag_string = $products->pagination->html_string( $link_str, '&order=' . $_GET['order'] );
		} else {
			$pag_string = $products->pagination->html_string( $link_str );
		}
		$sort_str = $link_str . '1&order=';
		$smarty->assign( 'products', $products->product_list );
		$smarty->assign( 'count', $products->pagination->pages );
		$smarty->assign( 'pag_string', $pag_string );
		$smarty->assign( 'sort_str', $sort_str );
		$search_keywords = $_POST['search_keywords'];
		if ( $search_keywords == '' ) {
			$search_keywords = $_GET['search_keywords'];
		}
		$smarty->assign( 'search_keywords', $search_keywords );
		$smarty->register_object( 'basket', $basket, null, false );
		$smarty->assign( 'hide_sortby', 'true' );
		$content_template_file = "$base_path/modules/$module_path/templates/shop_product_list.tpl";
		if ( $search_keywords != '' ) {
			$smarty->assign( 'category_body', 'Search results for <b>"' . $search_keywords . '"</b> in order of relevance.' );
		}
	}

	_d( 'shop_page_type :' . $shop_page_type );
	_d( 'template file :' . $template_file );

	function fixObject( &$object ) {
		// if we get the __PHP_Incomplete_Class in the basket, can fix it here. We use it on the confirm section of the form
		if ( ! is_object( $object ) && gettype( $object ) == 'object' ) {
			return ( $object = unserialize( serialize( $object ) ) );
		}
		return $object;
	}

	$smarty_cache_id = "{$shop_page_type}-{$page}";
	$smarty->display( "file:$content_template_file", $smarty_cache_id );


	// ******************************************************
	// New Sage Pay functions //
	// ******************************************************
	// see classes/checkout_form.php for the decode versions
	function decode( $strIn ) {
		$decodedString = decodeAndDecrypt( $strIn );
		parse_str( $decodedString, $sagePayResponse );
		return $sagePayResponse;
	}

	function decodeAndDecrypt( $strIn ) {
		// get password from our global
		$encryptPassword = PROTX_ENCRYPTION_PASSWORD;
		$strIn           = substr( $strIn, 1 );
		$strIn           = pack( 'H*', $strIn );
		return mcrypt_decrypt( MCRYPT_RIJNDAEL_128, $encryptPassword, $strIn, MCRYPT_MODE_CBC, $encryptPassword );
	}

	// ******************************************************
?>
