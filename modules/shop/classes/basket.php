<?php

	class basket {

		var $attributes;
		var $items;
		var $count;
		var $product_total;
		var $product_total_before_tax;
		var $tax_total;
		var $total_before_tax;
		var $total_inc_delivery;
		var $total;
		var $shipping_exception;
		var $shipping_rate;
		var $shipping_units;
		var $shipping_total;
		var $shipping_total_before_tax;
		var $VAT_territory;
		var $delivery_territory;
		var $vatrate; // one rate for whole basket
		var $deliverycost;   // one price for whole basket
		var $bulk_discount_item1;
		var $bulk_discount_discount1;
		var $bulk_discount_item2;
		var $bulk_discount_discount2;
		var $bulk_discount_item3;
		var $bulk_discount_discount3;
		var $discount_text;

		function add_item_to_basket( $items ) {
			if ( isset( $_POST['product_type'] ) ) {
				$product_type = $_POST['product_type'];
			} else {
				// regular shop product
				$product_type = 0;
			}
			// this is the route for regular shop products
			if ( $product_type == 0 ) {
				$id  = $_POST['product_id'];
				$qty = $_POST['qty'];
				// either just add the item to the basket - or increment current matching item in basket.
				$item_already_in_basket = false;
				$key                    = uniqid();
				$new_item               = array( 'key' => $key, 'id' => $id, 'qty' => $qty );
				foreach ( $this->attributes as $attr ) {
					$new_item[$attr] = $_POST[$attr];
				}
				// does this item already exist in the array
				if(isset($items)) {
					foreach ( $items as &$item ) {
						$count = 0;
						if ( $item['id'] == $new_item['id'] ) {
							// same id ...
							foreach ( $this->attributes as $attr ) {
								if ( $new_item[$attr] == $item[$attr] ) {
									//echo 'matching value for ' . $attr;
									$count ++;
								}
							}
							if ( $count == sizeof( $this->attributes ) ) {
								//echo 'found a match';
								$item_already_in_basket = true;
								$key_value              = $item['key'];
								$item['qty']            = $item['qty'] + $new_item['qty'];
								// will need to recalculate row price .....
								$item['row_price'] = $item['price'] * $item['qty'];
							}
						}
					}
				}
				if ( ! $item_already_in_basket ) {
					// going to add it to array. lets do a look up for the rest of item details
					$sql = "SELECT p.*, bd.`dis1`, bd.`dis2`, bd.`dis3`, bd.`items1` , bd.`items2`, bd.`items3`
FROM shop_product p  LEFT JOIN shop_bulk_discount bd ON bd.id = p.`bulk_discount_id`
WHERE p.id  = " . $id;
					$row = db_get_single_row( $sql );
					// record item as a regular shop item - rather than donation or event etc
					$new_item['product_type']            = $product_type;
					$new_item['name']                    = $row['name'];
					$new_item['price']                   = $row['price'];
					$new_item['row_price']               = $row['price'] * $new_item['qty'];
					$new_item['bulk_discount_item1']     = $row['items1'];
					$new_item['bulk_discount_discount1'] = $row['dis1'];
					$new_item['bulk_discount_item2']     = $row['items2'];
					$new_item['bulk_discount_discount2'] = $row['dis2'];
					$new_item['bulk_discount_item3']     = $row['items3'];
					$new_item['bulk_discount_discount3'] = $row['dis3'];
					$new_item['bulk_discount_id']        = $row['bulk_discount_id'];
					$new_item['size_name']               = db_get_single_value( "SELECT title FROM shop_size WHERE id =" . $new_item['size_id'] );
					$new_item['gender_name']             = db_get_single_value( "SELECT title FROM shop_gender WHERE id =" . $new_item['gender_id'] );
					$new_item['colour_name']             = db_get_single_value( "SELECT `name` FROM colour_colour_details WHERE id = " . $new_item['colour_id'] );
					if ( ! isset( $items ) ) {
						$items = array();
					}
					$items[$key] = $new_item;
				}
			} else {
				// non-shop products - add to the basket
				$key      = uniqid();
				$new_item = array( 'key' => $key );
				/***********************************************/
				// adding donation to the basket
				/***********************************************/
				if ( $product_type == '1' ) {
					// set the donation type
					$new_item['id'] = $_POST['product_id'];
					// it's 1
					$new_item['qty'] = $_POST['qty'];



					// record the product type for later processing - this is a donation
					$new_item['product_type'] = $product_type;
					// donation description
					$new_item['name']                  = $_POST['item_description'];
					if($_POST['giftaid'] != null) {
						$new_item['gift_aid'] = 1;
						$new_item['name']   = $new_item['name'] . " with Gift Aid";
					}else{
						$new_item['gift_aid'] = 0;
					}


					if(trim($_POST['price']) == ''){
						// get price from dropdown instead
						$new_item['price'] = $_POST['fixed_values'];
					}else {
						$new_item['price'] = $_POST['price'];
					}
					$new_item['row_price']             = $new_item['price'] * $new_item['qty']; // qty is always 1 for this type of item
					$new_item['donate_user_response1'] = $_POST['donate_user_response1'];
					$new_item['donate_user_response2'] = $_POST['donate_user_response2'];
					$new_item['donation_location'] = $_POST['donation_location'];




					// Add to basket
					if ( ! isset( $items ) ) {
						$items = array();
					}
					$items[$key] = $new_item;
				}
				/*************** end donations *********************/

				/***********************************************/
				// adding events to the basket
				/***********************************************/
				if ( $product_type == '2' ) {

					foreach ( $_POST as $name => $val ) {
						if ( $name != "product_type" ) {
							if ( $name != "submit_basket_add" ) {
								// don't bother adding items in the POST with the zero items
								if ( $val != "0" ) {
									// the key is unique - we will use it as a reference later
									$key      = uniqid();
									$new_item = array( 'key' => $key );
									// figure out which type of product (either ticket or product) and id we have
									$pos = strpos( $name, "ticket_id" );
									if ( $pos === false ) {
										// not a ticket - so is a product
										$item_id = str_replace( "product_id_", "", $name );
										$sub_type = "product";
										// get the item description with a lookup
										$row = db_get_single_row( "SELECT * FROM booking_product WHERE id =" . $item_id );
										$event_name = db_get_single_value("SELECT b.`title` FROM booking_ticket bt INNER JOIN booking b ON b.id = bt.booking_id WHERE bt.id =" . $item_id );

									} else {
										// its a ticket
										$item_id = str_replace( "ticket_id_", "", $name );
										$sub_type = "ticket";
										// get the item description with a lookup
										$row = db_get_single_row( "SELECT * FROM booking_ticket WHERE id =" . $item_id );
										$event_name = db_get_single_value("SELECT b.`title` FROM booking_product bt INNER JOIN booking b ON b.id = bt.booking_id WHERE bt.id =" . $item_id );

									}
									// record the product type for later processing - this is a event
									$new_item['id']  = $item_id;
									$new_item['qty'] = $val;
									$new_item['product_type'] = $product_type;
									$new_item['sub_type']     = $sub_type;

									// get event name
									$new_item['name']      = $event_name . ':' . $row['title'] . " (&pound; " . $row['price'] . " x " . $val . ")";

									$new_item['price']     = $row['price'];
									$new_item['row_price'] = $row['price'] * $val; // qty is always 1 for this type of item
									// Add to basket
									if ( ! isset( $items ) ) {
										$items = array();
									}
									$items[$key] = $new_item;
								}
							}
						}
					}
				}
				/*************** end events *********************/

				/***********************************************/
				// adding lotto to the basket
				/***********************************************/
				if ( $product_type == '3' ) {
					//   var_dump($_POST);
					//    die();
					// TODO: need to figure out recurring payments version
					$price = LOTTERY_TICKET_COST;
					// set the donation type
					$new_item['id'] = $_POST['product_id'];
					// it's 1
					$new_item['qty'] = $_POST['qty'];
					// record the product type for later processing - this is a donation
					$new_item['product_type'] = $product_type;
					$new_item['name']         = $_POST['item_description'] . ': ' . $_POST['weeks'] . ' Weeks of ' . $_POST['qty'] . ' tickets  @ £' . $price;
					$new_item['price']        = $price * $_POST['weeks'];
					$new_item['row_price']    = ( $price * $_POST['weeks'] ) * $new_item['qty']; // qty is always 1 for this type of item
					$new_item['weeks']        = $_POST['weeks'];
					$new_item['subscription'] = $_POST['subscription'];
					// Add to basket
					if ( ! isset( $items ) ) {
						$items = array();
					}
					$items[$key] = $new_item;
				}
				/*************** end events *********************/
			}
			return $items;
		}

		function basket( $process = false ) {
			// kill off basket if we set the kill in the url
			if ( isset( $_GET['kill'] ) ) {
				session_destroy();
				$basket             = array();
				$_SESSION['basket'] = array();
				unset($_SESSION['collection']);
			}
			// if we have a basket in session then use it
			if ( isset( $_SESSION['basket'] ) ) {
				$basket      = $_SESSION['basket'];
				$this->items = $_SESSION['basket']['items'];
			} else {
				// else create new array
				$basket = array();
			}




			// shop configured product attributes
			// if you add more attributes on to the system, add to this array
			$this->attributes = array( 'gender_id', 'colour_id', 'size_id' );
			// initalise items
			$this->count = 0;
			$page_name  = strip_tags( $_SERVER['REQUEST_URI'] );
			$name_parts = explode( '/', $page_name );
			// remove any get strings on url
			$t              = explode( '?', $name_parts[3] );

			if ( $name_parts[1] == 'shop' && $name_parts[2] == 'confirm' ) {
				//  keep the delivery calculations
			} else {
				unset( $_SESSION['vatrate'] );
				unset( $_SESSION['deliverycost'] );
			}
			// if we are adding/removing or editing
			if ( $process == true ) {
				// if adding items
				if ( isset( $_POST['submit_basket_add'] ) ) {
					// add to cart  either add a new entry or increment an existing one.
					$basket['items'] = $this->add_item_to_basket( $basket['items'] );
				};
				// editing items in the basket, I think we can only do this via the basket page
				if ( isset( $_POST['submit_basket_change'] ) ) {
					// if we use basket keys this might be easier
					$qty = $_POST['qty'];
					$key = $_POST['key'];
					// remove if quanity of item set to 0
					if ( $qty === '0' ) {
						// remove item from array with key == $key
						unset( $basket['items'][$key] );
					} else {
						$basket['items'][$key]['qty'] = $qty;
						// need to recalculate the row price
						$basket['items'][$key]['row_price'] = $basket['items'][$key]['qty'] * $basket['items'][$key]['price'];
					}
					// update session basket
					$_SESSION['basket'] = $basket;
					// need to reload the page to update the basket
					header( 'Location: basket' );
				}
			} // end updates
			// reset totals
			$this->total     = 0;
			$this->total_qty = 0;
			// loop basket and do some calculations here

			if(isset($basket['items'])) {
				foreach ( $basket['items'] as $item ) {
					// check wheather any item in the cart is a bulk discount item, will figure out if there are actually any discounts later on.
					$contains_bulk_discount = false;
					if ( $item['dis1'] > 0 || $item['dis2'] > 0 || $item['dis3'] > 0 ) {
						$contains_bulk_discount = true;
					}
					$this->total += $item['row_price'];
					$this->total_qty += $item['qty']; // whats this for ??
					$this->tax_total += $commission_tax; // just changed this  ??
				}
			}

			if ( SHOP_USE_BULK_BUY ) {
				// due to the potential of bulk discounts ... need to reexamine the cart
				if ( $contains_bulk_discount ) {
					$this->calculate_bulk_discounts();
				}
			}
			// this sets vat for public users, default to uk vat
			$this->product_total_before_tax = $this->total; // - $this->tax_total;
			$this->total                    = $this->total + $_SESSION['deliverycost'];

			if ( isset( $_SESSION['vatrate'] ) ) {
				$vat_rate        = $_SESSION['vatrate'];
				$this->tax_total = $this->total * ( $vat_rate / 100 );
			} else {
				if ( $this->VAT_territory != null ) {
					// figure out which location we are billing to
					$vat_rate        = db_get_single_value( "SELECT vat_rate FROM shop_vat_zone WHERE id = " . $this->VAT_territory, 'vat_rate' );
					$this->tax_total = $this->total * ( $vat_rate / 100 ); // 20% of total - for example
				} else {
					// assume uk - this is really just for basket preview
					$vat_rate        = db_get_single_value( "SELECT vat_rate FROM shop_vat_zone WHERE id = 1", 'vat_rate' );
					$this->tax_total = $this->total * ( $vat_rate / 100 ); // 20% of total - for example

				}
			}
			// basically all product (NOT DELIVERY + VAT)
			$this->product_total      = $this->product_total_before_tax + ( $this->product_total_before_tax * ( $vat_rate / 100 ) );
			$this->total              = $this->total;
			$this->total_inc_delivery = $this > total;
			$_SESSION['basket'] = $basket;
			return $basket;
		}

		function setDeliveryCountryCode( $country_id ) {
			$country_code                      = db_get_single_value( 'SELECT `code` FROM shop_country WHERE id  =  ' . $country_id, 'code' );
			$_SESSION['delivery_country_code'] = $country_code;
			$country_name                      = db_get_single_value( 'SELECT `name` FROM shop_country WHERE id  =  ' . $country_id, 'name' );
			$_SESSION['delivery_country']      = $country_name;
		}

		function setBilllingCountryCode( $country_id ) {
			$country_code                     = db_get_single_value( 'SELECT `code` FROM shop_country WHERE id  =  ' . $country_id, 'code' );
			$_SESSION['billing_country_code'] = $country_code;
			$country_name                     = db_get_single_value( 'SELECT `name` FROM shop_country WHERE id  =  ' . $country_id, 'name' );
			$_SESSION['billing_country']      = $country_name;
		}

		function setVAT_territory( $country_id ) {
			$zone_id             = db_get_single_value( 'SELECT zone_id FROM shop_country WHERE id  = ' . $country_id, 'zone_id' );
			$vatrate             = db_get_single_value( 'select vat_rate from shop_vat_zone where id = ' . $zone_id, 'vat_rate' );
			$this->VAT_territory = $zone_id;
			$this->vatrate       = $vatrate;
			$_SESSION['vatrate'] = $this->vatrate;
		}

		function basketTotalMisusFreeDeliveryItems() {
			// look through basket for any items that are free delivery.
			// ad up the total of the items
			// take it away from the basket total (before vat + delivery)
			echo '<br> product_total = ' . $this->product_total_before_tax;
			$sql_in = '';
			$non_shop_value = 0;
			foreach ( $this->items as $item ) {
				// only shop products
				if ( $item['product_type'] == 0 ) {
					// do a look up on all items
					$sql_in .= $item['id'] . ',';
				}else{
					// the product will be free as it either a ticket, donation , lottery etc
					$no_shop_free_delivery_items[] = $item['id'];
					// just add up stuff that isn't a shop product
					$non_shop_value = $non_shop_value + ($item["price"] * $item["qty"]);

 				}
			}

			if($sql_in!="") {
				// tidy up loop extra comma
				$sql_in = substr_replace( $sql_in, "", - 1 );
				$sql    = 'SELECT id FROM shop_product WHERE free_delivery = 1 AND id IN (' . $sql_in . ')';
				$result = mysql_query( $sql );
				while ( $row = mysql_fetch_array( $result ) ) {
					$free_delivery_items[] = array( $row['id'] );
				}

				$total_free_delivery_items =0;
				foreach ( $free_delivery_items as $fdi_item ) {
					// loop through each free delivery item
					foreach ( $this->items as $basket_item ) {
						// find it in the basket for regular shop items
						if ( $fdi_item[0] == $basket_item["id"] ) {
							// basket price - has got the right public/trade price. so use that here
							// TODO: because we loop the array for items, if there is an item with many colours this will not work ????
							$item_cost                 = $basket_item["price"] * $basket_item["qty"];
							$total_free_delivery_items = $total_free_delivery_items + $item_cost;
						}
					}
				}
			}

			/*foreach ( $no_shop_free_delivery_items as $fdi_item ) {
				echo ' <br> non shop fdi_item["id"] = ' . $fdi_item[0] . ' ; ';
				// loop through each free delivery item
				foreach ( $this->items as $basket_item ) {
					echo ' ***  basket item ["id"] ' . $basket_item["id"];
					// find it in the basket for regular shop items
					if ( $fdi_item[0] == $basket_item["id"] ) {
						// basket price - has got the right public/trade price. so use that here
						echo 'free delievery item <hr>';
						echo $basket_item["price"];
						echo '<br>' . $basket_item["qty"];
						// TODO: because we loop the array for items, if there is an item with many colours this will not work ????
						$item_cost                 = $basket_item["price"] * $basket_item["qty"];
						$total_free_delivery_items = $total_free_delivery_items + $item_cost;
					}
				}
			}*/



			$delivery_chargeable_amount = $this->product_total_before_tax - $total_free_delivery_items - $non_shop_value;
			//echo '<br> product_free delivery_items = ' . $total_free_delivery_items;
			//echo '<br> products total - free deliery items = ' . $delivery_chargeable_amount;
			return $delivery_chargeable_amount;
		}

		function setDelivery_territory( $country_id ) {


				// to figure out delivery cost we look up the delivery zone - fromthe country_id
				// then get the total - and see which bracket it falls into
				$zone_id = db_get_single_value( 'SELECT zone_id FROM shop_country WHERE id  = ' . $country_id, 'zone_id' );
				// New Code
				// we need to consider free delivery items - like member services - on the map !
				// if we have products with no delivery - take their cost off of the the total - before this look up
				// need to get the cost off all items less the cost of free delivery items.
				$total_less_delivery = $this->basketTotalMisusFreeDeliveryItems();
				if ( $total_less_delivery == '0' ) {
					echo ' setting delivery to 0 ';
					$deliverycost = 0;
				} else {
					$q_sql = 'SELECT shipping_cost  FROM shop_shipping_rates WHERE location_id = ' . $zone_id . ' AND ' . $total_less_delivery . ' > rate_start AND ' . $total_less_delivery . ' < rate_end ';
					echo $q_sql;
					$deliverycost = db_get_single_value( $q_sql, 'shipping_cost' );
					echo '<br> del cost is ' . $deliverycost;
				}
				// die();


			$this->delivery_territory  = $id;


			if(isset($_SESSION['collection'])) {

				$deliverycost = 0;

			}


			$this->deliverycost        = $deliverycost;
			$this->shipping_total      = $deliverycost;
			$_SESSION['deliverycost']  = $deliverycost;
			$this->total_inc_delivery  = $this->total + $this->deliverycost;
			$_SESSION['total_inc_del'] = $this->total_inc_delivery;
		}

		/*     * *
		 * this creates the basket list we send to Sage Pay.
		 * We DO NOT USE THIS FOR PAYPAL or WORLDPAY
		 */
		function get_basket_str() {
			// number of lines of detail to show in sage
			// generally it is number of product + 1 line for delivery
			$str = count( $this->items ) + 1;
			// if they have a VAT number - we need to add 1 more line to say 'VAT Exempt'
			if ( trim( $_SESSION['vat_number'] ) != "" ) {
				// adding vat message AND delivery lines
				$str = count( $this->items ) + 2;
			}
			foreach ( $this->items as $item ) {
				$str .= ":" . $this->format_item_name( $item ) . ":" . $item['qty'];
				$str .= ":" . number_format( $item['price'], 2 );
				/** Price ex-Vat * */
				$str .= ":" . number_format( ( $item['price'] * $this->vatrate ) / 100, 2 );
				/** VAT component * */
				$str .= ":" . number_format( $item['price'], 2 );
				/** Item price (price + vat)  * */
				$str .= ":" . number_format( ( $item['price'] * $item['qty'] ), 2 );
				/** Line total * */
			}
			// A - (A/100 * B)
			$del    = $_SESSION['deliverycost'] - ( $_SESSION['deliverycost'] / 100 * SHOP_VAT_RATE );
			$delvat = $_SESSION['deliverycost'] * ( SHOP_VAT_RATE / 100 ); // dont add vat to del //  ($del * $_SESSION['vatrate'] ) / 100;
			$str .= ":Delivery:1:" . number_format( $del, 2 ) . ':' . number_format( $delvat, 2 ) . ':' . number_format( $del + $delvat, 2 ) . ':' . number_format( $del + $delvat, 2 );
			if ( strlen( $str ) > 5500 ) {
				// do minimized basket str
				$str = $this->minimised_basket_str();
			}
			// if they have a vat number - write the exception
			if ( trim( $_SESSION['vat_number'] ) != "" ) {
				// do we have a vat no
				$str .= ":Vat Exempt Order (Vat No. " . $_SESSION['vat_number'] . " ):1:0:0:0:0";
			}

			return $str;
		}

		function format_item_name( $item ) {
			$out = $item['name'];
			if ( $item['gender_name'] != '' ) {
				$out .= ' ' . $item['gender_name'];
			}
			if ( $item['colour_name'] != '' ) {
				$out .= ' ' . $item['colour_name'];
			}
			if ( $item['size_name'] != '' ) {
				$out .= ' ' . $item['size_name'];
			}
			return $out;
		}

		function minimised_basket_str() {
			$str = "2";
			if ( trim( $_SESSION['vat_number'] ) != "" ) {
				// adding vat message AND delivery lines
				$str = "3";
			}
			$desc = 'Large Product Order (Details in email to follow)';
			$qty  = "";
			$r1   = 0;
			$r2   = 0;
			$r3   = 0;
			$r4   = 0;
			foreach ( $this->items as $item ) {
				$qty = $qty + $item['qty']; //	$str .= ": id - " . $item['id'] . ' [cid='  . $item['colour_id'] .      '] '  .  ":" . $item['qty'];
				$r1  = $r1 + $item['price']; // $str .= ":" . number_format($item['price'],2); /** Price ex-Vat **/
				$r2  = $r2 + ( ( $item['price'] * $this->vatrate ) / 100 );  // $str .= ":" . number_format(($item['price'] * $this->vatrate) / 100 ,2); /** VAT component **/
				$r3  = $r3 + ( $item['price'] + ( ( $item['price'] * $this->vatrate ) / 100 ) );  // $str .= ":" . number_format($item['price'] + (($item['price'] * $this->vatrate)/100) ,2); /** Item price (price + vat)  **/
				$r4  = $r4 + ( $item['price'] * $item['qty'] ); // $str .= ":" . number_format( (($item['price'] + (($item['price'] * $this->vatrate)/100)) * $item['qty']) ,2); /** Line total **/
			}
			$str .= ":" . $desc . ":" . $qty . ":" . number_format( $r1, 2 ) . ":" . number_format( $r2, 2 ) . ":" . number_format( $r3, 2 ) . ":" . number_format( $r3, 2 );
			$del    = $_SESSION['deliverycost'];
			$delvat = ( $del * $_SESSION['vatrate'] ) / 100;
			$str .= ":Delivery:1:" . number_format( $del, 2 ) . ':' . number_format( $delvat, 2 ) . ':' . number_format( $del + $delvat, 2 ) . ':' . number_format( $del + $delvat, 2 );
			return $str;
		}

		/*
		 *
		 * where do we fire this from ??
		 */
		function add_to_order( $orderid ) {
			//   $this->items = $_SESSION['basket']['items'];
			//echo '<h2> this> items = </h2>';
			//var_dump($this->items);
			foreach ( $this->items as $item ) {
				// get all items in the `My Palette`
				$product_id = $item['id'];
				$quantity   = $item['qty'];
				$price      = $item['price'];
				if ( $item['size_id'] == '' ) {
					$size_id = 0;
				} else {
					$size_id = $item['size_id'];
				}
				if ( $item['gender_id'] == '' ) {
					$gender_id = 0;
				} else {
					$gender_id = $item['gender_id'];
				}
				$product_type = $item['product_type'];
				$colour_id   = $item['colour_id'];
				$size_name   = $item['size_name'];
				$gender_name = $item['gender_name'];
				$colour_name = $item['colour_name'];
				// $gross_price = $price + $commission;
				$quantity  = $item['qty'];
				$maker_tax = $item['maker_tax'];
				// going to use this as our vat vield
				// $comm_tax =   $price  * ( $_SESSION['vatrate'] / 100 );
				$comm_tax = $price * ( $_SESSION['vatrate'] / 100 );
				/// $commission = $item['commission'];
				$discount_text = $item['discount_text'];
				//
				$description = $this->format_item_name( $item );
				/****
				 *  if product type = 2 > booking
				 *  set a sub type for either ticket or product
				 *  stay with the default of 0 for a product - just set to 1 if it is a ticket
				 *
				 *
				 */
				$product_sub_type = 0;
				if ( $product_type == '2' ) {
					//   $sub_type = "ticket";
					if ( $item['sub_type'] == "ticket" ) {
						$product_sub_type = 1;
					}
				}

				// start things with the usual shop_order_item - then add specific extra entries if required

				$insert_order_item_sql = 'insert into shop_order_item (order_id, product_id, product_type, product_sub_type, description, quantity,  maker_tax, comm_tax, colour_id, discount_text,price, gender_id, size_id, size_name, gender_name, colour_name  ) ' .
				                         "values ('$orderid', '$product_id','$product_type', '$product_sub_type','$description', '$quantity',  '$maker_tax', '$comm_tax',  '$colour_id', '$discount_text', $price, $gender_id, $size_id, '$size_name', '$gender_name', '$colour_name')";
				echo $insert_order_item_sql;
				$insert_order_item_result = mysql_query( $insert_order_item_sql );
				/*************  need to record $product_type specific data here  **********/
				// donations
				if ( $product_type == '1' ) {
					// get last insert id
					$r1       = mysql_real_escape_string($item['donate_user_response1']);
					$r2       = mysql_real_escape_string($item['donate_user_response2']);
					$loc      = mysql_real_escape_string($item['donation_location']);
					$gift_aid = mysql_real_escape_string($item['gift_aid']);


					// this is a donation
					$d_sql  = " insert into shop_order_item_donate (shop_order_item_id, donate_user_response1, donate_user_response2, gift_aid, donation_location )
 values (LAST_INSERT_ID(), '$r1', '$r2' , '$gift_aid', '$loc' ) ";
					$result = mysql_query( $d_sql );
				}
				/*************  need to record $product_type specific data here **********/
				// events
				if ( $product_type == '2' ) {
				 // Don't need anything else here

				}
				/*************  need to record $product_type specific data here **********/
				// lotto
				if ( $product_type == '3' ) {
					// get last insert id
					$weeks        = $item['weeks'];
					$subscription = $item['subscription'];
					// this is a donation
					$d_sql  = " insert into shop_order_item_lottery (shop_order_item_id, weeks, subscription )
 values (LAST_INSERT_ID(), '$weeks',   '$subscription' ) ";
					$result = mysql_query( $d_sql );
				}
			}
			//die();
		}

		function removeBasketItem( $colourArray, $colour_id, $value ) {
			$i = 0;
			$j = 0;
			if ( isset( $colourArray ) ) {
				while ( sizeof( $colourArray ) > $j ) {
					$itemArr = $colourArray[$j];
					if ( $itemArr[0] == $colour_id ) {
						array_splice( $colourArray, $j, 1 );
					}
					$j = $j + 1;
				}
			}
			return $colourArray;
		}

		function setBasketItem( $colourArray, $colour_id, $value ) {
			$i           = 0;
			$j           = 0;
			$int_options = array( "options" =>
				array( "min_range" => 1, "max_range" => 9999 ) );
			if ( filter_var( $value, FILTER_VALIDATE_INT, $int_options ) == false ) {
				// value must be an int from 1 to 9999 or else exit
				return $colourArray;
			}
			if ( isset( $colourArray ) ) {
				while ( sizeof( $colourArray ) > $j ) {
					$itemArr = $colourArray[$j];
					if ( $itemArr[0] == $colour_id ) {
						$colourArray[$j][1] = $value;
						// todo: GL need to pass back message , that the values is not an int
					}
					$j = $j + 1;
				}
			}
			return $colourArray;
		}

		function incrBasketItem( $colourArray, $colour_id, $value ) {
			$i = 0;
			$j = 0;
			if ( isset( $colourArray ) ) {
				while ( sizeof( $colourArray ) > $j ) {
					$itemArr = $colourArray[$j];
					if ( $itemArr[0] == $colour_id ) {
						$colourArray[$j][1] = $colourArray[$j][1] + $value;
					}
					$j = $j + 1;
				}
			}
			return $colourArray;
		}

		function getcurrentbasketcolours( $b ) {
			$colourArray = array();
			$i           = 0;
			if ( isset( $b ) ) {
				foreach ( $b as $itemArr ) {
					while ( sizeof( $itemArr ) > $i ) {
						array_push( $colourArray, $itemArr[$i][0] );
						$i = $i + 1;
					}
				}
			}
			return $colourArray;
		}

		function product_qty( $prod_id ) {
			$qty = 0;
			foreach ( $this->items as $item ) {
				if ( $item['id'] == $prod_id ) {
					$qty = 9; // $item['qty'];
					break;
				}
			}
			return $qty;
		}

		/************************************************************************************
		 * Figure out what items might be discounted from the current basket and at what rates
		 ************************************************************************************ */
		function calculate_bulk_discounts() {
			//$this->items = $_SESSION['basket']['items'];
			$ids_with_discount[] = array();
			foreach ( $this->items as $item ) {
				if ( $item[bulk_discount_item1] > 0 || $item[bulk_discount_item2] > 0 || $item[bulk_discount_item3] > 0 ) {
					// this item potentially has a discount.
					// check if we have a product with this id , if so inc the qty field
					// echo ' here ';
					// >>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
					//  need to shoe horn in this ... bulk_discount_id
					// echo " >> item > " . $item['bulk_discount_id'];
					if ( $this->checkInArray( $discountitems, $item['bulk_discount_id'] ) ) {
						// need to increment item in array
						// keep the & infront of $ditem value to set its value - some dark magic there !
						foreach ( $discountitems as &$ditem ) {
							//echo "<br>  ditem > " . $ditem['bulk_discount_id'];
							//echo " >> item > " . $item['bulk_discount_id'];
							if ( $ditem['bulk_discount_id'] == $item['bulk_discount_id'] ) {
								$ditem['qty'] = ( $ditem['qty'] + $item['qty'] );
							}
						}
					} else {
						// purposely letout the colour_id field for simplicity
						$discountitems[] = array(
							'bulk_discount_id' => $item['bulk_discount_id'],
							'qty' => $item['qty'],
							'bulk_discount_item1' => $item['bulk_discount_item1'],
							'bulk_discount_discount1' => $item['bulk_discount_discount1'],
							'bulk_discount_item2' => $item['bulk_discount_item2'],
							'bulk_discount_discount2' => $item['bulk_discount_discount2'],
							'bulk_discount_item3' => $item['bulk_discount_item3'],
							'bulk_discount_discount3' => $item['bulk_discount_discount3'],
						);
					}
				} // end if
			} // end foreach
			// var_dump($discountitems);
			foreach ( $discountitems as $item ) {
				// if the qty of the item gets us a discount
				$percentage = 0;
				if ( ( $item['qty'] >= $item['bulk_discount_item1'] ) && ( $item['bulk_discount_item1'] > 0 ) ) {
					//echo 'do discount 1 , qty  is '. $item['qty'] . ' discount is ' . $item['bulk_discount_discount1'];
					$percentage = $item['bulk_discount_discount1'];
				}
				if ( ( $item['qty'] >= $item['bulk_discount_item2'] ) && ( $item['bulk_discount_item2'] > 0 ) ) {
					//echo 'do discount 2 , qty  is '. $item['qty']  . ' discount is ' . $item['bulk_discount_discount2'];
					$percentage = $item['bulk_discount_discount2'];
				}
				if ( ( $item['qty'] >= $item['bulk_discount_item3'] ) && ( $item['bulk_discount_item3'] > 0 ) ) {
					//echo 'do discount 3 , qty  is '. $item['qty']  . ' discount is ' . $item['bulk_discount_discount3'];
					$percentage = $item['bulk_discount_discount3'];
				}
				if ( $percentage > 0 ) {
					$discounteditems[] = array(
						'bulk_discount_id' => $item['bulk_discount_id'],
						'percentage' => $percentage,
					);
				}
				// see what discount that gives us ...
				//var_dump($discounteditems);
				// then apply to all items in basket with that id
			}
			// should be a list of product ids and the percentage reduction
			// now apply it to the $this->items array
			foreach ( $this->items as &$item ) {
				if ( is_array( $discounteditems ) ) {
					foreach ( $discounteditems as &$ditem ) {
						if ( $ditem['bulk_discount_id'] == $item['bulk_discount_id'] ) {
							//  reduce price by percentage
							$item['price'] = sprintf( "%01.2f", $item['price'] ); // price without vat
							// $item['commission_tax'] = ($item['price'] / 100) * $ditem['percentage'];
							$discount      = ( $item['price'] / 100 ) * $ditem['percentage'];
							$item['price'] = sprintf( "%01.2f", ( $item['price'] - $discount ) ); // price without vat
							//$item['gross_price'] =  sprintf("%01.2f",  $item['price'] + (($item['price'] / 100) * $ditem['percentage']));
							$item['row_price']     = $item['price'] * $item['qty'];
							$item['discount_text'] = " - Multi Purchase discount " . $ditem['percentage'] . "% off";
							//  . "<br> bulk_discount_id = " .  $item['bulk_discount_id'];
							//  echo '<br> now price is ' . $item['price'] ;
						}
					}
				}
			}
			// TODO: here
			// need to recalculate basket totals
			$total_inc_vat = 0;
			foreach ( $this->items as &$item ) {
				$total_qty += $item['qty'];
				//$tax_total += $item['commission_tax']; //  + $commission_tax;
				$total += $item['row_price']; // price * qty [ no tax]
			}
			$this->total_qty = $total_qty;
			$this->tax_total = $tax_total;
			$this->total     = $total;
			//	$this->total = $this->total + $_SESSION['deliverycost']  +  $tax_total;
			//  echo $total;
			// var_dump($this->items);
		}

// end func
		function checkInArray( $discountitems, $id ) {
			if ( sizeof( $discountitems ) > 0 ) {
				foreach ( $discountitems as $item ) {
					if ( $item['bulk_discount_id'] == $id ) {
						return true;
					}
				}
			} else {
				return false;
			}
			return false;
		}
	}

	// end class
?>
