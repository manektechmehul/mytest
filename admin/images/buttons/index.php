<?php
$style= $_GET['style'];
$text= $_GET['text'];
$state= $_GET['state'];
$file_name = "./cache/{$style}_".urlencode($text)."_{$state}.gif";
if (file_exists($file_name))
{
	Header('Content-Type: image/gif');
	echo file_get_contents($file_name);
}else{
	$text = str_replace('_', ' ', $text);
	include 'makebutton.php';
}
?>