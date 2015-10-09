<?php

    if ((isset($session_user_id)) && ($page_name != 'logout'))
    {

		$subnav_sql="select title, page_name from member_page where id > 1 and published = 1 order by order_num";
          
        $subnav_result = mysql_query($subnav_sql);  

        if (mysql_num_rows($subnav_result) > 0) 
        {
            $submenu = array();
            while ($subnav_row = mysql_fetch_array($subnav_result)) {    
                $submenuitem = array();
                $submenuitem['name'] = $subnav_row["title"];
                //$submenuitem['icon'] = show_thumb($subnav_row["thumbnail_image_loc"], 6, '', 'alt="'.$submenuitem['name'].'"', 'class="news-item_icon"');
                //$submenuitem['summary'] = $subnav_row["summary"];
                $submenuitem['link'] = $menulink.'/'.$subnav_row["page_name"];
                $submenu[] = $submenuitem;
            }
            $menuitem['submenu'] = $submenu;
            $menuitem['has_submenu'] = true;
        }
    }
?>
