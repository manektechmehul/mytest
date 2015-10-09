<?php
	require_once "$base_path/modules/documents/conf.php";

	function getDocsSubmenu($menulink) {
		global $moduleObject;
		return $moduleObject->getSubmenu($menulink);
	}
