<?php

session_cache_limiter('must-revalidate');
session_start();

$style = isset($_GET['style']) ? $_GET['style'] : '';
$ajax = isset($_GET['no_redirect']) ? $_GET['no_redirect'] : '';

switch ($style)
{
	case 'clear': $css_style = 'clear'; break;
	case 'contrast': $css_style = 'contrast'; break;
	default: $css_style = 'default'; break;
}
	
$wrong_addresses = array('images', 'css', 'admin', 'cmsimages', 'modules', 'swf', 'templates');
	
$_SESSION["session_display_style"] = $css_style;

if (!$ajax)
{
	$redirect_location = $_SESSION["session_page"];
	if (in_array($redirect_location, $wrong_addresses))
		$redirect_location = '';
	header("Location: /$redirect_location");
}
?>