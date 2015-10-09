<?php

include_once $base_path . '/php/classes/page_listmodule.php';
include_once $base_path . '/php/classes/pagination.php';
include_once 'model/news.php';

//include_once $base_path.'/modules/sitecategories/classes/categories.php';

class newsModule extends PageListModule
{

    public $listName;
    public $currentSection;
    public $title;
    public $pageName;


    function __construct($module_path, $paginate, $paginate_items_per_page, $pageUrl, $module_key)
    {
        $this->listName = 'news';
        $this->itemName = 'newsitem';
        $this->sideboxListName = 'sideboxdocuments';
        $this->pluralName = 'News';
        $this->singleName = 'News Item';
        $this->section_page_name = 'news';
        $this->pageName = $pageName;
        $this->model = new newsModel($pageUrl);
        $this->model->setOrderField('date desc');
        // parent::__construct($module_path);

        parent::__construct($module_path, $paginate, $pageUrl);
        if ($paginate) {
            $this->pagination = new pagination($paginate_items_per_page);
            $this->pagination->set_page();
        }
    }


    function ReadNewsItems($type)
    {
        $archive = ($type == 'current') ? 0 : 1;
        $fields = $this->model->GetFields();
        $sql = "select $fields from news where archive = '$archive' and published = 1 order by date desc " ;

        $items = $this->model->ReadItems($sql);

        if ($this->paginate) {
            $this->pagination->set_pages();
        }

        $items = $this->model->ReadItems($sql,$this->pagination);
        return $items;
    }


    function DisplayList($type)
    {
        global $smarty;
        global $currentPageUrl;
        $items = $this->ReadNewsItems($type);

        if ($this->paginate) {
            $paginationStr = $this->pagination->html_string($currentPageUrl . '?page=');
            $smarty->assign('pagination', $paginationStr);
        }
        $smarty->assign('pageLink', $pageLink);
        $smarty->assign($this->listName, $items);
        $smarty->assign('no_' . $this->listName, $this->noItemsMessage);
        $smarty->assign('viewing_archive', $type == 'archive');
        $smarty->assign('NEWS_HAS_THUMBNAIL', NEWS_HAS_THUMBNAIL);
        $templateFile = $this->moduleFolder . 'templates/list.tpl';
        $smarty->display("file:" . $templateFile);
		
    }

    function DisplayItem($page, $type)
    {
        global $smarty;
        $smarty->assign('viewing_archive', $type == 'archive');
        $smarty->assign('NEWS_HAS_THUMBNAIL', NEWS_HAS_THUMBNAIL);
        parent::DisplayItem($page);
        $item =  parent::GetSingleItem($page);
		
		// to push news image into page_image function in header
        if (empty($item["thumbnail"])) {
		} else {
		  global $filters;
		  global $promo_image;
		  $promo_image = "/php/thumbimage.php?img=/UserFiles/Image/" . $item["thumbnail"] . "&size=50";
		  $filters['promo_image'] = array(
	    	 'search_string' => '/<!-- CS page image start --><!-- CS page image end -->/s',
	    	 'replace_string' => '{$promo_image}'
		  );
		}
	
    }

    function GetSingleItem($pagename)
    {
        // overload parent class for get single item (by id)
        global $smarty;
        $sql = "select * from news WHERE page_name = '$pagename'";
        $result = mysql_query($sql);
        if (mysql_num_rows($result) > 0) {
            $item = array();
            $row = mysql_fetch_array($result);
            $item = $row;
        }

        return $item;
    }

    function GetOGData($page, $thumb)
    {
        $data = $this->GetSingleItem($page);
        $og = array(
            'title' => $data['title'],
            'thumb' => $thumb,
            'description' => $data['summary']
        );
        return $og;
    }

}