<?php
    $second_level_navigavtion = '';

    $on = ($second_admin_tab == 'categories') ? 'over' : 'off';    
    $second_level_navigavtion .= '<a href="documents_category.php" onmouseout="button_off(this)" onmouseover="button_over(this)"><img src="/admin/images/buttons/2ndrowtab-categories-'.$on.'.gif" alt="Categories" name="categories"  /></a>';

    $on = ($second_admin_tab == 'documents') ? 'over' : 'off';    
    $second_level_navigavtion .= '<a href="documents.php" onmouseout="button_off(this)" onmouseover="button_over(this)"><img src="/admin/images/buttons/2ndrowtab-documents-'.$on.'.gif" alt="Documents" name="documents"  /></a>';

