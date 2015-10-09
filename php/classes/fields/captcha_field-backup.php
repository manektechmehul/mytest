<?php

class captcha_field extends abstract_field {

    private $titlepos;
    private $len;
    public $outputRightTemplate;

    function __construct($fieldname, $name, $required = false, $validate = false, $outputTemplate = '', $emailTemplate = '', $outputRightTemplate = '')
	{
        parent::__construct($fieldname, $name, $required, $validate, $outputTemplate, $emailTemplate);
        $this->titlepos = '';
        $this->outputRightTemplate = '';
		$this->validate = true;
        //$this->validate = false; // auto form will force validation
    }

    function setOutputTemplate($outputTemplate) {
        if (!empty($outputTemplate))
            $this->outputTemplate = $outputTemplate;
        else
            $this->outputTemplate = '<tr valign="middle">
					<td width="22%%">
					  <div align="right">%s*</div>
					</td>
					<td><span id="captcha_flag" style="display:none"></span>
					<span id="captchaImg"></span>
					 
					<img src="/php/securimage/securimage_show.php" style="float:left; width:220px;"><input style="float:left; width:220px; height:40px;" type="text" name="%s" />
				  </tr>';
    }

    function output() {
        $output = sprintf($this->outputTemplate, $this->name, $this->fieldname);
        return $output;
    }

    function getEmailMessage() {
        return '';
    }

    function isDataMissing() {
        return empty($this->data);
    }

    function isDataInvalid() {
        include './php/securimage/securimage.php';
        $img = new securimage();

        if ($img->check($this->data))
            return false;
        else {
            $this->invalidDataMessage = "{$this->name}: Invalid " . $this->name;
            return true;
        }
    }

    function processTemplate($message) {
        $message = str_replace("[FC:{$this->fieldname}]", ucwords($this->data), $message);
        $message = str_replace("[{$this->fieldname}]", $this->data, $message);
        return $message;
    }

}