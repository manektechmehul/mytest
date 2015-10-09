<?

class address_field extends abstract_field {

    function __construct($fieldname, $name, $required = false, $validate = false, $outputTemplate = '', $emailTemplate = '') {
        parent::__construct($fieldname, $name, $required, $validate, $outputTemplate, $emailTemplate);
    }

    function output() {
        
    }

    function isDataMissing() {
        return empty($this->data);
    }

    function isDataInvalid() {
        return false;
    }

    function processTemplate($message) {
        $text = implode(',', $this->data);
        $message = str_replace("[{$this->fieldname}]", $text, $message);
        return $message;
    }

}

