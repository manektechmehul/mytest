<?php

include_once "$base_path/modules/$module_path/conf.php";
$onpagelinkstemplate = "$base_path/modules/$module_path/templates/list.tpl";
// will need to figure out the last name part
$opl_page_name = $name_parts[0];

// if hompage set to slash for db look up
if($opl_page_name == ''){
	$opl_page_name = '/';	
	// should you need a different tpl file for the home page - make a tpl and use it
	// $onpagelinkstemplate = "$base_path/modules/$module_path/templates/homepage_list.tpl";
}


$opl_sidebox_sql = "SELECT opl.* , cs.title as cs_title, cs.description as cs_desc, cs.id AS cs_id, cs.page_name AS cs_page_name, cs.thumb as cs_thumb FROM onpagelink opl
INNER JOIN content ct ON ct.id = opl.`content_id`
left join case_study cs on opl.`module_id` = cs.`id`
WHERE ct.page_name ='{$opl_page_name}' AND  opl.published = '1' order by opl.order_num "; // need a oder by clause and a hidden condition

$opl_sidebox_result = mysql_query($opl_sidebox_sql);

// NOTE - error on truncate ?? make sure its in php funcs
while ($opl_sidebox_row = mysql_fetch_array($opl_sidebox_result)) {
	$sidebox_opl_items[] = array(
            'id' => $opl_sidebox_row['id'],
            'link_type' => $opl_sidebox_row['link_type'],
            'module_id' => $opl_sidebox_row['module_id'],
            'title' => $opl_sidebox_row['title'],
            'summary' => $opl_sidebox_row['summary'],
            //'summary' => truncate(strip_tags($opl_sidebox_row['summary'],'<a>'),50,'...',false,true),
            'file' => $opl_sidebox_row['file'],
            'thumb' => $opl_sidebox_row['thumb'],
			'audio' => $opl_sidebox_row['audio'],
            'link' => checklink($opl_sidebox_row['link'], $opl_sidebox_row['external_link']),
            'external_link' => $opl_sidebox_row['external_link'],
            'video_type' => $opl_sidebox_row['video_type'],
            'video_id' => $opl_sidebox_row['video_id'],
            'freetext' => $opl_sidebox_row['freetext'],
            'content_id' => $opl_sidebox_row['content_id'],
            'cs_title' => $opl_sidebox_row['cs_title'],
            //'cs_desc' => truncate(strip_tags($opl_sidebox_row['cs_desc']),50,'...',false,true),
		    'cs_desc' => $opl_sidebox_row['cs_desc'],
            'cs_id' => $opl_sidebox_row['cs_id'],
			'cs_thumb' => $opl_sidebox_row['cs_thumb'],
			'cs_page_name' => $opl_sidebox_row['cs_page_name'],
	);
}

$smarty->assign('sidebox_opl', $sidebox_opl_items);

$filterName = $module_path . '_opl_small';
$filters[$filterName] = array ('search_string'  => '/<!-- CS assoclinks start -->.*<!-- CS assoclinks end -->/s',
	'replace_string' => "{include file=\"$onpagelinkstemplate\"}");

?>