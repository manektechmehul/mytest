<?php
	include '../../../admin/classes/template.php';

	class newsAdmin extends template {
		function __construct() {
			$this->template();
			$this->table           = 'news';
			$this->group_name      = 'News';
			$this->single_name     = 'News';
			$this->has_page_name   = true;
			$this->singular        = 'a';
			$this->hideable        = true;
			$this->has_page_name   = true;
			$this->order_clause    = '`title`';
			$this->javascript_file = 'js/admin.js';
			$this->ToolbarSet      = 'Default';
			$this->list_top_text   = '<style> #top-row{ display: none; } </style>';
			$this->buttons         = array(
				'edit' => array( 'text' => 'add', 'type' => 'standard_edit' ),
				'hide' => array( 'text' => 'hide', 'type' => 'standard_hide' ),
				//'featured' => array( 'text' => 'hide', 'type' => 'function', 'function' => 'set_featured' ),
				'archive' => array( 'text' => 'hide', 'type' => 'function', 'function' => 'set_archive' ),
				'delete' => array( 'text' => 'delete', 'type' => 'standard_delete' ),
				'move' => array( 'text' => 'move', 'type' => 'standard_move' ),
			);
			// unix_timestamp(date) as
			$this->custom_list_sql = 'select SQL_CALC_FOUND_ROWS *  from news order by date desc';
			$this->fields          = array(
				'title' => array( 'name' => 'Name', 'formtype' => 'text', 'list' => true, 'required' => true, 'primary' => true ),
				'date' => array( 'name' => 'Date', 'formtype' => 'date', 'required' => true ),
				//'time' => array('name' => 'Time', 'formtype' => 'time', 'list' => true, 'required' => true ),
				'page_name' => array( 'name' => 'Page Name', 'formtype' => 'hidden', 'keepfirst' => true ),
				//'location' => array('name' => 'Location', 'formtype' => 'text', 'size' => 70, 'required' => true),
				//'fee' => array('name' => 'Fee', 'formtype' => 'text', 'size' => 10, 'required' => true),
				//'deadline' => array('name' => 'Booking Deadline', 'formtype' => 'date', 'required' => true ),
				//'thumbnail' => array('name' => 'Thumbnail', 'formtype' => 'image', 'list' => false, 'required' => false, 'size' => 2),
				'summary' => array( 'name' => 'Summary', 'formtype' => 'textarea', 'list' => false, 'rows' => 3, 'cols' => 60, 'required' => true ),
				//'keywords' => array('name' => 'Keywords', 'formtype' => 'textarea', 'list' => false, 'rows' => 3, 'cols' => 60, 'required' => true),
				'body' => array( 'name' => 'Main Content', 'formtype' => 'fckhtml', 'list' => false, 'required' => true ),
				//'file' => array('name' => 'Document', 'formtype' => 'file', 'list' => false, 'required' => true),
			);
		}

		function onload() {
			if ( NEWS_ADMIN_PAGINATE ) {
				$this->paginated               = true;
				$this->paginate_items_per_page = NEWS_ADMIN_PAGINATE_ITEMS_PER_PAGE;
			}
			// add the gallery chooser drop down if appropriate
			if ( NEWS_HAS_INLINE_GALLERIES ) {
				$this->fields['gallery'] = array( 'name' => 'Gallery', 'formtype' => 'lookup', 'required' => false, 'function' => 'gallerylookup' );
			}

			if ( SITE_HAS_DONATE ) {
				$this->fields['donate'] = array('name' => 'Donation', 'formtype' => 'lookup', 'required' => false, 'function' => 'donatelookup');
			}
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

		function show_row_title( $content_row, $row_level, $row_title_class ) {
			parent::show_row_title( $content_row, $row_level, $row_title_class );
			echo '<div class="newseventdatefieldactive">' . date( 'd/m/Y', strtotime( $content_row['date'] ) ) . '</div>';
		}

		function get_form_data( $id, $parent_id = false ) {
			if ( NEWS_HAS_THUMBNAIL == 1 ) {
				$this->fields['thumbnail'] = array( 'name' => 'Thumbnail', 'formtype' => 'image', 'list' => false, 'required' => false, 'size' => 2 );
			}
			parent::get_form_data( $id, $parent_id = false );
		}

		function show_edit( $id, $parent_id = false ) {
			if ( NEWS_HAS_THUMBNAIL == 1 ) {
				$this->fields['thumbnail'] = array( 'name' => 'Thumbnail', 'formtype' => 'image', 'list' => false, 'required' => true, 'size' => 2 );
			}
			parent::show_edit( $id, $parent_id = false );
		}

		function delete_item( $id ) {
			$sql = "delete from news where id = $id";
			return mysql_query( $sql );
		}

		function set_archive( $id ) {
			$archive   = db_get_single_value( 'select archive from ' . $this->table . ' where id = ' . $id, 'archive' );
			$hide_show = ( $archive ) ? 'hide' : 'show';
			$archive   = ( $archive ) ? 'archive' : 'not archive';
			$href      = sprintf( "%s?%s%s_item=yes&id=%s", $this->page_self, $parent_part, $hide_show, $content_row["id"] );
			$this->cms_admin_button( $href, $hide_show . 'content', $archive, "onclick='return set_archive_item(this, \"$archive\",\"$id\");'" );
		}



		function get_crumbs( $page ) {
			if ( $page == '' ) {
				return "<b>{$this->single_name} Admin</b>";
			} else {
				return "<a href='blogs.php'>{$this->single_name} Admin</a> > <b>$page</b>";
			}
		}
	}

	$template = new newsAdmin();

	$main_page  = 'index.php';
	$main_title = 'Return to main page';


	$admin_tab = "news";

	//include 'second_level_navigavtion.php';
	include( "../../../admin/template.php" );
?>


