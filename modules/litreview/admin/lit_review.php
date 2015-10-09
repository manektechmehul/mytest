<?php
include '../../../admin/classes/template.php'; 

class litReviewAdmin extends template
{
	function __construct()
	{
		$this->template();
         
                
		$this->table = 'lit_review';
		
		$this->single_name = 'Literature Review ';
		
		$this->has_page_name = true;
		$this->singular = 'a';
		$this->hideable = true;
                $startOfYesterday = mktime(0,0,0,date("m"),date("d"),date("Y"));
                $date = date('y-m-d', $startOfYesterday);
                
                
                /* put thses bits in to add a categorisation to the listing page */                
                $this->group_name = 'Categories';
                $this->grouping = " group by category_id ";
                $this->grouping_name_function = 'getcategoryname';
                
                
		$this->order_clause = '`title`';	
		$this->javascript_file = 'js/admin.js';		
		$this->ToolbarSet = 'Default';

		//$this->subfunction = 'subfunction';

               $this->ordered = true;
                
                
                 $this->buttons = array(
                            'edit' => array( 'text' => 'add', 'type' => 'standard_edit'),
                            'hide' => array( 'text' => 'hide', 'type' => 'standard_hide'),
                            //'featured' => array( 'text' => 'hide', 'type' => 'function', 'function' => 'set_featured'),
                           // 'archive' => array( 'text' => 'hide', 'type' => 'function', 'function' => 'set_archive'),
                            'delete' => array( 'text' => 'delete', 'type' => 'standard_delete'),
                            'move' => array( 'text' => 'move', 'type' => 'standard_move'),
                            );	
                 
		//$this->custom_list_sql = 'select * from lit_review order by  order_num';

             $this->custom_list_sql = 'SELECT lr.*, lrc.`name` FROM lit_review AS  lr
INNER JOIN lit_review_categories AS lrc ON lr.`category_id` = lrc.`id`
ORDER BY  category_id, lrc.`name` , order_num'; 
                
              /*   $this->custom_list_sql = 'SELECT * FROM lit_review AS  lr
INNER JOIN lit_review_categories AS lrc ON lr.`category_id` = lrc.`id`
ORDER BY  NAME, order_num'; */
                 
        //$this->list_top_text = '<img src="/admin/images/events-field-names.gif">';
        //$this->list_top_text = '<div style="float: left; font-size:8.5px; font-weight:bold; padding-left: 30px; width: 263px">&or; events item title</div><div style="float: left; font-size:8.5px; font-weight:bold; padding-left: 10px">&or; events item date</div>';

		$this->fields = array( 
			'title' => array('name' => 'Name', 'formtype' => 'text', 'list' => true, 'required' => true, 'primary' => true),
			
                         //'startdate' => array('name' => 'Start Date', 'formtype' => 'date', 'required' => true ),
			//'enddate' => array('name' => 'End Date', 'formtype' => 'date', 'required' => true ),
			//'time' => array('name' => 'Time', 'formtype' => 'time', 'list' => true, 'required' => true ),
                        //'page_name' => array('name' => 'Page Name', 'formtype' => 'hidden', 'keepfirst' => true),
			//'location' => array('name' => 'Location', 'formtype' => 'text', 'size' => 70, 'required' => true),
			//'fee' => array('name' => 'Fee', 'formtype' => 'text', 'size' => 10, 'required' => true),
			//'deadline' => array('name' => 'Booking Deadline', 'formtype' => 'date', 'required' => true ),
			//'thumb' => array('name' => 'Thumbnail', 'formtype' => 'image', 'list' => false, 'required' => true, 'size' => 2),
                        'category_id' =>  array('name' => 'Main Category', 'formtype' => 'lookup', 'function' => 'categorylookup', 'required' => false),                     
                        'author' => array('name' => 'Author', 'formtype' => 'text' ),
                        'date_of_publication' => array('name' => 'Date of Publication', 'formtype' => 'text' ),
                        'origin' => array('name' => 'Country/Origin', 'formtype' => 'text' ),
                    
                    
			// 'archive' => array('name' => 'archive', 'formtype' => 'checkbox', 'list' => false, 'rows' => 3, 'cols' => 60, 'required' => true),
			//'keywords' => array('name' => 'Keywords', 'formtype' => 'textarea', 'list' => false, 'rows' => 3, 'cols' => 60, 'required' => true),
			'body' => array('name' => 'Main Content', 'formtype' => 'fckhtml', 'list' => false, 'required' => true),
			//'file' => array('name' => 'Document', 'formtype' => 'file', 'list' => false, 'required' => true),
		);			

	}
/*
    function group_name($id)
    {
      /*  $startOfYesterday = mktime(0,0,0,date("m"),date("d"),date("Y"));
        $date = date('y-m-d', $startOfYesterday);
        $sql = "select enddate < '$date' from events where id = '$id'";
        $expired = db_get_single_value($sql);
        return ($expired) ? 'Expired Events' : 'Upcoming Events';
        
        return "Literature Reviews";
    }

    function show_row_title($content_row, $row_level, $row_title_class)
    {
        parent::show_row_title($content_row, $row_level, $row_title_class);

        // echo '<div class="newseventdatefieldactive"> category </div>';
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
    function getcategoryname($id)
    {
       // $cat_name =  db_get_single_value("SELECT name FROM lit_review_categories WHERE id = '$id'", 'name');
         $cat_name =  db_get_single_value("SELECT  lrc.`name` FROM lit_review AS  lr
 INNER JOIN lit_review_categories AS lrc ON lr.`category_id` = lrc.`id`
 WHERE lr.id = '$id'", 'name');
        
        
        return  $cat_name;
    }
    
     function categorylookup($id)
    {
        $sql = 'select * from lit_review_categories ';
        $result = mysql_query($sql);

        $out = '<select name="category_id">';
        while ($row = mysql_fetch_array($result))
        {
            $selected = ($id == $row[id]) ? ' selected="selected"' : '';
            $out .= '<option value="'.$row['id'].'"'.$selected.' >'.$row[name].'</option>';
        }        
        $out .= '</select>';
        
        return $out;
    }
    
	function delete_item($id)
	{
		$sql = "delete from lit_review where id = $id";
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
/*
	function set_featured($id)
	{
		$featured = db_get_single_value('select featured from '.$this->table.' where id = '.$id, 'featured');
		$hide_show = ($featured) ? 'hide' : 'show';
		$featured = ($featured) ? 'featured' : 'not featured';
		$href = sprintf ("%s?%s%s_item=yes&id=%s", $this->page_self, $parent_part, $hide_show, $content_row["id"]);
		$this->cms_admin_button($href, $hide_show.'content', $featured, "onclick='return set_featured_item(this, \"$featured\",\"$id\");'");                            
	}
 * 
 */
	
    function get_crumbs($page)
    {
        if ($page == '')
            return "<b>{$this->single_name} Admin</b>";
        else
            return "<a href='lit_review.php'>{$this->single_name} Admin</a> > <b>$page</b>";
    }      
}

$template = new litReviewAdmin();

$main_page = 'index.php';
$main_title = 'Return to main page';
			

$admin_tab = "Lit Review";

//include 'second_level_navigavtion.php';
include ("../../../admin/template.php");
?>


