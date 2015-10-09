<?php
	// ORIGINAL... $main_body_sql="select * from content where section_id = '$section_id' AND content_type_id = $content_type_id AND template_type = 'main_body' AND live = 1 order by order_num DESC";
	$main_body_sql="select * from content where section_id = '$section_id' AND content_type_id = $content_type_id AND template_type = 'main_body' order by order_num DESC";
    $main_body_result = mysql_query($main_body_sql);	
    $main_body_row = mysql_fetch_array($main_body_result);
	
    if (FIREBUG == 1)    
        FB::log("SQL : $main_body_sql");

	$form_id = $content_row['form_id'];

	$suppress_output = false;
	if ($form_id > 0)
	{
		include './php/classes/auto_form.php';
		$page_form = new auto_form($form_id);

		$page_form->get_data();

		$submit = (isset($_POST['Submit'])) ? $_POST['Submit'] : "";

		if ($submit) 
		{
			$suppress_output = true;
			$page_form->validate_data();
			
			if ($page_form->has_errors())
				$page_form->display_errors();
			else
				$page_form->process_data();
		}
	}
 

	if (!$suppress_output)
	{
		echo $main_body_row['body']."\n";

		if (($form_id > 0) && (!$submit))
		{
			$page_form->display_form();
		}

		if ($content_type_id != 0) {

			// IF NO content_id DISPLAY MAIN BODY OF PAGE CONTENT FOR content_type_id
			// AND LIST items for content_type

			$sql="select * from content where content_type_id = $content_type_id AND template_type != 'main_body' AND live = 1 order by order_num DESC";
			$result = mysql_query($sql);				
			if (mysql_num_rows($result) > 0) 
			{
				echo '<hr />';
				while ($myrow = mysql_fetch_array($result)) {	
					echo "<p><span class='large'>{$myrow["title"]}</span><br />";
					echo "{$myrow["summary"]}<br />";
					if ($myrow["page_name"])
						printf ("<a href=\"%s\">Read more</a>", $myrow["page_name"]);
					else
						printf ("<a href=\"%s?id=%s\">Read more</a>", $page_name, $myrow["id"]);
				}
				
			}
		}


	}
