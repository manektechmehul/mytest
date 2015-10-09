<?php
        require_once "$base_path/modules/$module_path/classes/main_class.php";    
        
         /* You need to rename the class in classes/main_class.php 
          * to be unique to this module */
	$moduleObject = new inpagelinks_Module($module_path, $session_section_id);
        
        /* configure the main object */        
        $moduleObject->AssignSearchCategories('bulletinsSearchCategories');  
        
        $moduleObject->listName = 'bulletins';
        $moduleObject->itemName = 'bulletin';
        $moduleObject->sideboxListName = 'sideboxbulletins';
        $moduleObject->pluralName = 'bulletins';
        $moduleObject->singleName = 'bulletin';
        $moduleObject->section_page_name = 'bulletins';

        $moduleObject->table = 'bulletins';
        $moduleObject->linkTable = 'bulletins_category_link';
        $moduleObject->categoryTable = 'bulletins_category';
        $moduleObject->linkField = 'bulletins_category_id';
        $moduleObject->keyField = 'bulletins_id';
        
        // TODO: get this module root working in the templates
        $module_root =  "/bulletins/"; // {$name_parts[0]}";
        $moduleObject->section_page_name = $module_root;
        
        $docs_page = 'bulletins';
	$title = 'Resourcing Mission Bulletin';  
        $smarty->assign('module_page', $docs_page); 
        
        $moduleNamePart = '';             
	// todo: rename and us this in the template files 
	$smarty->assign('module_root', $module_root);
      
        
        
	if (empty($parent_section_page_name)){
            $moduleObject->section_page_name = $docs_page;
        }else{
            $moduleObject->section_page_name = $parent_section_page_name.'/'.$docs_page;
        }        
	$moduleObject->sideboxItemsDisplayed = 3;
?>