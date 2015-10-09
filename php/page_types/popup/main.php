<?php
	$template_file = 'popup.htm';
	echo "<h1>$title</h1>";    
	$main_body_sql="select * from content where section_id = '$section_id' AND content_type_id = $content_type_id AND template_type = 'main_body' AND live = 1 order by order_num DESC";
    $main_body_result = mysql_query($main_body_sql);	
    $main_body_row = mysql_fetch_array($main_body_result);
	
	if (isset($preview) && $preview)
		echo $preview_body."\n";
	else
		echo $main_body_row['body']."\n";

	if ($content_type_id != 0) {

		// IF NO content_id DISPLAY MAIN BODY OF PAGE CONTENT FOR content_type_id
		// AND LIST items for content_type

		echo "<p>";
		display_items ($section_id, $content_type_id, "", 100, $path_prefix . USER_IMAGE_DIR, "no");				
	}
?>
