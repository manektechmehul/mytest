<?php

    function GetPoll($id) {
        global $smarty;
        
        $sql = "select * from polls where id = $id";
        $pollRow = db_get_single_row($sql);
        $poll =  array(
            'id' => $id,
            'question' => $pollRow['question'],
            'answers' => preg_split("/\r\n/", $pollRow['answers'], null, PREG_SPLIT_NO_EMPTY),
            );
        return $poll;
    }
    
if (($page_type_row['id'] == 0) && (empty($article_name))) {
    //require_once $base_path.'/modules/normal/classes/normal.php';
    //$currentPage = new normal('normal', $content_row['form_id']);
    
    // need to think of a way to make this dynamic
    
    $pollId = $content_type_row['poll_id'];
    if ($pollId) {
        $poll = GetPoll($pollId);
        $smarty->assign('poll', $poll);
    }
}
$pollTemplateFile = "$base_path/modules/polls/templates/sidebox.tpl";

 

$filters['pollsidebox'] = array('search_string'  => '/<!-- CS poll start -->(.*)<!-- CS poll end -->/s',
   'replace_string' => '{if isset($poll)}{include file="'.$pollTemplateFile.'"}{/if}');
