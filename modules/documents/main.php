<?php

if (FIREBUG == 1)
    FB::info("In docs main.php, levels = $levels, name_parts = " . var_export($name_parts, true));

$hasSearch = DOCUMENTS_HAS_SEARCH;
$hasCategories = DOCUMENTS_HAVE_CATEGORIES;
$moduleNamePart = '';
include_once "$base_path/modules/$module_path/conf.php";
$module_root = "/{$name_parts[0]}";
$smarty->assign('member_module_root', $module_root);
$moduleObject->section_page_name = $module_root;
if ($levels == 1) {
    if ($hasSearch == 1) {
        $smarty->assign('module_root', $module_root);
        $smarty->display("$base_path/modules/$module_path/templates/searchbox.tpl");
    }
    if ($hasCategories)
        $moduleObject->DisplayList();
    else
        $moduleObject->DisplayAll();
};

if ($levels == 2) {
    if ($name_parts[1] == 'all') {
        if ($hasSearch == 1) {
            $smarty->assign('module_root', $module_root);
            $smarty->display("$base_path/modules/$module_path/templates/searchbox.tpl");
        }
        $moduleObject->DisplayAll();
    } else if ($name_parts[1] == 'results') {
        // Get the data
        $searchKeywords = $_POST['keywords'];
        $category = $_POST['category'];

        // clean the data
        $searchKeywords = strtolower(preg_replace('/[^a-z +]/', '', $searchKeywords));
        $category = !is_numeric($category) ? 0 : $category;

        //$smarty->display("$base_path/modules/$module_path/templates/searchbox.tpl");
        $moduleObject->DisplaySearchResults($searchKeywords, $category);
    } else {
        if ($hasSearch) {
            $moduleObject->SetCategoryForPage($name_parts[1]);
            $title = $moduleObject->GetCategoryTitle($moduleObject->currentCategory);
            //		$title .= ': '.$moduleObject->GetCategoryTitle($moduleObject->currentCategory);
            $moduleObject->DisplayList();
        }
        else
            $moduleObject->DisplayAll();
    }
}
