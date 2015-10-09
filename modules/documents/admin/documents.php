<?php
include '../../../admin/classes/template.php'; 

class documents extends template 
{
	function __construct()
	{
		$this->template();
		$this->table = 'documents';
		$this->group_name = 'Documents';
		$this->single_name = 'Document';
		$this->debug = true;
		$this->singular = 'a';
		$this->hideable = true;
		//$this->list_top_text = "The case study at the top of the list will be featured on the home page";

		$this->order_clause = '`date`';
	
		$this->javascript_file = 'js/admin.js';
		
		$this->ToolbarSet = 'Default';

		//$this->subfunction = 'subfunction';

        $this->buttons = array(
                            'edit' => array( 'text' => 'add', 'type' => 'standard_edit'),
                            'hide' => array( 'text' => 'hide', 'type' => 'standard_hide'),
                            //'featured' => array( 'text' => 'hide', 'type' => 'function', 'function' => 'set_featured'),
                            'delete' => array( 'text' => 'delete', 'type' => 'standard_delete'),
                            'move' => array( 'text' => 'move', 'type' => 'standard_move'),
                            );		
		$this->custom_list_sql = 'select id,  title, summary, description, published,  `date` from documents order by `date` desc';
		
		$this->fields = array( 
			//'publishdate' => array('name' => 'Publish on', 'formtype' => 'date', 'required' => true ),
			'date' => array('name' => 'Date', 'formtype' => 'date',  'required' => true ),
			//'time' => array('name' => 'Time', 'formtype' => 'time', 'list' => true, 'required' => true ),
			'title' => array('name' => 'Title', 'formtype' => 'text', 'list' => true, 'required' => true, 'primary' => true),
			'category' => array('name' => 'Categories', 'formtype' => 'checklink', 'required' => true, 'not_field' => true, 'link' => 'category', 
				'customfunction' => 'category_checklist'),
            
			//'location' => array('name' => 'Location', 'formtype' => 'text', 'size' => 70, 'required' => true),
			//'fee' => array('name' => 'Fee', 'formtype' => 'text', 'size' => 10, 'required' => true),
			//'deadline' => array('name' => 'Booking Deadline', 'formtype' => 'date', 'required' => true ),
			//'thumb' => array('name' => 'Thumbnail', 'formtype' => 'image', 'list' => false, 'required' => true, 'size' => 2),
			'summary' => array('name' => 'Summary', 'formtype' => 'textarea', 'list' => false, 'rows' => 3, 'cols' => 60, 'required' => true),
			'keywords' => array('name' => 'Keywords', 'formtype' => 'textarea', 'list' => false, 'rows' => 3, 'cols' => 60, 'required' => true),
			//'description' => array('name' => 'Main Content', 'formtype' => 'fckhtml', 'list' => false, 'required' => true)
                    
                        
                        'link_type' => array('name' => 'Link Type', 'formtype' => 'lookup', 'required' => false, 'function' => 'linktypelookup'),
                    
                    'file' => array('name' => 'Document', 'formtype' => 'file', 'list' => false, 'required' => false),
                    'audio' => array('name' => 'Audio', 'formtype' => 'checkbox', 'list' => false, 'required' => false),
                    'thumb' => array('name' => 'Thumbnail', 'formtype' => 'image', 'list' => false, 'required' => false, 'size' => 2),
                    
                    
                        'link' => array('name' => 'Link', 'formtype' => 'text', 'list' => false, 'required' => false),
                        'external_link' => array('name' => 'External link', 'formtype' => 'checkbox', 'required' => false),
			
			
			

                        'video_type' => array('name' => 'Video Source', 'formtype' => 'lookup', 'required' => false, 'function' => 'videotypelookup'),
                        'video_id' => array('name' => 'Video id', 'formtype' => 'shorttext', 'required' => false),

		);			

		$cat_sql = 'select id, title from documents_category order by special desc,   case special when 1 then id when 0 then title end';;
		$this->links = array( 'category' => array('link_table' => 'documents_category_link', 'table' => 'documents_category', 'name' => 'title') );

	}

