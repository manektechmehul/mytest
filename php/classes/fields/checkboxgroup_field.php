<?php

class checkboxgroup_field extends abstract_field {

    function __construct($formSection, $fieldname, $name, $required = false, $validate = false, $outputTemplate = '', $emailTemplate = '') {
        parent::__construct($formSection, $fieldname, $name, $required = false, $validate = false, $outputTemplate = '', $emailTemplate = '');
        $this->initialValues = true;
    }

    function output() {
        $inner = '';
        foreach ($this->values as $value)
            $inner .= "<input type=\"checkbox\" name=\"{$this->fieldname}[]\" value=\"$value\" /> $value<br />";

        $output = sprintf($this->outputTemplate, $this->name, $this->required, $inner);
        return $output;
    }

    function setOutputTemplate($outputTemplate) {
        if (!empty($outputTemplate))
            $this->outputTemplate = $outputTemplate;
        else
            $this->outputTemplate = '<tr valign=top><td width="22%%"><div align="right">%s%s</div></td><td>%s</td></tr>';
    }

    function __get($var) {
        if ($var == 'values')
            return $var;
    }

    function __set($var, $value) {
        if ($var == 'values')
            $this->setValues($value);
    }

    function setValues($data) {
        $this->values = explode("\n", $data);
    }

    function getEmailMessage() {
        $chosen = implode($this->data, ', ');
        return sprintf($this->emailTemplate, $this->name, $chosen);
    }

    function isDataMissing() {
        return empty($this->data);
    }

    function isDataInvalid() {
        return false;
    }

    function processTemplate($message) {
        $message = str_replace("[FC:{$this->fieldname}]", ucwords($this->data), $message);
        $message = str_replace("[{$this->fieldname}]", $this->data, $message);
        return $message;
    }

}

