<?php
	define("USER_IMAGE_DIR", "/UserFiles/Image/");
		
	$site_sql = "SELECT * FROM configuration";
	$site_result = mysql_query($site_sql);
	
	while (	$site_row = mysql_fetch_array($site_result) )
		define ($site_row['name'], $site_row['value']);

	if (DEBUG_MODE == 'DEBUG')
	{
		include_once $base_path.'/php/firebug/fb.php';
	}