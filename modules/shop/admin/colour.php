<?php

include '../../../admin/classes/template.php';

class main extends template {

    function main() {
        $this->template();
        $this->debug_log = false;
        $this->php_debug = false;
        /* #module specific */
        $this->table = 'colour_colour_details';
        /* #module specific */
        
        /* #module specific */
        $this->single_name = 'Colour';
        $this->ordered = true;
       // $this->order_clause = ' order_num desc ';
        $this->singular = 'a';
        $this->hideable = true;
        //$this->list_top_text = "The case study at the top of the list will be featured on the home page";
        $this->javascript_file = array('js/admin.js','js/colpick.js','js/setcolourfields.js');
        
        $this->ToolbarSet = 'Default';
        $this->has_page_name = true;
        // up this figure to show add button
        // $this->max_items = 6;

        $this->buttons = array(
            'edit' => array('text' => 'add', 'type' => 'standard_edit'),
            'hide' => array('text' => 'hide', 'type' => 'standard_hide'),
            // need to dynamically initiate this button
            //'featured' => array('text' => 'hide', 'type' => 'function', 'function' => 'set_featured'),
            'delete' => array('text' => 'delete', 'type' => 'standard_delete'),
            'move' => array('text' => 'move', 'type' => 'standard_move'),
            'colour' => array('text' => 'hide', 'type' => 'function', 'function' => 'show_colour'),
        );

        $this->fields = array(
            'name' => array('name' => 'Title', 'formtype' => 'text', 'list' => true, 'required' => true, 'primary' => true),
            'rgb' => array('name' => 'web colour (RGB - no hash)', 'formtype' => 'text',   'required' => true),
    
            
        );
     }

  function onload() {
      echo '<link href="css/colpick.css" rel="stylesheet" type="text/css"/>'; 
  }
     
     function show_colour($id){
         $rgb = db_get_single_value("select rgb from colour_colour_details where id =" . $id);
         
         echo '<div class="contentfield">
<div class="row-text" style="padding-top:6px;margin-left: 150px;height: 32px; background: #' . $rgb . ';" ><b>&nbsp;&nbsp; #' . $rgb . '</b></div>
</div>';
     }
 

    function get_crumbs($page) {
        if ($page == '')
            return "<b>{$this->single_name} Admin</b>";
        else
            return "<a href='main.php'>{$this->single_name} Admin</a> > <b>$page</b>";
    }

   

    
}

$template = new main();
$main_page = 'index.php';
$main_title = 'Return to main page';
/* #module specific */
$admin_tab = "shop";
/* #module specific */
$second_admin_tab = "colour";

include 'second_level_navigation.php';
include ("../../../admin/template.php");

?>


