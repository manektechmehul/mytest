<?php

class abstractField {
	function __construct() {
		;
	}
	
	function dataMissing() {
		return '';
	}
	
	function dataValid() {
		return array(true, '');
	}
	
	function display() {
		
	}
	
}