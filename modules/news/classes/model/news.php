<?php

include_once $base_path . "/php/classes/model/listmodel.php";

class NewsModel extends ListModel {

    function __construct($pageUrl) {
        parent::__construct();
        $this->table = 'news';

        $this->dataFields = array(
            'title' => new dataField('title'),
            'summary' => new dataField('summary'),
            'description' => new dataField('body'),
            'date' => new dataField('news_date', false, 'UNIX_TIMESTAMP(`date`) as news_date'),
            'archive' => new dataField('archive'),
            'published' => new dataField('published'),
            'link' => new linkField('published', false, "page_name", $pageUrl),
            'new_date' => new newDateFlagField('news_date'),
            'thumbnail' => new dataField('thumbnail')
        );

        $this->publishedField = 'published';
    }
/*
    function ReadNewsItems($type) {
        $archive = ($type == 'current') ? 0 : 1;
        $fields = $this->GetFields();
        $sql = "select $fields from news where archive = '$archive' and published = 1 order by date desc";
        return $this->ReadItems($sql);
    }
*/
    function GetTitle($page_name) {
        $sql = "select title from {$this->table} where page_name = '$page_name'";
        return db_get_single_value($sql, 'title');
    }

}