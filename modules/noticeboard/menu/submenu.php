<?php
$level = 1;

$subnav_sql="select title, page_name from notice_category where published = 1 order by order_num";

$subnav_result = mysql_query($subnav_sql);  

if (mysql_num_rows($subnav_result) > 0) 
{
    $submenuitem = array();
    $submenuitem['name'] = 'Discussions';
    $submenuitem['link'] = '/discussions';
    $submenuitem['on'] = true;
    $submenuitem['open'] = true;
    $pageMenu = array();
    while ($subnav_row = mysql_fetch_array($subnav_result)) {    
        $disscussionItem = array();
        $disscussionItem['name'] = $subnav_row["title"];
        $disscussionItem['on'] = ($subnav_row["page_name"] == $name_parts[1]);
        //$submenuitem['icon'] = show_thumb($subnav_row["thumbnail_image_loc"], 6, '', 'alt="'.$submenuitem['name'].'"', 'class="news-item_icon"');
        //$submenuitem['summary'] = $subnav_row["summary"];
        $disscussionItem['link'] = $menulink.'/'.$subnav_row["page_name"];
        $pageMenu[] = $disscussionItem;
     }
     $submenuitem['pageMenu'] = $pageMenu;
}
$submenu = array($submenuitem);
$submenu = array_merge($submenu, getSubMenu($navmain_row["parent_id"], $level));

if ($session_member_id) {
    $submenuitem = array();
    $submenuitem['name'] = 'Edit my details';
    $submenuitem['link'] = '/edit_details';
    $submenuitem['on'] = false;
    $submenu[] = $submenuitem;
} else {
    $submenuitem = array();
    $submenuitem['name'] = 'Sign up';
    $submenuitem['link'] = '/sign_up';
    $submenuitem['on'] = false;
    $submenu[] = $submenuitem;
}