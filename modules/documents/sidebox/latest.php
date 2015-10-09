<?php

    $latest_documents = db_get_rows('SELECT * FROM documents WHERE published = 1 ORDER BY `date` DESC limit ' . DOCUMENTS_SIDEBOX_LIST_COUNT);
    $smarty->assign('documents', $latest_documents);
    $smarty->assign('docs_module_url', $module_url);


    $case_studies_latest_template_file = "$base_path/modules/documents/templates/latest.tpl";
    $filters['documents_latest'] = array('search_string'  => '/<!-- CS documents latest start -->(.*)<!-- CS documents latest end -->/s',
         'replace_string' => '{include file="file:'.$case_studies_latest_template_file.'"}');
?>
