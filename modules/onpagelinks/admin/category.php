<?php
include '../../../admin/classes/template.php'; 
include_once 'conf.php';

class myAdmin extends template 
{
	function __construct()
	{
		$this->template();
		$this->table = 'Onpagelink_category';
		$this->group_name = 'On Pagelink Categories';
		$this->single_name = 'On Pagelink Category';
		$this->ordered = true;
		$this->singular = 'a';
		$this->hideable = true;
		$this->has_page_name = true;
		//$this->list_top_text = "The case study at the top of the list will be featured on the home page";
		
		$this->ToolbarSet = 'Default';

		$this->custom_list_sql = 'select id, title from bulletins_category where special = 0 order by order_num ';

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
		if (($button == 'delete') && (db_get_single_value('select count(*) as used from bulletins_category_link where bulletins_category_id = '.$id, 'used') > 0))
			$result = false;
		return $result;
	}
}

$template = new myAdmin(); 
$template->table = $cat_table;
$template->group_name = $cat_group_name;
$template->single_name = $cat_single_name;

     
$main_page = 'index.php';
$main_title = 'Return to main page';

$admin_tab = $main_admin_tab_name;
$second_admin_tab = "categories";

include 'second_level_navigavtion.php';
include ("../../../admin/template.php");
?>


 
