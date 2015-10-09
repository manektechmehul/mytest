<?php
function get_event_list ($content_type_id, $display_count = "")
{    
    $date_now = date("Y-m-d 0:0:0");

    $page_type_sql = "select page_type, page_name from content c join content_type ct on content_type_id = ct.id where template_type = 'main_body' and ct.id = ".$content_type_id;
    $page_type_result = mysql_query($page_type_sql);
    $page_type_row = mysql_fetch_array($page_type_result);
    $page_type = $page_type_row['page_type']; 
    $page_name = $page_type_row['page_name']; 
    if ($page_name)
        $page_name .= '?';
    else
        $page_name = "content_type_id=$content_type_id&";

    if ($page_type == 2) // 2 - news doesnt "time out"
        $diary_item_sql = "select * from diary_item WHERE content_type_id = $content_type_id and published = 1 and archive = 0 ORDER BY date_of_event DESC";
    else
        $diary_item_sql = "select * from diary_item WHERE content_type_id = $content_type_id ".
                          "  AND date_of_event >= '$date_now' and published = 1 and archive = 0 ORDER BY date_of_event";
                          
    if ($display_count)
        $diary_item_sql .= " limit $display_count";

    $diary_item_result = mysql_query($diary_item_sql);  

    $events = array();

    if (mysql_num_rows($diary_item_result) > 0) 
    {
        $event_date_to_display = "";

        while ($diary_item_row = mysql_fetch_array($diary_item_result)) 
        {
            $event = array();
            $event_day = substr($diary_item_row["date_of_event"], 8, 2);
            $event_month = substr($diary_item_row["date_of_event"], 5, 2);
            $event_year = substr($diary_item_row["date_of_event"], 0, 4); 

            if ($diary_item_row["summary"])
                $short_desc = $diary_item_row["summary"];
            else
                $short_desc = substr($diary_item_row["description"],0,100);
            $event['date'] = mktime(12, 30, 10, $event_month, $event_day, $event_year);
            $event['title'] = $diary_item_row["title"];
            $event['icon'] = show_thumb($diary_item_row["icon"], 6, '', 'alt="'.$event['title'].'"', 'class="news-item_icon"');
            $event['description'] = strip_tags($short_desc);
            $event['id'] = $diary_item_row["id"];
            $event['link'] = $page_name . 'id='. $event['id'];

            $events[] = $event;
        }
    }
        
    return $events;
}
?>
