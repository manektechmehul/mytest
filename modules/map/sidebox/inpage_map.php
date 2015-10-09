<?php
	/***
	 * This is the controller for the inline map. This is a map which doesn't need to be with in a module area,
	 * so perhaps all over the sie in a footer or a side box.
	 *
	 * To use the inline map , make sure you add
	 * 1) <!-- CS inline map onload start -->.*<!-- CS inline map onload end -->
	 * this needs to be added at the top of the body of the page, ideally just after any onload event
	 *
	 * 2) <!-- CS inline map start -->.*<!-- CS inline map end -->
	 * This is where the map will appear. So pop it in your template.htm file where you would like it
	 *
	 * To change the dimensions/style of the map, edit the inline_map.tpl
	 *
	 * The map module will need to be on for this to work.
	 */
	if ( true ) {
		include_once $base_path . '/modules/map/classes/myMap.php';
		// Define the main attributes of the map
		$myMap  = new MyMap();
		$output = $myMap->drawMap();
		/*** echo out the output, along with the onload code **/
		$smarty->assign( "output", $output );
		$map_inline_onload_template_file  = "$base_path/modules/$module_path/templates/onload.tpl";
		$filters['map_inline_map_onload'] = array( 'search_string' => '/<!-- CS inline map onload start -->.*<!-- CS inline map onload end -->/s',
			'replace_string' => '{include file="' . $map_inline_onload_template_file . '"}' );

		/** Output the map in the required location  */
		$smarty->assign( "printMap", $printMap );
		$map_inline_template_file  = "$base_path/modules/$module_path/templates/inline_map.tpl";
		$filters['map_inline_map'] = array( 'search_string' => '/<!-- CS inline map start -->.*<!-- CS inline map end -->/s',
			'replace_string' => '{include file="' . $map_inline_template_file . '"}' );
	}