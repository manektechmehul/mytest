<?php

include '../../../admin/classes/template.php';

class categories extends template {

    function categories() {
        $this->template();
        $this->debug_log = false;
        $this->php_debug = false;
        /* #module specific */
        $this->table = '[[CATEGORY_TABLE]]';
        $this->group_name = 'Categories';
        $this->single_name = 'Category';
        $this->singular = 'a';
        $this->hidable = false;
        $this->ordered = true;
        $this->min_items = 1;
        $this->has_page_name=true;
        /* #module specific */
        $this->custom_list_sql = 'select id, title from [[CATEGORY_TABLE]] where special = 0 order by order_num ';
        $this->has_page_name = true;
        /* #module specific */
     //   $this->list_bottom_text = sprintf("<a href=\"main.php\" onMouseOut=\"MM_swapImgRestore()\" onMouseOver=\"MM_swapImage('defaultbackto[[PLURALLOWER]]','','/admin/images/buttons/cmsbutton-Back_to_[[PLURALUPPER]]-over.gif',0)\"><img style='border:none' src='./images/buttons/cmsbutton-Back_to_[[PLURALUPPER]]-off.gif' name='defaultbackto[[PLURALLOWER]]'></a>", $PHP_SELF);
        $this->fields = array(
            'title' => array('name' => 'Title', 'formtype' => 'text', 'list' => true, 'required' => true, 'primary' => true),
            'page_name' => array('name' => 'page_name', 'formtype' => 'hidden'),
        );
    }

    function get_crumbs($page) {
       /* #module specific */
        return "<a href='main.php'>[[UPPERCASE_NAME]] Admin</a> > <b>Categories</b>";
    }
 
}

$template = new categories();
/* #module specific */
$admin_tab = "[[LOWERCASE_NAME]]";
$second_admin_tab = "categories";
include 'second_level_navigation.php';
include ("../../../admin/template.php");
?>


