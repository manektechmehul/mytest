<?php

class checkbox_field extends abstract_field {

    function __construct($formSection,$fieldname, $name, $required = false, $validate = false, $outputTemplate = '', $emailTemplate = '') {
        parent::__construct($formSection,$fieldname, $name, $required, $validate, $outputTemplate, $emailTemplate);
    }

    function setOutputTemplate($outputTemplate) {
        if (!empty($outputTemplate))
            $this->outputTemplate = $outputTemplate;
        else
            $this->outputTemplate = '<tr valign=top><td></td><td><input type=checkbox name=%s %s %s value=%s> %s%s</td></tr></td></tr>';
    }

    function setEmailTemplate($emailTemplate) {
        if (!empty($emailTemplate))
            $this->emailTemplate = $emailTemplate;
        else
            $this->emailTemplate = "%s: %s\n\n";
    }

    function output() {
        $onclick = (isset($this->onclick)) ? 'onclick="' . $this->onclick . '"' : '';
        $checked = ((isset($this->checked)) && ($this->checked)) ? "checked" : "";
        return sprintf($this->outputTemplate, $this->fieldname, $checked, $onclick, 1, $this->name, $this->required);
    }

    function getEmailMessage() {
        $answer = ($this->data == 1) ? 'yes' : 'no';
        return sprintf($this->emailTemplate, $this->name, $answer);
    }

    function isDataMissing() {
        return empty($this->data);
    }

    function isDataInvalid() {
        return false;
    }

    function processTemplate($message) {
        $text = ($this->data) ? 'yes' : 'no';
        $message = str_replace("[{$this->fieldname}]", $text, $message);
        return $message;
    }

}

