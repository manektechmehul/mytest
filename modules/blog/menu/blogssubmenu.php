<?php

function getBlogSubmenu() {
    global $name_parts;

    $sql = 'select * from blogs where blog_status = 1 and published = 1 order by order_num';
    $result = mysql_query($sql);
    $submenu = array();
    $menulink = '/' . $name_parts[0];
    if (mysql_num_rows($result) > 0) {
        while ($row = mysql_fetch_array($result)) {
            $submenuitem = array();
            $submenuitem['name'] = $row["title"];
            $submenuitem['on'] = ($row["page_name"] == $name_parts[1]);
            $submenuitem['link'] = $menulink . '/' . $row["page_name"];
            $submenu[] = $submenuitem;
        }
    }
    return $submenu;
}