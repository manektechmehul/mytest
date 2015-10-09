<?php

class dropdown_field extends abstract_field {

    function __construct($formSection,$fieldname, $name, $required = false, $validate = false, $outputTemplate = '', $emailTemplate = '') {
        parent::__construct($formSection,$fieldname, $name, $required = false, $validate = false, $outputTemplate = '', $emailTemplate = '');
        $this->initialValues = true;
    }

    function setOutputTemplate($outputTemplate) {
        if (!empty($outputTemplate))
            $this->outputTemplate = $outputTemplate;
        else
            $this->outputTemplate = '<tr valign="center"><td width="22%%"><div align="right">%s%s</div></td><td>%s</td></tr>';
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

    function output() {
        $inner = '';
        $inner = "<select name=\"{$this->fieldname}\">";

        foreach ($this->values as $value) {
            $value = trim($value);
            $selected = ($this->default == $value) ? 'selected="selected"' : '';
            $inner .= "<option value=\"$value\" $selected/> $value</option>";
        }
        $inner .= "</select>";
        $output = sprintf($this->outputTemplate, $this->name, $this->required, $inner);
        return $output;
    }

    function getEmailMessage() {

        /* 			foreach ($this->values as $value)
          if ($this->data == $value)
          $chosen = $value;
         */
        return sprintf($this->emailTemplate, $this->name, $this->data);
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

