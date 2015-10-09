<?php
	include_once "php/news_events_list_function.php";
// todo: add new featured flag
	$num_f_news_displayed = NEWS_FEATURED_SIDEBOX_NUMBER_OF_ITEMS;
	$news_featured_sidebox_sql = "select id, title, summary,  body as description, unix_timestamp(`date`) as news_date, thumbnail, page_name from news where published = 1 and archive = 0 and featured=1 order by date desc limit $num_f_news_displayed";

	$news_featured_sidebox_result = mysql_query( $news_featured_sidebox_sql );
	$sidebox_news_items = array();
	while ( $news_featured_sidebox_row = mysql_fetch_array( $news_featured_sidebox_result ) ) {
		$sidebox_news_items_f[] = array(
			'date' => $news_featured_sidebox_row['news_date'],
			'title' => $news_featured_sidebox_row['title'],
			'description' => $news_featured_sidebox_row['description'],
			'summary' => $news_featured_sidebox_row['summary'],
			'link' => '/news/' . $news_featured_sidebox_row['page_name'],
			'thumbnail' => $news_featured_sidebox_row['thumbnail']
		);
	}
        
	$smarty->assign( 'sidebox_news_f', $sidebox_news_items_f );
	$smarty->assign( 'news_f', $sidebox_news_items );
	$smarty->assign( 'NEWS_HAS_THUMBNAIL', NEWS_HAS_THUMBNAIL );
//$smarty->assign('no_news',  NEWS_NO_NEWS_MESSAGE);
	$featured_news_sidebox_template_file = "$base_path/modules/news/templates/featured.tpl";
	$filters['featured_news'] = array( 'search_string' => '/<!-- CS featured news start *-->.*<!-- CS featured news end *-->/s',
		'replace_string' => '{include file="' . $featured_news_sidebox_template_file . '"}' );

