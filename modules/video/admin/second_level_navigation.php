<?php
/* add a new tab to the tabs list in admin/admin_header_inc.php */
    $second_level_navigavtion = '';
    $on = ($second_admin_tab == 'categories') ? 'over' : 'off';    
    $second_level_navigavtion .= '<a href="categories.php" onmouseout="button_off(this)" 
        onmouseover="button_over(this)"><img src="/admin/images/buttons/2ndrowtab-categories-'.$on.'.gif" alt="Categories" name="categories"  /></a>';
/* #module specific : change tab name, alt + name + src */
    $on = ($second_admin_tab == 'videos') ? 'over' : 'off';    
    $second_level_navigavtion .= '<a href="main.php" onmouseout="button_off(this)" 
        onmouseover="button_over(this)"><img src="/admin/images/buttons/2ndrowtab-videos-'.$on.'.gif" alt="Videos" name="videos"  /></a>';

