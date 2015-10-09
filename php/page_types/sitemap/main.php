<?php
	$main_body_sql="select * from content where section_id = '$section_id' AND content_type_id = $content_type_id AND template_type = 'main_body' AND live = 1 order by order_num DESC";
    $main_body_result = mysql_query($main_body_sql);	
    $main_body_row = mysql_fetch_array($main_body_result);
	
	echo $main_body_row['body']."\n";
	
	$navmain_sql="select * from content c join content_type ct on ct.id = content_type_id join section s on s.id = c.section_id where section_type_id = '1'  AND s.status = '1' and ct.parent_id = '0' ORDER BY s.order_num";
	$navmain_result = mysql_query($navmain_sql);  

	echo "<ul>";

	while ($navmain_row = mysql_fetch_array($navmain_result)) {	

		$map_page_name = $navmain_row["page_name"];
		if ($navmain_row["id"] == "1") {
			$map_page_name = "/";
		}


		$section_id = $navmain_row["id"];

		// DISPLAY MAIN SECTION

		printf ("<li><a href=\"%s\">%s</a></li>",  $map_page_name, $navmain_row["name"]);

		// DISPLAY SUB-MENUS

		$subnav_sql="select * from content c join content_type ct on ct.id = content_type_id where ct.section_id = '$section_id' AND ct.parent_id != '0' AND ct.status = '1' and template_type = 'main_body' and level = 1 ORDER BY ct.order_num";
		$subnav_result = mysql_query($subnav_sql);  

		if (mysql_num_rows($subnav_result) > 0) {
			echo "<ul>";
			while ($subnav_row = mysql_fetch_array($subnav_result)) {	

				printf ("<li><a href=\"%s\">%s</a></li>", $subnav_row["page_name"], $subnav_row["name"]);
				$subSubParent = $subnav_row["content_type_id"];
				$subsubnav_sql="select * from content c join content_type ct on ct.id = content_type_id where ct.section_id = '$section_id' AND ct.parent_id = $subSubParent AND ct.status = '1' and template_type = 'main_body' and level = 2 ORDER BY ct.order_num";

				$subsubnav_result = mysql_query($subsubnav_sql);

				if (mysql_num_rows($subsubnav_result) > 0) {
					echo "<ul>";
					while ($subsubnav_row = mysql_fetch_array($subsubnav_result)) {
						printf ("<li><a href=\"%s\">%s</a></li>", $subsubnav_row["page_name"], $subsubnav_row["name"]);

					}
					echo "</ul>";
				}
			}
			echo "</ul>";
			}
							
		}
	echo "</ul>";



