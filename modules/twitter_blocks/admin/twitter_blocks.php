<?php
include '../../../admin/classes/template.php'; 

class twitter_Admin extends template 
{
	function __construct()
	{
		$this->template();
		$this->table = 'twitter_block';
		$this->group_name = 'Tweets';
		$this->single_name = 'Tweet Block';
                //$this->ordered = true;
		$this->singular = 'a';
		//$this->hideable = true;
		//$this->list_top_text = "The Link at the top of the list will be featured on the home page";		
		$this->javascript_file = 'js/admin.js';		
		$this->ToolbarSet = 'Default';

                $this->buttons = array(
                            'edit' => array( 'text' => 'add', 'type' => 'standard_edit'),
                            'hide' => array( 'text' => 'hide', 'type' => 'standard_hide'),
                            'featured' => array( 'text' => 'hide', 'type' => 'function', 'function' => 'set_featured'),
                            'delete' => array( 'text' => 'delete', 'type' => 'standard_delete'),
                            //'move' => array( 'text' => 'move', 'type' => 'standard_move'),
                            );		
				
		$this->fields = array( 
			'name' => array('name' => 'name', 'formtype' => 'text', 'list' => true, 'required' => true, 'primary' => true),
            //            'banner_image' => array('name' => 'Image', 'formtype' => 'image', 'list' => false, 'required' => true, 'size' => 2),
			'tags' => array('name' => 'tags', 'formtype' => 'text', 'required' => true),
                        'no_of_items_to_display' => array('name' => 'No of items to display', 'formtype' => 'text', 'required' => true),
              //          'description' => array('name' => 'Description', 'formtype' => 'textarea', 'list' => false, 'required' => true),
                       
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
            return "<a href='" . $this->page_self . "'>{$this->single_name} Admin</a> > <b>$page</b>";
    }      
}

$template = new twitter_Admin(); 
$main_page = 'index.php';
$main_title = 'Return to main page';
			
$admin_tab = "twitter";
// $second_admin_tab = "promos";
// include 'second_level_navigavtion.php';
include ("../../../admin/template.php");




