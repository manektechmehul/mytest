<?php
	       
        include_once "$base_path/modules/$module_path/conf.php";     
        
        if ($levels == 1)
	{
            // set the newest bulletin as the featured item
            $first_item_id = db_get_single_value("SELECT id FROM {$moduleObject->table} ORDER BY DATE DESC LIMIT 1", 'id');
            // show the 'latest bulletins' title in the template
            $smarty->assign('show_homepage_title', 'true');
            // get the feature item
            $item = $moduleObject->DisplaySingleItem($first_item_id);
            // show the first 8 itmes - less the featured one
            $moduleObject->DisplayAll(" limit 4 ", true,  $first_item_id);
            
            /*
		if ($hasSearch == 1){
			$smarty->assign('module_root',$module_root);
			$smarty->display("$base_path/modules/$module_path/templates/searchbox.tpl");
		}
                
		if ($hasCategories ){
			$moduleObject->DisplayList();
                }else{
			$moduleObject->DisplayAll();
                }
             * 
             */
	};
	
	if ($levels == 2)
	{
		if ($name_parts[1] == 'all')
		{
			if ($hasSearch == 1)
			{
				//$smarty->assign('module_root', $module_root);                              
				$smarty->display("$base_path/modules/$module_path/templates/searchbox.tpl");
			}
            
			// this will show the whole listing
			// add the link back to the latest link - as you are looking at the archive view
			$smarty->assign('show_back_to_latest_link', 'true');
			// get all of them, but limit things to 250
			$moduleObject->DisplayAll(" limit 250 ");
			
		}
		else if ($name_parts[1] == 'results')
		{
			// Get the data
			$searchKeywords = $_POST['keywords'];
			$category = $_POST['category'];
			// clean the data
			$searchKeywords = strtolower(preg_replace('/[^a-z +]/', '', $searchKeywords));
			$category = !is_numeric($category) ? 0 : $category;
			//$smarty->display("$base_path/modules/$module_path/templates/searchbox.tpl");
			$moduleObject->DisplaySearchResults($searchKeywords, $category);
		}
		else
		{
			if ($hasSearch)
			{
				$moduleObject->SetCategoryForPage($name_parts[1]);
				$title = $moduleObject->GetCategoryTitle($moduleObject->currentCategory);
		//		$title .= ': '.$moduleObject->GetCategoryTitle($moduleObject->currentCategory);
				$moduleObject->DisplayList();
			}
			else
				// how feature item
                                $item = $moduleObject->DisplaySingleItem($name_parts[1]);
                                // show the first 8 itmes - less the featured one                              
				$moduleObject->DisplayAll(" limit 8 ", true, $name_parts[1]);
		}
	}
