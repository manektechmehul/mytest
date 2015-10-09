<?php

// todo: needs to be customised for base listing. 
if(CASESTUDIES_ON_LEVEL == 0){
    /*
     * If the case studies is on level 0, set the sub menu items to be the categories of case studies module
     * 
     * If the case studies is on level 1, don't over write the other items in the sub menu via this auto run script.
     * The other script with be called explicitally later on the get 
     */
    $submenu = getCaseStudiesSubmenu($menulink);
    $menuitem['submenu'] = $submenu;
    $menuitem['has_submenu'] = true;
}   


function getCaseStudiesSubmenu($menulink) {
  if(CASESTUDIES_HAS_CATEGORIES != 0){
      
  
	global $session_member_id;	
        global $name_parts;
	$pageMenu = array();	
		//$subnav_sql="SELECT title, page_name FROM category ORDER BY order_num";
		// only show categories with items available
		$subnav_sql="SELECT DISTINCT c.* FROM case_study_category csc
INNER JOIN case_study cs ON cs.id = csc.case_study_id
INNER JOIN category c ON c.id = csc.category_id
WHERE cs.`published` = 1 and special = 0 ORDER BY c.order_num";
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
	
}
	return $pageMenu;	
/*	
 *  Add a static item after menu
 * 
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