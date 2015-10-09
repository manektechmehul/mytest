<?php

include_once $base_path . '/php/classes/page_listmodule.php';
include_once $base_path . '/php/classes/pagination.php';
include_once 'model/model.php';

class PropertyModule extends PageListModule {

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

    function __construct($module_path, $paginate, $paginate_items_per_page, $pageUrl, $module_key) {
        $this->getModuleKeys($module_key);
        /* #module specific */
        $this->listName = 'properties';
        $this->itemName = 'property';
        $this->pluralName = 'Properties';
        $this->singleName = 'Property';
        $this->section_page_name = 'Property';        

        $this->table = 'property';
        $this->categoryTable = 'property_category';

        $this->linkTable = 'property_category_lookup';
        $this->linkField = 'category_id';
        $this->keyField = 'item_id';

        $this->sideboxListName = 'sideboxproperties'; // ?? odd name
        $this->pageName = $pageName;
        $this->currentCategory = "";

        /* #module specific */
        $this->model = new propertymodel($pageUrl);
        $this->model->setTable('property');

        $this->model->setpublishedField('published');
        $this->model->setOrderField('order_num desc');

        /* #module specific */
        $this->sidebox_filter_key = "properties";
        /* need this to be a variable */


        /*
          $this->moduleFolder this is the file system location, is set automatically in the listmodule
         */
        parent::__construct($module_path, $paginate, $pageUrl);
        if ($paginate) {
            $this->pagination = new pagination($paginate_items_per_page);
            $this->pagination->set_page();
        }
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
        $this->module_name = $row['name'];
        $this->module_web_path = $row['page_name'];
        $this->level = $row['level'];
        $this->page_type = $row['page_type'];
        $this->parent_id = $row['parent_id'];
        $this->content_type_id = $row['content_type_id'];
        $this->section_id = $row['section_id'];
        $this->nav_title = $row['nav_title'];
        $this->module_title = $row['title'];
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
        
 /*       
        $custom_sql = "SELECT p.*, pl.title AS location, ps.title AS `status`, pt.title AS `type`
FROM property p 
 INNER JOIN property_location pl ON pl.id = p.`property_location_id`
 INNER JOIN property_status ps ON ps.id = p.`property_status_id`
 INNER JOIN property_type pt ON pt.id = p.`property_type_id`
WHERE p.page_name='" . $name_parts[1] . "'"; */
        $custom_sql = "SELECT p.*, pl.title AS location, pb.title AS bedroom
FROM property p 
 INNER JOIN property_location pl ON pl.id = p.`property_location_id`
 INNER JOIN property_bedroom pb ON pb.id = p.`property_bedroom_id`
WHERE p.page_name='" . $name_parts[1] . "'";
        
        $item = db_get_single_row($custom_sql);               
        
        $location_str = $this->get_map_locations();
        $location_head = $this->get_map_locations_heading();
        #echo $location_head; exit;        
        $smarty->assign('location_str',$location_str);
        $smarty->assign('location_head',$location_head);
        $smarty->assign('button_link','do_js_back');
        $smarty->assign('listName', $this->listName);
        $smarty->assign('itemName', $this->itemName);
        $smarty->assign('pluralName', $this->pluralName);
        $smarty->assign('module_web_path', $this->module_web_path);
        // todo:make this the default at some point ?
        $smarty->assign('singleitem', $item);
        $smarty->display("file:" . $this->singleTemplateFile);
    }

    function DisplayItemByPageName($pagename) {
        global $smarty;
        $item = $this->model->GetItemByPageName($pageName);
        $smarty->assign($this->itemName, $item);
        $this->title = $item['title'];
        $templateFile = $this->moduleFolder . 'templates/single.tpl';
        $smarty->display("file:" . $templateFile);
    }

    function SetCategoryForPage($pagename) {
        $this->currentCategory = db_get_single_value("select id from {$this->categoryTable} where page_name = '$pagename'", 'id');
    }

    function GetCategoryTitle() {
        $sql = "select title from {$this->categoryTable} where id = {$this->currentCategory}";
        return db_get_single_value($sql, 'title');
    }

