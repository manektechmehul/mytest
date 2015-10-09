<?php

include '../../../admin/classes/template.php';

class locations extends template {

    function locations() {
        $this->template();
        $this->debug_log = false;
        $this->php_debug = false;
        /* #module specific */
        $this->table = 'property_location';
        $this->group_name = 'Locations';
        $this->single_name = 'Location';
        $this->singular = 'a';
        $this->hidable = true;
        $this->ToolbarSet = 'Default';
        /* #module specific */
        $this->custom_list_sql = 'select id, title from property_location ';
        
        /* #module specific */
        $this->list_bottom_text = sprintf("<a href=\"main.php\" onMouseOut=\"MM_swapImgRestore()\" onMouseOver=\"MM_swapImage('defaultbacktoproperties','','/admin/images/buttons/cmsbutton-Back_to_Locations-over.gif',0)\"><img style='border:none' src='./images/buttons/cmsbutton-Back_to_Locations-off.gif' name='defaultbacktoproperties'></a>", $PHP_SELF);
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
        return "<a href='main.php'>Property Admin</a> > <b>Locations</b>";
    }
 
}

$template = new locations();
/* #module specific */
$admin_tab = "property";
$second_admin_tab = "locations";
include 'second_level_navigation.php';
include ("../../../admin/template.php");
?>


