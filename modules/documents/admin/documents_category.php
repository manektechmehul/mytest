<?php
include '../../../admin/classes/template.php'; 

class event_category extends template 
{
	function __construct()
	{
		$this->template();
		$this->table = 'documents_category';
		$this->group_name = 'Document Categories';
		$this->single_name = 'Document Category';
		$this->ordered = true;
		$this->singular = 'a';
		$this->hideable = true;
		$this->has_page_name = true;
		//$this->list_top_text = "The case study at the top of the list will be featured on the home page";
		
		$this->ToolbarSet = 'Default';

		$this->custom_list_sql = 'select id, title from documents_category where special = 0 order by order_num ';

        $this->buttons = array(
                            'edit' => array( 'text' => 'add', 'type' => 'standard_edit'),
                            //'hide' => array( 'text' => 'hide', 'type' => 'standard_hide'),
                            //'featured' => array( 'text' => 'hide', 'type' => 'function', 'function' => 'set_featured'),
                            'delete' => array( 'text' => 'delete', 'type' => 'standard_delete'),
                            'move' => array( 'text' => 'move', 'type' => 'standard_move'),
                            );		
		
		$this->fields = array( 
			'title' => array('name' => 'Title', 'formtype' => 'text', 'list' => true, 'required' => true, 'primary' => true),
            'page_name' => array('name' => 'Page Name', 'formtype' => 'hidden', 'keepfirst' => true),
//			'summary' => array('name' => 'Summary', 'formtype' => 'textarea', 'list' => false, 'rows' => 3, 'cols' => 60, 'required' => true),
//			'description' => array('name' => 'Main Content', 'formtype' => 'fckhtml', 'list' => false, 'required' => true)
			);			
	}	
	

    function get_crumbs($page)
    {
        if ($page == '')
            return "<b>{$this->single_name} Admin</b>";
        else
            return "<a href='blogs.php'>{$this->single_name} Admin</a> > <b>$page</b>";
    }    

	function row_show_button($button, $id)
	{
		$result = true;
		if (($button == 'delete') && (db_get_single_value('select count(*) as used from documents_category_link where documents_category_id = '.$id, 'used') > 0))
			$result = false;
		return $result;
	}
}

$template = new event_category(); 

$main_page = 'index.php';
$main_title = 'Return to main page';
			

$admin_tab = "documents";
$second_admin_tab = "categories";

include 'second_level_navigavtion.php';
include ("../../../admin/template.php");
?>


 
