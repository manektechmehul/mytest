<?php
	$case_studies_sql = 'select * from case_study';
	$case_studies_result = mysql_query($case_studies_sql);
	$case_studies_array = array();
	while ($case_studies_row = mysql_fetch_array($case_studies_result))
	{
		$case_studies_array[] = array(
			'title' => $case_studies_row['title'],
			'description' => $case_studies_row['description'],
			'thumb' => $case_studies_row['thumb'],
			'link' => 'case_studes?id='.$case_studies_row['id'],
		);
	}
	$smarty->assign('case_studies', $case_studies_array);
	
	$smarty->assign('no_case_studies', CASE_STUDIES_NO_CASE_STUDIES_TEXT);
	
	$filters['case_studies'] = array('search_string'  => '/<!-- CS case_studies start -->(.*)<!-- CS case_studies end -->/s',
        'replace_string' => '{include file="subtemplates/case_studies.tpl"}');
?>