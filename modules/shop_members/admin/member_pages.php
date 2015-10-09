<?php
include '../../../admin/classes/template.php'; 

class member_pages extends template 
{
	function __construct()
	{
		$this->template();
		$this->table = 'shop_member_page';
		$this->group_name = 'Member Pages';
		$this->single_name = 'Member Page';
        $this->has_page_name = true;
		$this->singular = 'a';
		$this->hideable = true;
		$this->ordered = true;

        $this->parent_child = true;
		$this->parent_id_name = 'parent_page_id';
        $this->where_clause = ' where parent_id = 0 ';
        $this->child = new member_sub_pages();

		$this->invalid_page_names = array('documents');
		
		//$this->list_top_text = sprintf ("<a href=\"categories.php\" onMouseOut=\"MM_swapImgRestore()\" onMouseOver=\"MM_swapImage('defaultcategories','','/admin/images/buttons/cmsbutton-Categories-over.gif',0)\"><img style='border:none' src='./images/buttons/cmsbutton-Categories-off.gif' name='defaultcategories'></a>", $PHP_SELF);
		$this->ToolbarSet = 'Default';

        $this->buttons = array(
                            'edit' => array( 'text' => 'add', 'type' => 'standard_edit'),
                            'articles' => array( 'text' => 'articles', 'type' => 'button', 'pattern' => '/modules/members/admin/member_articles.php?parent_page_id=%s'),
                            'hide' => array( 'text' => 'hide', 'type' => 'standard_hide'),
                            'delete' => array( 'text' => 'delete', 'type' => 'standard_delete'),
                            'move' => array( 'text' => 'move', 'type' => 'standard_move'),);


		$this->fields = array( 
			//'title' => array('name' => 'Title', 'formtype' => 'text', 'list' => true, 'required' => true, 'primary' => true, 'edit_condition' => 'title_can_change'),
			'title' => array('name' => 'Title', 'formtype' => 'text', 'list' => true, 'required' => true, 'primary' => true),
            'page_name' => array('name' => 'Page Name', 'formtype' => 'hidden', 'keepfirst' => true),
			//'thumb' => array('name' => 'Thumbnail', 'formtype' => 'image', 'list' => false, 'required' => true, 'size' => 2),
			//'category' => array('name' => 'Categories', 'formtype' => 'checklink', 'list' => false, 'required' => true, 'not_field' => true, 'link' => 'category'),
			//'description' => array('name' => 'Description', 'formtype' => 'textarea', 'list' => false, 'rows' => 3, 'cols' => 60, 'required' => true),
			'body' => array('name' => 'Main Content', 'formtype' => 'fckhtml', 'list' => false, 'required' => true)
		);			
		//$this->links = array( 'category' => array('link_table' => 'case_study_category', 'table' => 'category', 'name' => 'title') );
	}	

	function title_can_change()
	{
		return ($this->data[id] != 1);
	}

	function row_show_button($button, $id)
	{
		return true;
	}
}

class member_sub_pages extends template 
{
	function __construct()
	{
		$this->template();
		$this->table = 'member_page';
		$this->group_name = 'Member Sub Pages';
		$this->single_name = 'Member Sub Page';
        $this->has_page_name = true;
		$this->singular = 'a';
		$this->hideable = true;
		$this->ordered = true;

		$parent_page_id = $_REQUEST['parent_page_id'];
        $this->parent_child = true;
		$this->parent_id = $parent_page_id;
		$this->parent_field = 'parent_id';
		$this->parent_id_name = 'parent_page_id';
		
		//$this->list_top_text = sprintf ("<a href=\"categories.php\" onMouseOut=\"MM_swapImgRestore()\" onMouseOver=\"MM_swapImage('defaultcategories','','/admin/images/buttons/cmsbutton-Categories-over.gif',0)\"><img style='border:none' src='./images/buttons/cmsbutton-Categories-off.gif' name='defaultcategories'></a>", $PHP_SELF);
		$this->ToolbarSet = 'Default';

        $this->buttons = array(
                            'edit' => array( 'text' => 'add', 'type' => 'standard_edit'),
                            'articles' => array( 'text' => 'articles', 'type' => 'button', 'pattern' => '/modules/members/admin/member_articles.php?parent_page_id=%s'),
                            'hide' => array( 'text' => 'hide', 'type' => 'standard_hide'),
                            'delete' => array( 'text' => 'delete', 'type' => 'standard_delete'),
                            'move' => array( 'text' => 'move', 'type' => 'standard_move'),);

		$this->fields = array( 
			//'title' => array('name' => 'Title', 'formtype' => 'text', 'list' => true, 'required' => true, 'primary' => true, 'edit_condition' => 'title_can_change'),
			'title' => array('name' => 'Title', 'formtype' => 'text', 'list' => true, 'required' => true, 'primary' => true),
            'page_name' => array('name' => 'Page Name', 'formtype' => 'hidden', 'keepfirst' => true),
			//'thumb' => array('name' => 'Thumbnail', 'formtype' => 'image', 'list' => false, 'required' => true, 'size' => 2),
			//'category' => array('name' => 'Categories', 'formtype' => 'checklink', 'list' => false, 'required' => true, 'not_field' => true, 'link' => 'category'),
			//'description' => array('name' => 'Description', 'formtype' => 'textarea', 'list' => false, 'rows' => 3, 'cols' => 60, 'required' => true),
			'body' => array('name' => 'Main Content', 'formtype' => 'fckhtml', 'list' => false, 'required' => true)
		);			
		
		
		
		//$this->links = array( 'category' => array('link_table' => 'case_study_category', 'table' => 'category', 'name' => 'title') );
	}	
	function title_can_change()
	{
		return ($this->data[id] != 1);
	}

	function row_show_button($button, $id)
	{
		return true;
	}
	
}


$template = new member_pages(); 

$main_page = 'index.php';
$main_title = 'Return to main page';
			

$admin_tab = "member_admin";
$second_admin_tab = "pages";
include 'second_level_navigavtion.php';

include '../../../admin/template.php';
