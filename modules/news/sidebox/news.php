<?php
include_once "php/news_events_list_function.php";

$num_news_displayed = NEWS_SIDEBOX_LIST_COUNT;
// SEE FEATURE 1/0 TO HIDE ID DISPLAYED IN FEATURE LIST
$news_sidebox_sql = "select id, title, summary,  body as description, unix_timestamp(`date`) as news_date, thumbnail, page_name from news where published = 1 and featured=0 and archive = 0 order by date desc limit $num_news_displayed";

$news_sidebox_result = mysql_query($news_sidebox_sql); 

$sidebox_news_items = array();

while ($news_sidebox_row = mysql_fetch_array($news_sidebox_result)) {
	$sidebox_news_items[] = array(
		'date' => $news_sidebox_row['news_date'],
		'title' => $news_sidebox_row['title'],
		'description' => $news_sidebox_row['description'],
		'summary' => $news_sidebox_row['summary'],
		'link' => '/news/'.$news_sidebox_row['page_name'],
		'thumbnail' => $news_sidebox_row['thumbnail']
	);
}

$smarty->assign('sidebox_news', $sidebox_news_items);
$smarty->assign('news', $sidebox_news_items);
$smarty->assign('NEWS_HAS_THUMBNAIL',NEWS_HAS_THUMBNAIL);
//$smarty->assign('no_news',  NEWS_NO_NEWS_MESSAGE);

$news_sidebox_template_file = "$base_path/modules/news/templates/sidebox.tpl";

$filters['news'] = array ('search_string'  => '/<!-- CS news start *-->.*<!-- CS news end *-->/s',
       'replace_string' => '{include file="'.$news_sidebox_template_file.'"}');

