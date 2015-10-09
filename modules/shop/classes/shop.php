<?php

	/**
	 * Genric class shop helper
	 *
	 * @author glen
	 */
	class shop {

		function getShopPageType( $name_parts ) {
			global $name_parts;
			if ( is_array( $name_parts ) ) {
				$part_count = count( $name_parts );
				if ( $part_count > 1 ) {
					$sub_page_name = $name_parts[1];
					if ( $sub_page_name == 'search' ) {
						$shop_page_type = 'search';
					}
					if ( $sub_page_name == 'results' ) {
						$shop_page_type = 'results';
					}
					if ( $sub_page_name == 'basket' ) {
						$shop_page_type = 'basket';
					}
					if ( $sub_page_name == 'subscribe' ) {
						$shop_page_type = 'subscribe';
					}
					if ( $sub_page_name == 'subscribe_confirm' ) {
						$shop_page_type = 'subscribe_confirm';
					}
					if ( $sub_page_name == 'test' ) {
						$shop_page_type = 'test';
					} else if ( $sub_page_name == 'category' ) {
						if ( $part_count > 2 ) {
							$cat_page_name  = $name_parts[2];
							$shop_page_type = 'category';
						}
					} else if ( $sub_page_name == 'product' ) {
						$shop_page_type = 'product';
						if ( $part_count > 2 ) {
							$product_link      = "/$page_name/{$name_parts[1]}/{$name_parts[2]}/";
							$product_id        = db_get_single_value( "select id from shop_product where page_name ='{$name_parts[2]}'" );
							$product_page_name = $name_parts[2];
						}
					} else if ( ( $sub_page_name == 'checkout' ) ) {
						$shop_page_type = 'checkout';
					} else if ( ( $sub_page_name == 'confirm' ) ) {
						$shop_page_type = 'confirm';
					} else if ( ( $sub_page_name == 'success' ) ) {
						$shop_page_type = 'success';
					} else if ( ( $sub_page_name == 'failed' ) ) {
						$shop_page_type = 'failed';
					} else if ( ( $sub_page_name == 'failure' ) ) {
						$shop_page_type = 'failed';
					} else if ( ( $sub_page_name == 'notify' ) ) {
						$shop_page_type = 'ipn';
					} else if ( ( $sub_page_name == 'ipn' ) ) {
						$shop_page_type = 'ipn';
					} else if ( ( $sub_page_name == 'cancel' ) ) { // paypal cancel
						$shop_page_type = 'cancel';
					} else if ( ( $sub_page_name == 'donate' ) ) { // fast forward checkout donations
						$shop_page_type = 'donate';
					} else if ( ( $sub_page_name == 'collection' ) ) { // set delivery type to collection
						$shop_page_type = 'collection';
					}
				}
			}
			return array( $shop_page_type, $cat_page_name, $product_link, $product_id, $product_page_name );
		}

		function insertNewRecurringLotteryPayment( $futurePayId, $transId, $post_data, $serialised_post, $transStatus ) {
			//echo "<h2> insertNewRecurringLotteryPayment fpid= " . $futurePayId . " </h2>";
			$last_order_line_id = $this->getLastOrderItemIdFromFuturePayId( $futurePayId );
			//cs_log("last order line was '{$last_order_line_id}' ",__FILE__,$_POST);
			$new_order_line_id = $this->duplicateShopOrderItem( $last_order_line_id );
			//cs_log("new order line was '{$new_order_line_id}' ",__FILE__,$_POST);
			$this->duplicateShopOrderItemLottery( $futurePayId, $new_order_line_id, $transId, $post_data, $serialised_post, $transStatus );
			// trigger an email ??
			// might need a flag in the email to say its a recurring payment too
		}


		private function getLastOrderItemIdFromFuturePayId( $futurePayId ) {
			$sql = "SELECT soi.id FROM shop_order_item_lottery soil
INNER JOIN shop_order_item soi ON soi.id = soil.`shop_order_item_id`
WHERE soil.`futurePayId` = " . $futurePayId . " order by 1 desc limit 1";
			//echo "<br> SQL :: "  . $sql;
			return db_get_single_value( $sql );
		}

		private function duplicateShopOrderItem( $order_item_id ) {
			// that every field except the timestamp ...
			$duplicate_shop_order_item_sql = "INSERT INTO `shop_order_item`
            (`order_id`, `product_type`, `product_sub_type`, `product_id`, `quantity`, `sale_id`, `_net_price`,
            `maker_tax`, `comm_tax`, `commission`, `discount_text`, `gross_price`, `price`, `description`,
            `colour_id`, `size_id`, `gender_id`, `colour_name`, `size_name`, `gender_name`)
SELECT `order_id`, `product_type`, `product_sub_type`, `product_id`, `quantity`, `sale_id`, `_net_price`,
`maker_tax`, `comm_tax`, `commission`, `discount_text`, `gross_price`, `price`, `description`,
`colour_id`, `size_id`, `gender_id`, `colour_name`, `size_name`, `gender_name`
FROM `shop_order_item` WHERE id = " . $order_item_id;
			//echo "<br> SQL :: "  . $duplicate_shop_order_item_sql;
			// you need to know the last insert id to use on the shop_oder_item_lottery
			$result                 = mysql_query( $duplicate_shop_order_item_sql );
			$new_shop_order_item_id = mysql_insert_id();
			return $new_shop_order_item_id;
		}

		function duplicateShopOrderItemLottery( $futurePayId, $new_order_line_id, $transId, $post_data, $serialised_post, $transStatus ) {
			// get the last lottery item, that relates to this future pay entry to use to aid duplication
			$last_shop_order_item_lottery_id = db_get_single_value( "SELECT soil.id FROM shop_order_item_lottery soil WHERE soil.`futurePayId` = " . $futurePayId . " ORDER BY 1 DESC" );
			//echo "<br> the last lottery entry for this future pay item should have id = " . $last_shop_order_item_lottery_id;
			// this duplicates the previous order_line for lottery and connects it to the  new entry in shop_order_item
			$duplicate_shop_order_item_lottery_sql = "INSERT INTO `shop_order_item_lottery`
            (`shop_order_item_id`, `weeks`, `tickets`, `subscription`, `futurePayId`, `transId`, `post_data`, `serialised_post`, `transStatus`)
SELECT '" . $new_order_line_id . "', `weeks`, `tickets`, `subscription`, '{$futurePayId}', '{$transId}', '{$post_data}', '{$serialised_post}', '{$transStatus}'
FROM `shop_order_item_lottery` WHERE id =" . $last_shop_order_item_lottery_id;
			//echo "<br> SQL :: "  . $duplicate_shop_order_item_lottery_sql;
			mysql_query( $duplicate_shop_order_item_lottery_sql );
		}
	}