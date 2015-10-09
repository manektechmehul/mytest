<?php

include_once "$base_path/modules/$module_path/conf.php";
$levels = count($name_parts);
$smarty->assign('pageName', $currentPageUrl);
// if something link www.mywebsite.com/case-studies
$hasSearch = PROPERTY_HAS_SEARCH;
$hasCategories = PROPERTY_HAVE_CATEGORIES;

if ($levels == 1)
{   
    //echo $content_row['body'];
    $moduleObject->SetCategoryForPage('Main');
    #echo $moduleObject->has_categories; exit;
    
    if($moduleObject->has_categories){             
        $prop_category=$moduleObject->GetSearchCategories();
        $smarty->assign('prop_category', $prop_category);   
        #echo "<pre>"; print_r($prop_category); exit;
        $searchboxTemplate = "$base_path/modules/$module_path/templates/searchbox.tpl";
        $smarty->assign('searchboxTemplate', $searchboxTemplate);        
        $moduleObject->DisplayFeatureList(); 
        dsfsdf 
    }else{
       
        // if there are no categories, we must display all - else how will a a user find them        
        $moduleObject->DisplayList(); // 
    }
}
else if ($levels == 2)
{
    // echo 'levels is 2 ';
    $pageName = $name_parts[1];
    if($pageName=='populate'){
        $moduleObject->insertRandomData(30);
    }
    // if it's an integer - display the single view ... else list the category    
    // look up the item - check if it is a valid category name - if so process like a cat else like a single item
    $is_category_page = $moduleObject->isCategoryPage($name_parts[1]);    
    if(!$is_category_page){            
       // if we are not looking a category - check if we have a search system in place 
       if (constant($module_prefix . "HAS_SEARCH")){              
            if ($name_parts[1] == 'all')
                // $this->createSearchBox();
                $moduleObject->DisplayAll();
            else if ($name_parts[1] == 'results') {
                // Get the data
                $searchKeywords = $_POST['keywords'];
                // $category = $_GET['category'];
                // clean the data
                $searchKeywords = preg_replace('/[^a-z ]/', '', $searchKeywords);
                //$category = is_numeric($category) ? 0 : $category;               
                
                $moduleObject->DisplayCustomSearchResults($searchKeywords,$_POST['location'], $_POST['bedroom'], $_POST['year']);                 
                
            }else{
                $moduleObject->DisplayItem($pageName);        
                $og = $moduleObject->GetOGData($pageName);
                $smarty->assign('og', $og);	
                $title = $moduleObject->GetTitle($pageName);
            }              
       }else{
        // if no search just output the appropriate item        
        $moduleObject->DisplayItem($pageName);        
        $og = $moduleObject->GetOGData($pageName);
        $smarty->assign('og', $og);			
        $title = $moduleObject->GetTitle($pageName);        
       }      
        
    }else{        
        $moduleObject->SetCategoryForPage($name_parts[1]);          
        $moduleObject->DisplayList($name_parts[1]);        
		$title .= ': ' .  $moduleObject->GetCategoryTitle($moduleObject->currentCategory);    
    }     
}

#echo "<pre>"; print_r($filters); exit;
//$moduleObject->createSearchBox();


