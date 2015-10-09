 <?
include_once "$base_path/modules/$module_path/conf.php";

$bulletinSearchboxTemplate = "$base_path/modules/$module_path/templates/searchbox.tpl";
$filterName = 'bulletins_searchbox';
$filters[$filterName] = array ('search_string'  => '/<!-- CS bulletins searchbox start -->.*<!-- CS bulletins searchbox end -->/s',
	'replace_string' => "{include file=\"$bulletinSearchboxTemplate\"}");


?>