<?php

include '../../../admin/classes/template.php';

class case_study extends template {

    function case_study() {
        $this->template();
        $this->debug_log = false;
        $this->php_debug = false;
        $this->table = 'category';
        $this->group_name = 'Categories';
        $this->single_name = 'Category';
        $this->singular = 'a';
        $this->hidable = false;
        $this->ordered = true;
        $this->min_items = 1;
        $this->has_page_name=true;
        $this->custom_list_sql = 'select id, title from category where special = 0 order by order_num ';
        $this->has_page_name = true;
       // $this->list_bottom_text = sprintf("<a href=\"case_studies.php\" onMouseOut=\"MM_swapImgRestore()\" onMouseOver=\"MM_swapImage('defaultbacktocasestudies','','/admin/images/buttons/cmsbutton-Back_to_Case_Studies-over.gif',0)\"><img style='border:none' src='./images/buttons/cmsbutton-Back_to_Case_Studies-off.gif' name='defaultbacktocasestudies'></a>", $PHP_SELF);
        $this->fields = array(
            'title' => array('name' => 'Title', 'formtype' => 'text', 'list' => true, 'required' => true, 'primary' => true),
            'page_name' => array('name' => 'page_name', 'formtype' => 'hidden'),
            'thumb' => array('name' => 'Thumbnail', 'formtype' => 'image', 'list' => false, 'required' => true, 'size' => 2),
        );
        
        // limited for the factors module
        $this->buttons = array(
            'edit' => array('text' => 'add', 'type' => 'standard_edit'),            
            'move' => array('text' => 'move', 'type' => 'standard_move'),
        );
        $this->max_items = 9;
    }

    function get_crumbs($page) {
       return "<a href='main.php'>Case Study Admin</a> > <b>Categories</b>";
    }
 
}

$template = new case_study();
$admin_tab = "case_studies";
$second_admin_tab = "categories";
include 'second_level_navigation.php';
include ("../../../admin/template.php");
?>


