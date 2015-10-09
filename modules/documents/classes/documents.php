<?php

include_once $base_path . '/php/classes/listmodule.php';
include_once $base_path . '/php/classes/pagination.php';

class docsModule extends listModule {

    public $listName;
    public $currentSection;
    public $pagination;
    function __construct($module_path, $section_id) {
        $this->listName = 'documents';
        $this->itemName = 'document';
        $this->sideboxListName = 'sideboxdocuments';
        $this->pluralName = 'documents';
        $this->singleName = 'document';
        $this->section_page_name = 'documents';

        $this->table = 'documents';
        $this->linkTable = 'documents_category_link';
        $this->categoryTable = 'documents_category';
        $this->linkField = 'documents_category_id';
        $this->keyField = 'documents_id';
        //custom
        $this->currentCategory = 1; //db_get_single_value('select category_id from section where id = '.$section_id, 'category_id');

        $this->pagination = new pagination(6);
        $this->pagination->set_page();

        parent::__construct($module_path);
    }

    /** Added thumb - GL 2/8/2012 * */
    function ReadItems($sql) {

        $items = array();

        $result = mysql_query($sql);

        while ($row = mysql_fetch_array($result)) {
            $file = '/UserFiles/File/' . $row['file'];
            $fileinfo = pathinfo($file);
            $ext = $fileinfo['extension'];
            $items[] = array(
                'date' => $row['date'],
                'title' => $row['title'],
                'file' => $file,
                'fileext' => $ext,
                'summary' => $row['summary'],
                'link' => '/' . $this->section_page_name . '/' . $row['id'],
                'download_link' => $row['link'],
                'thumb' => $row['thumb'],
                'audio' => $row['audio'],
                'external_link' => $row['external_link'],
                'link_type' => $row['link_type'],  
                'video_id' => $row['video_id'],
                'video_type' => $row['video_type']
                
            );
        };
        return $items;
    }

    function GetItems() {
        $cat_join = " join {$this->linkTable} cl on {$this->keyField} = t.id ";
        $cat_clause = "and {$this->linkField} = '{$this->currentCategory}'";
        $limits = $this->pagination->GetLimits();

        $sql = "select SQL_CALC_FOUND_ROWS t.id, title, description, thumb,  summary, file, audio,link_type, external_link,link,video_type,video_id,  " .
                "unix_timestamp(`date`) as `date` from {$this->table} t " .
                $cat_join .
                "where published = 1 " .
                $cat_clause .
                //"and deadline > now() ".
                "order by `date` desc, t.id $limits";
        $items = $this->ReadItems($sql);
        $this->pagination->set_pages();
        return $items;
    }

    function GetAllItems() {
        $cat_join = ($this->currentCategory == 1) ? '' : " join {$this->linkTable} cl on {$this->keyField} = t.id ";
        $cat_clause = ($this->currentCategory == 1) ? '' : "and {$this->linkField} = '{$this->currentCategory}' ";

        $limits = $this->pagination->GetLimits();

        $sql = "select SQL_CALC_FOUND_ROWS t.id, title, thumb, description, summary, file, audio, unix_timestamp(`date`) as `date` from {$this->table} t " .
                $cat_join .
                "where published = 1 " .
                $cat_clause .
                //"and deadline > now() ".
                "order by `date` desc, t.id $limits";

        $items = $this->ReadItems($sql);
        $this->pagination->set_pages();
        return $items;
    }

    function GetSearchResultItems($searchKeywords, $category) {
        $cat_join = ($category == 0) ? '' : " join {$this->linkTable} cl on {$this->keyField} = t.id ";
        $cat_clause = ($category == 0) ? '' : "and {$this->linkField} = '{$this->currentCategory}' ";

        if (!empty($searchKeywords))
            $match = "MATCH (t.title, t.summary, t.keywords) AGAINST ('$searchKeywords' IN BOOLEAN MODE)";
        else
            $match = ' 1 ';

        $sql = "select  SQL_CALC_FOUND_ROWS t.*, $match as score
			from {$this->table} t $cat_join
			where $cat_clause
			t.published = 1 and
			$match";

        return $this->ReadItems($sql);
    }

