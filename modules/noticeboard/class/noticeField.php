<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of noticeField
 *
 * @author Ian
 */
class noticeField {

	public $name;

    function __construct($name) {
		$this->name = $name;
	}

	function getData($data) {

	}
}


class noticeWebLinkField extends noticeField {

	public $name;
	public $title;
	public $link;

    function __construct($name) {
		$this->name = $name;
	}

	function getData($data) {

	}
}
