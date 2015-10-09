<?php
include '../../../admin/classes/template.php';

class eventsAdmin extends template
{
	function __construct()
	{
		$this->template();
		$this->table = 'events';
		$this->group_name = 'Events';
		$this->single_name = 'Event';
		//$this->ordered = true;
		$this->has_page_name = true;
		$this->singular = 'a';
		$this->hideable = true;

        $startOfYesterday = mktime(0,0,0,date("m"),date("d"),date("Y"));
        $date = date('y-m-d', $startOfYesterday);

        $this->grouping = "case when enddate < '$date' then 1 else 0 end";
		//$this->list_top_text = "The case study at the top of the list will be featured on the home page";

        $this->grouping_name_function = 'group_name';

		$this->order_clause = '`title`';

		$this->javascript_file = 'js/admin.js';

		$this->ToolbarSet = 'Default';

		 	$this->list_top_text = '<style> #top-row{ display: none; } </style>';
		//$this->subfunction = 'subfunction';

        $this->buttons = array(
                            'edit' => array( 'text' => 'add', 'type' => 'standard_edit'),
                            'hide' => array( 'text' => 'hide', 'type' => 'standard_hide'),
                            'featured' => array( 'text' => 'hide', 'type' => 'function', 'function' => 'set_featured'),
                            'archive' => array( 'text' => 'hide', 'type' => 'function', 'function' => 'set_archive'),
                            'delete' => array( 'text' => 'delete', 'type' => 'standard_delete'),
                            'move' => array( 'text' => 'move', 'type' => 'standard_move'),
                            );
        // unix_timestamp(enddate) as enddate
		$this->custom_list_sql = 'select SQL_CALC_FOUND_ROWS *  from events order by enddate desc';

        //$this->list_top_text = '<img src="/admin/images/events-field-names.gif">';
        //$this->list_top_text = '<div style="float: left; font-size:8.5px; font-weight:bold; padding-left: 30px; width: 263px">&or; events item title</div><div style="float: left; font-size:8.5px; font-weight:bold; padding-left: 10px">&or; events item date</div>';

		$this->fields = array(
			'title' => array('name' => 'Name', 'formtype' => 'text', 'list' => true, 'required' => true, 'primary' => true),
			'startdate' => array('name' => 'Start Date', 'formtype' => 'date', 'required' => true ),
			'enddate' => array('name' => 'End Date', 'formtype' => 'date', 'required' => true ),
			//'time' => array('name' => 'Time', 'formtype' => 'time', 'list' => true, 'required' => true ),
                        'page_name' => array('name' => 'Page Name', 'formtype' => 'hidden', 'keepfirst' => true),
			//'location' => array('name' => 'Location', 'formtype' => 'text', 'size' => 70, 'required' => true),
			//'fee' => array('name' => 'Fee', 'formtype' => 'text', 'size' => 10, 'required' => true),
			//'deadline' => array('name' => 'Booking Deadline', 'formtype' => 'date', 'required' => true ),
			// 'thumbnail' => array('name' => 'Thumbnail', 'formtype' => 'image', 'list' => false, 'required' => true, 'size' => 2),
			'summary' => array('name' => 'Summary', 'formtype' => 'textarea', 'list' => false, 'rows' => 3, 'cols' => 60, 'required' => true),
			//'keywords' => array('name' => 'Keywords', 'formtype' => 'textarea', 'list' => false, 'rows' => 3, 'cols' => 60, 'required' => true),
			'body' => array('name' => 'Main Content', 'formtype' => 'fckhtml', 'list' => false, 'required' => true),
			//'file' => array('name' => 'Document', 'formtype' => 'file', 'list' => false, 'required' => true),
		);

	}

function onload() {
    if (EVENTS_ADMIN_PAGINATE) {
		$this->paginated = true;
		$this->paginate_items_per_page = EVENTS_ADMIN_PAGINATE_ITEMS_PER_PAGE;
	}

	// add the gallery chooser dropdown if appropriate
	if ( EVENTS_HAS_INLINE_GALLERIES ) {
		$this->fields['gallery'] = array('name' => 'Gallery', 'formtype' => 'lookup', 'required' => false, 'function' => 'gallerylookup');
	}
}


