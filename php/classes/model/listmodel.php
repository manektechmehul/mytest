<?php

include_once $base_path . '/php/classes/model/data_field.php';

abstract class ListModel {

    protected $table;
    protected $orderField;
    protected $publishedField;
    protected $pagination;

    function __construct($paginate = false) {
        $this->orderField = '';
        $this->publishedField = '';
    }

    function setOrderField($orderField) {
        $this->orderField = $orderField;
    }

    function GetFields() {
        $fieldsStr = '';
        foreach ($this->dataFields as $field) {
            if ($field->calculated != true)
                $fieldsStr .= $field->sqlField . ',';
        }

        return trim($fieldsStr, ',');
    }

    function GetPaginationLimits($pagination) {
        $start = $pagination->page_start_row();
        $perPage = $pagination->perPage;

        return " limit $start, $perPage";
    }

    function ReadItems($sql = '', $pagination = '') {
        if ($sql == '') {
            if (isset($this->dataFields))
                $fields = $this->GetFields();
            else
                $fields = '*';
            $sql = "select SQL_CALC_FOUND_ROWS $fields from {$this->table}";
            if ($this->publishedField != '')
                $sql .= " where {$this->publishedField} = 1 ";
            if ($this->orderField != '')
                $sql .= " order by {$this->orderField}";
        }
        $hasLimit = strpos($sql, ' limit ');
        if ($hasLimit === false) {
            if ($pagination) {
                $sql .= $this->GetPaginationLimits($pagination);
            }
        }
        $items = array();
        $result = mysql_query($sql);

        if (isset($this->dataFields)) {
            while ($row = mysql_fetch_array($result)) {
                foreach ($this->dataFields as $name => $dataField) {
                    $item[$name] = $dataField->getData($row);
                }
                $items[] = $item;
            }
        }
        else
            while ($row = mysql_fetch_array($result))
                $items[] = $row;
        return $items;
    }

    function ReadItem($id, $sql = '') {
        if (isset($this->dataFields)){
            $fields = $this->GetFields();
        }else{
            $fields = '*';
        }
        if ($sql == ''){            
            // if page_name is an Int match with id field else page_name
            if(!is_int($id) ? (ctype_digit(id)) : true ){ 
                $sql = "select $fields from {$this->table} where id = {$id}";
            }else{                    
                $sql = "select $fields from {$this->table} where page_name = '{$id}'";       
            }
        }
              
        $item = array();
        $row = db_get_single_row($sql);
        
        if (isset($this->dataFields))
            foreach ($this->dataFields as $name => $dataField)
                $item[$name] = $dataField->getData($row);
        else
            $item = db_get_single_row($sql);
        return $item;
    }

    function GetID($page) {
        $sql = "select id from {$this->table} where page_name = '$page'";
        return db_get_single_value($sql, 'id');
    }

    function GetTitle($page_name) {
        $sql = "select title from {$this->table} where page_name = '$page_name'";
        return db_get_single_value($sql, 'title');
    }

    function GetItemByPageName($pageName) {
        $id = $this->GetID($pageName);
        $item = $this->ReadItem($id);
        return $item;
    }

}