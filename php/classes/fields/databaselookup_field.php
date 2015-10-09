<?php

class databaselookup_field extends abstract_field {

    private $tabledata;

    function __construct($fieldname, $name, $required = false, $validate = false, $outputTemplate = '', $emailTemplate = '') {
        parent::__construct($fieldname, $name, $required = false, $validate = false, $outputTemplate = '', $emailTemplate = '');
        $this->initialValues = true;
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
        while ($row = mysql_fetch_array($result)) {




			if(isset($row['title'])){
				$title = $row['title'];
			}

			if(isset($row['name'])){
				$title = $row['name'];
			}

			
            $this->tabledata[$row['id']] = array(
                'id' => $row['id'],
                // TODO: GL need to pass in a varible to select the chosen field  
                // Currently just mash up the two most likely columns 			
                'title' => $title,
            );
        }
    }

    function output() {
        // $selectedItem = $_GET[$this->default];
        $selectedItem = $this->default;

        $inner = "<select name=\"{$this->fieldname}\">";

        foreach ($this->tabledata as $value) {
            $selected = ($value['id'] == $selectedItem) ? 'selected="selected" ' : '';
            $inner .= "<option value=\"{$value['id']}\" $selected/> {$value['title']}<br />";
        }
        $inner .= "</select>";
        $output = sprintf($this->outputTemplate, $this->title, $this->required, $inner);
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

