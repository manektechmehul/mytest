<?php
    $second_level_navigavtion = '';

    $on = ($second_admin_tab == 'static') ? 'over' : 'off';    
    $second_level_navigavtion .= '<a href="static.php" onmouseout="button_off(this)" onmouseover="button_over(this)"><img src="/admin/images/buttons/2ndrowtab-Static_Promos-'.$on.'.gif" alt="Static Promos" name="static"  /></a>';

    $on = ($second_admin_tab == 'promos') ? 'over' : 'off';    
    $second_level_navigavtion .= '<a href="links.php" onmouseout="button_off(this)" onmouseover="button_over(this)"><img src="/admin/images/buttons/2ndrowtab-Promos-'.$on.'.gif" alt="promos" name="promos"  /></a>';

