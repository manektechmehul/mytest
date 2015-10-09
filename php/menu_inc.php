<?php

/*
 * Get main menu - top level only with the first query
 * to have no "home" displayed, simply add: ' and c.id > 1 ' to the where clause
 * - set in_navigation flag in content_type table to show/hide item in menu
 */


// This query get just the top level menu
$navmain_sql =
        "select s.*, c.page_name, m.path, pt.module_or_type, ct.id as parent_id, ct.in_navigation
		from section s
		join content_type ct on ct.section_id = s.id
		join content c on c.content_type_id = ct.id
		join page_type pt on ct.page_type  = pt.id
		left join  module m on module_or_type = m.name
		left join configuration cfg on config_flag = cfg.name
		where parent_id = 0 and template_type = 'main_body'  and COALESCE(`value`,'1') = '1'
		and s.status = 1 and section_type_id = 1
		order by s.order_num";

$navmain_result = mysql_query($navmain_sql);
$menu = array();
while ($navmain_row = mysql_fetch_array($navmain_result)) {

    $menu_section_id = $navmain_row["id"];
    if (($navmain_row['path'] == 'members') && (empty($session_member_id) || ($page_name == 'logout')))
        continue;

    //if ($menu_section_id == "1")
    $mainpage = "index.php";
    // else
    //    $mainpage = "index.php";
define ('GRAPHICAL_MENU', 0);
    $menuitem = array();
    $menuitem['name'] = $navmain_row["name"];
    $menuitem['classname'] = (GRAPHICAL_MENU) ? $navmain_row["css_class"] : "";
    $menuitem['external'] = false;
    if ($navmain_row['page_name']) {
        $menulink = $navmain_row["page_name"];
        $is_external_link = (substr($menulink, 0, 7) == 'http://');
        $menuitem['external'] = $is_external_link;
        if (!$is_external_link && ($menulink != '/'))
            $menulink = '/' . $menulink;
        $menuitem['link'] = $menulink;
    }
    else
        $menuitem['link'] = sprintf("%s?section_id=%s", $mainpage, $navmain_row["id"]);

    // if ((MENU_STYLE == 0) || ($menu_section_id == $session_section_id)) {

    $menuitem['on'] = ($menu_section_id == $session_section_id);
    $submenu = '';
    $module_menu = ($navmain_row['path']) ? $base_path . '/modules/' . $navmain_row['path'] . '/menu/submenu.php' : '';

    



    if (file_exists($module_menu)) {
        // Seems to be for module generated category menu on the top level
        // This will over write a menu if the module is on the second level
        // echo '<br> including menu  > ' . $module_menu;
        include_once $module_menu;
    } 
    
    if($submenu == '') {
        // I think is to get a cms sub menu then potentially a module sub menu
        // This facilitates a module on the section level of the cms rather than the top            
        $level = 1;
        $submenu = getSubMenu($navmain_row["parent_id"], $level);
    }else{
        // merging menus - categories wash in to pages       
        $modulemenu = $submenu;      
        $level = 1;
        $submenu = getSubMenu($navmain_row["parent_id"], $level);
        foreach($submenu as $item){
           $modulemenu[] = $item;
        }        
        $submenu = $modulemenu;              
    }

    // add on submenu if there is one
    if (!empty($submenu)) {
        $menuitem['submenu'] = $submenu;
        $menuitem['has_submenu'] = true;
        if ($menu_section_id == $session_section_id)
            $smarty->assign('submenu', $submenu);
    }


    // }
    // only display items that has the in_navigation flag set
    if ($navmain_row['in_navigation']) {
        $menu[] = $menuitem;
    }
}
$smarty->assign('navmenu', $menu);

