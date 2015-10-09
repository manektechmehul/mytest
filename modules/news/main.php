<?php

include_once 'conf.php';

$has_archive = (NEWS_HAS_ARCHIVE == 1);

$section_page_name = 'news';
$levels = count($name_parts);

$newsType = ($name_parts[1] == 'archive') ? 'archive' : 'current';
$smarty->assign('pageName', $currentPageUrl);

if (($name_parts[1] == 'archive')){
    $title .= " Archive";
}
if ($levels == 1){
    echo $content_row['body'];
}
if (($levels == 1) || (($levels == 2) && ($name_parts[1] == 'archive'))) {
    $viewing_archive = ($name_parts[1] == 'archive') ? 1 : 0;
    $newsItems = $moduleObject->DisplayList($newsType);
} else if (($levels == 2) || (($levels == 3) && ($name_parts[1] == 'archive'))) {
    $pageName = (($levels == 3) && ($name_parts[1] == 'archive')) ? $name_parts[2] : $name_parts[1];    
    $newsItems = $moduleObject->DisplayItem($pageName, $newsType);
    $og = $moduleObject->GetOGData($pageName, '/images/sharelogo.jpg');
    $smarty->assign('og', $og);   
    $title = $moduleObject->GetTitle($pageName);
}


    /*
		$viewing_archive = ($name_parts[1] == 'archive');
		if ($viewing_archive)
			$id = $name_parts[2];
		else
			$id = $name_parts[1]; 

		$newsSql = "select UNIX_TIMESTAMP(date) as date, title, body as description, archive from news WHERE id = '$id'";
		//$events_sql = "select UNIX_TIMESTAMP(`date`) as event_date, title, description from events WHERE id = '$id'";
		$newsResult = mysql_query($newsSql);

		if (mysql_num_rows($newsResult) > 0) {

			$news_row = mysql_fetch_array($newsResult);

			$diaryitem['title'] = $news_row['title'];

			$newsItem['event_date'] =  $news_row['event_date'];
			$newsItem['description'] = $news_row['description'];
			$newsItem['page_link'] = "/$section_page_name";
			 $newsItem['in_achive'] = $viewing_archive;
			$diary_single_template_file = "{$base_path}modules/$module_path/templates/single.tpl";
			$smarty->assign('newsitem', $newsItem);
			$smarty->assign('viewing_archive', $viewing_archive);
			$smarty->display("file:$diary_single_template_file");
		}
     *
     */

  