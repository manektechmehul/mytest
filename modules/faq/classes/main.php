<?php

include_once $base_path . '/php/classes/page_listmodule.php';
include_once $base_path . '/php/classes/pagination.php';
include_once 'model/model.php';

class FaqModule extends PageListModule {

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
        // these first few should be renamed if you are renaming a module but leaving the base tables as standard.
        // eg renaming Videos to Stories. You will need to change the base page name in the cms admin contnetn page too.
        // to do: large over lap of names here we have 3 for plural and 2 for single !!!
        $this->listName = 'faqs';
        $this->itemName = 'faq';
        $this->pluralName = 'Faqs';
        $this->singleName = 'Faq';
        $this->section_page_name = 'faqs';

        // technical names
        $this->table = 'faq';
        $this->categoryTable = 'faq_category';
        $this->linkTable = 'faq_category_lookup';
        $this->linkField = 'category_id';
        $this->keyField = 'item_id';

        $this->sideboxListName = 'sideboxfaqs'; // ?? odd name
        $this->pageName = $pageName;
        $this->currentCategory = "";

        /* #module specific */
        $this->model = new faqmodel($pageUrl);
        $this->model->setTable('faq');

        $this->model->setpublishedField('published');
        $this->model->setOrderField('order_num desc');

        /* #module specific */
        $this->sidebox_filter_key = "faq";
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
        $item = $this->GetSingleItem($id);
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
        }
        $smarty->assign('no_' . $this->listName, $this->noItemsMessage);
        $smarty->assign('listName', $this->listName);
        $smarty->assign('itemName', $this->itemName);
        $smarty->assign('pluralName', $this->pluralName);
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
                'thumb' => $row['thumb']
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
            $sql = "SELECT SQL_CALC_FOUND_ROWS t.id, title, description, thumb, description, page_name " .
                    " from {$this->table} t " .
                    "where published = 1 " .
                    //"and deadline > now() ".
                    "order by `order_num` desc " . $limits;
        } else {
            /* #module specific - addfields */
            $sql = "SELECT SQL_CALC_FOUND_ROWS t.id, title, description, thumb, description, page_name " .
                    " from {$this->table} t " .
                    $cat_join .
                    "where published = 1 " .
                    $cat_clause .
                    //"and deadline > now() ".
                    "order by `order_num` desc " . $limits;
        }
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
        $sql .= " where published = 1 and featured =1 ";
      /*  if ($this->has_categories) {
            $sql .= " INNER JOIN {$this->linkTable} csc ON csc.item_id = {$this->table}.id ";
            $sql .= " where published = 1 AND category_id = 1 ";
        } else {
            $sql .= " where published = 1 and featured =1 ";
        }*/
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
        $filters[$this->itemName . '_faq_search_filter'] = array('search_string' => '/<!-- CS ' . $this->sidebox_filter_key . ' searchbox start -->(.*)<!-- CS ' . $this->sidebox_filter_key . ' searchbox end -->/s',
            'replace_string' => '{include file="file:' . $template_file . '"}');
    }

    /* Search Functions */
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
        $searchCats = $this->GetSearchCategories();
        $smarty->assign($this->itemName . '_searchCategories', $searchCats);
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

//end func
}

// end class