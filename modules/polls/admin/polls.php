<?php

include '../../../admin/classes/template.php';

class polls extends template {

    function __construct() {
        $this->template();
        $this->table = 'polls';
        $this->group_name = 'Polls';
        $this->single_name = 'Poll';
        //$this->ordered = true;
        $this->singular = 'a';
        //$this->debug_log = true;
        //$this->hideable = true;
        //$this->list_top_text = "The Link at the top of the list will be featured on the home page";

        $this->javascript_file = 'js/link_admin.js';

        $this->ToolbarSet = 'Default';

        $this->buttons = array(
            'edit' => array('text' => 'add', 'type' => 'standard_edit'),
            //'hide' => array( 'text' => 'hide', 'type' => 'standard_hide'),
            //'featured' => array( 'text' => 'hide', 'type' => 'function', 'function' => 'set_featured'),
            'delete' => array('text' => 'delete', 'type' => 'standard_delete'),
                //'move' => array( 'text' => 'move', 'type' => 'standard_move'),
        );


        $this->fields = array(
            'question' => array('name' => 'Poll Question/Statement', 'formtype' => 'text', 'list' => true, 'required' => true, 'primary' => true),
            'answers' => array('name' => 'Answers (1 per line)', 'formtype' => 'textarea', 'required' => true),
            'responses' => array('name' => 'Responses', 'formtype' => 'lookup', 'function'=>'getresponses','not_field'=>true),
            
            
        );

        //$this->links = array( 'category' => array('link_table' => 'link_category_link', 'table' => 'link_category', 'name' => 'title') );
    }
    
    
    function getresponses(){
        $poll = $_GET['id'];
        $total = db_get_single_value("select count(*) from poll_response where poll = $poll");
        
        
        $answers = db_get_rows("select answer, count(*) as 'count' from poll_response where poll = $poll group by answer");
        
                foreach ($answers as $row) {
                   
                //   var_dump($row);
                    
                    $out .=  'Q' . $row['answer']  . ' : ' .  $row['count'] . ' votes = ' .  round((  $row['count'] / $total ) * 100 )  . '% of votes <br>'; 
                    
                    
                }
         
        
         
        
        
        
        return $out;
    }
    
    

}





$template = new polls();

$main_page = 'index.php';
$main_title = 'Return to main page';


$admin_tab = "polls";
//$second_admin_tab = "links";
//include 'second_level_navigavtion.php';

include ("../../../admin/template.php");



