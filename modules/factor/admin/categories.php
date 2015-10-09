<?php

include '../../../admin/classes/template.php';

class categories extends template {

    function categories() {
        $this->template();
        $this->debug_log = false;
        $this->php_debug = false;
        /* #module specific */
        $this->table = 'factor_category';
        $this->group_name = 'Categories';
        $this->single_name = 'Category';
        $this->singular = 'a';
        $this->hidable = false;
        $this->ordered = true;
        $this->min_items = 1;
        $this->has_page_name=true;
        /* #module specific */
        $this->custom_list_sql = 'select id, title from factor_category where special = 0 order by order_num ';
        $this->has_page_name = true;
        $this->fields = array(
            'title' => array('name' => 'Title', 'formtype' => 'text', 'list' => true, 'required' => true, 'primary' => true),
            'page_name' => array('name' => 'page_name', 'formtype' => 'hidden'),
        );
        // limited for the factors module
        $this->buttons = array(
            'edit' => array('text' => 'add', 'type' => 'standard_edit'),            
            'move' => array('text' => 'move', 'type' => 'standard_move'),
        );
        $this->max_items = 9;
    }

    function get_crumbs($page) {
       /* #module specific */
        return "<a href='main.php'>Factor Admin</a> > <b>Categories</b>";
    }
 
}

$template = new categories();
/* #module specific */
$admin_tab = "factor";
$second_admin_tab = "categories";
include 'second_level_navigation.php';
include ("../../../admin/template.php");
?>


