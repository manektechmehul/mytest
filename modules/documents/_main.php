<?php
	include_once "$base_path/modules/$module_path/conf.php";
    if ($levels == 1)
	{
		$smarty->display("$base_path/modules/$module_path/templates/searchbox.tpl");
		$docs_module->DisplayList();
	};
	
	if ($levels == 2)
	{
		if ($name_parts[1] == 'all')
		{
			$smarty->display("$base_path/modules/$module_path/templates/searchbox.tpl");
			$docs_module->DisplayAll();
		}
		else if ($name_parts[1] == 'results')
		{
			// Get the data
			$searchKeywords = $_POST['keywords'];
			$category = $_POST['category'];

			// clean the data
			$searchKeywords = preg_replace('/[^a-z ]/', '', $searchKeywords);
			$category = is_numeric($category) ? 0 : $category;

			$docs_module->DisplaySearchResults($searchKeywords, $category);
		}
		else
		{
			if (($name_parts[0] == 'documents') && ($docs_module->currentCategory == 1))
				$docs_module->SetCategoryForPage($name_parts[1]);
			$docs_module->DisplayList();
		}
	}
