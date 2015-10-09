<?php

include '../../../admin/classes/template.php';

class bedrooms extends template {

    function bedrooms() {
        $this->template();
        $this->debug_log = false;
        $this->php_debug = false;
        /* #module specific */
        $this->table = 'property_bedroom';
        $this->group_name = 'Bedrooms';
        $this->single_name = 'Bedroom';
        $this->singular = 'a';
        $this->hidable = true;
        $this->ToolbarSet = 'Default';
        /* #module specific */
        $this->custom_list_sql = 'select id, title from property_bedroom ';
        
        /* #module specific */
        $this->list_bottom_text = sprintf("<a href=\"main.php\" onMouseOut=\"MM_swapImgRestore()\" onMouseOver=\"MM_swapImage('defaultbacktoproperties','','/admin/images/buttons/cmsbutton-Back_to_Bedrooms-over.gif',0)\"><img style='border:none' src='./images/buttons/cmsbutton-Back_to_Bedrooms-off.gif' name='defaultbacktoproperties'></a>", $PHP_SELF);
        $this->buttons = array(         
          'edit' => array('text' => 'add', 'type' => 'standard_edit'),          
          'delete' => array('text' => 'delete', 'type' => 'standard_delete'),          
        );
        $this->fields = array(
            'title' => array('name' => 'Title', 'formtype' => 'text', 'list' => true, 'required' => true, 'primary' => true),            
        );
    }

    function get_crumbs($page) {
       /* #module specific */
        return "<a href='main.php'>Property Admin</a> > <b>Bedrooms</b>";
    }
 
}

$template = new bedrooms();
/* #module specific */
$admin_tab = "property";
$second_admin_tab = "bedrooms";
include 'second_level_navigation.php';
include ("../../../admin/template.php");
?>


