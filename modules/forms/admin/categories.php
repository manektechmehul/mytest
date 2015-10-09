<?php
include '../../../admin/classes/template.php'; 
class case_study extends template 
{
	function case_study()
	{
		$this->template();
		$this->table = 'category';
		$this->group_name = 'Categories';
		$this->single_name = 'Category';
		$this->singular = 'a';
		$this->hidable = false;
		$this->ordered = true;
                $this->min_items = 1;		
		$this->list_bottom_text = sprintf ("<a href=\"case_studies.php\" onMouseOut=\"MM_swapImgRestore()\" onMouseOver=\"MM_swapImage('defaultbacktocasestudies','','/admin/images/buttons/cmsbutton-Back_to_Case_Studies-over.gif',0)\"><img style='border:none' src='./images/buttons/cmsbutton-Back_to_Case_Studies-off.gif' name='defaultbacktocasestudies'></a>", $PHP_SELF);
		$this->fields = array( 
			'title' => array('name' => 'Title', 'formtype' => 'text', 'list' => true, 'required' => true, 'primary' => true),
			//'order_num' => array('name' => 'Order', 'formtype' => 'order', 'list' => false, 'required' => false)
		);			
	}	
    function get_crumbs($page)
    {
            return "<a href='case_studies.php'>Case Study Admin</a> > <b>Categories</b>";
    }      
}
$template = new case_study(); 	
$admin_tab = "case_studies";
include ("../../../admin/template.php");
?>


