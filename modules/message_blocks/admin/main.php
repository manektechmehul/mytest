<?php
include '../../../admin/classes/template.php';
class message_block extends template
{
    function __construct() {
        $this->template();
        $this->table = 'message_blocks';
        $this->group_name = 'Message blocks';
        $this->single_name = 'Message block';
        $this->max_items = 1;
        $this->singular = 'a';
        $this->ToolbarSet = 'Default';
        $this->buttons = array(
            'edit' => array('text' => 'add', 'type' => 'standard_edit'),
                //'hide' => array( 'text' => 'hide', 'type' => 'standard_hide'),
                //'featured' => array( 'text' => 'hide', 'type' => 'function', 'function' => 'set_featured'),
                //'delete' => array( 'text' => 'delete', 'type' => 'standard_delete'),
                //'move' => array( 'text' => 'move', 'type' => 'standard_move'),
        );

        $this->fields = array(
            'name' => array('name' => 'Title', 'formtype' => 'text', 'list' => true, 'required' => true, 'primary' => true),
            // 'sys_code' => array('name' => 'System Code[Do Not Change]', 'formtype' => 'text', 'list' => true, 'required' => true, 'primary' => true),
            'message' => array('name' => 'Message', 'formtype' => 'fckhtml', 'required' => true),
            'show_on_page' => array('name' => 'Show on website', 'formtype' => 'checkbox', 'required' => false),
            // 'show_on_email' => array('name' => 'Include in email', 'formtype' => 'checkbox', 'required' => false),
        );
    }

    function get_crumbs($page)
    {
        if ($page == '')
            return "<b>{$this->single_name} Admin</b>";
        else
            return "<a href='main.php'>{$this->single_name} Admin</a> > <b>$page</b>";
    }

	function process_submit($id, $parent_id = false) {
		// FORCE flush on update
		$result = parent::process_submit($id, $parent_id);
		global $base_path;
		require $base_path . '/php/smarty/Smarty.class.php';
		$smarty = new Smarty;
		$smarty->compile_dir = $base_path . '/templates/templates_c';
		$smarty->clear_compiled_tpl();
		$smarty->clear_all_cache();
	}
}
$template = new message_block();
$main_page = 'index.php';
$main_title = 'Return to main page';
$admin_tab = "message_blocks";
include ("../../../admin/template.php");