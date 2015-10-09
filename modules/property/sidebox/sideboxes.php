<?php
include_once "$base_path/modules/$module_path/conf.php";
// bounce of the main class to get the required sideboxes


if(call_user_func(array($moduleObject, 'doSideBoxes'))){
    $moduleObject->doSideBoxes();    
}else{
    // echo '<br> could not launch sideboxes '; exit;   
    $searchboxTemplate = "$base_path/modules/$module_path/templates/searchbox.tpl";
    $filterName = 'searchbox';
    $filters[$filterName] = array ('search_string'  => '/<!-- CS searchbox start -->.*<!-- CS searchbox end -->/s',
	'replace_string' => "{include file=\"$searchboxTemplate\"}");
}
?>