    function GetSearchCategories() {
        $sql = "select c.id, c.title from {$this->linkTable} cl " .
                "join {$this->categoryTable} c on cl.{$this->linkField} = c.id " .
                "join {$this->table} t on cl.{$this->keyField} = t.id  where published = 1 and special = 0 " .
                "group by c.id, c.title having count(*) > 0";
        $result = mysql_query($sql);
        while ($row = mysql_fetch_array($result)) {
            $data[] = $row;
        }
        return $data;
    }

    function GetSingleItem($id) {

        $sql = "select title, description , audio from {$this->table} WHERE id = '$id'";
        $result = mysql_query($sql);
        if (mysql_num_rows($result) > 0) {
            $item = array();
            $row = mysql_fetch_array($result);
            $item['audio'] = $row['audio'];
            $item['title'] = $row['title'];
            $item['description'] = $row['description'];
            $item['page_link'] = '/' . $this->section_page_name;
        }
        return $item;
    }

    function GetSideboxItems() {
        $items_displayed = $this->sideboxItemsDisplayed;
        $sql = "select t.id, date, title, description, summary, file from {$this->table} t " .
                "join {$this->linkTable} cl on {$this->keyField} = t.id where published = 1 " .
                "and cl.{$this->linkField} = '{$this->currentCategory}' " .
                "order by `date` limit $items_displayed";


        return $this->ReadItems($sql);
    }

    function AssignSearchCategories() {
        global $smarty;

        $searchCats = $this->GetSearchCategories();
        $smarty->assign('searchCategories', $searchCats);
    }

    function DisplayAll() {
        global $smarty;
        global $currentPageUrl;

        $items = $this->GetAllItems();

        $smarty->assign($this->listName, $items);
        $paginationStr = $this->pagination->html_string($currentPageUrl . '?page=');
        $smarty->assign('pagination', $paginationStr);
        $smarty->assign('no_' . $this->listName, $this->noItemsMessage);
        $templateFile = $this->moduleFolder . 'templates/listall.tpl';
        $smarty->display("file:" . $templateFile);
    }

    function DisplayList() {
        global $smarty;
        global $currentPageUrl;

        $items = $this->GetItems();

        $smarty->assign($this->listName, $items);
        $paginationStr = $this->pagination->html_string($currentPageUrl . '?page=');
        $smarty->assign('pagination', $paginationStr);
        $smarty->assign('no_' . $this->listName, $this->noItemsMessage);
        $templateFile = $this->moduleFolder . 'templates/list.tpl';
        $smarty->display("file:" . $templateFile);
    }

    function DisplaySearchResults($searchKeywords, $category) {
        global $smarty;

        $items = $this->GetSearchResultItems($searchKeywords, $category);
        $smarty->assign($this->listName, $items);
        $smarty->assign('no_' . $this->listName, $this->noItemsMessage);
        $templateFile = $this->moduleFolder . 'templates/results.tpl';
        $smarty->display("file:" . $templateFile);
    }

    function GetSubmenu($menulink) {
        global $name_parts;

        $sql = "select c.id, c.title, page_name, count(*) from {$this->categoryTable} c join {$this->linkTable} cl on c.id = cl.{$this->linkField} " .
                "join {$this->table} t on cl.{$this->keyField} = t.id " .
                ' where special = 0 and published = 1 group by order_num';

        $result = mysql_query($sql);
        $this->has_submenu = false;
        if (mysql_num_rows($result) > 0) {
            $submenu = array();
            while ($row = mysql_fetch_array($result)) {
                $submenuitem = array();
                $submenuitem['name'] = $row["title"];
                $submenuitem['on'] = ($row["page_name"] == $name_parts[1]);
                $submenuitem['link'] = $menulink . '/' . $row["page_name"];
                $submenu[] = $submenuitem;
            }
            $this->has_submenu = true;
            return $submenu;
        }
    }

    function SetCategoryForPage($pagename) {
        $this->currentCategory = db_get_single_value("select id from {$this->categoryTable} where page_name = '$pagename'", 'id');
    }

    function GetCategoryTitle() {
        $sql = "select title from {$this->categoryTable} where id = {$this->currentCategory}";

        return db_get_single_value($sql, 'title');
    }

}