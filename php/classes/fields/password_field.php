<?php

class password_field extends abstract_field {

    private $titlepos;
    private $len;
    public $outputRightTemplate;

    function __construct($fieldname, $name, $required = false, $validate = true, $outputTemplate = '', $emailTemplate = '', $outputRightTemplate = '') {
        parent::__construct($fieldname, $name, $required, $validate, $outputTemplate, $emailTemplate);
        $this->titlepos = '';
        $this->len = 65;
        $this->outputRightTemplate = '';
    }

    function setOutputTemplate($outputTemplate) {
        if (!empty($outputTemplate))
            $this->outputTemplate = $outputTemplate;
        else
            $this->outputTemplate = "<tr valign=\"middle\">\n<td width=\"22%%\">\n<div align=\"right\">%s%s</div>\n</td>\n" .
                    "<td>\n<input type=\"password\" name=\"%s\" maxlength=\"%s\"></td></tr>\n\n";
    }

    function output() {
        if (isset($field['titlepos']) && ($field['titlepos'] == 'right'))
            $output = sprintf($this->outputRightTemplate, $this->name, $this->fieldname, $this->len, $this->required);
        else
            $output = sprintf($this->outputTemplate, $this->name, $this->required, $this->fieldname, $this->len);
        return $output;
    }

    function getEmailMessage() {
        return '';
    }

    function isDataMissing() {
        return empty($this->data);
    }

    function isDataInvalid() {
        $isTooShort = (strlen($this->data) < 8);
        if ($isTooShort)
            $this->invalidDataMessage = $this->name . ' should be at least 8 characters long';
        return $isTooShort;
    }

    function processTemplate($message) {
        $message = str_replace("[FC:{$this->fieldname}]", ucwords($this->data), $message);
        $message = str_replace("[{$this->fieldname}]", $this->data, $message);
        return $message;
    }

}

