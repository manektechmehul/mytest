<?php
/* add a new tab to the tabs list in admin/admin_header_inc.php */
	$second_level_navigavtion = '';
	/* #module specific : change tab name, alt + name + src */
	$on = ($second_admin_tab == 'orders') ? 'over' : 'off';
	$second_level_navigavtion .= '<a href="orders.php" onmouseout="button_off(this)"
        onmouseover="button_over(this)"><img src="/admin/images/buttons/2ndrowtab-orders-'.$on.'.gif" alt="Orders" name="Orders"  /></a>';
    $on = ($second_admin_tab == 'Categories') ? 'over' : 'off';
    $second_level_navigavtion .= '<a href="categories.php" onmouseout="button_off(this)" 
        onmouseover="button_over(this)"><img src="/admin/images/buttons/2ndrowtab-categories-'.$on.'.gif" alt="Categories" name="Categories"  /></a>'; 
/* #module specific : change tab name, alt + name + src */
    $on = ($second_admin_tab == 'booking') ? 'over' : 'off';    
    $second_level_navigavtion .= '<a href="main.php" onmouseout="button_off(this)" 
        onmouseover="button_over(this)"><img src="/admin/images/buttons/2ndrowtab-Booking_Events-'.$on.'.gif" alt="Bookings" name="Booking"  /></a>';
