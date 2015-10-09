<?php

$date_now = date("Y-m-d 0:0:0");

$archive = 0;

if (isset($has_archive) && $has_archive) 
	$archive = (isset($_REQUEST['archive'])) ? 1 : 0;


$diary_item_sql = "select * from diary_item WHERE content_type_id = $content_type_id  and published = 1 ";
if ($page_type == 2)
	$diary_item_sql .= " and archive = $archive ORDER BY date_of_event DESC";
else
	$diary_item_sql .= " and date_of_event >= '$date_now' ORDER BY date_of_event";

$diary_item_result = mysql_query($diary_item_sql);  

if (mysql_num_rows($diary_item_result) > 0) {

	echo "<div id='newsevents'>";

	//if ($page_type == 1)

	$event_date_to_display = "";

	$odd = false;
	while ($diary_item_row = mysql_fetch_array($diary_item_result)) 
	{
		echo "<div class='newseventsitem $oddeven_class'>";
		$event_day = substr($diary_item_row["date_of_event"], 8, 2);
		$event_month = substr($diary_item_row["date_of_event"], 5, 2);
		$event_year = substr($diary_item_row["date_of_event"], 0, 4); 

		$event_date =  mktime(12, 30, 10, $event_month, $event_day, $event_year);	

		if ($event_date_to_display != $event_date) {
			$event_date_to_display = $event_date;

			//echo "<tr valign=top><td colspan=2><hr></td></tr>";
			printf ("<span class='newsevents-item-date'>%s</span>", date('jS F Y', $event_date_to_display));
		}
		$oddeven_class = ($odd) ? 'newseventsitem-odd' : 'newseventsitem-even';
		$odd = !$odd;
		printf( "<span class='newsevents-item-title'><a href='{$page}?id={$diary_item_row['id']}'>%s</a></span> ", $diary_item_row["title"]);
		printf( "<span class='newsevents-item-summary'>%s</span> ", $diary_item_row["summary"]);
		echo "<span class='newsevents-item-link'> <a href='{$page}?id={$diary_item_row['id']}'>Read more</a></span>";
		echo "</div>";
	}

	echo "</div>";
}
else {
	echo '<p>';
	if ($page_type == 2) 
		if ($archive)
			echo NEWS_NO_ARCHIVE_NEWS_MESSAGE;
		else
			echo NEWS_NO_NEWS_MESSAGE;
	else
			echo EVENTS_NO_EVENTS_MESSAGE;

	echo '</p>';
}

if ($has_archive)
	if ($archive)
	{
		echo "<a href='news' onmouseover=\"MM_swapImage('news-current-button','','/images/buttons/button-current_news-over.png',1)\" onmouseout='MM_swapImgRestore()'>";
		echo "<img id='news-current-button' name='news-current-button' src='/images/buttons/button-current_news-off.png' alt='Current News' /></a>";
	}
	else
	{
		echo "<a href='news?archive' onmouseover=\"MM_swapImage('news-archive-button','','/images/buttons/button-news_archive-over.png',1)\" onmouseout='MM_swapImgRestore()'>";
		echo "<img id='news-archive-button' name='news-archive-button' src='/images/buttons/button-news_archive-off.png' alt='News Archive' /></a>";
	}
	
?>

