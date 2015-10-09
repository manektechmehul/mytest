<?php

class countries_field extends databaselookup_field {

    function __construct($fieldname, $name, $required = false, $validate = false, $outputTemplate = '', $emailTemplate = '') {
        parent::__construct($fieldname, $name, $required, $validate, $outputTemplate, $emailTemplate);
        $this->initialValues = true;
        $this->default = 234;
    }

    function output() {
        $inner = "<select name=\"{$this->fieldname}\">";
        $selectedItem = (empty($this->default)) ? 234 : $this->default;

        foreach ($this->tabledata as $value) {
            $selected = ($value['id'] == $selectedItem) ? 'selected="selected" ' : '';
            $inner .= "<option value=\"{$value['id']}\" $selected/> {$value['title']}<br />";
        }
        $inner .= "</select>";
        $output = sprintf($this->outputTemplate, $this->name, $this->required, $inner);
        return $output;
    }

}