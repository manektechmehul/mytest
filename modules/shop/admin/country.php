<?php
include '../../../admin/classes/template.php'; 
class country extends template 
{
	function __construct()
	{
		$this->template();
		$this->table = 'shop_country';
		$this->group_name = 'Countries';
		$this->single_name = 'Country';
        $this->ordered = false;
		$this->singular = 'a';
		//$this->hideable = true;
		//$this->list_top_text = "The Link at the top of the list will be featured on the home page";
		$this->javascript_file = 'js/link_admin.js';
		$this->ToolbarSet = 'Default';
        $this->buttons = array(
                            'edit' => array( 'text' => 'add', 'type' => 'standard_edit'),
                            'hide' => array( 'text' => 'hide', 'type' => 'standard_hide'),
                            //'featured' => array( 'text' => 'hide', 'type' => 'function', 'function' => 'set_featured'),
                            'delete' => array( 'text' => 'delete', 'type' => 'standard_delete'),
                            //'move' => array( 'text' => 'move', 'type' => 'standard_move'),
                            );		
		$this->fields = array( 
			'name' => array('name' => 'Name', 'formtype' => 'text', 'list' => true, 'required' => true),
			'code' => array('name' => 'Code', 'formtype' => 'shorttext', 'required' => true),
			'zone_id' => array('name' => 'Zone', 'formtype' => 'lookup', 'required' => true, 'function' => 'zone_lookup' ),
			);			
		$this->links = array( 'category' => array('link_table' => 'link_category_link', 'table' => 'link_category', 'name' => 'title') );
	}	
	function zone_lookup($zoneId) {
		return $this->lookup('shop_vat_zone', 'zone_id', $zoneId, 'name');
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
$template = new country(); 
$main_page = 'index.php';
$main_title = 'Return to main page';
$admin_tab = "links";
$second_admin_tab = "links";
include 'second_level_navigation.php';
include ("../../../admin/template.php");
?>
