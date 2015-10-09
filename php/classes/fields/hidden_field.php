<?php

class hidden_field extends abstract_field {

    private $titlepos;
    private $len;
    public $outputRightTemplate;
    public $values;

    function __construct($fieldname, $name, $required = false, $validate = false, $outputTemplate = '', $emailTemplate = '', $outputRightTemplate = '') {
        parent::__construct($fieldname, $name, $required, $validate, $outputTemplate, $emailTemplate);
        $this->titlepos = '';
        $this->len = 65;
        $this->outputRightTemplate = '';
        $this->initialValues = true;
    }

    function setOutputTemplate($outputTemplate) {
        if (!empty($outputTemplate))
            $this->outputTemplate = $outputTemplate;
        else
            $this->outputTemplate = "<input type=\"hidden\" name=\"%s\"  value=\"%s\"/>\n\n";
    }

    function output() {
        global $pageData;
        $value = $pageData[$this->values];
        $output = sprintf($this->outputTemplate, $this->fieldname, $value);
        return $output;
    }

    function getEmailMessage() {
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