    function linktypelookup($id) {
        // see the onpagelinks / admin / js file for layout swithing
        // keep the id on this dropdown for the js			
		$types = '';	
                // case studies not yet implemented
                // set db config table for setting
		if(DOWNLOAD_CAT_CASESTUDIES){
			$types .= '1,';
		}
		if(DOWNLOAD_CAT_DOWNLOADS){
			$types .= '2,';
		}
		if(DOWNLOAD_CAT_LINK){
			$types .= '3,';
		}
		if(DOWNLOAD_CAT_LINK){
			$types .= '4,';
		}
		if(DOWNLOAD_CAT_STATIC_LINK){
			$types .= '5,';
		}		
		$types = rtrim($types, ',');		
        $sql = 'select * from document_type where id in (' . $types . ') and active= 1 order by title';
	
		
        $result = mysql_query($sql);
        //	echo $sql;
        $out = '<select id="link_type" name="link_type">';
        while ($row = mysql_fetch_array($result)) {
            $selected = ($id == $row[id]) ? ' selected="selected"' : '';
            $out .= '<option value="' . $row['id'] . '"' . $selected . ' >' . $row['title'] . '</option>';
        }
        $out .= '</select>';

        return $out;
    }

    function videotypelookup($id) {
        $sql = 'select * from documents_video_type order by title';
        $result = mysql_query($sql);
        // echo $sql;
        $out = '<select name="video_type">';
        while ($row = mysql_fetch_array($result)) {
            $selected = ($id == $row[id]) ? ' selected="selected"' : '';
            $out .= '<option value="' . $row['id'] . '"' . $selected . ' >' . $row['title'] . '</option>';
        }
        $out .= '</select>';

        return $out;
    }




        function show_row_title($content_row, $row_level, $row_title_class)
    {
        parent::show_row_title($content_row, $row_level, $row_title_class);

        echo '<div class="newseventdatefieldactive">'.date('d/m/Y',strtotime($content_row['date'])).'</div>';
    }
        
	function category_checklist($id, $fieldname, $field)
	{
		$checklink = $this->links[$field['link']];
		$linksql = "select t.id, t.title, t.special, l.documents_id from documents_category t left outer join documents_category_link l".
			" on t.id = l.documents_category_id and l.documents_id = '$id' order by title";
			$linkresult = mysql_query($linksql);
;
		$template = '<span class="form-checkbox"><input type=checkbox name=%s[] %s value=%s>%s</span>';
		$specialflag = 1;
		while ($linkrow = mysql_fetch_array($linkresult))
		{
			if ($specialflag) {
				if ($linkrow['special'] == 0) {
					$specialflag = 0;
				}
			}
			$checked = ($linkrow['documents_id']) ? "checked" : "";                                             
			$inner .= sprintf($template, $fieldname, $checked, $linkrow['id'], $linkrow['title']);
		}
		printf ('<tr valign=top><td>%s</td><td><div class="form-checkbox-group">%s</div></td></tr>', $field['name'], $inner);			
	}
	
	
	function subfunction($id)
	{
		return db_get_single_value("select `date` <= now() as notexpired from documents where id = '$id'", 'notexpired');
	}
	
    function categorylookup($id)
    {
        $sql = 'select * from event_category order by title';
        $result = mysql_query($sql);
		echo $sql;
        $out = '<select name="event_category_id">';
        while ($row = mysql_fetch_array($result))
        {
            $selected = ($id == $row[id]) ? ' selected="selected"' : '';
            $out .= '<option value="'.$row['id'].'"'.$selected.' >'.$row['title'].'</option>';
        }        
        $out .= '</select>';
        
        return $out;
    }	

	function categoryname($id)
	{
		return (db_get_single_value("select title from documents_category where id = '$id'", 'title'));
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
*/	
    function get_crumbs($page)
    {
        if ($page == '')
            return "<b>{$this->single_name} Admin</b>";
        else
            return "<a href='blogs.php'>{$this->single_name} Admin</a> > <b>$page</b>";
    }      
}

$template = new documents(); 

$main_page = 'index.php';
$main_title = 'Return to main page';
			

$admin_tab = "documents";
$second_admin_tab = "documents";

include 'second_level_navigavtion.php';
include ("../../../admin/template.php");
?>


