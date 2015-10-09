<?php
    include_once 'conf.php';
    $second_level_navigavtion = '';

    $on = ($second_admin_tab == 'categories') ? 'over' : 'off';    
    $second_level_navigavtion .= '<a href="category.php" onmouseout="button_off(this)" onmouseover="button_over(this)">
        <img src="/admin/images/buttons/2ndrowtab-' . $secondary_level_main_title . ' Categories-'.$on.'.gif" alt="Categories" name="categories"  /></a>';

    $on = ($second_admin_tab == 'main') ? 'over' : 'off';    
    $second_level_navigavtion .= '<a href="main.php" onmouseout="button_off(this)" 
        onmouseover="button_over(this)"><img src="/admin/images/buttons/2ndrowtab-' . $secondary_level_main_title . '-'.$on.'.gif" alt="' . $secondary_level_main_title . '" 
            name="main"  /></a>';

?>