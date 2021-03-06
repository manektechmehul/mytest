<?php

include_once $base_path . '/php/classes/page_listmodule.php';
include_once $base_path . '/php/classes/pagination.php';
include_once 'model/model.php';

//include_once $base_path.'/modules/sitecategories/classes/categories.php';

class VideoModule extends PageListModule {

    public $listName;
    public $currentSection;
    public $title;
    public $pageName;
    public $has_categories;
    // extracted from db on init
    public $module_name;
    public $module_web_path;
    public $level;
    public $page_type;
    public $parent_id;
    public $content_type_id;
    public $section_id;
    public $nav_title;
    public $module_title;
    // new ones that need to go into the db
    // new ones to put in the db
    public $sidebox_filter_key;
    
    // populated from the conf page
    public $has_latest_sidebox;
    public $has_featured_sidebox;
    /*
     * Pagination is currently untested 
     * Menu type 1/0 is still unknown
     * Testing this module skeleton with another active module is still unknown
     */

    function __construct($module_path, $paginate, $pageUrl, $module_key) {

        $this->getModuleKeys($module_key);

        /* #module specific */
        $this->listName = 'videos';
        $this->itemName = 'video';
        $this->pluralName = 'Videos';
        $this->singleName = 'Video';
        $this->section_page_name = 'videos';

        $this->table = 'video';
        $this->categoryTable = 'video_category';
        
        $this->linkTable = 'video_category_lookup';        
        $this->linkField = 'category_id';
        $this->keyField = 'item_id';

        $this->sideboxListName = 'sideboxdocuments'; // ?? odd name

        $this->pageName = $pageName;
        $this->currentCategory = "";

        /* #module specific */
        $this->model = new videomodel($pageUrl);
        $this->model->setTable('video');
        
        $this->model->setpublishedField('published');
        $this->model->setOrderField('order_num desc');
               

        /* #module specific */
        $this->sidebox_filter_key = "video";

        /*
          $this->moduleFolder this is the file system location, is set automatically in the listmodule
         */
        parent::__construct($module_path, $paginate, $pageUrl);
    }

    function getModuleKeys($module_key) {
        // get the module data from the database
       $sql = "SELECT 
module.name, module.path AS module_path,content_type.level, content_type.page_type, content_type.parent_id, content.page_name, content_type_id, content.section_id, nav_title, title 
FROM content_type
INNER JOIN page_type ON (content_type.page_type=page_type.id) 
INNER JOIN content ON (content.content_type_id=content_type.id) 
INNER JOIN module ON (module.constant = page_type.config_flag )
WHERE page_type.config_flag = '" . $module_key . "';";

        $row = db_get_single_row($sql);        
        //echo $sql;
        //var_dump($row);
        $this->module_name = $row['name'];
        $this->module_web_path = $row['page_name'];
        $this->level = $row['level'];
        $this->page_type = $row['page_type'];
        $this->parent_id = $row['parent_id'];
        $this->content_type_id = $row['content_type_id'];
        $this->section_id = $row['section_id'];
        $this->nav_title = $row['nav_title'];
        $this->module_title = $row['title'];
        
        // get module settings here ?                      
        
    }

    function GetOGData($page, $thumb) {
        $data = $this->model->ReadItem($page);
        $og = array(
            'title' => $data['title'],
            'thumb' => '/UserFiles/Image/' . $data['thumb'],
            'description' => $data['description']
        );
        return $og;
    }

    function DisplayItem($id) {
        global $smarty;
        global $name_parts;
        $smarty->assign('button_text', 'Back to ' . $this->pluralName . ' list');
        if ($this->has_categories) {
            // as we don't know where it came from , just do a browser back
            $smarty->assign('button_link', 'do_js_back');
        } else {
            // link back to base listing
            $smarty->assign('button_link', '/' . $name_parts[0]);
        }
        //parent::DisplayItem($id);
      
        $item = $this->GetSingleItem($id);
        $smarty->assign('listName',  $this->listName);
        $smarty->assign('itemName',  $this->itemName);
        $smarty->assign('pluralName',  $this->pluralName);                  
        $smarty->assign('module_web_path', $this->module_web_path);
        // todo:make this the default at some point ?
        $smarty->assign('singleitem', $item);          
        $smarty->display("file:" . $this->singleTemplateFile);
    }

