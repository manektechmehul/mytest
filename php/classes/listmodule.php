<?php

abstract class ListModule {

    public $pluralName;
    public $singleName;
    public $listName;
    public $itemName;
    public $noItemsMessage;
    public $sideboxItemsDisplayed;
    public $listTemplateFile;
    public $singleTemplateFile;
    public $sideboxTemplateFile;
    public $moduleFolder;
    // the url fromthe base cms eg. case-studies or church-examples
    public $moduleHomeUrl;
   

    function __construct($module_path) {
        global $base_path;

        $this->moduleFolder = "$base_path/modules/$module_path/";

        if (empty($this->sideboxItemsDisplayed))
            $this->sideboxItemsDisplayed = 3;

        if (empty($this->pluralName))
            $this->pluralName = 'items';

        if (empty($this->noItemsMessage))
            $this->noItemsMessage = 'There are no ' . $this->pluralName;

        if (empty($this->listTemplateFile))
            $this->listTemplateFile = "$base_path/modules/$module_path/templates/list.tpl";
        if (empty($this->singleTemplateFile))
            $this->singleTemplateFile = "$base_path/modules/$module_path/templates/single.tpl";
        if (empty($this->sideboxTemplateFile))
            $this->sideboxTemplateFile = "$base_path/modules/$module_path/templates/sidebox.tpl";
    }

    function GetItems() {
        
    }

    function GetSingleItem($id) {
        
    }

    function GetSideboxItems() {
        
    }

    function AddSideboxFilter($filterName, $filterKey) {
        global $filters;
        global $smarty;
        $sideboxItems = $this->GetSideboxItems();
        $smarty->assign($this->sideboxListName, $sideboxItems);
        $smarty->assign('no_' . $this->listName, $this->noItemsMessage);
        $filters[$filterName] = array('search_string' => '/<!-- CS ' . $filterKey . ' start *-->.*<!-- CS ' . $filterKey . ' end *-->/s',
            'replace_string' => '{include file="' . $this->sideboxTemplateFile . '"}');
    }

    function DisplayList() {
        global $smarty;
        $items = $this->GetItems();
        $smarty->assign($this->listName, $items);
        $smarty->assign('no_' . $this->listName, $this->noItemsMessage);
        $smarty->display("file:" . $this->listTemplateFile);
    }

    function DisplayItem($id) {
        global $smarty;
        $item = $this->GetSingleItem($id);
        $smarty->assign($this->itemName, $item);          
        $smarty->display("file:" . $this->singleTemplateFile);
    }

    function DisplayItemByPagename($pagename) {
        
    }
    
   

}