<?php
	$article_sql = "select * from content where id = $id";
	$article_result = mysql_query($article_sql);	

	$article_row = mysql_fetch_array($article_result);
	

	$back_sql = "select page_name from content where content_type_id = $content_type_id and template_type = 'main_body'";
	$back_result = mysql_query($back_sql);
	$back_row = mysql_fetch_array($back_result);
	$back_page = $back_row['page_name'];
	
	echo '<h1 class="sifr-title">'.$article_row['title'].'</h1>';
	echo '<p>'.$article_row['body'].'</p>';
	echo "<a href='$back_page'>Back</a></li>";
?>