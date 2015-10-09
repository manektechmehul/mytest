<?
	$cspage = 'case_studies';

	$casestudies_sql="select id, title, thumb, body, page_image from case_study where id = '$id'";
    $casestudies_result = mysql_query($casestudies_sql);	
	$casestudies_row = mysql_fetch_array($casestudies_result);

    if ($casestudies_row['page_image'])
        $promo_image = sprintf("<img src='".USER_IMAGE_DIR."%s' alt='%s' />", $casestudies_row['page_image'], $title);
    
	//echo "<div style='margin-right:5px;'><img src='/UserFiles/Image/{$casestudies_row['thumb']}'></div>";
	echo "<br /><b>{$casestudies_row['title']}</b><br />";
	echo "<p>{$casestudies_row['body']}</p>";
	echo "<a class=\"button-purple\" href='$cspage'>View all Case Studies</a> ";
?>