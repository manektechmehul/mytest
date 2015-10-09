<?


include '../../php/databaseconnection.php';
include '../../php/functions_inc.php';
 

function getEventsForCalendar(){
            
     //   $sql = "SELECT title, DATE_FORMAT(startdate,'%e/%c/%Y') AS date , summary, page_name FROM `events` WHERE published = 1 ORDER BY startdate ASC";

        $sql = "SELECT title, DATE_FORMAT(start_date,'%e/%c/%Y') AS date , description  AS summary, page_name FROM `booking` WHERE published = 1 ORDER BY start_date ASC";
        $results = mysql_query($sql);
        $json = array();
        while (is_resource($results) && $row = mysql_fetch_object($results)) {
			$date =   str_replace('/', '\/', $row->date);
			// "27\/3\/2015" vs "12\/3\/2015"
			// 01/09/2015
			$json[] = '{"date" : "' . $date  . '","link":"/events/' . $row->page_name . '", "content":"' . $row->title . $thumb  . '" }';
			//$json[] = '{"date" : "' . $date  . '", "title" : "' . $row->title . '","link":"' . $row->page_name . '", "content":"' . $row->summary . $thumb  . '" }';
	    }        
      	return '['  . implode(',', $json) . ']';
}

echo getEventsForCalendar();

