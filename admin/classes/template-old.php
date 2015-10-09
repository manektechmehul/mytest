<?php

	$base_path = $_SERVER['DOCUMENT_ROOT'];
	include( "$base_path/php/functions/form_functions.php" );
	include( "$base_path/php/classes/pagination.php" );
	include( "$base_path/php/password/PasswordHelper.php" );

	class template {

		// Main table for the module to admin
		var $table;
		var $group_name;
		// names for gui
		var $singular;
		var $single_name;
		// the array that the editable page fields will be added to
		var $fields;
		// list of buttons to display when in listing mode
		var $buttons;
		// message to display at the top of the listing page
		var $list_top_text;
		// message to display at the bottom of the listing page
		var $list_bottom_text;
		// ordered the items in the list
		// this will add up/down arrow in the listing
		//
		// this requires a order_num field in the table
		var $ordered;
		// customise the order sql query
		var $order_clause;
		// items can be deleted - add a delete button ?
		var $deletable;
		var $hideable;
		// custom sql where condition
		var $where_clause;
		var $data; // ???
		var $parent_child;
		var $parent_id_name;
		var $invalid_page_names;
		var $delete_field;
		var $ToolbarSet;
		// not sure if this works
		var $paginated;
		// this will limit the number of items in the list. It will then hide the add item button when it reaches the maximum number
		var $max_items;
		// ??
		var $min_items;
		var $javascript_file;
		// custom sql for listing items
		var $custom_list_sql;
		// Item counter - not sure
		var $_acc;
		var $page_self;
		var $defaultTextAreaRows;
		var $defaultTextAreaCols;
		var $has_page_name;
		// dev flags - show the debug log of sql statements
		var $debug_log;
		// switch on the php debug to screen
		var $php_debug;
		// set in the initialised overload
		var $module_id;
		var $override_cancel_button;

		function template() {
			$this->debug_log              = false;
			$this->php_debug              = false;
			$this->override_cancel_button = false;
			$this->invalid_page_names     = array();
			$this->javascript_file        = '';
			$this->ordered                = "";
			$this->order_clause           = '';
			$this->list_top_text          = "";
			$this->grouping_two_levels    = false;
			$this->list_bottom_text       = '';
			//sprintf ("<a href=\"content_admin.php\" onMouseOut=\"MM_swapImgRestore()\" onMouseOver=\"MM_swapImage('defaultbacktocontent','','/admin/images/buttons/cmsbutton-Back_to_Content_Admin-over.gif',0)\"><img style='border:none' src='./images/buttons/cmsbutton-Back_to_Content_Admin-off.gif' name='defaultbacktocontent'></a>", $PHP_SELF);
			$this->hideable            = false;
			$this->deletable           = true;
			$this->list_top_text       = "";
			$this->max_items           = 0;
			$this->min_items           = 0;
			$this->ToolbarSet          = 'Basic';
			$this->parent_child        = false;
			$this->paginated           = false;
			$this->where_clause        = '';
			$this->delete_field        = '';
			$this->parent_id_name      = 'parent_id';
			$this->defaultTextAreaRows = 8;
			$this->defaultTextAreaCols = 90;
			$this->has_page_name       = false;
			$this->buttons = array(
				'edit' => array( 'text' => 'add', 'type' => 'standard_edit' ),
				'hide' => array( 'text' => 'hide', 'type' => 'standard_hide' ),
				'delete' => array( 'text' => 'delete', 'type' => 'standard_delete' ),
				'move' => array( 'text' => 'move', 'type' => 'standard_move' ),
			);
		}

		function onload() {
			// this is here to overload for the module admins
			// at this point the constants will be available and can add items to the fields array easily
			/*
			 *
			 if(SHOP_USE_SIZE){
			   $this->fields['size'] = array('name' => 'Gender', 'formtype' => 'lookup', 'required' => false, 'function' => 'sizelookup');
			 }
			 *
			 *
			 */
		}

		function set_featured( $id ) {
			$featured  = db_get_single_value( 'select featured from ' . $this->table . ' where id = ' . $id, 'featured' );
			$hide_show = ( $featured ) ? 'hide' : 'show';
			$featured  = ( $featured ) ? 'featured' : 'not featured';
			$href      = sprintf( "%s?%s%s_item=yes&id=%s", $this->page_self, $parent_part, $hide_show, $id );
			$this->cms_admin_button( $href, $hide_show . 'content', $featured, "onclick='return set_featured_item(this, \"$featured\",\"$id\");'" );
		}

		function InitFilter() {
			if ( ! isset( $this->filter ) ) {
				$this->filter = empty( $_GET['filter'] ) ? '' : $_GET['filter'];
			}
		}

		function GetFilterFormText() {
			/*
			 * This is where we create the search form controls to display in the page,
			 * from the items in the admin page where the item has
			 * 'filter' => true
			 *
			 * Currently handles text, shorttext, lookup, checkbox
			 *
			 * GL 12/10/2012
			 */
			$this->InitFilter();
			$self       = $_SERVER['PHP_SELF'];
			$formText   = '';
			$formFields = '';
			foreach ( $this->fields as $name => $field ) {
				if ( ! empty( $field['filter'] ) && ( $field['filter'] == true ) ) {
					if ( $field['formtype'] == 'text' ) {
						$formFields .= "<div class='search_criteria' >";
						$formFields .= "{$field['name']}: <input type=\"text\" name=\"filter[$name]\" value=\"{$this->filter[$name]}\"/>";
						$formFields .= "</div>";
					}
					if ( $field['formtype'] == 'shorttext' ) {
						$formFields .= "<div class='search_criteria' >";
						$formFields .= "{$field['name']}: <input type=\"text\" name=\"filter[$name]\" value=\"{$this->filter[$name]}\"/>";
						$formFields .= "</div>";
					}
					if ( $field['formtype'] == 'checkbox' ) {
						$formFields .= "<div class='search_criteria' >";
						$val = $this->filter[$name];
						$sel = "<select name=\"filter[$name]\">";
						if ( $val == '-1' ) {
							$sel .= "<option value=\"-1\" selected >all</option>";
						} else {
							$sel .= "<option value=\"-1\" >all</option>";
						}
						if ( $val == '1' ) {
							$sel .= "<option value=\"1\" selected >on</option>";
						} else {
							$sel .= "<option value=\"1\" >on</option>";
						}
						if ( $val == '0' ) {
							$sel .= "<option value=\"0\" selected >off</option>";
						} else {
							$sel .= "<option value=\"0\" >off</option>";
						}
						$sel .= " </select>";
						$sel .= "</div>";
						$formFields .= "{$field['name']}: " . $sel;
					}
					if ( $field['formtype'] == 'lookup' ) {
						$func     = get_class( $this ) . '::' . $field['function'] . '_Search';
						$db_field = $_GET['filter'][$name];
						try {
							$formFields .= "<div class='search_criteria' >";
							// remove an _id reference on the field for display
							$formFields .= str_replace( '_id', '', $name ) . ':' . call_user_func( $func, $db_field );
							$formFields .= '</div>';
						} catch ( Exception $e ) {
							echo 'Caught exception: ', $e->getMessage(), "\n";
						}
					}
				}
			}
			if ( ! empty( $formFields ) ) {
				$formText = "<form method=\"GET\" action=\"$self\" class='search_criteria_holder'><h1>Filter Search Results</h1> $formFields  <button>Search</button>&nbsp;<a href=\"$self\">Clear</a></form>";
			}
			return $formText;
		}

		function GetFilterWhere( $where = '' ) {
			/*
			 * Build the filter clause generated by the $_GET -
			 * via the form search generated GetFilterFormText()        *
			 *
			 */
			$this->InitFilter();
			$items = array();
			foreach ( $this->filter as $name => $value ) {
				$field = $this->fields[$name];
				if ( trim( $value ) != '' ) {
					/* going to need to figure out if its a dropdown or a text entry
					 *  as we will need to do = rather than like
					 */
					if ( $field['formtype'] == 'lookup' ) {
						if ( $value != '0' ) {  // leave it if nothing selected
							$items[] = "`$name` = $value ";
						}
					}
					if ( $field['formtype'] == 'text' || $field['formtype'] == 'shorttext' ) {
						$items[] = "`$name` like '%" . trim( $value ) . "%' ";
					}
				}
				if ( $field['formtype'] == 'checkbox' ) {
					if ( $value != '-1' ) {
						$items[] = "`$name` = $value ";
					}
				}
			}
			if ( count( $items ) > 0 ) {
				$where = ( ( $where ) ? $where : 'where ' ) . implode( ' and ', $items );
			} // If $where is empty - start with 'where '
			//  echo $where;
			return $where;
		}

		function shorten_text( $text, $ln ) {
			$body_len = strlen( $text );
			$text     = substr( $text, 0, $ln );
			if ( $body_len > $ln ) {
				$text = $text . "...";
			}
			return $text;
		}

		function lookup( $table, $fieldname, $id, $namefield = '', $none = false, $nonname = 'none' ) {
			$sql    = "select * from $table";
			$result = mysql_query( $sql );
			if ( $namefield == '' ) {
				$namefield = $fieldname;
			}
			$out = "<select name=\"$fieldname\">";
			if ( $none == true ) {
				$selected = ( empty( $id ) || ( $id == 0 ) ) ? ' selected="selected"' : '';
				$out .= '<option value="0"' . $selected . ' >' . $nonname . '</option>';
			}
			while ( $row = mysql_fetch_array( $result ) ) {
				$selected = ( $id == $row['id'] ) ? ' selected="selected"' : '';
				$out .= '<option value="' . $row['id'] . '"' . $selected . ' >' . $row[$namefield] . '</option>';
			}
			$out .= '</select>';
			return $out;
		}

		function get_crumbs() {
			return "<a href='content_admin.php'>Content Administration</a> > <b>{$this->single_name} Admin</b>";
		}

		function get_form_data() {
			$this->onload();
			if ( $this->php_debug ) {
				ini_set( 'display_errors', '1' );
				ini_set( 'html_errors', 'on' );
				ini_set( 'error_reporting', '1' );
			}
			foreach ( $this->fields as $fieldname => $field ) {
				if ( $field['formtype'] == 'date' ) {
					$year                   = ( isset( $_REQUEST[$fieldname . '_year'] ) ) ? $_REQUEST[$fieldname . '_year'] : "";
					$month                  = ( isset( $_REQUEST[$fieldname . '_month'] ) ) ? $_REQUEST[$fieldname . '_month'] : "";
					$day                    = ( isset( $_REQUEST[$fieldname . '_day'] ) ) ? $_REQUEST[$fieldname . '_day'] : "";
					$this->data[$fieldname] = $year . '-' . $month . '-' . $day;
				} else if ( $field['formtype'] == 'time' ) {
					$hour                   = ( isset( $_REQUEST[$fieldname . '_hour'] ) ) ? $_REQUEST[$fieldname . '_hour'] : "";
					$minute                 = ( isset( $_REQUEST[$fieldname . '_minute'] ) ) ? $_REQUEST[$fieldname . '_minute'] : "";
					$this->data[$fieldname] = $hour . ':' . $minute . ':00';
				} else if ( $field['formtype'] == 'address' ) {
					$lines = isset( $field['lines'] ) ? $field['lines'] : 3;
					for ( $i = 0; $i < $lines; $i ++ ) {
						$this->data[$fieldname][$i] = ( isset( $_REQUEST[$fieldname] ) ) ? $_REQUEST[$fieldname][$i] : "";
					}
				} else if ( ( $field['formtype'] == 'optionsgroup' ) && ( $field['type'] == 'checkbox' ) ) {
					if ( ! empty( $_REQUEST[$fieldname] ) ) {
						$this->data[$fieldname] = join( ',', $_REQUEST[$fieldname] );
					}
				} else if ( isset( $field['custom_get_function'] ) ) {
					$custom_get_function    = $field['custom_get_function'];
					$this->data[$fieldname] = $this->$custom_get_function();
				} else {
					$this->data[$fieldname] = ( isset( $_REQUEST[$fieldname] ) ) ? $_REQUEST[$fieldname] : "";
				}
			}
		}

		/* optional override function */
		function validate_data( $id, $data ) {
			return '';
		}

		function get_missing_data() {
			$missing = array();
			//var_dump($_REQUEST);
			foreach ( $this->fields as $fieldname => $field ) {
				$field_required = isset( $field['required'] ) ? $field['required'] : false;
				if ( is_callable( $this->field_required ) && ( $this->field_required( $this->data ) ) || ( $field_required ) ) {
					if ( $field['formtype'] == 'date' ) {
						$year  = ( isset( $_REQUEST[$fieldname . '_year'] ) ) ? $_REQUEST[$fieldname . '_year'] : "";
						$month = ( isset( $_REQUEST[$fieldname . '_month'] ) ) ? $_REQUEST[$fieldname . '_month'] : "";
						$day   = ( isset( $_REQUEST[$fieldname . '_day'] ) ) ? $_REQUEST[$fieldname . '_day'] : "";
						if ( ( $year == "" ) || ( $month == "" ) || ( $day == "" ) ) {
							$missing[] = $field['name'];
						}
					} else if ( $field['formtype'] == 'time' ) {
						$hour   = ( isset( $_REQUEST[$fieldname . '_hour'] ) ) ? $_REQUEST[$fieldname . '_hour'] : "";
						$minute = ( isset( $_REQUEST[$fieldname . '_minute'] ) ) ? $_REQUEST[$fieldname . '_minute'] : "";
						if ( ( $hour == "" ) || ( $minute == "" ) ) {
							$missing[] = $field['name'];
						}
					} else if ( $field['formtype'] == 'address' ) {
						if ( ! $this->data[$fieldname][0] ) {
							$missing[] = $field['name'];
						}
					} else {
						if ( ( ! isset( $this->data[$fieldname] ) ) || ( $this->data[$fieldname] === '' ) ) {
							$missing[] = $field['name'];
						}
					}
				}
			}
			return $missing;
		}

		/*
		 * Create a valid pagename for an item.
		 * Dependancy: Requires page_name field in the database table and $this->has_page_name to be set to true
		 */
		function make_valid_pagename( $id, $parent_id, $page_name ) {
			$page_name = preg_replace( '/[\',"]+/', '', strtolower( $page_name ) );
			$page_name = preg_replace( '/[^a-z0-9.]+/', '-', $page_name );
			$page_name = preg_replace( '/-$/', '', $page_name );
			$sql = "select page_name from {$this->table} where page_name like '%$page_name%'";
			if ( $id ) {
				$sql .= "and id <> '$id'";
			}
			if ( $parent_id ) {
				$sql .= "and {$this->parent_field} = '$parent_id'";
			}
			$result = mysql_query( $sql );
			if ( ( mysql_num_rows( $result ) > 0 ) || ( file_exists( $base_path . '/' . $page_name ) ) || ( in_array( $page_name, $this->invalid_page_names ) ) ) {
				$name_part = $page_name;
				$rows      = array();
				while ( $row = mysql_fetch_array( $result ) ) {
					$rows[] = $row['page_name'];
				}
				$i = 1;
				while ( ( in_array( $page_name, $rows ) ) || ( file_exists( $base_path . '/' . $page_name ) ) || ( in_array( $page_name, $this->invalid_page_names ) ) ) {
					$i ++;
					$page_name = $name_part . $i;
				}
			}
			return $page_name;
		}

		function check_data( $id ) {
			$message           = '';
			$missing           = $this->get_missing_data();
			$validation_result = $this->validate_data( $id, $this->data );
			$hasErrors         = ( count( $missing ) > 0 ) || ( $validation_result != '' );
			if ( $hasErrors ) {
				if ( count( $missing ) > 0 ) {
					$message = "<b>ERROR</b>: <b>Information missing</b>";
					$message .= "<p>You have not entered the item's:<ul>";
					foreach ( $missing as $missingitem ) {
						$message .= "<li>$missingitem</li>";
					}
					$message .= "</ul>";
					$message .= "<p>Please return to the form to enter the missing information.</p>";
				} else {
					$message = $validation_result;
				}
				$base_path = $_SERVER['DOCUMENT_ROOT'];
			}
			return array( $hasErrors, $message );
		}

		/* create the default update sql, override this function for a customised update */
		function make_update_sql_statement( $id ) {
			$sql   = "update {$this->table} set ";
			$i     = 0;
			$first = true;
			foreach ( $this->fields as $fieldname => $field ) {
				if ( isset( $field['not_field'] ) && $field['not_field'] ) {
					continue;
				}
				if ( ( $field['formtype'] == 'password' ) && ( trim( $this->data[$fieldname], '   *' ) == '' ) ) {
					continue;
				}
				if ( $first ) {
					$first = false;
				} else {
					$sql .= ', ';
				}
				if ( $field['formtype'] == 'address' ) {
					$lines = isset( $field['lines'] ) ? $field['lines'] : 3;
					for ( $i = 0; $i < $lines; $i ++ ) {
						$sql .= ( $i > 0 ) ? ', ' : '';
						$sql .= "$fieldname" . ( $i + 1 ) . " = '{$this->data[$fieldname][$i]}'";
					}
				} elseif ( $field['formtype'] == 'password' ) {
					$pass = new PasswordHelper();
					$hash = $pass->generateHash( $this->data[$fieldname] );
					$sql .= '`' . $fieldname . "` = '{$hash}'";
				} else {
					$clean_field = mysql_real_escape_string($this->data[$fieldname]);
					$sql .= '`' . $fieldname . "` = '{$clean_field}'";
					// mysql_real_escape_string($this->data[$fieldname])
				}
			}
			$sql .= " where id = $id";
			$this->admin_log( $sql, $this->table, 'make_update_sql_statement', 'user_id=' . $id );
			return $sql;
		}

		function make_insert_sql_statement( $parent_id ) {
			$sql_fields = '';
			$sql_data   = '';
			if ( $parent_id ) {
				$sql_fields .= $this->parent_field . ', ';
				$sql_data .= "'$parent_id', ";
			}
			if ( $this->ordered ) {
				$parent_part  = ( $parent_id ) ? "where {$this->parent_field} = $parent_id" : '';
				$order_sql    = "select coalesce(max(order_num),0) + 10 as order_num from {$this->table} $parent_part";
				$order_result = mysql_query( $order_sql );
				$order_row    = mysql_fetch_array( $order_result );
				$sql_fields .= 'order_num, ';
				$sql_data .= "'{$order_row['order_num']}', ";
			}
			$first = true;
			foreach ( $this->fields as $fieldname => $field ) {
				if ( isset( $field['not_field'] ) && $field['not_field'] ) {
					continue;
				}
				if ( $first ) {
					$first = false;
				} else {
					$sql_fields .= ', ';
					$sql_data .= ', ';
				}
				if ( $field['formtype'] == 'order' ) {
				}
				if ( $field['formtype'] == 'address' ) {
					$lines = isset( $field['lines'] ) ? $field['lines'] : 3;
					for ( $i = 0; $i < $lines; $i ++ ) {
						$cm = ( $i > 0 ) ? ', ' : '';
						$sql_fields .= "$cm$fieldname" . ( $i + 1 );
						$sql_data .= "$cm'{$this->data[$fieldname][$i]}'";
					}
				} elseif ( $field['formtype'] == 'password' ) {
					$sql_fields .= '`' . $fieldname . '`';
					$pass = new PasswordHelper();
					$hash = $pass->generateHash( $this->data[$fieldname] );
					$sql_data .= "'{$hash}'";
				} else {
					$sql_fields .= '`' . $fieldname . '`';
					$sql_data .= "'{$this->data[$fieldname]}'";
				}
			}
			$sql = "insert into {$this->table} ({$sql_fields}) values ({$sql_data})";
			$this->admin_log( $sql, $this->table, 'make_insert_sql_statement', '' );
			return $sql;
		}

		/*
		 * This is the default admin log function.
		 * It records the update statesments and the user credentials.
		 * This will NOT record overridden update/inserts
		 * TODO: Take a snapshot of the current table row as a reference before and after change.
		 */
		function admin_log( $query, $table, $function, $other ) {
			$user_id  = $_SESSION['session_user_id'];
			$username = gethostname();
			$sql      = 'insert into admin_log (user_id, `query`,`table`, `username`,`function`,other) values (' . $user_id . ',"'
			            . mysql_real_escape_string( $query ) . '","' . $table . '","' . $username . '","' . $function . '", "' . $other . '"  )';
			mysql_query( $sql );
		}

		/*
		  function call_stored_procedure($id)
		  {
		  $sql = $this->storedProcedureTemplate;
		  $i = 0;
		  $first = true;

		  // just make sure we have a 0
		  if (empty($id))
		  $id = 0;

		  $sql = str_replace('%id%', $id, $sql);

		  foreach ($this->fields as $fieldname => $field)
		  {
		  if ($field['formtype'] == 'address')
		  {
		  $lines = isset($field['lines']) ? $field['lines'] : 3;
		  for ($i = 0; $i <  $lines; $i++)
		  {
		  $sql = str_replace('%'.$fieldname.($i+1).'%', $this->data[$fieldname][$i], $sql);
		  }
		  }
		  else
		  $sql = str_replace('%'.$fieldname.'%', $this->data[$fieldname], $sql);
		  }
		  mysql_query('set @id = '.$id);
		  mysql_query($sql);
		  $id = db_get_single_value('select @id');
		  return 1;
		  }
		 */
		// this is the debug log
		function log( $message ) {
			if ( $this->debug_log ) {
				echo '<br>' . $message;
			}
		}

		function write_data( $id, $parent_id ) {
			if ( $id ) {
				$sql    = $this->make_update_sql_statement( $id );
				$result = mysql_query( $sql );
				$this->log( 'SQL:=' . $sql );
				$this->log( 'Result:=' . $result );
			} else {
				$sql      = $this->make_insert_sql_statement( $parent_id );
				$result   = mysql_query( $sql );
				$id       = mysql_insert_id();
				$this->id = $id;
				$this->log( 'SQL:=' . $sql );
				$this->log( 'Inserted id:=' . $id );
			}
			return array( $result, $id );
		}

		function update_links( $id ) {
			$this->log( 'update links' );
			foreach ( $this->links as $fieldname => $ext_link ) {
				$link_ids    = "";
				$link_values = "";
				$primaryKey = ( empty( $ext_link['primary'] ) ) ? $this->table . '_id' : $ext_link['primary'];
				$foreignKey = ( empty( $ext_link['foreign'] ) ) ? $ext_link['table'] . '_id' : $ext_link['foreign'];
				if ( is_array( $this->data[$fieldname] ) ) {
					foreach ( $this->data[$fieldname] as $link_id ) {
						if ( $link_ids ) {
							$link_ids .= ', ';
						}
						if ( $link_values ) {
							$link_values .= ', ';
						}
						$link_ids .= $link_id;
						$link_values .= "('$link_id', '$id')";
					}
					$delete_link_sql    = sprintf( "delete from %s where %s = '%s' and %s not in (%s)", $ext_link['link_table'], $primaryKey, $id, $foreignKey, $link_ids );
					$delete_link_result = mysql_query( $delete_link_sql );
					$this->log( 'link delete sql for checkbox group ::' . $delete_link_sql );
					$link_sql    = sprintf( "replace into %s (%s, %s) values %s", $ext_link['link_table'], $foreignKey, $primaryKey, $link_values );
					$link_result = mysql_query( $link_sql );
					$this->log( 'link sql for checkbox group ::' . $link_sql );
				} else {
					$delete_link_sql    = sprintf( "delete from %s where %s = '%s'", $ext_link['link_table'], $primaryKey, $id );
					$delete_link_result = mysql_query( $delete_link_sql );
				}
			}
		}

		function cms_admin_button( $href, $type, $text, $onclick = false ) {
			global $base_path;
			$buttons = "onmouseout='button_off(this)' onmouseover='button_over(this)'";
			$button_name = $type . str_replace( ' ', '', $text );
			$image       = '/admin/images/' . $type . '-' . str_replace( ' ', '', $text ) . '-off.gif';
			if ( ! file_exists( $base_path . $image ) ) {
				$image = '/admin/images/buttons/' . $type . '-' . str_replace( ' ', '_', $text ) . '-off.gif';
			}
			//	echo "<td width=0>";
			echo "<div style='float:left'>";
			//echo "<span>";
			$this->_acc ++;
			if ( $onclick ) {
				printf( "<a href='%s' $onclick $buttons><img src=\"%s\" alt=\"%s\" name=\"%s\"  /></a>", $href, $image, $text, $button_name . $this->_acc );
			} else {
				printf( "<a href='%s' $buttons><img src=\"%s\" alt=\"%s\" name=\"%s\"  /></a>", $href, $image, $text, $button_name . $this->_acc );
			}
			//	echo "</span>";
			echo "</div>";
			//	echo "</td>";
		}

		function process_submit( $id, $parent_id = false ) {
			global $base_path;
			// SUBMIT ITEM EDITS
			list( $hasErrors, $message ) = $this->check_data( $id );
			if ( $hasErrors ) {
				return $message;
			}
			if ( $this->has_page_name ) {
				$page_name = ( ( isset( $this->data['page_name'] ) ) && ( $this->data['page_name'] ) ) ? $this->data['page_name'] : $this->data['title'];
				$page_name = $this->data['title'];
				if ( ! isset( $this->data['title'] ) && isset( $this->data['name'] ) ) {
					$page_name = $this->data['name'];
				}
				$page_name               = $this->make_valid_pagename( $id, $parent_id, $page_name );
				$this->data['page_name'] = $page_name;
			}
			list( $result, $id ) = $this->write_data( $id, $parent_id );
			if ( is_array( $this->links ) && ( count( $this->links ) > 0 ) ) {
				$this->update_links( $id );
			}
			// check results
			if ( $result ) {
			} else {
				$errorMsg = mysql_error();
				echo "<script>window.console.log(\"$errorMsg\");</script>";
				echo "<p>Sorry, an error has occurred.  The content could not be added. Please contact the web administrator";
				$base_path = $_SERVER['DOCUMENT_ROOT'];
				include( "$base_path/admin/admin_footer_inc.php" );
				exit();
				//echo "$update_sql $insert_sql";
			}
			//echo "</ul>";
		}

		function show_hide( $hide_show_item, $id, $parent_id = false ) {
			$message = '';
			// Hide / Show item
			if ( $hide_show_item == 'show' ) {
				$action    = 'made visible';
				$published = 1;
			} else {
				$action    = 'hidden';
				$published = 0;
			}
			$hide_show_content_sql    = "update {$this->table} set published = $published WHERE id=$id";
			$hide_show_content_result = mysql_query( $hide_show_content_sql );
			if ( ! $hide_show_content_result ) {
				$message .= "<p>This item was unable to be $action</p>";
			}
			return $message;
		}

		function onDetailsLoad( $id, $parent_id ) {
		}

		function show_edit( $id, $parent_id = false ) {
			// ADD OR EDIT ITEM
			$content_sql    = "SELECT * FROM {$this->table} WHERE id = '$id'";
			$content_result = mysql_query( $content_sql );
			$content_row    = mysql_fetch_array( $content_result );
			$parent_part = ( ( $parent_id ) && ( ! $this->parent_child ) ) ? "?{$this->parent_id_name}=$parent_id" : '';
			echo "<div id='admin-page-content'>";
			printf( "<p><form action=\"%s$parent_part\" method=POST ENCTYPE=\"multipart/form-data\">", $this->page_self );
			printf( "<input type=\"hidden\" name=\"id\" value=\"%s\">", $id );
			if ( ( $parent_id ) && ( $this->parent_child ) ) {
				printf( "<input type=\"hidden\" name=\"%s\" value=\"%s\">", $this->parent_id_name, $parent_id );
			}
			echo "<table>";
			$hidden_template           = '<input type="hidden" name="%s" value="%s" />';
			$simple_template           = '<tr valign=top><td>%s</td><td>%s</td></tr>';
			$sectiontitle_template     = '<tr valign=top><td colspan=2>%s</td></tr>';
			$time_template             = '<tr valign=top><td>%s</td><td>(24hr) hh<input name="%s" size=2 value="%s"/> mm<input name="%s" size=2 value="%s"/> </td></tr>';
			$textfield_template        = '<tr valign=top><td>%s</td>
			<td>%s<input type=text name=%s size=%s value="%s" class="%s"></td></tr>';
			$passwordfield_template    = '<tr valign=top><td>%s</td>
            <td><input type="password" name=%s size=20 value="%s"></td></tr>';
			$textfield_short_template  = '<tr id="row_%s" valign=top><td>%s</td>
			<td><input type=text id="%s" name="%s" size=30 value="%s" %s class="%s"></td></tr>';
			$textfield_static_template = '<tr valign=top><td>%s</td>
			<td><input type=hidden name=%s value="%s">%s</td></tr>';
			$textarea_template         = '<tr valign=top><td>%s</td>
			<td>%s<textarea name=%s rows="%s" cols="%s" class="%s">%s</textarea></td></tr></td></tr>';
			$address_outer_template    = '<tr valign=top><td>%s</td>
			<td>%s</td></tr>';
			$address_inner_template    = '<input type=text name=%s[] size=65 value="%s"><br/>';
			$checkbox_template         = '<tr valign=top><td>%s</td><td>%s<input id="%s" type=checkbox name=%s %s value=%s %s></td></tr>';
			$options_group_outer_template = '<tr valign=top><td>%s</td><td>%s</td></tr>';
			$options_group_inner_template = '<span><input id="%s" type="%s" name="%s" %s value="%s"> %s</span>';
			$htmlfield_template = '<tr valign=top><td>%s</td><td width=100%%>%s</td></tr>';
			$image_template = '<tr valign=top><td>%s</td>
				<td><input type=hidden name=%s value=%s><span id="%s_img">%s</span>
                               <br><button onclick="return selectimage(\'%s\', %s);">Choose Image</button>
                               <button onclick="return clear_image(\'%s\');" >Clear Image</button>
                               </td></tr>';
			$image_preserve_template = '<tr valign=top><td>%s [set to transparent image]</td>
				<td>
                                <div id="preserve_image_block_%s"> 
                                
                                <div class="regular_image" >
                                 Standard Image <br>
                                <input type=hidden name=%s value=%s><span id="%s_img">%s</span>
                                <br><button onclick="return selectimage(\'%s\', %s);">Choose Image</button>
                                 <button onclick="return clear_image(\'%s\');" >Clear Image</button>
                                 
 <br> <br>
                                 <a href="#" onclick="use_reserve_image(event, 1,\'%s\');" class="button"> Transparent Image >> </a>

                                </div>
                                

                                <div class="preserve_image">
                                    
                                 <!---   %s -->
                                        <div class="large_image">
                                        <p> Large Image (1000px) </p><br>
                              <input type=hidden name="%s" value=%s><span id="%s_img"> %s </span>
                                <br><button onclick="return selectimage(\'%s\', %s);">Choose Image</button>
                                

                                 <button onclick="return clear_image(\'%s\');" >Clear Image</button>
                                
                                            
                                        </div>

                            <!---   %s -->
                                    <div class="small_image">
                                         <p> Small Image (500px)</p><br>
                                            <input type=hidden name="%s" value=%s><span id="%s_img"> %s </span>
                                <br><button onclick="return selectimage(\'%s\', %s);">Choose Image</button>
                                 <button onclick="return clear_image(\'%s\');" >Clear Image</button>
                                
                                           
                                        </div>


 <br> 
              
                                   <a href="#" onclick="use_reserve_image(event,0,\'%s\');" class="button" style="clear:both; " > << Standard Image </a>
                                 <input type="hidden" name="%s" value="%s"></input>
                                </div>
                                

                                <script>start_preserve_image_layout(%s, "preserve_image_block_%s");</script> 


                                </div>
                                </td></tr>';
			/*
			  $image_template = '<tr valign=top><td>%s</td>
			  <td><input type=hidden name=%s value=%s><span id="%s_img">%s</span><br><button onclick="return selectimage(\'%s\', %s);">Choose Image</button>
			  <button onclick="return clear_image(\'%s\');" >Clear Image</button></td></tr>';
			 */
			$file_template = '<tr valign=top><td>%s</td>
				<td><input type=hidden name=%s value=%s><span id="%s_file">%s</span>
                                <button onclick="return selectfile(\'%s\', %s);">Choose File</button>
                                <button onclick="return clear_file(\'%s\');" >Clear File</button></td></tr>';
			$checklink_outer_template = '<tr valign=top><td>%s</td><td>%s<div class="form-checkbox-group">%s</div></td></tr>';
			$checklink_inner_template = '<span class="form-checkbox"><input type=checkbox name=%s[] %s value=%s>%s</span>';
			$first = true;
			foreach ( $this->fields as $fieldname => $field ) {
				$toptext = ! empty( $field['toptext'] ) ? $field['toptext'] . '<br />' : '';
				if ( ! empty( $field['customfunction'] ) ) {
					$function = $field['customfunction'];
					$this->$function( $content_row['id'], $fieldname, $field );
					continue;
				}
				if ( $field['formtype'] == 'hidden' ) {
					$func = $field['function'];
					if ( $func ) {
						$value = $this->$func();
					} else {
						if ( isset( $field['value'] ) ) {
							$value = $field['value'];
						}
					}
					if ( $field['mode'] == 'keepfirst' ) {
						$value = ( $content_row[$fieldname] != '' ) ? $content_row[$fieldname] : $value;
					}
					printf( $hidden_template, $fieldname, $value );
				}
				if ( ( $id == "" ) && ( $field['default'] != "" ) ) {
					$content_row[$fieldname] = $field['default'];
				}
				if ( $field['formtype'] == 'title' ) {
					printf( $sectiontitle_template, $field['title'] );
				}
				if ( $field['formtype'] == 'lookup' ) {
					$func = $field['function'];
					if ( $field['not_field'] ) {
						$fieldname = $field['lookup_field'];
					}
					$value         = $content_row[$fieldname];
					$display_value = $this->$func( $value );
					if ( ! $field['not_field'] ) {
						printf( $hidden_template, $fieldname, $value );
					}
					printf( $simple_template, $field['name'], $display_value );
				}
				if ( ( $field['formtype'] == 'text' ) || ( $field['formtype'] == 'page_name' ) ) {
					$class = "";
					if ( $first ) {
						$class = 'first';
						$first = false;
					}
					$textfield_size = ( isset( $field['size'] ) ) ? $field['size'] : 65;
					if ( ( ! isset( $field['edit_condition'] ) ) || ( $this->$field['edit_condition']() ) ) {
						printf( $textfield_template, $field['name'], $toptext, $fieldname, $textfield_size, $content_row[$fieldname], $class );
					} else {
						printf( $textfield_static_template, $field['name'], $fieldname, $content_row[$fieldname], $content_row[$fieldname] );
					}
				}
				if ( ( $field['formtype'] == 'password' ) ) {
					$plen = strlen( $content_row[$fieldname] );
					if ( $plen > 6 ) {
						$plen = 6;
					}
					printf( $passwordfield_template, $field['name'], $fieldname, str_repeat( '*', $plen ) );
				}
				if ( $field['formtype'] == 'textarea' ) {
					$class = "";
					if ( $first ) {
						$class = 'first';
						$first = false;
					}
					$rows = empty( $field['rows'] ) ? $this->defaultTextAreaRows : $field['rows'];
					$cols = empty( $field['cols'] ) ? $this->defaultTextAreaCols : $field['cols'];
					printf( $textarea_template, $field['name'], $toptext, $fieldname, $rows, $cols, $class, $content_row[$fieldname] );
				}
				if ( $field['formtype'] == 'shorttext' ) {
					$class = "";
					if ( $first ) {
						$class = 'first';
						$first = false;
					}
					$onchange = ( $field['onchange'] ) ? "onchange=\"{$field['onchange']}\"" : "";
					printf( $textfield_short_template, $fieldname, $field['name'], $fieldname, $fieldname, $content_row[$fieldname], $onchange, $class );
				}
				if ( $field['formtype'] == 'staticdate' ) {
					if ( $content_row[$fieldname] ) {
						$ds          = $content_row[$fieldname];
						$dt          = mktime( 0, 0, 0, substr( $ds, 5, 2 ), substr( $ds, 8, 2 ), substr( $ds, 0, 4 ) );
						$static_date = date( 'jS F Y', $dt );
						$hidden_date = date( 'Y-m-d', $dt );
					} else {
						$hidden_date = date( 'Y-m-d' );
						$static_date = date( 'jS F Y' );
					}
					//$static_date =  $content_row[$fieldname];
					printf( $textfield_static_template, $field['name'], $fieldname, $hidden_date, $static_date );
				}
				if ( $field['formtype'] == 'date' ) {
					if ( $content_row[$fieldname] ) {
						$ds = $content_row[$fieldname];
						$dt = mktime( 0, 0, 0, substr( $ds, 5, 2 ), substr( $ds, 8, 2 ), substr( $ds, 0, 4 ) );
					} else {
						$dt = time();
					}
					//$static_date =  $content_row[$fieldname];
					$start_year = empty( $field['start_year'] ) ? date( 'Y' ) - 2 : $field['start_year'];
					$end_year   = empty( $field['end_year'] ) ? date( 'Y' ) + 3 : $field['end_year'];
					$datefield  = display_date_form_field( $fieldname, $dt, $start_year, $end_year );
					if ( ! empty( $toptext ) ) {
						$datefield = $toptext . '<br />' . $datefield;
					}
					printf( $simple_template, $field['name'], $datefield );
				}
				if ( $field['formtype'] == 'time' ) {
					if ( $content_row[$fieldname] ) {
						$ds     = $content_row[$fieldname];
						$hour   = substr( $ds, 0, 2 );
						$minute = substr( $ds, 3, 2 );
					}
					printf( $time_template, $field['name'], $fieldname . "_hour", $hour, $fieldname . "_minute", $minute );
				}
				if ( $field['formtype'] == 'checkbox' ) {
					$checked  = ( $content_row[$fieldname] ) ? "checked" : "";
					$onchange = ( $field['onchange'] ) ? "onchange=\"{$field['onchange']}\"" : "";
					printf( $checkbox_template, $field['name'], $toptext, $fieldname, $fieldname, $checked, 1, $onchange );
				}
				if ( $field['formtype'] == 'optionsgroup' ) {
					// '<tr valign=top><td>%s</td><td>%s</td></tr>';
					// '<span><input id="%s" type="radio" name="%s" %s value="%s"> %s</span>';
					$inner = '';
					if ( $field['toptext'] ) {
						$inner .= "{$field['toptext']}<br />";
					}
					if ( $field['type'] == 'checkbox' ) {
						$valuesSet = split( ',', $content_row[$fieldname] );
					}
					foreach ( $field['values'] as $value => $valueName ) {
						if ( $field['type'] == 'checkbox' ) {
							$checked = ( in_array( $value, $valuesSet ) ) ? "checked" : "";
						} else {
							$checked = ( $value == $content_row[$fieldname] ) ? "checked" : "";
						}
						//$onchange = ($field['onchange']) ? "onchange=\"{$field['onchange']}\"" : "";
						$htmlFieldName = ( $field['type'] == 'checkbox' ) ? $fieldname . '[]' : $fieldname;
						$inner .= sprintf( $options_group_inner_template, $fieldname . '_' . $value, $field['type'], $htmlFieldName, $checked, $value, $valueName );
					}
					printf( $options_group_outer_template, $field['name'], $inner );
				}
				if ( $field['formtype'] == 'address' ) {
					$lines = ( isset( $field['lines'] ) ) ? $field['lines'] : 3;
					$inner = "";
					for ( $i = 0; $i < $lines; $i ++ ) {
						$inner .= sprintf( $address_inner_template, $fieldname, $content_row[$fieldname . ( $i + 1 )] );
					}
					printf( $address_outer_template, $field['name'], $inner );
				}
				if ( $field['formtype'] == 'image' ) {
					$size      = ( isset( $field['size'] ) ) ? $field['size'] : 15;
					$thumbbit1 = show_thumb( $content_row[$fieldname], 13 );
					$thumbbit2 = show_thumb( $content_row[$fieldname], 16 );
					$imagebit  = '<span id="mythumb_img">
                    <a class="hoverthumb" href="#thumb">' . $thumbbit1 . '<span>' . $thumbbit2 . '</span></a></span>';
					printf( $image_template, $field['name'], $fieldname, $content_row[$fieldname], $fieldname, $imagebit, $fieldname, $size, $fieldname );
				}
				if ( $field['formtype'] == 'image_preserve_subimage' ) {
					// these are the small + large associated images - which we will not display ?!
					// add hidden values here ??
					//   printf($hidden_template, $fieldname, $value);
				}
				if ( $field['formtype'] == 'image_preserve_toggle' ) {
				}
				if ( $field['formtype'] == 'image_preserve' ) {
					$size = ( isset( $field['size'] ) ) ? $field['size'] : 15;
					$thumbbit1 = show_thumb( $content_row[$fieldname], 13 );
					$thumbbit2 = show_thumb( $content_row[$fieldname], 16 );
					$imagebit  = '<span id="mythumb_img"><a class="hoverthumb" href="#thumb">' . $thumbbit1 . '<span>' . $thumbbit2 . '</span></a></span>';
					// echo 'this >> ' .  $content_row[$fieldname .'_small_preserve'];
					// var_dump($content_row);
					$small_thumbbit1 = show_thumb( $content_row[$fieldname . '_preserve_small'], 13 );
					$small_thumbbit2 = show_thumb( $content_row[$fieldname . '_preserve_small'], 16 );
					$small_imagebit  = '<span id="mythumb_preserve_large_img"><a class="hoverthumb" href="#thumb_preserve_small">' . $small_thumbbit1 . '<span>' . $small_thumbbit2 . '</span></a></span>';
					$large_thumbbit1 = show_thumb( $content_row[$fieldname . '_preserve_large'], 13 );
					$large_thumbbit2 = show_thumb( $content_row[$fieldname . '_preserve_large'], 16 );
					$large_imagebit  = '<span id="mythumb_preserve_large_img"><a class="hoverthumb" href="#thumb_preserve_large">' . $large_thumbbit1 . '<span>' . $large_thumbbit2 . '</span></a></span>';
					$show_preserve = $content_row[$fieldname . '_preserve_toggle'];
					printf( $image_preserve_template,
						$field['name'], $fieldname, $fieldname, $content_row[$fieldname], $fieldname, $imagebit, $fieldname, $size, $fieldname, $fieldname . '_preserve_toggle',
						$field['name'], $fieldname . '_preserve_large', $content_row[$fieldname . '_preserve_large'], $fieldname . '_preserve_large', $large_imagebit, $fieldname . '_preserve_large', $size, $fieldname . '_preserve_large',
						$field['name'], $fieldname . '_preserve_small', $content_row[$fieldname . '_preserve_small'], $fieldname . '_preserve_small', $small_imagebit, $fieldname . '_preserve_small', $size, $fieldname . '_preserve_small',
						$fieldname . '_preserve_toggle', $fieldname . '_preserve_toggle', $show_preserve, $show_preserve, $fieldname
					);
				}
				if ( $field['formtype'] == 'file' ) {
					$icon = '';
					if ( ! empty( $content_row[$fieldname] ) ) {
						$fileinfo = pathinfo( $content_row[$fieldname] );
						$iconType = $fileinfo['extension'];
						$iconFile = "/php/filecontroller/images/$iconType.gif";
						if ( ! file_exists( '../../..' . $iconFile ) ) {
							$iconFile = "/php/filecontroller/images/file_icon.png";
						}
						$icon = '<img src="' . $iconFile . '"> ' . $content_row[$fieldname];
					}
					$size = ( isset( $field['size'] ) ) ? $field['size'] : 1;
					printf( $file_template, $field['name'], $fieldname, $content_row[$fieldname], $fieldname, $icon, $fieldname, $size, $fieldname );
					//$size = (isset($field['size'])) ? $field['size'] : 14;
					//printf($file_template, $field['name'], $fieldname, $content_row[$fieldname], $fieldname, show_thumb($content_row[$fieldname],$size), $fieldname, $size);
				}
				if ( $field['formtype'] == 'fckhtml' ) {
					$base_path = $_SERVER['DOCUMENT_ROOT'];
					if ( CKEDITOR == 1 ) {
						include_once( "$base_path/admin/ckeditor/ckeditor.php" );
					} else {
						include_once( "$base_path/html_editor/fckeditor.php" );
					}
					echo "";
					// insert HTML editor
					$sBasePath = "/html_editor/";
					if ( CKEDITOR == 1 ) {
						$ckeditor               = new CKEditor();
						$ckeditor->returnOutput = true;
						$fck_output             = $ckeditor->editor( $fieldname, $content_row[$fieldname] );
					} else {
						$oFCKeditor             = new FCKeditor( $fieldname );
						$oFCKeditor->BasePath   = $sBasePath;
						$oFCKeditor->ToolbarSet = $this->ToolbarSet;
						$oFCKeditor->Value      = $content_row[$fieldname];
						$fck_output             = $oFCKeditor->CreateHtml();
					}
					printf( $htmlfield_template, $field['name'], $fck_output );
				}
				if ( $field['formtype'] == 'checklink' ) {
					$checklink  = $this->links[$field['link']];
					$primaryKey = ( empty( $checklink['primary'] ) ) ? $this->table . '_id' : $checklink['primary'];
					$foreignKey = ( empty( $checklink['foreign'] ) ) ? $checklink['table'] . '_id' : $checklink['foreign'];
					$linksql    = sprintf( "select t.id, t.%s, l.%s from %s t left outer join %s l on t.id = l.%s and l.%s = '%s'", $checklink['name'], $foreignKey, $checklink['table'], $checklink['link_table'], $foreignKey, $primaryKey, $content_row[id] );
					$linkresult = mysql_query( $linksql );
					if ( ( ! $linkresult ) && ( DEBUG_MODE == 'DEBUG' ) ) {
						echo $linksql;
					}
					$inner = "";
					while ( $linkrow = mysql_fetch_array( $linkresult ) ) {
						$checked = ( $linkrow[$foreignKey] ) ? "checked" : "";
						$inner .= sprintf( $checklink_inner_template, $fieldname, $checked, $linkrow['id'], $linkrow[$checklink['name']] );
					}
					$checkBlockButtons = "<br /><button onclick=\"setAllChecks('{$fieldname}[]', true); return false\">Tick All</button> <button onclick=\"setAllChecks('{$fieldname}[]', false); return false\">Untick All</button>";
					printf( $checklink_outer_template, $field['name'], $checkBlockButtons, $inner );
				}
			}
			// Show the submit button
			printf( "<tr valign=top><td></td><td>" );
			if ( $this->override_cancel_button ) {
				printf( $this->override_cancel_button_code );
			} else {
				printf( "<INPUT TYPE=\"button\" VALUE=\"Cancel\" onClick=\"history.go(-1)\">" );
			}
			printf( "&nbsp;&nbsp;&nbsp;<input type=submit name=submit_edit value=\"Submit\">
			 </td></tr>" );
			echo "</table>";
			echo "</form>";
			echo "</div>";
		}

		function delete_links( $id ) {
			if ( ! empty( $this->links ) ) {
				foreach ( $this->links as $fieldname => $ext_link ) {
					$delete_link_sql    = sprintf( "delete from %s where %s_id = '%s'", $ext_link['link_table'], $this->table, $id );
					$delete_link_result = mysql_query( $delete_link_sql );
				}
			}
		}

		function delete_item( $id ) {
			if ( $this->delete_field ) {
				$delete_content_sql = "update {$this->table} set {$this->delete_field} = 0 WHERE id=$id";
			} else {
				$delete_content_sql = "DELETE FROM {$this->table} WHERE id=$id";
			}
			return mysql_query( $delete_content_sql );
		}

		function delete_page( $id, $confirm_delete, $parent_id = false ) {
			$message = '';
			$this->delete_links( $id );
			// DELETE ITEM
			$delete_content_result = $this->delete_item( $id );
			if ( ! $delete_content_result ) {
				$message .= "<p>This item was unable to be deleted";
			}
			return $message;
		}

		function show_row_title( $content_row, $row_level, $row_title_class ) {
			foreach ( $this->fields as $fieldname => $field ) {
				if ( isset( $field['list'] ) && ( $field['list'] == true ) ) {
					if ( $field['formtype'] == 'lookup' ) {
						$func  = $field['titlefunction'];
						$value = $this->$func( $content_row[$fieldname] );
					} else if ( $field['formtype'] == 'image' ) {
                        $fn = $fieldname;
                        $f = $field;
                        $src =  $content_row[$fn];
                        $has_image = true;
                        $thumb_image = show_thumb($src,'140x140','','','class="list_image"');

                    } else if ( ( $field['formtype'] == 'staticdate' ) || ( $field['formtype'] == 'date' ) ) {
						$value = date( 'd/m/y', $content_row[$fieldname] );
					} else {
						$value = $content_row[$fieldname];
					}
					if ( ( $field['formtype'] == 'textarea' ) && isset( $field['shorten'] ) && ( $field['shorten'] == true ) ) {
						$value = $this->shorten_text( $value, 140 );
					}
					if ( isset( $field['list_prefix'] ) ) {
						$rowname .= $field['list_prefix'];
					} else {
						$rowname .= ( $rowname ) ? ' ' : '';
					}
					// need this to stop the repeat of the last listing title
					if($field['formtype'] != 'image'){
						$rowname .= $value;
					}
				}
			}
			$class = $row_level . 'fieldactive';
			if ( ( $this->hidable ) && ( $content_row['published'] == 0 ) ) {
				$class = $row_level . 'fieldinactive';
			}


            if($has_image){

                printf( "<div class='$class image_row'><div   class='$row_title_class '>%s</div></div>%s", $rowname, $thumb_image );
            }else {
                printf( "<div class='$class'><div class='$row_title_class'>%s</div></div>", $rowname );
            }
		}

		function show_list( $parent_id = false ) {
			echo "<div id='top-row'>";
			/* echo "<a href=\"{$this->page_self}?edit_item=yes\" onmouseout='button_off(this)' onmouseover='button_over(this)'>";
			  printf ("<img src='/admin/images/buttons/cmsbutton-Add_%s_%s-off.gif' alt='Add %s %s' name='cmsbuttonadd%s'/></a>",
			  $this->singular, $this->single_name, $this->singular, $this->single_name, $this->singular);
			 */
			if ( $this->customer_filter_bar ) {
				include( $this->custom_filter_bar_include_file );
			}
			if ( $this->list_top_text ) {
				echo $this->list_top_text;
			} else {
				echo '&nbsp;';
			}
			echo $this->GetFilterFormText();
			echo "</div>";
			if ( ! $this->list_items( $parent_id ) ) {
				echo "<div class='adminboxbuttons'>";
				printf( "<p>There are currently no {$this->group_name}<p>" );
				echo "</div>";
			}
			echo "<div class='sectionbutton-row'>";
			if ( $this->list_bottom_text ) {
				echo $this->list_bottom_text;
			}
			echo "</div>";
		}

		function get_customer_filter_bar_custom_sql() {
			return "SELECT * FROM {$this->table} order by 1 ";
		}

		function list_items( $parent_id = false ) {
			$result = true;
			echo "<div class='item_rows'>";
			$where = $this->where_clause;
			$where = $this->GetFilterWhere( $where );
			if ( $where == "" ) {
				$where = ( $parent_id ) ? "where {$this->parent_field} = {$parent_id}" : '';
			} else {
				$where .= ( $parent_id ) ? " and {$this->parent_field} = {$parent_id}" : '';
			}
			if ( $this->custom_list_sql == "" ) {
				if ( $this->paginated ) {
					$content_sql = "SELECT SQL_CALC_FOUND_ROWS * FROM {$this->table} $where";
				} else {
					$content_sql = "SELECT * FROM {$this->table} $where";
				}
				$order_clause = "";
				if ( isset( $this->grouping ) ) {
					$order_clause = $this->grouping . ', ';
				}
				if ( $this->order_clause ) {
					$order_clause .= "{$this->order_clause}";
				} else if ( $this->ordered ) {
					$order_clause .= "order_num";
				}
				if ( $order_clause ) {
					$content_sql .= ' order by ' . $order_clause;
				}
				if ( $this->paginated ) {
					$page      = isset( $_REQUEST['page'] ) ? $_REQUEST['page'] : 1;
					$pagestart = ( $page - 1 ) * $this->paginate_items_per_page;
				//	$content_sql .= " limit $pagestart, 15 ";
				//	echo $content_sql;
				}
			} else {
				$content_sql = $this->custom_list_sql;
			}
			if ( $this->customer_filter_bar ) {
				$content_sql = $this->get_customer_filter_bar_custom_sql();
			}
			if ( $this->paginated ) {
				$pagesize  = $this->paginate_items_per_page;
				$page      = isset( $_REQUEST['page'] ) ? $_REQUEST['page'] : 1;
				$pagestart = ( $page - 1 ) * $pagesize;
				$content_sql .= " limit $pagestart, $pagesize ";
			}

			$content_result = mysql_query( $content_sql );
			$this->log( $content_sql );
			if ( $list_top_text ) {
				echo $list_top_text;
			}
			$row_count = mysql_num_rows( $content_result );
			if ( $row_count ) {
				$row_num = 1;
				$ordered       = $this->ordered;
				$hideable      = $this->hideable;
				$deletable     = $this->deletable;
				$current_group = '';
				if ( $this->paginated ) {
					$totalRowCount = db_get_single_value( 'SELECT FOUND_ROWS();' );
					//  echo 'total row count is ' . $totalRowCount;
					$pageCount = ceil( $totalRowCount / $this->paginate_items_per_page );
					//  echo ' <br> <b> page count is ' . $pageCount . ' </b>  pagesize is ' . $pagesize;
					$pagination               = new pagination();
					$pagination->itemsPerPage = $this->paginate_items_per_page;
					$pagination->perPage = $this->paginate_items_per_page;
					$pagination->set_pages( $totalRowCount );
					$pagination->set_page( $page );
					// force the page override .. something not working properly
					$pagination->pages = $pageCount;
					//  $address = $_SERVER['REQUEST_URI'];
					$address = $_SERVER['PHP_SELF'];
					//  $address .= (strpos($address, '?') === false) ? '?' : '&';
					$address .= '?page=';
					echo "<div class='search_pagination'><ul>";
					echo $pagination->html_string( $address );
					// var_dump($pagination);
					echo "</ul><div class='search_pagination_recs_found'> Listing " . $totalRowCount . " item(s)  </div></div>";
				}
				while ( $content_row = mysql_fetch_array( $content_result ) ) {
					$rowname = '';
					if ( method_exists( $this, 'row_show' ) && ! $this->row_show( $content_row["id"] ) ) {
						continue;
					}
					if ( ( $this->grouping_two_levels ) && isset( $this->grouping ) && ( $current_group != $content_row[$this->grouping_child] ) ) {
						$current_group        = $content_row[$this->grouping_child];
						$current_group_parent = $content_row[$this->grouping_parent];
						$func                 = $this->grouping_name_function;
						$group_heading        = $this->$func( $current_group_parent, $current_group );
						echo "<h1 class=\"fixed\">$group_heading</h1>";
					}
					if ( ( ! $this->grouping_two_levels ) && isset( $this->grouping ) ) {
						$func          = $this->grouping_name_function;
						$group_heading = $this->$func( $content_row['id'] );
						if ( $current_group != $group_heading ) {
							$current_group = $group_heading;
							echo "<h1 class=\"fixed\">$group_heading</h1>";
						}
					}
					$fixed = '';
					if ( ( $this->ordered ) && ( ! $ordered ) ) {
						$fixed = 'class="fixed"';
					}
					$func = isset( $this->subfunction ) ? $this->subfunction : '';
					if ( ( ( $parent_id ) && ( $this->parent_child ) ) || ( ( $func != '' ) && ( $this->$func( $content_row['id'] ) == 1 ) ) ) {
						$row_level       = 'contentsub';
						$row_title_class = 'sub-row-title-text';
						$move_level      = 'sub';
						echo "<div id='{$move_level}item-{$content_row["id"]}' $fixed><div class='subsection-row'>";
					} else {
						$row_level       = 'content';
						$row_title_class = 'row-title-text';
						$move_level      = '';
						echo "<div id='{$move_level}item-{$content_row["id"]}' $fixed><div class='section-row'>";
					}
					$button_type = $row_level . 'button';
					$parent_part = ( $parent_id ) ? "{$this->parent_id_name}=$parent_id&" : '';
					$this->show_row_title( $content_row, $row_level, $row_title_class );
					foreach ( $this->buttons as $buttonname => $button ) {
						/*
						  if (method_exists($this, 'row_show_button') && !$this->row_show_button($buttonname, $content_row["id"]))
						  continue;
						 */
						if ( $button['type'] == 'function' ) {
							$function = $button['function'];
							$this->$function( $content_row["id"] );
						}
						if ( $button['type'] == 'standard_edit' ) {
							$href = sprintf( "%s?%sedit_item=yes&id=%s", $this->page_self, $parent_part, $content_row["id"] );
							$this->cms_admin_button( $href, $button_type, 'edit' );
						}
						if ( ( $button['type'] == 'standard_hide' ) && ( $hideable ) ) {
							$hide_show = ( $content_row["published"] ) ? 'hide' : 'show';
							$href      = sprintf( "%s?%s%s_item=yes&id=%s", $this->page_self, $parent_part, $hide_show, $content_row["id"] );
							$this->cms_admin_button( $href, $hide_show . $row_level, $hide_show, "onclick='return {$hide_show}_item(this, \"{$this->table}\", \"{$content_row["id"]}\");'" );
						}
						if ( ( $button['type'] == 'standard_delete' ) && ( $deletable ) && ( $row_count > $this->min_items ) ) {
							$href = sprintf( "%s?%sdelete_item=yes&id=%s", $this->page_self, $parent_part, $content_row["id"] );
							$this->cms_admin_button( $href, $button_type, 'delete', "onclick='return confirm(\"Are you sure?\")'" );
						}
						if ( ( $button['type'] == 'standard_move' ) && ( $ordered ) ) {
							echo "<div style='width:10px; float:left;' >";
							$buttons = "onmouseout='button_off(this)' onmouseover='button_over(this)'";
							printf( "<img onclick='move_item_up(this, \"%s\", \"%s\")' %s name='moveupbtn%s' style='border:0' src='/admin/images/%sbutton-moveup-off.gif'/>", $this->table, $content_row["id"], $buttons, $content_row["id"], $move_level );
							printf( "<img onclick='move_item_down(this, \"%s\", \"%s\")' %s name='movedownbtn%s' style='border:0' src='/admin/images/%sbutton-movedown-off.gif' />", $this->table, $content_row["id"], $buttons, $content_row["id"], $move_level );
							echo "</div>";
						}
						if ( $button['type'] == 'button' ) {
							$href = sprintf( $button['pattern'], $content_row["id"], $parent_id );
							$this->cms_admin_button( $href, $button_type, $button['text'] );
						}
						/**/
						if ( $button['type'] == 'jsbutton' ) {
							$href = sprintf( $button['pattern'], $content_row["id"] );
							$js   = sprintf( $button['js'], $content_row["id"] );
							$this->cms_admin_button( $href, $button_type, $button['text'], $js );
						}
					}
					echo "&nbsp;<br/></div>";
					if ( $this->child ) {
						$this->child->list_items( $content_row["id"] );
					};
					echo "</div>";
					$row_num = $row_num + 1;
				}
				echo "</div>";
			} else {
				echo "</div>";
				$result = false;
			}
			$show_add = 1;
			if ( method_exists( $this, 'add_button_show' ) ) {
				$show_add = $this->add_button_show( $parent_id );
			}
		 	if ( $show_add && ( ( $this->max_items == 0 ) || ( $row_count < $this->max_items ) ) ) {

				//echo "<div style='height:31px;width:100%;background:url(/admin/images/lightbox-bk.gif)'>";
				if ( ( $parent_id ) && ( $this->parent_child ) && ( ( $this->parent_child ) ) ) {
					echo "<div class='subsectionbutton-row fixed'>";
					$add_level = 'sub';
				} else {
					echo "<div class='sectionbutton-row fixed'>";
					$add_level = '';
				}
				$parentpart = ( $parent_id ) ? "&{$this->parent_id_name}=$parent_id" : '';
				$show_add   = true;
				echo "<a href=\"{$this->page_self}?edit_item=yes$parentpart\" onmouseout='button_off(this)' onmouseover='button_over(this)'>";
				printf( "<img src='/admin/images/buttons/cms%sbutton-Add_%s_%s-off.gif' alt='Add %s %s' name='cms%buttonadd%s%s'/></a>", $add_level, $this->singular, $this->single_name, $this->singular, $this->single_name, $add_level, $this->singular, $parent_id );
				echo "</div>";
			}
			return $result;
		}
	}