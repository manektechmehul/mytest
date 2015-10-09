<?php

include_once "$base_path/modules/$module_path/classes/news.php";
$smarty->assign('module_page', $docs_page);
$pageUrl = '/' . $name_parts[0];
if ($name_parts[1] == 'archive') {
    $pageUrl .= '/' . $name_parts[1];
}

$paginate = constant("NEWS_PAGINATE");
$paginate_items_per_page =  constant("NEWS_PAGINATE_ITEMS_PER_PAGE");

// $moduleObject = new PropertyModule($module_path, $paginate,$paginate_items_per_page, $pageUrl, $module_key);
$moduleObject = new newsModule($module_path, $paginate,$paginate_items_per_page, $pageUrl, $module_key);

if (empty($parent_section_page_name)) {
    $moduleObject->section_page_name = $docs_page;
} else {
    $moduleObject->section_page_name = $parent_section_page_name . '/' . $docs_page;
}
$moduleObject->sideboxItemsDisplayed = 3;
$hasSearch = 0;
$hasCategories = 1;