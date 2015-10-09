<?php
    $second_level_navigavtion = '';
    
    $on = ($second_admin_tab == 'years') ? 'over' : 'off';    
    $second_level_navigavtion .= '<a href="years.php" onmouseout="button_off(this)" 
        onmouseover="button_over(this)"><img src="/admin/images/buttons/2ndrowtab-years-'.$on.'.gif" alt="Years" name="years"  /></a>';
    
    $on = ($second_admin_tab == 'locations') ? 'over' : 'off';    
    $second_level_navigavtion .= '<a href="locations.php" onmouseout="button_off(this)" 
        onmouseover="button_over(this)"><img src="/admin/images/buttons/2ndrowtab-locations-'.$on.'.gif" alt="Locations" name="locations"  /></a>';
    
    $on = ($second_admin_tab == 'bedrooms') ? 'over' : 'off';    
    $second_level_navigavtion .= '<a href="bedrooms.php" onmouseout="button_off(this)" 
        onmouseover="button_over(this)"><img src="/admin/images/buttons/2ndrowtab-bedrooms-'.$on.'.gif" alt="Bedrooms" name="bedrooms"  /></a>';

/* add a new tab to the tabs list in admin/admin_header_inc.php */
    /*
    $second_level_navigavtion = '';
    $on = ($second_admin_tab == 'categories') ? 'over' : 'off';    
    $second_level_navigavtion .= '<a href="categories.php" onmouseout="button_off(this)" 
        onmouseover="button_over(this)"><img src="/admin/images/buttons/2ndrowtab-categories-'.$on.'.gif" alt="Categories" name="categories"  /></a>';
     * */
    
/* #module specific : change tab name, alt + name + src */
    $on = ($second_admin_tab == 'property') ? 'over' : 'off';    
    $second_level_navigavtion .= '<a href="main.php" onmouseout="button_off(this)" 
        onmouseover="button_over(this)"><img src="/admin/images/buttons/2ndrowtab-property-'.$on.'.gif" alt="Properties" name="property"  /></a>';

