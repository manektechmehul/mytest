<?php
include_once "php/news_events_list_function.php";
include_once "$base_path/modules/xml/processxml.php";
$num_documents_displayed = 4; 
$documents_sidebox_sql = "SELECT id, title, summary, `file`, `thumbnail`, UNIX_TIMESTAMP(`date`) AS documents_date  FROM documents  WHERE published = 1  ORDER BY DATE DESC LIMIT $num_documents_displayed";
// This is the site to get the data from
$url="http://www.local-sttoms.com/modules/xml/xmlfeed.php?customquery=";                                       
$data = getCrossSiteData($documents_sidebox_sql,$url, false);
$sidebox_docs_items = array();
$count = 0;
foreach ($data as $doc_sidebox_row) {
	$sidebox_docs_items[] = array(
		'date' => $doc_sidebox_row['documents_date'],
		'title' => $doc_sidebox_row['title'],
		'file' => $doc_sidebox_row['file'],
                'thumbnail' => $doc_sidebox_row['thumbnail'],
		'summary' => $doc_sidebox_row['summary'],
		'link' => '/downloads/'.$doc_sidebox_row['id']
	);        
}
$smarty->assign('sidebox_docs', $sidebox_docs_items);
$docs_sidebox_template_file = "$base_path/modules/documents/templates/latestXML.tpl";
// make sure this name is unique
$filters['document_xml'] = array ('search_string'  => '/<!-- CS XML Latest documents start -->.*<!-- CS XML Latest document end -->/s',
       'replace_string' => '{include file="'.$docs_sidebox_template_file.'"}');
?>