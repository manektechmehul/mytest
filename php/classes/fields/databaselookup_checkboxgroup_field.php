<?php

class databaselookup_checkboxgroup_field extends abstract_field {

    public $tabledata;
    public $table_name;

    function __construct($fieldname, $name, $required = false, $validate = false, $outputTemplate = '', $emailTemplate = '') {
        parent::__construct($fieldname, $name, $required = false, $validate = false, $outputTemplate = '', $emailTemplate = '');
        $this->initialValues = true;
    }

    function setData() {
        $this->data = (!empty($_REQUEST[$this->fieldname])) ? $_REQUEST[$this->fieldname] : "";
    }

    function setOutputTemplate($outputTemplate) {
        if (!empty($outputTemplate))
            $this->outputTemplate = $outputTemplate;
        else
            $this->outputTemplate = '<tr valign=top><td width="22%%"><div align="right">%s%s</div></td><td>%s</td></tr>';
    }

    function __get($var) {
        if ($var == 'values')
            return '';
    }

    function __set($var, $value) {
        if ($var == 'values')
            $this->setValues($value);
    }

    function setValues($data) {
        $sql = "select * from $data";
        $result = mysql_query($sql);
        $this->table_name = $data;
        while ($row = mysql_fetch_array($result)) {
            $this->tabledata[$row['id']] = array(
                'id' => $row['id'],
                'title' => $row['title'],
            );
        }
    }

    function output() {
        $selectedItem = $_GET[$this->default];
        foreach ($this->tabledata as $value) {
            $inner .= "<input type=checkbox  name=\"{$this->fieldname}[]\" value=\"{$value['id']}\"    />{$value['title']}<br />";
        }
        $output = sprintf($this->outputTemplate, $this->name, $this->required, $inner);
        return $output;
    }

    function getEmailMessage() {
        return sprintf($this->emailTemplate, $this->name, $this->tabledata[$this->data]['title']);
    }

    function isDataMissing() {
        return empty($this->data);
    }

    function isDataInvalid() {
        return false;
    }

    function processTemplate($message) {
        $message = str_replace("[FC:{$this->fieldname}]", ucwords($this->tabledata[$this->data]['title']), $message);
        $message = str_replace("[{$this->fieldname}]", $this->tabledata[$this->data]['title'], $message);
        return $message;
    }

}

