<?php
include_once "$base_path/modules/$module_path/conf.php";

$searchboxTemplate = "$base_path/modules/$module_path/templates/searchbox.tpl";
$filterName = 'searchbox';
$filters[$filterName] = array ('search_string'  => '/<!-- CS searchbox start -->.*<!-- CS searchbox end -->/s',
	'replace_string' => "{include file=\"$searchboxTemplate\"}");
