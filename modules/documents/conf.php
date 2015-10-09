<?php
	require_once "$base_path/modules/documents/classes/documents.php";

	$docs_page = 'documents';
	//$title = 'Downloads';
	$smarty->assign('module_page', $docs_page);
	$moduleObject = new docsModule($module_path, $session_section_id);
	$moduleObject->AssignSearchCategories();

	if (empty($parent_section_page_name))
		$moduleObject->section_page_name = $docs_page;
	else
		$moduleObject->section_page_name = $parent_section_page_name.'/'.$docs_page;

	$moduleObject->sideboxItemsDisplayed = 3;
   
        $module_url = "downloads";
