<?php
/* add a new tab to the tabs list in admin/admin_header_inc.php */
    $second_level_navigavtion = '';


  //  $on = ($second_admin_tab == 'Categories') ? 'over' : 'off';
  //  $second_level_navigavtion .= '<a href="categories.php" onmouseout="button_off(this)"
 //       onmouseover="button_over(this)"><img src="/admin/images/buttons/2ndrowtab-categories-'.$on.'.gif" alt="Categories" name="Categories"  /></a>';
/* #module specific : change tab name, alt + name + src */


  $on = ($second_admin_tab == 'orders') ? 'over' : 'off';
  $second_level_navigavtion .= '<a href="orders.php" onmouseout="button_off(this)"
       onmouseover="button_over(this)"><img src="/admin/images/buttons/2ndrowtab-Donations-'.$on.'.gif" alt="Orders" name="Orders"  /></a>';

// orders tab



    $on = ($second_admin_tab == 'donate') ? 'over' : 'off';
    $second_level_navigavtion .= '<a href="main.php" onmouseout="button_off(this)" 
        onmouseover="button_over(this)"><img src="/admin/images/buttons/2ndrowtab-Donation_Types-'.$on.'.gif" alt="Donations" name="Donate"  /></a>';

