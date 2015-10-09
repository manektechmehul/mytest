<?php

// include standard list model
include_once $base_path . "/php/classes/model/listmodel.php";
/* #module specific */
class factormodel extends ListModel {

    function __construct($pageUrl) {
        parent::__construct();
    }

    function setTable($t) {
        $this->table = $t;
    }

    function setPublishedField($pf) {
        $this->publishedField = $pf;
    }

    function GetTitle($page_name) {
        // if an Int look up the id, else try the page_name
          if(!is_int($id) ? (ctype_digit(id)) : true ){ 
                $sql = "select title from {$this->table} where id = '$page_name'";
            }else{                    
                $sql = "select title from {$this->table} where page_name = '$page_name'";     
            }          
        return db_get_single_value($sql, 'title');
    }
    

}

