<?php
include_once "$base_path/modules/$module_path/conf.php";
$levels = count($name_parts);
$smarty->assign('pageName', $currentPageUrl);
// if someting link www.mywebsite.com/case-studies
if ($levels == 1)
{
    echo $content_row['body'];
    $moduleObject->SetCategoryForPage('Main');
     if($moduleObject->has_categories){
        $moduleObject->DisplayFeatureList();
    }else{
        // if there are no categories, we must display all - else how will a a user find them
        $moduleObject->DisplayList(); // 
    }
}
else if ($levels == 2)
{
    // echo 'levels is 2 ';
    $pageName = $name_parts[1];
    // if it's an integer - display the single view ... else list the category    
    // look up the item - check if it is a valid category name - if so process like a cat else like a single item
    $is_category_page = $moduleObject->isCategoryPage($name_parts[1]);    
    if(!$is_category_page){    
        $moduleObject->DisplayItem($pageName);        
        $og = $moduleObject->GetOGData($pageName);
        $smarty->assign('og', $og);	
        $title = $moduleObject->GetTitle($pageName);
    }else{        
        $moduleObject->SetCategoryForPage($name_parts[1]);          
        $moduleObject->DisplayList($name_parts[1]);        
	$title .= ': ' .  $moduleObject->GetCategoryTitle($moduleObject->currentCategory);    
    }     
}
