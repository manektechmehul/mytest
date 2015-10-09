<?php

if (SITE_HAS_NICE_URLS)
{
	$page = strip_tags($_SERVER['REQUEST_URI']);
	if (strpos($page,'?'))
		$page = substr($page, 0, strpos($page, '?'));
	if (isset($preview) && $preview)
		$page = substr($page, 8);
	$page_name = substr($page, 1); 
	
	$article_name = '';
     $print_template = false;
	
	if (strpos($page_name,'/'))
	{
		$name_parts = explode('/', $page_name);
		$page_name = $name_parts[0];
        if ($page_name == 'print')
        {
            array_shift($name_parts);
            $print_template = true;
            $page_name = $name_parts[0];
            if ($page_name == '')
                $page_name = $page = '/';
        }
		$article_name = $name_parts[1];
	}
	else
		$name_parts = array($page_name);
    $currentPageUrl = $page;

	$levels = count($name_parts);
	$smarty->assign('page_url_parts', $name_parts);
	$smarty->assign('page_url_levels', $levels);
	$smarty->assign('page_name', $page_name);	
    $smarty->assign('site_address', SITE_ADDRESS);

	$current_page['page_name'] = $page_name;
	$current_page['page_params'] = substr(strrchr($_SERVER['REQUEST_URI'], '?'),1);
	$tpl_type = 'content';
	
	if (($page == "/index.php") || ($page == "/content.php") || ($page == "/") )
	{
		$section_id =  (isset($_REQUEST['section_id'])) ? $_REQUEST['section_id'] : 1;
		$content_type_id = (isset($_REQUEST['content_type_id'])) ? $_REQUEST['content_type_id'] : "";                
		$id  = (isset($_REQUEST['id'])) ? $_REQUEST['id'] : "";
		$preview_id = (isset($_REQUEST['preview_id'])) ? $_REQUEST['preview_id'] : "";
	}
	else
	{	
		//check to see if there is a file that exists (e.g. site map)
            
		if (file_exists($root.$page)) 
		{
			// use the file if it exists
                    
			include($root.$page);
			if ($use_smarty)
				include_once './php/output.php';
			exit();
		}
		else {
			// the file doesn't exist so try and find it in the content table
                       
			$content_sql = "select section_id, content_type_id, id, template_type from content where page_name = '$page_name'";
  			$content_result = mysql_query($content_sql);  
			$content_row = mysql_fetch_array($content_result);

			if (mysql_num_rows($content_result) == 0)			
			{
				$content_sql = "select section_id, content_type_id, id, template_type from content where page_name = 'page_not_found' or page_name = 'page-not-found'";
	  			$content_result = mysql_query($content_sql);  
				$content_row = mysql_fetch_array($content_result);
			}			
			
			if (mysql_num_rows($content_result) > 0)
			{
				// ok found the "page" in content ...
				$section_id = $content_row['section_id'];
				$content_type_id = $content_row['content_type_id'];
				if ($content_row['template_type'] == 'main_body')
					$id =  "";
				else
					$id = $content_row['id'];
				$rewrite = true;
			}
		}
	}

	if  ($section_id == 1)
		$tpl_type = 'index';
}
