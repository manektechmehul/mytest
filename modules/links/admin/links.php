<?php
include '../../../admin/classes/template.php'; 

class link extends template 
{
	function __construct()
	{
		$this->template();
		$this->table = 'link';
		$this->group_name = 'Promo Links';
		$this->single_name = 'Promo Link';
        $this->ordered = true;
		$this->singular = 'a';
		$this->hideable = true;
		//$this->list_top_text = "The Link at the top of the list will be featured on the home page";
		
		$this->javascript_file = 'js/link_admin.js';
		
		$this->ToolbarSet = 'Default';

        $this->buttons = array(
                            'edit' => array( 'text' => 'add', 'type' => 'standard_edit'),
                            'hide' => array( 'text' => 'hide', 'type' => 'standard_hide'),
                            //'featured' => array( 'text' => 'hide', 'type' => 'function', 'function' => 'set_featured'),
                            'delete' => array( 'text' => 'delete', 'type' => 'standard_delete'),
                            'move' => array( 'text' => 'move', 'type' => 'standard_move'),
                            );		
		
		
		$this->fields = array( 
			'name' => array('name' => 'Title', 'formtype' => 'text', 'list' => true, 'required' => true, 'primary' => true),
			'subheading' => array('name' => 'Sub heading', 'formtype' => 'text', 'list' => false, 'required' => false, 'primary' => true),
			'thumb' => array('name' => 'Image', 'formtype' => 'image', 'list' => false, 'required' => true, 'size' => 2),
			'link' => array('name' => 'Link', 'formtype' => 'text', 'required' => true),
            'external' => array('name' => 'External link', 'formtype' => 'checkbox', 'required' => false),
			);			

		//$this->links = array( 'category' => array('link_table' => 'link_category_link', 'table' => 'link_category', 'name' => 'title') );
			
	}	
    
	function set_featured($id)
	{
		$featured = db_get_single_value('select featured from '.$this->table.' where id = '.$id, 'featured');
		$hide_show = ($featured) ? 'hide' : 'show';
		$featured = ($featured) ? 'featured' : 'not featured';
		$href = sprintf ("%s?%s%s_item=yes&id=%s", $this->page_self, $parent_part, $hide_show, $content_row["id"]);
		$this->cms_admin_button($href, $hide_show.'content', $featured, "onclick='return set_featured_item(this, \"$featured\",\"$id\");'");                            
	}
	
    function get_crumbs($page)
    {
        if ($page == '')
            return "<b>{$this->single_name} Admin</b>";
        else
            return "<a href='blogs.php'>{$this->single_name} Admin</a> > <b>$page</b>";
    }      
}

$template = new link(); 

$main_page = 'index.php';
$main_title = 'Return to main page';
			

$admin_tab = "links";
$second_admin_tab = "promos";
include 'second_level_navigavtion.php';

include ("../../../admin/template.php");



