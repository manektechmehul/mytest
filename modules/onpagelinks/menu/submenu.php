<?php

// this is called from the /php/menu_inc file - it has some unique database id bits on it

function getCaseStudiesSubmenu($menulink) {
	global $session_member_id;
	
	$pageMenu = array();
	
		$subnav_sql="SELECT title, page_name FROM category ORDER BY order_num";
		$subnav_result = mysql_query($subnav_sql);  
	
		if (mysql_num_rows($subnav_result) > 0) 
		{
			while ($subnav_row = mysql_fetch_array($subnav_result)) {    
                            
                            
                            
				$disscussionItem = array();
				$disscussionItem['name'] = $subnav_row["title"];
				$disscussionItem['on'] = ($subnav_row["page_name"] == $name_parts[1]);
				//$submenuitem['icon'] = show_thumb($subnav_row["thumbnail_image_loc"], 6, '', 'alt="'.$submenuitem['name'].'"', 'class="news-item_icon"');
				//$submenuitem['summary'] = $subnav_row["summary"];
				$disscussionItem['link'] = $menulink.'/'.$subnav_row["page_name"];
				$pageMenu[] = $disscussionItem;
			 }
	
                        /* add an extra static item
			$submenuitem = array();
			$submenuitem['name'] = 'Edit my details';
			$submenuitem['link'] = '/edit_details';
			$submenuitem['on'] = false;
			$pageMenu[] = $submenuitem;
                        * 
                        */
		}
	
	
	return $pageMenu;	
/*	
	if ($session_member_id) {
		$submenu[] = $submenuitem;
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
*/	

}