<?php
	/** shorter notation on a side box - template include */
	$filters[__FILE__] = array( 'search_string' => '/<!-- CS memorybook search start -->.*<!-- CS memorybook search end -->/s',
		'replace_string' => '{include file="' . $base_path . '/modules/' . $module_path . '/templates/searchbox.tpl"}' );