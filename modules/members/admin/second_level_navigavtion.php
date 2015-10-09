<?php
    $second_level_navigavtion = '';

//    $on = ($second_admin_tab == 'docs') ? 'over' : 'off';    
//    $second_level_navigavtion .= '<a href="documents.php" onmouseout="button_off(this)" onmouseover="button_over(this)"><img src="/admin/images/buttons/2ndrowtab-Documents-'.$on.'.gif" alt="Documents" name="Docuemnts"  /></a>';

//    $on = ($second_admin_tab == 'pages') ? 'over' : 'off';    
//    $second_level_navigavtion .= '<a href="member_pages.php" onmouseout="button_off(this)" onmouseover="button_over(this)"><img //src="/admin/images/buttons/2ndrowtab-Pages-'.$on.'.gif" alt="Pages" name="Pages"  /></a>';

    $on = ($second_admin_tab == 'members') ? 'over' : 'off';    
    $second_level_navigavtion .= '<a href="members.php" onmouseout="button_off(this)" onmouseover="button_over(this)"><img src="/admin/images/buttons/2ndrowtab-Members-'.$on.'.gif" alt="Members" name="Members"  /></a>';

