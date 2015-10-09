<?php
include_once $base_path.'/php/classes/page_listmodule.php';
include_once $base_path . '/php/classes/pagination.php';
include_once 'model/events.php';
//include_once $base_path.'/modules/sitecategories/classes/categories.php';

class EventsModule extends PageListModule
{
	public $listName;
	public $currentSection;
	public $title;
    public $pageName;

    function __construct($module_path, $paginate, $paginate_items_per_page, $pageUrl, $module_key)
	{
		$this->listName = 'events';
		$this->itemName = 'event';
		$this->sideboxListName = 'sideboxdocuments';
		$this->pluralName = 'Events';
		$this->singleName = 'Event';
		$this->section_page_name = 'events';
		$this->pageName = $pageName;


		$this->model = new EventsModel($pageUrl);
		$this->model->setOrderField('date');

        parent::__construct($module_path, $paginate, $pageUrl);
        if ($paginate) {
            $this->pagination = new pagination($paginate_items_per_page);
            $this->pagination->set_page();
        }
	}
        
        
     function GetSingleItem($pagename) {
        // overload parent class for get single item (by id)
        global $smarty;
        $sql = "select * from events WHERE page_name = '$pagename'";
        $result = mysql_query($sql);
        if (mysql_num_rows($result) > 0) {
            $item = array();
            $row = mysql_fetch_array($result);
            $item = $row;
        }

        return $item;
    }

/* This will show the eventimage as the header bg

	function DisplayItem($id) {

		global $smarty;
		$item = $this->GetSingleItem($id);
		global $filters;
		global $promo_image;
		$promo_image = "/images/thing" . $item["thumbnail"];
		$filters['promo_image'] = array(
			'search_string' => '/<!-- CS page image start -->(.*)<!-- CS page image end -->/s',
			'replace_string' => '{$promo_image}'
		);
		$smarty->assign($this->itemName, $item);
		$smarty->display("file:" . $this->singleTemplateFile);
	}
*/
    function GetOGData($page, $thumb)
    {
        $data =  $this->GetSingleItem($page);
        $og = array (
            'title' => $data['title'],
            'thumb' => $thumb,
            'description' => $data['summary']
            );
        return $og;
    }


    function ReadEventItems($type)
    {




      //  $archive = ($type == 'current') ? 0 : 1;
        $fields = $this->model->GetFields();
        $sql = "select $fields from events where published = 1 and `enddate` > '$date' order by `startdate`";


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
        $items = $this->ReadEventItems($type);
        if ($this->paginate) {
            $paginationStr = $this->pagination->html_string($currentPageUrl . '?page=');
            $smarty->assign('pagination', $paginationStr);
         //   var_dump($this->paginate);
        }
        $smarty->assign('pageLink', $pageLink);
        $smarty->assign($this->listName, $items);
        $smarty->assign('no_' . $this->listName, $this->noItemsMessage);
        $smarty->assign('viewing_archive', $type == 'archive');
        $smarty->assign('EVENTS_HAS_THUMBNAIL', NEWS_HAS_THUMBNAIL);
        $templateFile = $this->moduleFolder . 'templates/list.tpl';
        $smarty->display("file:" . $templateFile);
    }

}