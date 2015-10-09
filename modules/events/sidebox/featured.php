<?php
//	include_once "php/events_events_list_function.php";
	if(EVENTS_HAS_FEATURED_SIDEBOX) {
		$num_events_displayed           = EVENTS_FEATURED_SIDEBOX_NUMBER_OF_ITEMS; //todo - need a new flag for featured
		$startOfYesterday               = mktime( 0, 0, 0, date( "m" ), date( "d" ) - 1, date( "Y" ) );
		$date                           = date( 'y-m-d', $startOfYesterday );
		$events_featured_sidebox_sql    = "select id, title, summary, body as description, unix_timestamp(startdate) startdate, unix_timestamp(enddate) enddate, thumbnail, page_name from events " .
		                                  "where enddate > '$date' and published = 1 and featured = 1 order by startdate limit $num_events_displayed";
		$events_featured_sidebox_result = mysql_query( $events_featured_sidebox_sql );
		$sidebox_events_items_featured_ = array();
		while ( $events_featured_sidebox_row = mysql_fetch_array( $events_featured_sidebox_result ) ) {
			$sidebox_events_items_featured_[] = array(
				'startdate' => $events_featured_sidebox_row['startdate'],
				'enddate' => $events_featured_sidebox_row['enddate'],
				'title' => $events_featured_sidebox_row['title'],
				'description' => $events_featured_sidebox_row['description'],
				'summary' => $events_featured_sidebox_row['summary'],
				'link' => '/events/' . $events_featured_sidebox_row['page_name'],
				'thumbnail' => $events_featured_sidebox_row['thumbnail']
			);
		}
		$smarty->assign( 'f_sidebox_events', $sidebox_events_items_featured_ );
		$smarty->assign( 'no_events', events_NO_events_MESSAGE );
		$smarty->assign( 'EVENTS_HAS_THUMBNAIL', EVENTS_HAS_THUMBNAIL );
		$events_featured_sidebox_template_file = "$base_path/modules/events/templates/featured.tpl";
		$filters['featured_sidebox_events']    = array( 'search_string' => '/<!-- CS featured events start *-->.*<!-- CS featured events end *-->/s',
			'replace_string' => '{include file="' . $events_featured_sidebox_template_file . '"}' );
	}