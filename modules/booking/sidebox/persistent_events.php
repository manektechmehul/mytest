<?php
    // only show featured bits on module hope page
    global $name_parts;
    if (sizeof( $name_parts ) == 1) {
        $peristent_events = db_get_rows( '
SELECT * FROM booking t WHERE published = 1 AND featured= 0 AND end_date < (NOW() - INTERVAL 1 DAY)
AND (removal_date > (NOW() - INTERVAL 1 DAY ) AND persist_with_removal_date =1) ORDER BY end_date ' );
        $smarty->assign( 'peristent_events', $peristent_events );
//  $smarty->assign('docs_module_url', $module_url);
        $b_peristent__file                   = "$base_path/modules/booking/templates/persistent_events.tpl";
        $filters['98dfg9a8dfg9a87dfga978df'] = array(
            'search_string' => '/<!-- CS peristent_events start -->(.*)<!-- CS peristent_events end -->/s',
            'replace_string' => '{include file="file:' . $b_peristent__file . '"}'
        );
    }
?>