    function DisplayList() {
        global $smarty;
        global $currentPageUrl;
        $items = $this->GetItems();
        #echo "<pre>"; print_r($items); exit;
        $smarty->assign('itemList', $items);
        if ($this->paginate) {
            $paginationStr = $this->pagination->html_string($currentPageUrl . '?page=');
            $smarty->assign('pagination', $paginationStr);
        }
        $location_str = $this->get_map_locations();
        $location_head = $this->get_map_locations_heading();
        $location_page = $this->get_map_locations_page();
        
        $smarty->assign('location_str',$location_str);
        $smarty->assign('location_head',$location_head);
        $smarty->assign('location_page',$location_page);
        $smarty->assign('no_' . $this->listName, $this->noItemsMessage);
        $smarty->assign('listName', $this->listName);
        $smarty->assign('itemName', $this->itemName);
        $smarty->assign('pluralName', $this->pluralName);
        $smarty->assign('module_web_path', $this->module_web_path);
        $templateFile = $this->moduleFolder . 'templates/list.tpl';
        $smarty->display("file:" . $templateFile);
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
        for($i=0;$i<count($items);$i++){
            $items[$i]['cls']=$this->getPropertyCategory($items[$i]['id']);
        }
        #echo "<pre>"; print_r($items); exit;             
        $smarty->assign('itemList', $items);     
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
    /***
     * Add columns here for the details view
         */
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
		'property_status_id' => $row['property_status_id'],
                'address' => $row['address'],
                'price' => $row['price'],
                'bedroom' => $row['bedroom'],
                'postcode' => $row['postcode']
            );
        };	
		
        return $items;
    }

    /* set the field used in a listing here
     * todo: extract this list of field name to somewhere useful
     */
    function GetItems() {
        // this is the home page of the module listing or filtered by category
        
        //$cat_join = " join {$this->linkTable} cl on {$this->keyField} = t.id ";
        $cat_join = " left join property_bedroom cl on cl.id = t.property_bedroom_id ";
        $cat_clause = "and {$this->linkField} = '{$this->currentCategory}'";
        if ($this->paginate) {
            $limits = $this->pagination->GetLimits();
        }
        if ($this->currentCategory == '') {
            $cat_clause = '';
            /* #module specific - addfields  - also uses the feature filed in the default listing */
            $sql = "SELECT SQL_CALC_FOUND_ROWS t.id, t.title, t.description, t.thumb, t.description, t.page_name, t.property_status_id, t.address, t.price, cl.title as bedroom " .
                    " from {$this->table} t " .
                    $cat_join .
                    "where published = 1 " .
                    " and featured = 1 ".
                    "order by `order_num` desc " . $limits;
        } else {
            /* #module specific - addfields */
            $sql = "SELECT SQL_CALC_FOUND_ROWS t.id, t.title, t.description, t.thumb, t.description, t.page_name, t.property_status_id, t.address, t.price, cl.title as bedroom " .
                    " from {$this->table} t " .
                    $cat_join .
                    "where published = 1 " .
                    $cat_clause .                      
                    "order by `order_num` desc " . $limits;
        }
        //echo $sql;
        //global $title;
        //$title = "Featured Property";
        $items = $this->ReadItems($sql);
        
        if ($this->paginate) {
            $this->pagination->set_pages();
        }
        return $items;
    }

    /*
     * Sidebox Functions
     */

    function doSideBoxes() {
        if ($this->has_latest_sidebox) {
            $this->getLatestSidebox();
        }
        if ($this->has_featured_sidebox) {
            $this->getFeaturedSidebox();
        }
        // todo: need to have a has_search module var
        $this->createSearchBox();
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
            $smarty->assign($smarty_prefix . 'latest_title', 'Latest ' . $this->singleName);
        }
        // add these to latest
        $smarty->assign($smarty_prefix . 'listName', $this->listName);
        $smarty->assign($smarty_prefix . 'itemName', $this->itemName);
        $smarty->assign($smarty_prefix . 'pluralName', $this->pluralName);
        $smarty->assign($smarty_prefix . 'module_web_path', $this->module_web_path);
        // can we use the listmodule->AddSideboxFilter
        $template_file = "$base_path/modules/$module_path/templates/latest.tpl";
        $filters[$this->itemName . '_latest_sidebox_filter'] = array('search_string' => '/<!-- CS ' . $this->sidebox_filter_key . ' latest start -->(.*)<!-- CS ' . $this->sidebox_filter_key . ' latest end -->/s',
            'replace_string' => '{include file="file:' . $template_file . '"}');
    }

    function getFeaturedSidebox() {
        global $smarty;
        global $base_path;
        global $filters;
        global $module_path;
        global $module_prefix;
        $sql = "select * from {$this->table} ";
        if ($this->has_categories) {
            $sql .= " INNER JOIN {$this->linkTable} csc ON csc.item_id = {$this->table}.id ";
            $sql .= " where published = 1 AND category_id = 1 ";
        } else {
            $sql .= " where published = 1 and featured =1 ";
        }
        $sql .= " order by order_num desc limit " . constant($module_prefix . "FEATURED_SIDEBOX_NUMBER_OF_ITEMS");
        $data = db_get_rows($sql);
        $smarty_prefix = $this->listName . '_';
        $smarty->assign($smarty_prefix . 'featured', $data);
        if (constant($module_prefix . "FEATURED_SIDEBOX_NUMBER_OF_ITEMS") > 1) {
            $smarty->assign($smarty_prefix . 'featured_title', 'Featured ' . $this->pluralName);
        } else {
            $smarty->assign($smarty_prefix . 'featured_title', 'Featured ' . $this->singleName);
        }
        // add these to latest     
        $smarty->assign($smarty_prefix . 'listName', $this->listName);
        $smarty->assign($smarty_prefix . 'itemName', $this->itemName);
        $smarty->assign($smarty_prefix . 'pluralName', $this->pluralName);
        $smarty->assign($smarty_prefix . 'module_web_path', $this->module_web_path);
        // can we use the listmodule->AddSideboxFilter
        $template_file = "$base_path/modules/$module_path/templates/featured.tpl";
        $filters[$this->itemName . '_featured_sidebox_filter'] = array('search_string' => '/<!-- CS ' . $this->sidebox_filter_key . ' featured start -->(.*)<!-- CS ' . $this->sidebox_filter_key . ' featured end -->/s',
            'replace_string' => '{include file="file:' . $template_file . '"}');
    }

    function createSearchBox($hasCategories) {
        global $smarty;
        global $base_path;
        global $filters;
        global $module_path;
        global $module_prefix;
        
        $template_file = "$base_path/modules/$module_path/templates/searchbox.tpl";
        $smarty->assign('hasCategories', $hasCategories);
        $this->AssignSearchCategories();        
        $filters[$this->itemName . '_property_search_filter'] = array('search_string' => '/<!-- CS ' . $this->sidebox_filter_key . ' latest start -->(.*)<!-- CS ' . $this->sidebox_filter_key . ' latest end -->/s',
            'replace_string' => '{include file="file:' . $template_file . '"}');        
       // $smarty->display("file:" . $template_file);
        $smarty->assign('pr_filter', $template_file);
    }

    /* Search Functions */
    function DisplayCustomSearchResults($searchKeywords,$location, $bedroom, $year){  
        global $smarty;
        global $currentPageUrl;            
        
        $sql = "SELECT t.*, cl.title as bedroom FROM property t left join property_bedroom cl on cl.id=t.property_bedroom_id WHERE published = 1 and featured = 1 ";
        if($location != 0){
            $sql .= " AND t.property_location_id = " . $location;
        }  
        if($bedroom != 0){
            $sql .= " AND t.property_bedroom_id = " . $bedroom;
        } 
        if($year != 0){
            $sql .= " AND t.property_year_id = " . $year;
        }
       /*
	    if($price != 0){
            // look up price values
            $row = db_get_single_row("Select * from property_price where id="  . $price);
            $sql .= " AND price > " . $row['start'] . ' and price < ' . $row['end'];
        }
        */
        $sql .= " order by order_num desc ";
        $items = $this->ReadItems($sql);
              
        
       // $items = $this->GetSearchResultItems($searchKeywords, $category);
       /* todo: fix the pagination, limits but doesn't pass on search query 
        if ($this->paginate) {
            $paginationStr = $this->pagination->html_string($currentPageUrl . '?page=');
            $smarty->assign('pagination', $paginationStr);
        }
        */        
        
        $smarty->assign('listName', $this->listName);
        $smarty->assign('results_items', $items);
        $smarty->assign('no_' . $this->listName, $this->noItemsMessage);
        $templateFile = $this->moduleFolder . 'templates/results.tpl';
        $smarty->display("file:" . $templateFile);
    }
    
    // we are not using this method as we need to search with 4 cat types
    
    function DisplaySearchResults($searchKeywords, $category) {
        global $smarty;
        global $currentPageUrl;
        
        $items = $this->GetSearchResultItems($searchKeywords, $category);
       /* todo: fix the pagination, limits but doesn't pass on search query 
        if ($this->paginate) {
            $paginationStr = $this->pagination->html_string($currentPageUrl . '?page=');
            $smarty->assign('pagination', $paginationStr);
        }
        */
		
        $smarty->assign('results_items', $items);
        $smarty->assign('no_' . $this->listName, $this->noItemsMessage);
        $templateFile = $this->moduleFolder . 'templates/results.tpl';
        $smarty->display("file:" . $templateFile);
    }

    /* this for a single caterogy group module, we have 4 categories for search with this module, so I will not be using this functon */
    function GetSearchCategories() {
        $sql = "select c.id, c.title from {$this->linkTable} cl " .
                "join {$this->categoryTable} c on cl.{$this->linkField} = c.id " .
                "join {$this->table} t on cl.{$this->keyField} = t.id  where published = 1 and special = 0 " .
                "group by c.id, c.title having count(*) > 0";
        $result = mysql_query($sql);
        $data[0] = 'All';
        while ($row = mysql_fetch_array($result)) {
            $data[$row['id']] = $row['title'];
        }
        return $data;
    }

    function AssignSearchCategories() {
        global $smarty;
        /*
        $searchCats = $this->GetSearchCategories();
        $smarty->assign($this->itemName . '_searchCategories', $searchCats);
        */
        /* get the 4 search categories
         * 
         * type: flat, house etc
         * status: sale, lease etc
         * location: dundee, fife, perth etc
         * price: 0 - 50k, 50k - 100k
         * 
         */
        /* 
        $types = "select * from property_type";
        $result = mysql_query($types);
        $data[0] = 'Any';
        while ($row = mysql_fetch_array($result)) {
              $data[$row['id']] = $row['title'];
        }       
        $smarty->assign('property_searchType', $data);
        unset($data);
        
        $result = mysql_query("select * from property_status");
        $data[0] = 'Any';
        while ($row = mysql_fetch_array($result)) {
              $data[$row['id']] = $row['title'];
        }       
        $smarty->assign('property_searchStatus', $data);
        unset($data);
        */
        $result = mysql_query("select * from property_bedroom");
        $data[0] = 'Any number of bedrooms';
        while ($row = mysql_fetch_array($result)) {
              $data[$row['id']] = $row['title'];
        }       
        $smarty->assign('property_searchBedroom', $data);
        unset($data);
        
        $result = mysql_query("select * from property_location");
        $data[0] = 'Any location';
        while ($row = mysql_fetch_array($result)) {
              $data[$row['id']] = $row['title'];
        }       
        $smarty->assign('property_searchLocation', $data);
        unset($data);
        
        $result = mysql_query("select * from property_year");
        $data[0] = 'Any acadamic year';
        while ($row = mysql_fetch_array($result)) {
              $data[$row['id']] = $row['title'];
        }       
        $smarty->assign('property_searchYear', $data);
        unset($data);
        /* TAKEN OUT THE PRICE FILTER
        $result = mysql_query("select * from property_price");
        $data[0] = 'Any';
        while ($row = mysql_fetch_array($result)) {
              $data[$row['id']] = $row['start'] . ' > ' . $row['end'] ;
        }       
        $smarty->assign('property_searchPrice', $data);  
        unset($data);
        */
    }

    function GetSearchResultItems($searchKeywords, $category) {
        $cat_join = ($category == 0) ? '' : " join {$this->linkTable} cl on {$this->keyField} = t.id ";
        $cat_clause = ($category == 0) ? '' : " {$this->linkField} = '{$category}' and ";
        /* disable pag for search results, not paying forward query 
        if ($this->paginate) {
            $limits = $this->pagination->GetLimits();
        }
        */ 
        
        // matching only again whole words
        if (!empty($searchKeywords)) {
            $match = "MATCH (t.title, t.description, t.body) AGAINST ('$searchKeywords' IN BOOLEAN MODE)";
        } else {
            $match = ' 1 ';
        }
        $sql = "select  SQL_CALC_FOUND_ROWS t.*, $match as score
			from {$this->table} t $cat_join
			where $cat_clause
			t.published = 1 and
			$match"; //  . $limits;  // re introduce when pag is working           
        // $paginationStr = $this->pagination->html_string($currentPageUrl . '?page=');
        // echo $sql;                
                        
        $items = $this->ReadItems($sql);
       /*
        if ($this->paginate) {
            $this->pagination->set_pages();
        }
        * 
        */
        return $items;
    }

    /* Insert a load of test data into the main module table */
    function insertRandomData($no_of_items) {
        for ($i = 1; $i <= $no_of_items; $i++) {
            $sql .= " insert into {$this->table} (`title`, `description`, `body`, `thumb`, `page_image`, `published`, `order_num`, `page_name`, `featured`) 
               values('{$this->itemName} item {$i} title','{$this->itemName} item {$i} description ','<p>\r\n	This is the description of {$this->itemName} item {$i}, it is just dummy data for now.</p>\r\n','Test_Images/gallery/nature3.jpg','Test_Images/gallery/sheffield1.jpg','1','{$i}0','{$this->itemName}-item-{$i}-title','1'); ";
        }
        echo $sql;
        $result = mysql_query($sql);
    }
    
    
    function getPropertyCategory($id){        
        $sql="select * from {$this->linkTable} ";
        $sql .= " where item_id = {$id} ";
        $cat_items = db_get_rows($sql);
        $str="";
        for($i=0;$i<count($cat_items);$i++){
         //   $temp[]=$cat_items[$i]['category_id'];
            $str.=" cate-".$cat_items[$i]['category_id'];
        }            
        return $str;       
    }   
    function get_map_locations(){
        $sql = "select address, postcode from {$this->table} ";        
        $sql .= " where published = 1 and featured =1 ";
        $sql .= " order by order_num desc  ";
        
        $items = db_get_rows($sql);  
        $str = '[';
        for($i=0;$i<count($items);$i++){
            $str .= "'".$items[$i]['address']." ".$items[$i]['postcode']."',";
        }
        $str = substr($str, 0, -1);
        $str .= ']';
        return $str;
    }
    function get_map_locations_heading(){
        $sql = "select title from {$this->table} ";        
        $sql .= " where published = 1 and featured =1 ";
        $sql .= " order by order_num desc  ";
        
        $items = db_get_rows($sql);  
        $str = '[';
        for($i=0;$i<count($items);$i++){
            $str .= "'".$items[$i]['title']."',";
        }
        $str = substr($str, 0, -1);
        $str .= ']';
        return $str; 
    }
    function get_map_locations_page(){
        $sql = "select page_name from {$this->table} ";        
        $sql .= " where published = 1 and featured =1 ";
        $sql .= " order by order_num desc  ";
        
        $items = db_get_rows($sql);  
        $str = '[';
        for($i=0;$i<count($items);$i++){
            $str .= "'".$items[$i]['page_name']."',";
        }
        $str = substr($str, 0, -1);
        $str .= ']';
        return $str; 
    }

//end func
}

// end class