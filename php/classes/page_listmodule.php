<?php

include_once $base_path . '/php/classes/listmodule.php';
include_once $base_path . '/php/classes/pagination.php';

abstract class PageListModule extends ListModule {

    public $listName;
    protected $model;
    protected $paginate;
    protected $pagination;
    protected $pageUrl;

    function __construct($module_path, $paginate = false, $pageUrl) {
        parent::__construct($module_path);

        if (($paginate) && ($this->model)) {
            $this->paginate = $paginate;
            $this->pagination = new pagination();
            $this->pagination->set_page();
        }
        $this->pageUrl = $pageUrl;
    }
    
    function getListName(){
        return $this->listName;
    }

    function GetItemByPageName($pageName) {
        return $this->model->GetItemByPageName($pageName);
    }

    function GetItems() {
        return $this->model->ReadItems();
    }

    function GetSingleItem($id) {
        return $this->model->ReadItem($id);
    }

    function GetSideboxItems() {
        $items_displayed = $this->sideboxItemsDisplayed;
        return $this->model->GetSideboxItems();
    }

    function DisplayAll() {
        global $smarty;
        $items = $this->model->ReadItems('', $this->pagination);
        if ($this->paginate) {
            $this->pagination->set_pages();
            $paginationStr = $this->pagination->html_string("/$this->pageUrl?page=");
            $smarty->assign('pagination', $paginationStr);
        }
        $smarty->assign($this->listName, $items);
        $smarty->assign('no_' . $this->listName, $this->noItemsMessage);
        $templateFile = $this->moduleFolder . 'templates/listall.tpl';
        $smarty->display("file:" . $templateFile);
    }

    function DisplayItemByPageName($pageName) {
        global $smarty;
        $item = $this->model->GetItemByPageName($pageName);
        $smarty->assign($this->itemName, $item);    
        $this->title = $item['title'];
        $templateFile = $this->moduleFolder . 'templates/singe.tpl';
        $smarty->display("file:" . $templateFile);
    }

    function GetTitle($page) {
        return $this->model->GetTitle($page);
    }

}