	function gallerylookup($id) {
		$sql = 'select * from gallery order by 1 asc';
		$result = mysql_query($sql);
		//	echo $sql;
		$out = '<select id="gallery" name="gallery">';
		$selected = ( $id == 0 ) ? ' selected="selected"' : '';
		$out .= '<option value="0"' . $selected . ' >No Gallery</option>';
		while ($row = mysql_fetch_array($result)) {
			$selected = ($id == $row[id]) ? ' selected="selected"' : '';
			$out .= '<option value="' . $row['id'] . '"' . $selected . ' >' . $row['title'] . '</option>';
		}
		$out .= '</select>';

		return $out;
	}

	function get_form_data ($id, $parent_id = false){
        if (EVENTS_HAS_THUMBNAIL == 1) {
            $this->fields['thumbnail'] = array('name' => 'Thumbnail', 'formtype' => 'image', 'list' => false, 'size' => 2);
        }
        parent::get_form_data($id, $parent_id = false);
    }
	
	function show_edit($id, $parent_id = false) {
        if (EVENTS_HAS_THUMBNAIL == 1) {
            $this->fields['thumbnail'] = array('name' => 'Thumbnail', 'formtype' => 'image', 'list' => false, 'size' => 2);
        }
        parent::show_edit($id, $parent_id = false);
    }

    function group_name($id)
    {
        $startOfYesterday = mktime(0,0,0,date("m"),date("d"),date("Y"));
        $date = date('y-m-d', $startOfYesterday);
        $sql = "select enddate < '$date' from events where id = '$id'";
        $expired = db_get_single_value($sql);
        return ($expired) ? 'Expired Events' : 'Upcoming Events';
    }

    function show_row_title($content_row, $row_level, $row_title_class)
    {
        parent::show_row_title($content_row, $row_level, $row_title_class);

        //echo '<div class="newseventdatefieldactive">'.date('d/m/Y',strtotime($content_row['startdate'])).'</div>';
        echo '<div class="newseventdatefieldactive" style="font-size:13.5px; padding:7px 0 0 8px; width:83px;">Ends '.date('d.m.y',strtotime($content_row['enddate'])).'</div>';
    }
/*
	function write_data($id, $parent_id)
	{
		// just make sure we have a 0
		if (empty($id))
			$id = 0;
		$sql = "call update_news(@id, '%title%', '%body%', '%date%', '%page_name%', 2, 1)";

		foreach ($this->fields as $fieldname => $field)
			$sql = str_replace('%'.$fieldname.'%', $this->data[$fieldname], $sql);
		mysql_query('set @id = '.$id);
		echo '<script>window.console.log("'.$sql.'");</script>';
		$result = mysql_query($sql);

		$id = db_get_single_value('select @id');
		return array(1,$id);
	}
*/
	function delete_item($id)
	{
		$sql = "delete from events where id = $id";
		return mysql_query($sql);
	}

	function set_archive($id)
	{
		$archive = db_get_single_value('select archive from '.$this->table.' where id = '.$id, 'archive');
		$hide_show = ($archive) ? 'hide' : 'show';
		$archive = ($archive) ? 'archive' : 'not archive';
		$href = sprintf ("%s?%s%s_item=yes&id=%s", $this->page_self, $parent_part, $hide_show, $content_row["id"]);
		$this->cms_admin_button($href, $hide_show.'content', $archive, "onclick='return set_archive_item(this, \"$archive\",\"$id\");'");
	}



    function get_crumbs($page)
    {
        if ($page == '')
            return "<b>{$this->single_name} Admin</b>";
        else
            return "<a href='blogs.php'>{$this->single_name} Admin</a> > <b>$page</b>";
    }
}

$template = new eventsAdmin();

$main_page = 'index.php';
$main_title = 'Return to main page';


$admin_tab = "events";

//include 'second_level_navigavtion.php';
include ("../../../admin/template.php");
?>


