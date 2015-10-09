<?php
// large ones, this sets the general template, the data is actually gathered via ajax.
$onpagelinkstemplate_large = "$base_path/modules/$module_path/templates/list_large.tpl";
$filterName = $module_path . '_opl_large';
$filters[$filterName] = array ('search_string'  => '/<!-- CS assoclinks_large start -->.*<!-- CS assoclinks_large end -->/s',
	'replace_string' => "{include file=\"$onpagelinkstemplate_large\"}");
?>
