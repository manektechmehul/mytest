<?php
include_once "$base_path/modules/$module_path/classes/main.php";
/***************************************************************
 * 
 * Just set the following 3 field as appropriate
 * 
 */
// put this in the module to look up some key values from db
/* #module specific */
$module_key = 'SITE_HAS_LOTTERY';
// TODO: register this class name somewhere ??
/* #module specific */
$module_prefix = "LOTTERY_";
/* #module specific */
/* these are not used in this module */
$paginate = 0; //constant($module_prefix . "PAGINATE");
$paginate_items_per_page = 0; // constant($module_prefix . "PAGINATE_ITEMS_PER_PAGE");

$moduleObject = new LotteryModule($module_path, $paginate,$paginate_items_per_page, $pageUrl, $module_key);

/*  
 * From here is mucky but doesn't require any edits  
 * TODO: These vars could do with being read into the module via the init config function
 *  
 */
$class_file = constant($module_prefix . "CLASSFILE"); 
$module_classname = constant($module_prefix . "CLASSNAME"); 
$moduleObject->sideboxItemsDisplayed = 0; //constant($module_prefix . "SIDEBOX_NUMBER_OF_ITEMS");
$moduleObject->has_latest_sidebox = constant($module_prefix . "HAS_LATEST_SIDEBOX");
$moduleObject->has_featured_sidebox = constant($module_prefix . "HAS_FEATURED_SIDEBOX");


$hasCategories = constant($module_prefix . "HAS_CATEGORIES");
$hasSearch = constant($module_prefix . "HAS_SEARCH");
if($hasSearch){
    $moduleObject->createSearchBox($hasCategories);
}

$moduleObject->has_categories = $hasCategories;
$smarty->assign('module_page', $docs_page);
$pageUrl = '/' . $name_parts[0];

if (empty($parent_section_page_name)){
    $moduleObject->section_page_name = $docs_page;
}else{
    $moduleObject->section_page_name = $parent_section_page_name . '/' . $docs_page;
}