    function DisplayItemByPageName($pagename) {

        global $smarty;
        $item = $this->model->GetItemByPageName($pageName);
        // $this->itemName
         $smarty->assign($this->itemName, $item); // $item);   
        //$smarty->assign('singleitem', $item);
        $this->title = $item['title'];        
        $templateFile = $this->moduleFolder . 'templates/singe.tpl';
        $smarty->display("file:" . $templateFile);
    }

    function SetCategoryForPage($pagename) {
        $this->currentCategory = db_get_single_value("select id from {$this->categoryTable} where page_name = '$pagename'", 'id');
    }

    function GetCategoryTitle() {
        $sql = "select title from {$this->categoryTable} where id = {$this->currentCategory}";
        return db_get_single_value($sql, 'title');
    }

    
     function DisplayFeatureList(){
        // this is basically a mashup of feature list and display list
        global $smarty;
        global $base_path;
        global $filters;
        global $module_path;
        global $module_prefix;  
        global $currentPageUrl;
                     
        $sql = "select * from {$this->table} ";        
        $sql .= " where published = 1 and featured =1 ";
        $sql .= " order by order_num desc  ";                         
        $items = db_get_rows($sql);  
        $smarty->assign('itemList', $items);     
        $smarty->assign('listName',  $this->listName);
        $smarty->assign('itemName',  $this->itemName);
        $smarty->assign('pluralName',  $this->pluralName);                  
        $smarty->assign('module_web_path', $this->module_web_path);       
        $templateFile = $this->moduleFolder . 'templates/list.tpl';
        $smarty->display("file:" . $templateFile); 
       
    }
    
    function DisplayList() {
        global $smarty;
        global $currentPageUrl;
        $items = $this->GetItems();
        $smarty->assign('itemList', $items);
        if ($this->paginate) {
            $paginationStr = $this->pagination->html_string($currentPageUrl . '?page=');
            $smarty->assign('pagination', $paginationStr);
            $smarty->assign('no_' . $this->listName, $this->noItemsMessage);
        }
        $smarty->assign('listName',  $this->listName);
        $smarty->assign('itemName',  $this->itemName);
        $smarty->assign('pluralName',  $this->pluralName);                  
        $smarty->assign('module_web_path', $this->module_web_path);       
                      
        $templateFile = $this->moduleFolder . 'templates/list.tpl';
        $smarty->display("file:" . $templateFile);
    }

    function isCategoryPage($page_name) {
        // Do a lookup to see if the pagename references category
        // should this go up to the listmodule obj ?
        $sql = "SELECT COUNT(*) FROM {$this->categoryTable} WHERE page_name='{$page_name}'";
        $count = db_get_single_value($sql);
        if ($count > 0) {
            return true;
        } else {
            return false;
        }
    }

    function ReadItems($sql) {
        $items = array();
        $result = mysql_query($sql);
        while ($row = mysql_fetch_array($result)) {
            /* #module specific - addfields */
            $items[] = array(
                'id' => $row['id'],
                'title' => $row['title'],
                'description' => $row['description'],
                'link' => '/' . $this->section_page_name . '/' . $row['id'],
                'page_name' => $row['page_name'],
                'thumb' => $row['thumb'],
                'video_id' => $row['video_id'],
                'video_type' => $row['video_type']
            );
        };
        return $items;
    }

    // set the field used in a listing here
    // todo: extract this list of field name to somewhere useful
    function GetItems() {
        $cat_join = " join {$this->linkTable} cl on {$this->keyField} = t.id ";
        $cat_clause = "and {$this->linkField} = '{$this->currentCategory}'";

        if ($this->paginate) {
            $limits = $this->pagination->GetLimits();
        }

        if ($this->currentCategory == '') {
            $cat_clause = '';
            /* #module specific - addfields */
            $sql = "SELECT SQL_CALC_FOUND_ROWS t.id, title, description, thumb, description, page_name, video_id, video_type " .
                    " from {$this->table} t " .
                    "where published = 1 " .
                    //"and deadline > now() ".
                    "order by `order_num` desc ";
        } else {
            /* #module specific - addfields */
            $sql = "SELECT SQL_CALC_FOUND_ROWS t.id, title, description, thumb, description, page_name, video_id, video_type " .
                    " from {$this->table} t " .
                    $cat_join .
                    "where published = 1 " .
                    $cat_clause .
                    //"and deadline > now() ".
                    "order by `order_num` desc ";
        }

        $items = $this->ReadItems($sql);
        if ($this->paginate) {
            $this->pagination->set_pages();
        }
        return $items;
    }

