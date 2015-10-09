<?php

	class products {

		public $product_list;
		public $pagination;
		public $sql;
		static public $page_name;

		function products() {
			$this->pagination = new pagination( PRODUCTS_PER_PAGE );
			$this->sql        = "select SQL_CALC_FOUND_ROWS * from shop_product p where published = 1";
		}

		function get_product( $product_id ) {
			$sql = "SELECT sbd.*, p.*, 1
FROM shop_product p
LEFT JOIN shop_bulk_discount sbd ON sbd.id = p.bulk_discount_id
WHERE p.id = '$product_id'";
			$result = mysql_query( $sql );
			if ( mysql_numrows( $result ) > 0 ) {
				$row              = mysql_fetch_array( $result );
				$background_image = ( BACKGROUND_TYPE == 'light' ) ? 'gallerysquaresmall_darkbg.png' : 'gallerysquaresmall_lightbg.png';
				$thumb            = $row['thumb'];
				//   if (isset($_SESSION['isTrade'])) {
				//      $price = $row['trade_price'];
				//   } else {
				$price = $row['price'];
				//   }
				$danc = $row['display_as_non_colour'];
				if ( trim( $danc ) == '' ) {
					$danc = 0;
				}
				$otherItems = "";
				// if (isset($_SESSION['isTrade'])) {
				if ( SHOP_USE_BULK_BUY ) {
					$otherItems = $this->otherItemsInDiscountGroup( $row['bulk_discount_id'], $product_id );
					if ( $row['dis1'] > 0 ) {
						// this product has a bulk discount promotion
						// make some text up to explain .....
						$offer = "<div id='bulk_discount' >This product has a bulk discount promotion. When purchasing " . $row['items1'] .
						         "  items you will recieve a " . $row['dis1'] . "% discount. ";
						// append string wth other levels if available
						if ( $row['dis2'] > 0 ) {
							$offer .= "When purchasing " . $row['items2'] . " items you will recieve a " . $row['dis2'] . "% discount. ";
						}
						if ( $row['dis3'] > 0 ) {
							$offer .= "When purchasing " . $row['items3'] . "  items you will recieve a " . $row['dis3'] . "% discount. ";
						}
						$offer .= $otherItems;
						$offer .= "</div>";
					}
				}
				$product = array(
					'id' => $row['id'],
					'name' => $row['name'],
					'thumb' => $thumb,
					'summary' => $row['summary'],
					'description' => $row['description'],
					'price' => $price,
					'stock_code' => $row['stock_code'],
					'stock_level' => $row['stock_level'],
					'net_price' => $row['price'],
					'trade_only' => $row['trade_only'],
					'display_as_non_colour' => $danc,
					'special_offer' => $offer
				);
			}
			return $product;
		}

		function otherItemsInDiscountGroup( $id, $product_id ) {
			$sql    = "SELECT `name`, `id`  FROM shop_product WHERE  bulk_discount_id =" . $id;
			$result = mysql_query( $sql );
			if ( mysql_numrows( $result ) > 0 ) {
				while ( $row = mysql_fetch_array( $result ) ) {
					if ( $row['id'] != $product_id ) {
						$out .= "<a href='" . $row['id'] . "' > " . $row['name'] . " </a> ,";
					}
				}
			}
			// drop off last comma
			if ( $out == "" ) {
			} else {
				$out = substr( $out, 0, strlen( $out ) - 1 );
				$out = "Other products that use can buy in conjunction with this discount are " . $out;
			}
			return $out;
		}

		function get_products( $paginate = true ) {
			$sql = $this->sql;
			// might already have an order condition like in the search results page
			if ( strpos( $sql, 'order by' ) === false ) {
				$order = "";
				if ( isset( $_GET['order'] ) ) {
					// prevent injection
					if ( $_GET['order'] == 'asc' ) {
						$order = 'asc ';
					}
					if ( $_GET['order'] == 'desc' ) {
						$order = 'desc ';
					}
				}
				if ( $order == "" ) {
					$order = 'asc ';
				}
				//       if (isset($_SESSION['isTrade'])) {
				//       $orderby = " order by p.trade_price " . $order;
				//   } else {
				$orderby = " order by p.name " . $order;
				//   }
			} else {
				$orderby = '';
			}
			// if trade user order by trade price
			// else price
			if ( $paginate ) {
				$start = $this->pagination->page_start_row();
				$rows  = PRODUCTS_PER_PAGE;
				$sql .= $orderby;
				$sql .= " limit $start, $rows";
			}
			_d( $sql );



			$result       = mysql_query( $sql );
			$sql_count    = 'select FOUND_ROWS() as rowcount';
			$result_count = mysql_query( $sql_count );
			$row_count    = mysql_fetch_array( $result_count );
			if ( $paginate ) {
				$this->pagination->set_pages_val( $row_count['rowcount'] );
			}
			if ( mysql_numrows( $result ) > 0 ) {
				while ( $row = mysql_fetch_array( $result ) ) {
					$background_image = ( BACKGROUND_TYPE == 'dark' ) ? 'gallerysquaresmall_darkbg.png' : 'gallerysquaresmall_lightbg.png';
					$thumb            = $row['thumb'];
					$page_name        = products::$page_name;
					$product_link     = "/$page_name/product/{$row['page_name']}";
					//   if (isset($_SESSION['isTrade'])) {
					//     $price = $row['trade_price'];
					//  } else {
					// ADDING VAT
					// vat rate as a percentage
					$vat_rate = db_get_single_value( "SELECT vat_rate FROM shop_vat_zone WHERE id = 1", 'vat_rate' );
					$price    = $row['price']; // + ($row['price'] * ($vat_rate / 100));
					//  }
					$cat_parentname_and_pagename = $row['cat_parentname_and_pagename'];
					//echo $cat_parentname_and_id;
					$arr = explode( "|", $cat_parentname_and_pagename );
					// echo $arr[0];
					$cat_parent_name     = $arr[0];
					$cat_parent_pagename = $arr[1];
					$products[]          = array(
						'id' => $row['id'],
						'link' => $product_link,
						'name' => $row['name'],
						'thumb' => $thumb,
						'summary' => $row['summary'],
						'price' => $price,
						'stock_code' => $row['stock_code'],
						'level' => $row['level'],
						'commission' => $row['commission'],
						'net_price' => $row['price'],
						'trade_only' => $row['trade_only'],
						'cat_pagename' => $row['cat_pagename'],
						'cat_name' => $row['cat_name'],
						'cat_parent_name' => $cat_parent_name,
						'cat_parent_pagename' => $cat_parent_pagename,
						'isRoot' => $row['isRoot'],
						'thumb_preserve_toggle' => $row['thumb_preserve_toggle'],
						'thumb_preserve_small' => $row['thumb_preserve_small'],
						'rgb' => $row['rgb']
					);
				};
			}
			$this->product_list = $products;
		}

		function get_categories() {
			$categories      = array();
			$categories[- 1] = 'All';
			$sql             = 'select * from shop_category where special = 0 order by order_num';
			$result          = mysql_query( $sql );
			while ( $row = mysql_fetch_array( $result ) ) {
				$categories[$row['id']] = $row['name'];
			}
			return $categories;
		}
	}