function getSubMenu($parent, $level) {

    global $content_type_row;
    // this gets the sub pages for this section for the content table [non module menu]
    $subnav_sql = "select ct.*, c.page_name, thumbnail_image_loc, summary from content c join content_type ct on c.content_type_id = ct.id" .
            " where parent_id = '$parent' AND status = '1' and level = $level and template_type = 'main_body'  ORDER BY order_num";

    //echo '<p> ' . $subnav_sql . ';</p>';
    
    $subnav_result = mysql_query($subnav_sql);
    $submenu = array();


    if (mysql_num_rows($subnav_result) > 0) {
        while ($subnav_row = mysql_fetch_array($subnav_result)) {
            $submenuitem = array();
            $submenuitem['name'] = $subnav_row["name"];
            $currentLevel = $content_type_row['level'];
            if ($level == 1) {
                $on = (($currentLevel == 1) && ($content_type_row['id'] == $subnav_row["id"])) ||
                        (($currentLevel == 2) && ($content_type_row['parent_id'] == $subnav_row["id"]));
            }
            if ($level == 2) {
                $on = ($content_type_row['id'] == $subnav_row["id"]);
            }
            $submenuitem['on'] = $on ? 1 : 0;
            $submenuitem['icon'] = show_thumb($subnav_row["thumbnail_image_loc"], 6, '', 'alt="' . $submenuitem['name'] . '"', 'class="news-item_icon"');
            $submenuitem['summary'] = $subnav_row["summary"];
            /*             * ****
             * So this section is all about adding a module specific menu on to an exsiting sub menu.
             * This is typically the case when a module homepage is not on a top level, but on level 1.
             *
             * You may have a structure like
             *
             * > community
             * >> item 1
             * >> item 2
             * >> item 3
             * >> Case Studies
             * >>> Ministry Transitions
             * >>> Ministry Expressions
             *
             */
            

            if ((MENU_STYLE == 0) || ($on)) {

                if (($on) && ($subnav_row['page_type'] == 28)) {
                    $base_path = ".";
                    include "modules/documents/menu/docssubmenu.php";
                    $pageMenu = getDocsSubmenu('/' . $subnav_row["page_name"]);
                } else if (($on) && ($subnav_row['page_type'] == 102)) {
                    $base_path = ".";
                    include "modules/blog/menu/blogssubmenu.php";
                    $pageMenu = getBlogSubmenu('/' . $subnav_row["page_name"]);
                } else if (($on) && ($subnav_row['page_type'] == 200)) {
                    $base_path = ".";
                    include "modules/noticeboard/menu/diss_submenu.php";
                    $pageMenu = getDiscussionsSubmenu('/' . $subnav_row["page_name"]);
                } else if (($on) && ($subnav_row['page_type'] == 7)) {
                    $base_path = ".";
                    include "modules/case_studies/menu/submenu.php";
                    $pageMenu = getCaseStudiesSubmenu('/' . $subnav_row["page_name"]);
                }// todo: this switch needs to be customised for listing modules manually when on the second level of the site
                else if (($on) && ($subnav_row['page_type'] == 201)) {
                    // this is for a sub menu on a module on a second level 
                    $base_path = ".";
                    include_once "modules/video/menu/submenu.php";
                    $pageMenu = getVideosSubmenu('/' . $subnav_row["page_name"]);
                } // 
                 else if (($on) && ($subnav_row['page_type'] == 202)) {
                    $base_path = ".";
                    include "modules/property/menu/submenu.php";
                    $pageMenu = getPropertiesSubmenu('/' . $subnav_row["page_name"]);
                } //
                 else if (($on) && ($subnav_row['page_type'] == 203)) {
	                 $base_path = ".";
	                 include "modules/profiles/menu/submenu.php";
	                 $pageMenu = getProfilesSubmenu('/' . $subnav_row["page_name"]);
                 } //
                else{
                    $pageMenu = getSubMenu($subnav_row["id"], $level + 1);
                }
                if (!empty($pageMenu)) {
                    $submenuitem['pageMenu'] = $pageMenu;
                }
            }

            if ($subnav_row['page_name']) {
                if (substr($subnav_row['page_name'], 0, 7) == 'http://')
                    $submenuitem['link'] = $subnav_row["page_name"];
                else
                    $submenuitem['link'] = '/' . $subnav_row["page_name"];
            }
            else
                $submenuitem['link'] = sprintf("index.php?section_id=%s&content_type_id=%s", $menu_section_id, $subnav_row["id"]);
            $submenu[] = $submenuitem;
        }
    }
    return $submenu;
}

?>