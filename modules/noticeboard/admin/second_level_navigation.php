<?php
/*$secondLevelButtons = array(
    array('tabname' => 'notice_posts', 'filename' => 'notice_posts.php', 'buttontitle' => 'Noticeboard Posts'),
    array('tabname' => 'notice_categories', 'filename' => 'notice_categories.php', 'buttontitle' => 'Noticeboard Categories'),
);
*/

    $on = ($second_admin_tab == 'comments') ? 'over' : 'off';    
    $second_level_navigavtion .= '<a href="comments.php" onmouseout="button_off(this)" onmouseover="button_over(this)"><img src="/admin/images/buttons/2ndrowtab-Comments-'.$on.'.gif" alt="Comments" name="Comments"  /></a>';

    $on = ($second_admin_tab == 'posts') ? 'over' : 'off';    
    $second_level_navigavtion .= '<a href="notice_posts.php" onmouseout="button_off(this)" onmouseover="button_over(this)"><img src="/admin/images/buttons/2ndrowtab-Posts-'.$on.'.gif" alt="Posts" name="Posts"  /></a>';

    $on = ($second_admin_tab == 'categories') ? 'over' : 'off';    
    $second_level_navigavtion .= '<a href="notice_categories.php" onmouseout="button_off(this)" onmouseover="button_over(this)"><img src="/admin/images/buttons/2ndrowtab-Categories-'.$on.'.gif" alt="Categories" name="Categories"  /></a>';

