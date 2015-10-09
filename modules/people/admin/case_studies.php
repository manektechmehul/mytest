<?php
include '../../../admin/classes/template.php'; 

class case_study extends template 
{
	function case_study()
	{
		$this->template();
		$this->table = 'case_study';
		$this->group_name = 'Case Studies';
		$this->single_name = 'Case Study';
		$this->singular = 'a';
		$this->hideable = true;
		$this->list_top_text = sprintf ("<a href=\"categories.php\" onMouseOut=\"MM_swapImgRestore()\" onMouseOver=\"MM_swapImage('defaultcategories','','/admin/images/buttons/cmsbutton-Categories-over.gif',0)\"><img style='border:none' src='/admin/images/buttons/cmsbutton-Categories-off.gif' name='defaultcategories'></a>", $PHP_SELF);
		$this->ToolbarSet = 'Default';

		$this->fields = array( 
			'title' => array('name' => 'Title', 'formtype' => 'text', 'list' => true, 'required' => true, 'primary' => true),
			//'thumb' => array('name' => 'Thumbnail', 'formtype' => 'image', 'list' => false, 'required' => true, 'size' => 2),
			'category' => array('name' => 'Categories', 'formtype' => 'checklink', 'list' => false, 'required' => true, 'not_field' => true, 'link' => 'category'),
			'description' => array('name' => 'Description', 'formtype' => 'textarea', 'list' => false, 'rows' => 3, 'cols' => 60, 'required' => true),
			'body' => array('name' => 'Main Content', 'formtype' => 'fckhtml', 'list' => false, 'required' => true)
			);			
			
		$this->links = array( 'category' => array('link_table' => 'case_study_category', 'table' => 'category', 'name' => 'title') );
	}	
    
    function get_crumbs($page)
    {
        if ($page == '')
            return "<b>{$this->single_name} Admin</b>";
        else
            return "<a href='blogs.php'>{$this->single_name} Admin</a> > <b>$page</b>";
    }      
}

$template = new case_study(); 

$main_page = 'index.php';
$main_title = 'Return to main page';
			

$admin_tab = "case_studies";

include ("../../../admin/template.php");
?>


