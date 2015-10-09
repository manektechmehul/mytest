<?php
include_once "$base_path/modules/$module_path/classes/main.php";

/***************************************************************
 * 
 * 
 * Just set the following 3 field as appropriate
 * 
 * 
 */

// put this in the module to look up some key values from db
$module_key = 'SITE_HAS_CASE_STUDIES';
// TODO: register this class name somewhere ??
$moduleObject = new CaseStudiesModule($module_path, $paginate, $pageUrl, $module_key);
$module_prefix = "CASESTUDIES_";





/**
 * 
 * 
 * 
 * From here is mucky but doesn't require any edits  
 * TODO: These vars could do with being read into the module via the init config function
 * 
 *  
 * 
 * 
 * 
 */
$class_file = constant($module_prefix . "CLASSFILE"); // =casestudies
$module_classname = constant($module_prefix . "CLASSNAME"); // =CaseStudies
	/** TODO:  CASESTUDIES_SIDEBOX_NUMBER_OF_ITEMS is missing from the config table*/
$moduleObject->sideboxItemsDisplayed = 3; //constant($module_prefix . "SIDEBOX_NUMBER_OF_ITEMS");//


$moduleObject->has_latest_sidebox = constant($module_prefix . "HAS_LATEST_SIDEBOX");
$moduleObject->has_featured_sidebox = constant($module_prefix . "HAS_FEATURED_SIDEBOX");
// this may be used else where
/** @var  $hasSearch - TODO: CASESTUDIES_HAS_SEARCH is missing from the db config */
$hasSearch = 0; //constant($module_prefix . "HAS_SEARCH");
// this is used in the menu
$hasCategories = constant($module_prefix . "HAS_CATEGORIES");
$moduleObject->has_categories = $hasCategories;
$smarty->assign('module_page', $docs_page);
$pageUrl = '/' . $name_parts[0];
// TODo: pagination should be a db flag - still needs testing
$paginate = false;

if (empty($parent_section_page_name)){
    $moduleObject->section_page_name = $docs_page;
}else{
    $moduleObject->section_page_name = $parent_section_page_name . '/' . $docs_page;
}