<?php

include_once '../../../php/databaseconnection.php';
include '../../../admin/classes/template.php';

class formSections extends template {

    function __construct() {
        $form_id = $_GET['form'];
        $this->formName = db_get_single_value("select title from forms where id = $form_id", 'title');
        $this->template();
        $this->table = 'form_sections';
        $this->group_name = 'Form Sections';
        $this->single_name = 'Form Section';
        $this->singular = 'a';
        $this->hideable = true;
        $this->ToolbarSet = 'Default';
        $this->ordered = true;
        $this->parent_id = $form_id;
        $this->parent_field = 'form';
        $this->parent_id_name = 'form';
        $this->php_debug = true;
        $this->debug_log = false;
        
        $this->buttons = array(
            'edit' => array('text' => 'edit', 'type' => 'standard_edit'),
            'delete' => array('text' => 'delete', 'type' => 'standard_delete'),
             'move' => array( 'text' => 'move', 'type' => 'standard_move'),
        );
        
        
        $this->fields = array(
            'title' => array('name' => 'Title', 'formtype' => 'text', 'list' => true, 'required' => true, 'primary' => true),
            'description' => array('name' => 'Description', 'formtype' => 'fckhtml', 'required' => false),
            'footer' => array('name' => 'Footer Text', 'formtype' => 'fckhtml', 'required' => false),
            'onload' => array('name' => 'onLoad [JS code only]', 'formtype' => 'text', 'required' => false),
            'onexit' => array('name' => 'onExit [JS code only]', 'formtype' => 'text', 'required' => false),
        );
    }
   
    function get_crumbs($page) {
        return "<a href='forms.php'>Form Admin</a> > <b>{$this->formName} Fields Admin</b>";
    }

}

$template = new formSections();
$main_page = 'index.php';
$main_title = 'Return to main page';
$admin_tab = "forms_admin";
include ("../../../admin/template.php");