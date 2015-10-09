<?
	$cspage = 'case_studies';

	$casestudies_sql="select id, title, thumb, body from case_study where id = '$id'";
    $casestudies_result = mysql_query($casestudies_sql);	
	$casestudies_row = mysql_fetch_array($casestudies_result);

	echo "<br /><br />";
	echo "<div style='margin-right:5px;'><img src='/UserFiles/Image/{$casestudies_row['thumb']}'></div>";
	echo "<br /><b>{$casestudies_row['title']}</b><br />";
	echo "<p>{$casestudies_row['body']}</p>";
	echo "<a href='$cspage'>View all Case Studies.</a> ";
?>