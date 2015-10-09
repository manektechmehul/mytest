<?php
$style= $_GET['style'];
$text= $_GET['text'];
$state= $_GET['state'];

$file_name = "./cache/{$style}_".urlencode($text)."_{$state}.png";
if (file_exists($file_name))
{
	Header('Content-Type: image/png');
	echo file_get_contents($file_name);
}
else
{

	$text = str_replace('_', ' ', $text);
	include 'makebutton.php';
}

?>