    function doSideBoxes() {       
        if($this->has_latest_sidebox){
            $this->getLatestSidebox();
        }
        if($this->has_featured_sidebox){
            $this->getFeaturedSidebox();
        }
    }

    function getLatestSidebox() {
        global $smarty;
        global $base_path;
        global $filters;
        global $module_path;
        global $module_prefix;

        $latest = db_get_rows("select * from {$this->table} where published = 1 order by order_num desc limit " . constant($module_prefix . "LATEST_SIDEBOX_NUMBER_OF_ITEMS"));
        $smarty_prefix = $this->listName . '_';
        $smarty->assign($smarty_prefix . 'latest', $latest);
           
        if (constant($module_prefix . "LATEST_SIDEBOX_NUMBER_OF_ITEMS") > 1) {
            $smarty->assign($smarty_prefix . 'latest_title', 'Latest ' . $this->pluralName);
        } else {
            $smarty->assign($smarty_prefix. 'latest_title', 'Latest ' . $this->singleName);
        }        
        // add these to latest      
        $smarty->assign($smarty_prefix . 'listName',  $this->listName);
        $smarty->assign($smarty_prefix . 'itemName',  $this->itemName);
        $smarty->assign($smarty_prefix . 'pluralName',  $this->pluralName);                  
        $smarty->assign($smarty_prefix . 'module_web_path', $this->module_web_path); 
        
        // can we use the listmodule->AddSideboxFilter
        $template_file = "$base_path/modules/$module_path/templates/latest.tpl";
        $random_name = uniqid(md5(rand()), true);
        $filters[$random_name] = array('search_string' => '/<!-- CS ' . $this->sidebox_filter_key . ' latest start -->(.*)<!-- CS ' . $this->sidebox_filter_key . ' latest end -->/s',
            'replace_string' => '{include file="file:' . $template_file . '"}');
    }

    function getFeaturedSidebox() {
        global $smarty;
        global $base_path;
        global $filters;
        global $module_path;
        global $module_prefix;      
             
		// the code below BROKE featured...     
        //$sql = "select * from {$this->table} ";        
        //if($this->has_categories){
        //    $sql .= " INNER JOIN {$this->linkTable} csc ON csc.item_id = {$this->table}.id ";
        //    $sql .= " where published = 1 AND category_id = 1 ";
        //}else{
        //    $sql .= " where published = 1 and featured =1 ";
        //} 
        //$sql .= " order by order_num desc limit " . constant($module_prefix . "FEATURED_SIDEBOX_NUMBER_OF_ITEMS");   
		
		//this from living out fixes the issue
        $sql = "select * from {$this->table} ";        
        $sql .= " where published = 1 and featured =1 ";        
        $sql .= " order by order_num desc limit " . constant($module_prefix . "FEATURED_SIDEBOX_NUMBER_OF_ITEMS");  
                
        $data = db_get_rows($sql);                     
         $smarty_prefix = $this->listName . '_';
        $smarty->assign($smarty_prefix .'featured', $data);        
        if (constant($module_prefix . "FEATURED_SIDEBOX_NUMBER_OF_ITEMS") > 1) {
            $smarty->assign($smarty_prefix .'featured_title', 'Featured ' . $this->pluralName);
        } else {
            $smarty->assign($smarty_prefix .'featured_title', 'Featured ' . $this->singleName);
        }        
        // add these to latest     
        $smarty->assign($smarty_prefix .'listName',  $this->listName);
        $smarty->assign($smarty_prefix .'itemName',  $this->itemName);
        $smarty->assign($smarty_prefix .'pluralName',  $this->pluralName);                  
        $smarty->assign($smarty_prefix .'module_web_path', $this->module_web_path);
        // can we use the listmodule->AddSideboxFilter
        $template_file = "$base_path/modules/$module_path/templates/featured.tpl";
        $random_name = uniqid(md5(rand()), true);   
        $filters[$random_name] = array('search_string' => '/<!-- CS ' . $this->sidebox_filter_key . ' featured start -->(.*)<!-- CS ' . $this->sidebox_filter_key . ' featured end -->/s',
            'replace_string' => '{include file="file:' . $template_file . '"}');
      
   
    }

}