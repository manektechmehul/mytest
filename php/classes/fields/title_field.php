<?php

class title_field extends abstract_field {

    public $dataField = false;

    function __construct($fieldname, $name, $required = false, $validate = false) {
        parent::__construct($fieldname, $name, $required, $validate, $outputTemplate, $emailTemplate);
        $this->dataField = false;
        $this->required = false;
    }

    function setOutputTemplate($outputTemplate) {
        if (!empty($outputTemplate))
            $this->outputTemplate = $outputTemplate;
        else
            $this->outputTemplate = "<tr valign=\"middle\">\n<td colspan=2>%s</td></tr>\n\n";
    }

    function output() {
        $output = sprintf($this->outputTemplate, $this->name);
        return $output;
    }

}

