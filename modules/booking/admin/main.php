<?php

	include '../../../admin/classes/template.php';

	class main extends template {
		function main() {
			$this->template();

			$this->custom_list_sql = 'select SQL_CALC_FOUND_ROWS *  from booking order by end_date desc ';

			$this->debug_log               = false;
			$this->php_debug               = false;
			/* #module specific */
			$this->table = 'booking';
			/* #module specific */
			$this->group_name = 'Bookings';
			/* #module specific */
			$this->single_name  = 'Booking Event';
		//	$this->ordered      = true;
			$this->order_clause = ' order_num desc ';
			$this->singular     = 'a';
			$this->hideable     = true;
			//$this->list_top_text = "The case study at the top of the list will be featured on the home page";
			$this->javascript_file = array( '/modules/booking/admin/js/admin.js', '/modules/map/js/maphelper.js' );
			$this->ToolbarSet      = 'Default';
			$this->has_page_name   = true;
			$this->buttons         = array(
				'edit' => array( 'text' => 'add', 'type' => 'standard_edit' ),
				'hide' => array( 'text' => 'hide', 'type' => 'standard_hide' ),
				// need to dynamically initiate this button
				//'featured' => array( 'text' => 'hide', 'type' => 'function', 'function' => 'set_featured' ),
				'delete' => array( 'text' => 'delete', 'type' => 'standard_delete' ),
				//'event_products' => array( 'type' => 'function', 'function' => 'event_products' ),
				'event_ticket_types' => array( 'type' => 'function', 'function' => 'event_ticket_types' ),
				'event_guestlist' => array( 'type' => 'function', 'function' => 'event_guestlist' ),
				'move' => array( 'text' => 'move', 'type' => 'standard_move' ),
			);
			$this->fields          = array(
				'title' => array( 'name' => 'Title&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;', 'formtype' => 'text', 'list' => true, 'required' => true, 'primary' => true ),
				'description' => array( 'name' => 'Description', 'formtype' => 'textarea', 'list' => false, 'rows' => 3, 'cols' => 60, 'required' => true ),

				'category_id' => array('name' => 'Category', 'formtype' => 'lookup', 'required' => false, 'function' => 'category_lookup'),



				//	'hospice_event' => array( 'name' => 'Hospice Event', 'formtype' => 'lookup', 'function' => 'hospiceeventtypelookup' ),
			//	'event_type' => array( 'name' => 'Event Type', 'formtype' => 'lookup', 'function' => 'eventtypelookup' ),
				'start_date' => array( 'name' => 'Start Date', 'formtype' => 'date', 'required' => true ),
				'end_date' => array( 'name' => 'End Date', 'formtype' => 'date', 'required' => true ),
			//	'persist_with_removal_date' => array( 'name' => 'Persist with Removal Date', 'formtype' => 'checkbox' ),
			//	'removal_date' => array( 'name' => 'Removal Date from Website', 'formtype' => 'date', 'required' => true ),
				'event_time' => array( 'name' => 'Event time (optional)', 'formtype' => 'text', 'required' => false ),
				'speaker_info' => array( 'name' => 'Speaker info (optional)', 'formtype' => 'text', 'required' => false ),
				// for ticketed events only
				'tickets_available' => array( 'name' => 'Number of tickets', 'formtype' => 'text', ),
			//	'parental_consent_required' => array( 'name' => 'Parental Consent Required', 'formtype' => 'checkbox' ),
				// optional email message
			//	'email_message' => array( 'name' => 'Email Message (include the full address when linking to files)', 'formtype' => 'fckhtml' ),

			// mapping bits
			//	'show_map' => array( 'name' => 'Show Map', 'formtype' => 'checkbox' ),
			//	'mapping_address1' => array( 'name' => 'Address Line 1', 'formtype' => 'text', 'required' => false ),
			//	'mapping_address2' => array( 'name' => 'Address Line 2', 'formtype' => 'text', 'required' => false ),
			//	'mapping_address3' => array( 'name' => ' Address Line 3', 'formtype' => 'text', 'required' => false ),
			//	'mapping_postalcode' => array( 'name' => 'Postcode', 'formtype' => 'text', 'required' => false ),
			//	'mapping_country_id' => array( 'name' => 'Country', 'formtype' => 'lookup', 'function' => 'mappingCountryLookup', 'required' => true ),
			//	'maptools' => array( 'name' => 'Mapping Tools', 'formtype' => 'lookup', 'not_field' => true, 'function' => 'maptools' ),
			//	'lon' => array( 'name' => 'Mapping - Lon', 'formtype' => 'shorttext', 'required' => false ),
			//	'lat' => array( 'name' => 'Mapping - Lat', 'formtype' => 'shorttext', 'required' => false ),
				'thumb' => array( 'name' => 'Thumbnail', 'formtype' => 'image', 'list' => false, 'required' => true, 'size' => 2 ),
				'page_image' => array( 'name' => 'Header image (optional)', 'formtype' => 'image', 'required' => false, 'size' => 2 ),
				'body' => array( 'name' => 'Main Content', 'formtype' => 'fckhtml', 'list' => false, 'required' => true ),
				'page_name' => array( 'name' => 'Page Name', 'formtype' => 'hidden', 'keepfirst' => true ),
			//	'description_seo' => array( 'name' => 'Description<br>(for SEO)', 'formtype' => 'lookup', 'not_field' => true, 'function' => 'getSEODescription' ),
			//	'clear_custom_seo' => array( 'name' => 'Clear Custom SEO<br> (Check this box then submit)', 'formtype' => 'checkbox', 'not_field' => true ),
			);
			// this will populate the link table - categories
			/* #module specific */
			$this->links = array( 'category' => array( 'link_table' => 'booking_category_lookup', 'table' => 'booking_category', 'name' => 'title', 'primary' => 'item_id', 'foreign' => 'category_id' ) );
		}

		// add gallery bits here
		function onload() {

			if ( SITE_HAS_DONATE ) {
				$this->fields['donate'] = array('name' => 'Donation', 'formtype' => 'lookup', 'required' => false, 'function' => 'donatelookup');
			}
			// add the gallery chooser dropdown if appropriate
			if ( BOOKING_HAS_INLINE_GALLERIES ) {
				$this->fields['gallery'] = array( 'name' => 'Gallery', 'formtype' => 'lookup', 'required' => false, 'function' => 'gallerylookup' );
			}
			$this->paginated               = true;
			$this->paginate_items_per_page = BOOKINGS_ADMIN_PAGINATE_ITEMS_PER_PAGE;



		}

		function show_row_title( $content_row, $row_level, $row_title_class ) {
			parent::show_row_title( $content_row, $row_level, $row_title_class );
			echo '<div class="newseventdatefieldactive">' . date( 'd/m/Y', strtotime( $content_row['end_date'] ) ) . '</div>';
		}

		function category_lookup($id)
		{
			$sql = 'select * from booking_category order by order_num';
			$result = mysql_query($sql);
			// echo $sql;
			$out = '<select name="category_id">';
			while ($row = mysql_fetch_array($result)) {
				$selected = ($id == $row[id]) ? ' selected="selected"' : '';
				$out .= '<option value="' . $row['id'] . '"' . $selected . ' >' . $row['title'] . '</option>';
			}
			$out .= '</select>';

			return $out;
		}

		function donatelookup($id) {
			$sql = 'select * from donate where published=1 order by title';
			$result = mysql_query($sql);
			//	echo $sql;
			$out = '<select id="donate" name="donate">';
			$selected = ( $id == 0 ) ? ' selected="selected"' : '';
			$out .= '<option value="0"' . $selected . ' >No Donate</option>';
			while ($row = mysql_fetch_array($result)) {
				$selected = ($id == $row[id]) ? ' selected="selected"' : '';
				$out .= '<option value="' . $row['id'] . '"' . $selected . ' >' . $row['title'] . '</option>';
			}
			$out .= '</select>';

			return $out;
		}


		function event_guestlist( $id ) {
			$row = db_get_single_row( "SELECT hospice_event, event_type FROM booking WHERE id=" . $id );
			if ( $row['hospice_event'] == '0' && $row['event_type'] != '3' ) {
				$href  = "/modules/shop/admin/reports/guestlist.php?parent_id=" . $id;
				$label = "guest list";
				$this->cms_admin_button( $href, 'contentbutton', $label, $href );
			}
		}

		function event_products( $id ) {
			//	$new_count = db_get_single_value("SELECT COUNT(*) FROM memorybook_memory WHERE  new = 1 and  memorybook_id={$id}");
			$href  = "eventproducts.php?parent_id=" . $id;
			$label = "products for event";
			//	if($new_count > 0){
			//		$label .= " [_" . $new_count . '_new]__' ;
			//	}
			$this->cms_admin_button( $href, 'contentbutton', $label, $href );
		}

		function event_ticket_types( $id ) {
			$row = db_get_single_row( "SELECT hospice_event, event_type FROM booking WHERE id=" . $id );
			if ( $row['hospice_event'] == '0' && $row['event_type'] != '3' ) {
				$href  = "tickettypes.php?parent_id=" . $id;
				$label = "ticket types for event";
				$this->cms_admin_button( $href, 'contentbutton', $label, $href );
			}
		}

		function maptools() {
			$helpers = "<br>After updating the mapping address, we need to check that we can find it on the map.<a style='cursor:pointer; color:#368; text-decoration: underline;' onclick='updategeocode_primer()'>Check the Location Now</a>";
			$helpers = $helpers . "<br> To see your location on the map, or to resolve a problem with that location, try the <a style='cursor:pointer; color:#368; text-decoration: underline; ' onclick='openMapTool()'>Map Location Tool</a>";
			$helpers = $helpers . "<br>";
			return $helpers;
		}

		function mappingCountryLookup( $id ) {
			return $this->lookup( 'shop_country', 'mapping_country_id', $id, 'name' );
		}

		function hospiceeventtypelookup( $id ) {
			$out = '<select name="hospice_event_ddl">';
			if ( $id == "0" ) {
				$selected = " selected ";
			} else {
				$selected = "   ";
			}
			$out .= '<option value="0"' . $selected . ' > Hospice Event </option>';
			if ( $id == "1" ) {
				$selected = " selected ";
			} else {
				$selected = "   ";
			}
			$out .= '<option value="1"' . $selected . ' > Community Event </option>';
			$out .= '</select>';
			return $out;
		}

		function eventtypelookup( $id ) {
			//$sql = 'select * from documents_video_type order by title';
			//$result = mysql_query($sql);
			// echo $sql;
			$out = '<select name="event_type_ddl">';
			if ( $id == "1" ) {
				$selected = " selected ";
			} else {
				$selected = "   ";
			}
			$out .= '<option value="1"' . $selected . ' > Ticketed Event </option>';
			if ( $id == "2" ) {
				$selected = " selected ";
			} else {
				$selected = "   ";
			}
			$out .= '<option value="2"' . $selected . ' > Registration Event </option>';
			if ( $id == "3" ) {
				$selected = " selected ";
			} else {
				$selected = "   ";
			}
			$out .= '<option value="3"' . $selected . ' > Info only (no booking) </option>';
			$out .= '</select>';
			return $out;
		}

		function gallerylookup( $id ) {
			$sql    = 'select * from gallery order by 1 asc';
			$result = mysql_query( $sql );
			//	echo $sql;
			$out      = '<select id="gallery" name="gallery">';
			$selected = ( $id == 0 ) ? ' selected="selected"' : '';
			$out .= '<option value="0"' . $selected . ' >No Gallery</option>';
			while ( $row = mysql_fetch_array( $result ) ) {
				$selected = ( $id == $row[id] ) ? ' selected="selected"' : '';
				$out .= '<option value="' . $row['id'] . '"' . $selected . ' >' . $row['title'] . '</option>';
			}
			$out .= '</select>';
			return $out;
		}

		function get_form_data() {
			$this->module_id = db_get_single_value( "select id from module where constant = 'SITE_HAS_BOOKING'", "id" );
			parent::get_form_data();
		}

		function getSEODescription( $id ) {
			$module_id = $this->module_id;
			if ( isset( $_GET['id'] ) ) {
				$id = $_GET['id'];
			}
			$sql  = "SELECT description FROM metatag WHERE ext_id ='{$id}' AND module_id = '{$module_id}'";
			$desc = db_get_single_value( $sql );
			return "<textarea rows=4 cols=50 name=description_seo>{$desc}</textarea>";
		}

		function get_crumbs( $page ) {
			if ( $page == '' ) {
				return "<b>{$this->single_name} Admin</b>";
			} else {
				return "<a href='main.php'>{$this->single_name} Admin</a> > <b>$page</b>";
			}
		}

		function category_checklist( $id, $fieldname, $field ) {
			$checklink = $this->links[$field['link']];
			/* #module specific */
			$linksql     = "SELECT t.id, t.title, l.item_id FROM booking_category t
LEFT OUTER JOIN booking_category_lookup l ON t.id = l.category_id AND l.item_id = '$id' order by title";
			$linkresult  = mysql_query( $linksql );
			$template    = '<span class="form-checkbox"><input type=checkbox name=%s[] %s value=%s>%s</span>';
			$specialflag = 1;
			$inner       = '';
			while ( $linkrow = mysql_fetch_array( $linkresult ) ) {
				if ( $specialflag ) {
					if ( $linkrow['special'] == 0 ) {
						$specialflag = 0;
					}
				}
				$checked = ( $linkrow['item_id'] ) ? "checked" : "";
				$inner .= sprintf( $template, $fieldname, $checked, $linkrow['id'], $linkrow['title'] );
			}
			printf( '<tr valign=top><td>%s</td><td><div class="form-checkbox-group">%s</div></td></tr>', $field['name'], $inner );
		}

		function process_submit( $id, $parent_id = false ) {
			$module_id = $this->module_id;
			$result    = parent::process_submit( $id, $parent_id );
			if ( $id == '' ) {
				// insert into tags
				$id  = $this->id;

				// if the record wasn't create - if blocked by validator (so had missing fields)
				// don't create a metatag record with 0 as id
				if($id != ''){
					$sql = "insert into `metatag` (`ext_id`, `title`, `description`, `keywords`, `module_id`)
values ( '{$id}', '{$_REQUEST['title']}', '{$_REQUEST['description_seo']}', 'keywords', '{$module_id}'); ";
				}else{
					$sql = "";
				}


			} else {
				// check if this item already has a tage entry
				$count = db_get_single_value( "SELECT count(*) FROM metatag WHERE ext_id = '{$id}' AND module_id = '{$module_id}'" );
				if ( $count > 0 ) {
					// update tags
					$sql = " UPDATE `metatag` SET `title` = '{$_REQUEST['title']}', `description` = '{$_REQUEST['description_seo']}', `keywords` = 'keywords'  where module_id ='{$module_id}'  and ext_id = '{$id}'";
				} else {
					$sql = "insert into `metatag` (`ext_id`, `title`, `description`, `keywords`, `module_id`)
values ( '{$id}', '{$_REQUEST['title']}', '{$_REQUEST['description_seo']}', 'keywords', '{$module_id}'); ";
				}
			}
			mysql_query( $sql );
			if ( $_REQUEST['clear_custom_seo'] == '1' ) {
				echo $_REQUEST['clear_custom_seo'];
				mysql_query( "delete from metatag where ext_id = '{$id}' AND module_id = '{$module_id}'" );
			}
			return $result;
		}
	}

	$template   = new main();
	$main_page  = 'index.php';
	$main_title = 'Return to main page';
	/* #module specific */
	$admin_tab = "booking";
	/* #module specific */
	$second_admin_tab = "booking";

	include 'second_level_navigation.php';
	include( "../../../admin/template.php" );

?>


