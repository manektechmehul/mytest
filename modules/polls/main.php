<?php
$poll = $pollId;
$pollDetails = db_get_single_row("select question, answers from polls where id = $poll");
$answersStr = $pollDetails['answers'];
$answersStr = trim($answersStr);
$answers = preg_split("/\n/", $answersStr);

$total = db_get_single_value("select count(*) from poll_response where poll = $poll");
$responses = db_get_mapped_rows("select answer, count(*) as 'count' from poll_response where poll = $poll group by answer");

foreach ($answers as $num => $answer) {
    $pollResponses[] = array (
        'answer' => $answer,
        'percent' => $responses[$num + 1] * 100 / $total,
    );
}

$smarty->assign('question', $pollDetails['question']);
$smarty->assign('poll_responses', $pollResponses);

$pollResponsesTemplateFile = "$base_path/modules/polls/templates/single.tpl";
echo $smarty->fetch($pollResponsesTemplateFile);


