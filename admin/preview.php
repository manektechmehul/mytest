<?php
	include '../php/databaseconnection.php';
	$content_type_id = (isset($_REQUEST['content_type_id'])) ? $_REQUEST['content_type_id'] : '';
	$title = (isset($_REQUEST['title'])) ? $_REQUEST['title'] : '';
	$body = (isset($_REQUEST['body'])) ? $_REQUEST['body'] : '';

	$preview_sql = "delete from preview where content_type_id = '$content_type_id'";
	$preview_result = mysql_query($preview_sql);
	
	$preview_sql = "insert into preview (content_type_id, title, body) values ('$content_type_id', '$title', '$body')";
	$preview_result = mysql_query($preview_sql);
	$preview_id = mysql_insert_id();

	echo "<head><style type='text/css'><!--body {margin:0;padding:0;}--></style></head><body>";
	echo "<p style='font-family:Arial; font-size:11px; color:dddddd; margin:0; padding:8px 0; text-align:center; display:block; background:#333;'>This is a preview of this page for review only. <a style='color:fff;' href='javascript:window.close();'>Close window</a>.</p>";
	echo "<iframe src='/index.php?preview_id=$preview_id' width='100%' height='760px' frameborder='0'></iframe>";
	echo "</body>";
?>
