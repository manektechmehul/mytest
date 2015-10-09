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
		//$this->ordered = true;
		$this->singular = 'a';
		$this->hideable = true;
		//$this->list_top_text = "The case study at the top of the list will be featured on the home page";

		$this->order_clause = '`date`';
	
		$this->javascript_file = 'js/case_study_admin.js';
		
		$this->ToolbarSet = 'Default';

		//$this->subfunction = 'subfunction';

        $this->buttons = array(
                            'edit' => array( 'text' => 'add', 'type' => 'standard_edit'),
                            'hide' => array( 'text' => 'hide', 'type' => 'standard_hide'),
                            //'featured' => array( 'text' => 'hide', 'type' => 'function', 'function' => 'set_featured'),
                            'delete' => array( 'text' => 'delete', 'type' => 'standard_delete'),
                            'move' => array( 'text' => 'move', 'type' => 'standard_move'),
                            );		
		$this->custom_list_sql = 'select id,  title, summary, description, published, unix_timestamp(date) as date from documents order by `date` desc';
		
		$this->fields = array( 
			//'publishdate' => array('name' => 'Publish on', 'formtype' => 'date', 'required' => true ),
			'date' => array('name' => 'Date', 'formtype' => 'date', 'list' => true, 'required' => true ),
			//'time' => array('name' => 'Time', 'formtype' => 'time', 'list' => true, 'required' => true ),
			'title' => array('name' => 'Title', 'formtype' => 'text', 'list' => true, 'required' => true, 'primary' => true),
			'members' => array('name' => 'Accessible by Members ', 'formtype' => 'checklink', 'required' => true, 'not_field' => true, 'link' => 'members', 
				'customfunction' => 'member_checklist'),
			//'location' => array('name' => 'Location', 'formtype' => 'text', 'size' => 70, 'required' => true),
			//'fee' => array('name' => 'Fee', 'formtype' => 'text', 'size' => 10, 'required' => true),
			//'deadline' => array('name' => 'Booking Deadline', 'formtype' => 'date', 'required' => true ),
			//'thumb' => array('name' => 'Thumbnail', 'formtype' => 'image', 'list' => false, 'required' => true, 'size' => 2),
			'summary' => array('name' => 'Summary', 'formtype' => 'textarea', 'list' => false, 'rows' => 3, 'cols' => 60, 'required' => true),
			//'keywords' => array('name' => 'Keywords', 'formtype' => 'textarea', 'list' => false, 'rows' => 3, 'cols' => 60, 'required' => true),
			//'description' => array('name' => 'Main Content', 'formtype' => 'fckhtml', 'list' => false, 'required' => true)
			'file' => array('name' => 'Document', 'formtype' => 'file', 'list' => false, 'required' => true),
		);			

		$cat_sql = 'select id, title from documents_category order by special desc,   case special when 1 then id when 0 then title end';;
		$this->links = array( 'members' => array('link_table' => 'document_member_user_link', 'table' => 'member_user', 'name' => 'firstname') );

	}	

	function member_checklist($id, $fieldname, $field)
	{
		$checklink = $this->links[$field['link']];
		$linksql = "select u.id, u.firstname, u.surname, u.username, u.type_id, l.documents_id from member_user u left outer join document_member_user_link l".
			" on u.id = l.member_user_id and l.documents_id = '$id' order by u.type_id, firstname";
		$linkresult = mysql_query($linksql);
		$template = '<span class="form-checkbox"><input type=checkbox name=%s[] %s value=%s>%s</span>';
		$inner = "<b>Clients</b><br />";
		$specialflag = 1;
		while ($linkrow = mysql_fetch_array($linkresult))
		{
			if ($specialflag) {
				if ($linkrow['type_id'] > 1) {
					$specialflag = 0;
					$inner .= '</td></tr><tr><td></td><td><b>Team</b><br />';
				}
			}
			$checked = ($linkrow['documents_id']) ? "checked" : "";    
			$name = "{$linkrow['firstname']} {$linkrow['surname']} ({$linkrow['username']})";
			$inner .= sprintf($template, $fieldname, $checked, $linkrow['id'], $name);
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
	
	function process_submit($id, $parent_id = false)
	{
		parent::process_submit($id, $parent_id);
		if (empty($id))
		{
			$sql = 'select email, firstname, surname 
					from document_member_user_link dmul 
					join member_user mu on mu.id = member_user_id
					where documents_id = '.$this->id;
			$result = mysql_query($sql);
			if (mysql_num_rows($result) > 0)
			{
				$headers = "From: \"Together for Regeneration\"<".SITE_CONTACT_EMAIL.">\r\n";
				$emailTitle = MEMBERS_DOCUMENT_EMAIL_TITLE;
				while ($row = mysql_fetch_array($result))
				{
					$body = str_replace(
						array('{title}', '{firstname}', '{surname}'), 
						array($this->data['title'], $row['firstname'], $row['surname']), 
						MEMBERS_DOCUMENT_EMAIL_BODY);
					$emailTo = $row['email'];
					mail($emailTo, $emailTitle, $body, $headers);
				}
			}
		}
	}
}

$template = new documents(); 

$main_page = 'index.php';
$main_title = 'Return to main page';
			

$admin_tab = "member_admin";
$second_admin_tab = "docs";
include 'second_level_navigavtion.php';

include 'second_level_navigavtion.php';
include ("../../../admin/template.php");



