<?php
error_reporting(E_ERROR | E_USER_ERROR);
include '../../php/thumbnails_inc.php';

$html = '';



$currdir = '';
$scandir = '../../UserFiles/Image/';
if (isset($_GET['files_folder']) && ($_GET['files_folder']))
	$currdir = $_GET['files_folder'].'/';

$foldername	= $currdir;

if (($currdir == '/') || ($currdir == '//'))
	$currdir = '';
	
$files = scandir($scandir.$currdir);

foreach ($files as $file)
{
		$i = 0;	
	//		echo "<p><a href='{$_SERVER['PHP_SELF']}?dir=$subdir'>$dir</a></p>";
	if (is_file($scandir.$currdir.$file))
	{
		if (i > 8) 
			break;
		$html .= "<div class=\"imageSquare\"><div class=\"imageSquareInner\">";
		// thumb size
		$html .= show_thumb($currdir.$file, 13,  'onclick="pick_image(\\\''.$currdir.$file.'\\\', this.src)"');
		//$html .= "<img src=\"/$currdir$file\"/>";
		// no of characters
		if (strlen($file) > 23)
			$short_file_name = substr($file, 0,23) . '...';
		else
			$short_file_name = $file;
		$html .= "</div>";
		$html .= "<div style=\'float:right;padding:0 1px 2px 1px;\'>";
		//$html .= "<a style=\'color:red;margin:0;padding:0 0 2px 0;line-height:6px;\' href=\'javascript:delete_image(\"$file\")\'>x</a></div>";
		$html .= "<form method=\'post\' action=\'\' onsubmit=\'return delete_check()\'><input type=\'hidden\' name=\'foldername\' value=\'$foldername\'/>";
		$html .= "<input type=\'hidden\' name=\'deletefilename\' value=\'$currdir$file\'/>";
		$html .= "<input type=\'image\' src=\'images/delete.png\' name=\'delete\' value=\'$currdir$file\'/>";
		$html .= "</form></div>";
		$html .= "<span style=\'font-family:Arial, Helvetica, sans-serif;\'>$short_file_name</span></div>";
		$i++;
	}
}

?>
var div = document.getElementById('Grid');
div.innerHTML = '<?php echo $html; ?>';
