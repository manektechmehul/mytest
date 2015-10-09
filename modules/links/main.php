<?php
	$cspage = 'links';
	
	$main_body_sql = "select body from content where section_id = '$section_id' AND content_type_id = $content_type_id AND template_type = 'main_body' AND live = 1 order by order_num DESC";
	$body = db_get_single_value($main_body_sql, 'body');
	$smarty->assign('body', $body);

	function getLinks()
	{
		$sql="select title, l.name, thumb, summary, l.link
				from link l 
				join link_category_link lcl on link_id = l.id 
				join link_category lc on link_category_id = lc.id
				where l.published = 1
				order by lc.order_num, title, l.name";

    	$result = mysql_query($sql);
		$currentTitle = '';
		while ($row = mysql_fetch_array($result))
		{	
			$row['new_cat'] = ($row['title'] != $currentTitle) ? true: false;
			$data[] = $row;	
			if ($row['new_cat'])
				$currentTitle = $row['title'];
		}
		return $data;
	}
	
	$linksData = getLinks(); 
	$links_template_file =  $base_path.'/modules/links/templates/links.tpl';
	$smarty->assign('linksData', $linksData);
	$smarty->display("file:$links_template_file");

	