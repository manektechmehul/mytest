<?php

class textarea_field extends abstract_field {

    private $rows = 3;
    private $cols = 20;

    function __construct($fieldname, $name, $required = false, $validate = false, $outputTemplate = '', $emailTemplate = '') {
        parent::__construct($fieldname, $name, $required, $validate, $outputTemplate, $emailTemplate);
    }

    function setOutputTemplate($outputTemplate) {
        if (!empty($outputTemplate))
            $this->outputTemplate = $outputTemplate;
        else
            $this->outputTemplate = '<tr valign=top><td width="22%%"><div align="right">%s%s</div></td>
			<td><textarea name=%s rows="%s" cols="%s">%s</textarea></td></tr>';
    }

    function output() {
        $output = sprintf($this->outputTemplate, $this->name, $this->required, $this->fieldname, $this->rows, $this->cols, $this->default);
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
        $text = str_replace("\'", "'", $this->data);
        $message = str_replace("[{$this->fieldname}]", $text, $message);
        return $message;
    }

}

