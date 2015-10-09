<?php

include '../../php/thumbnails_inc.php';

$html = '';

$upload_dir = '/UserFiles/File/';

$currdir = '';
$scandir = '../..'.$upload_dir;
if (isset($_GET['files_folder']) && ($_GET['files_folder']))
	$currdir = $_GET['files_folder'].'/';

$foldername	= $currdir;

if (($currdir == '/') || ($currdir == '//'))
	$currdir = '';
	
$files = scandir($scandir.$currdir);

$file_type_icons = array(
	'mp3' => 'mp3.gif',
	'wma' => 'mp3.gif',
	'mov' => 'mov.gif',
	'pdf' => 'pdf.gif',
	'gif' => 'img.gif',
	'jpg' => 'img.gif',
	);
	

foreach ($files as $file)
{
		$i = 0;	
	//		echo "<p><a href='{$_SERVER['PHP_SELF']}?dir=$subdir'>$dir</a></p>";
	if (is_file($scandir.$currdir.$file))
	{
		//if ($i > 8)
		//	break;

		$fileinfo = pathinfo($file);
		$ext = $fileinfo['extension'];

		$iconFile = "/php/filecontroller/images/$ext.gif";
		$icon_name = "$ext.gif";
		if (!file_exists('../..'.$iconFile))
		{
			$icon_name = 'generic.gif';
			$iconFile = "/php/filecontroller/images/generic.gif";
		}
		
		$pickAction = "pick_file(\'$currdir$file\', \'$iconFile\')";
		
		$html .= "<div class=\"fileSquare\"><div class=\"fileSquareInner\" onclick=\"$pickAction\">";
		$html .= "<img src=\'./images/$icon_name\' />";
		$html .= "</div>";


		if (strlen($file) > 50)
			$short_file_name = substr($file, 0,50) . '...';
		else
			$short_file_name = $file;
		$html .= "<div class=\'fileDelete\'>";
		$html .= "<form method=\'post\' action=\'\' onsubmit=\'return delete_check()\'><input type=\'hidden\' name=\'foldername\' value=\'$foldername\'/>";
		$html .= "<input type=\'hidden\' name=\'deletefilename\' value=\'$currdir$file\'/>";
		$html .= "<input type=\'image\' src=\'images/delete-off.gif\' name=\'delete\' value=\'$currdir$file\'/>";
		$html .= "</form></div>";
		$html .= "<div class =\"fileDescription\" onclick=\"$pickAction\">$short_file_name</div></div>";
		$i++;
	}
}

?>
div = document.getElementById('Grid');
div.innerHTML = '<?php echo $html; ?>';
