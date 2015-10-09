<?php

class email_field extends text_field {

    function __construct($fieldname, $name, $required = false, $validate = true, $outputTemplate = '', $emailTemplate = '', $outputRightTemplate = '') {
        parent::__construct($fieldname, $name, $required, $validate, $outputTemplate, $emailTemplate);
    }

    function isDataInvalid($email) {
        $valid = ereg('^[-!#$%&\'*+\\./0-9=?A-Z^_`a-z{|}~]+' .
                '@' .
                '[-!#$%&\'*+\\/0-9=?A-Z^_`a-z{|}~]+\.' .
                '[-!#$%&\'*+\\./0-9=?A-Z^_`a-z{|}~]+$', $this->data);

        if ($valid == false)
            $this->invalidDataMessage = 'Invalid email address';
        return ($valid == false);
    }

}