<?php
	include_once "$base_path/modules/$module_path/classes/events.php";

	$smarty->assign('module_page', $docs_page);

    $pageUrl = '/'.$name_parts[0];
    if ($name_parts[1] == 'archive')
        $pageUrl .= '/'.$name_parts[1];


$paginate = constant("EVENTS_PAGINATE");
$paginate_items_per_page =  constant("EVENTS_PAGINATE_ITEMS_PER_PAGE");


$moduleObject = new EventsModule($module_path, $paginate,$paginate_items_per_page, $pageUrl, $module_key);
//	$moduleObject = new EventsModule($module_path, $pageUrl);

	if (empty($parent_section_page_name))
		$moduleObject->section_page_name = $docs_page;
	else
		$moduleObject->section_page_name = $parent_section_page_name.'/'.$docs_page;

	$moduleObject->sideboxItemsDisplayed = 3;

	$hasSearch = 0;
	$hasCategories = 1;
