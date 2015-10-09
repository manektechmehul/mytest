<?php
	include_once "$base_path/modules/documents/conf.php";

	//$submenu = $docs_module->getSubmenu($menulink);
	$submenu = $moduleObject->getSubmenu($menulink);
	$menuitem['submenu'] = $submenu;
	$menuitem['has_submenu'] = $docs_module->hasSubmenu;
