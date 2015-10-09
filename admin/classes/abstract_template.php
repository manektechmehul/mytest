<?php


class abstractTemplate {

	function __construct() {
		//standard buttons
	}
	
	function getData() {
		foreach ($this->fields as $fieldname => $field) {
			$field->getData();
		}
	}
	
	function dataMissing() {
		$missing = array();
		foreach ($this->fields as $fieldname => $field) {
			$missing[] = $field->dataMissing();
		};
	}

	function dataValid() {
		$message = '';
		$valid = true;
		foreach ($this->fields as $fieldname => $field) {
			list($valid, $message) = $field->dataValid;
			if (!$valid) 
				break;
		};
		return array($valid, $message);
	}

}
