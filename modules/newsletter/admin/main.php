<?php
include '../../../admin/classes/template.php'; 

class newsletter extends template 
{
	function __construct() 
    {
		$this->template();
		$this->table = 'newsletter';
		$this->group_name = 'Newsletters';
		$this->single_name = 'Newsletter';
        $this->ordered = true;
        $this->order_clause = 'order_num desc';
		$this->singular = 'a';
		$this->hideable = true;

	    $this->list_top_text = "The newsletter will be sent every XXXXXXX at XXXXam<br/>[<a target='_blank' href='/modules/newsletter/main.php?test'>Test Newsletter Now</a>]<br/><br/>";

	    $this->max_items = 1;
		$this->javascript_file = 'js/link_admin.js';
	    $this->debug_log;
		
		$this->ToolbarSet = 'Default';
	    $this->buttons = array(
		    'hide' => array( 'text' => 'hide', 'type' => 'standard_hide' ),
		    'edit' => array( 'text' => 'add', 'type' => 'standard_edit' ),
		    //'featured' => array( 'text' => 'hide', 'type' => 'function', 'function' => 'set_featured'),
		   // 'delete' => array( 'text' => 'delete', 'type' => 'standard_delete' ),
		 //   'move' => array( 'text' => 'move', 'type' => 'standard_move' ),
	    );
		
		
		$this->fields = array( 
			'name' => array('name' => 'Name', 'formtype' => 'text', 'list' => true, 'required' => true, 'primary' => true),
			'plain' => array('name' => 'Plain', 'formtype' => 'textarea', 'required' => true),
			'html' => array('name' => 'HTML', 'formtype' => 'fckhtml', 'required' => true),
			);			

	}	
    

    
    function get_crumbs($page)
    {
        if ($page == '')
            return "<b>{$this->single_name} Admin</b>";
        else
            return "<a href='main.php'>{$this->single_name} Admin</a> > <b>$page</b>";
    }      
}

$template = new newsletter(); 

$main_page = 'index.php';
$main_title = 'Return to main page';
			

$admin_tab = "newsletter";

include 'second_level_navigavtion.php';

include ("../../../admin/template.php");
?>


