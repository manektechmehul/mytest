<?php

include_once $base_path."/php/classes/model/listmodel.php";

class EventsModel extends ListModel
{
	function  __construct($pageUrl) {
		parent::__construct();
		$this->table = 'events';

        $this->dataFields = array(
            'title' => new dataField('title'),
            'summary' => new dataField('summary'),
            'description' => new dataField('body'),
            'startdate' => new dataField('start_event_date', false, 'UNIX_TIMESTAMP(`startdate`) as start_event_date'),
            'enddate' => new dataField('end_event_date', false, 'UNIX_TIMESTAMP(`enddate`) as end_event_date'),
            'archive' => new dataField('archive'),
            'published' => new dataField('published'),
            'link' => new linkField('published', false, "page_name", $pageUrl),
            'new_date' => new newDateFlagField('start_event_date'),
			'thumbnail' => new dataField('thumbnail')

        );

		$this->publishedField = 'published';
	}




    function ReadItems($sql,$pagination)
    {
        $startOfYesterday = mktime(0,0,0,date("m"),date("d")-1,date("Y"));
        $date = date('y-m-d', $startOfYesterday);
        $fields = $this->GetFields();
        $sql = "select $fields from events where published = 1 and `enddate` > '$date' order by `startdate`";
        return parent::ReadItems($sql,$pagination);
    }




    function GetTitle($page_name)
	{
		$sql = "select title from {$this->table} where page_name = '$page_name'";
		return db_get_single_value($sql, 'title');
	}
}

