<?php

abstract class abstract_field {

    public $name;
    public $required;
    public $validate;
    public $fieldname;
    public $emailTemplate;
    public $outputTemplate;
    public $dataField = true;
    public $attachmentField = false;
    public $keyField = false;
    public $filterValueField = false;
    protected $values;
    public $data;
    public $invalidDataMessage;
    public $default;
    public $initialValues;
    public $formSection;

    function __construct($formSection = '', $fieldname, $name, $required = false, $validate = false, $outputTemplate = '', $emailTemplate = '' ) {
        $this->required = ($required) ? '*' : '';
        $this->validate = $validate;
        $this->fieldname = $fieldname;
        $this->name = $name;
        $this->emailTemplate = $emailTemplate;
        $this->setOutputTemplate($outputTemplate);
        $this->setEmailTemplate($emailTemplate);
        $this->formSection = $formSection;
        
    }

    function getFormSection(){
        return $this->formSection;
    }
    
    function setOutputTemplate($outputTemplate) {
        $this->outputTemplate = $outputTemplate;
    }

    function setEmailTemplate($emailTemplate) {
        if (!empty($emailTemplate))
            $this->emailTemplate = $emailTemplate;
        else
            $this->emailTemplate = "%s: %s\n\n";
    }

    function output() {
        $output = sprintf($this->outputTemplate, $this->name, $this->fieldname, $this->required);
        return $output;
    }

    function setData() {
        $this->data = (!empty($_REQUEST[$this->fieldname])) ? $_REQUEST[$this->fieldname] : "";
    }

    function getData() {
        return $this->data;
    }

    function getEmailMessage() {
        
    }

    function isDataMissing() {
        return empty($this->data);
    }

    function isDataInvalid($key = '') {
        return false;
    }

    function processTemplate($message) {
        return $message;
    }

}

