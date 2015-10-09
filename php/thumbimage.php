<?
include ('../admin/cms_functions_inc.php');
if (isset($_GET['size']))
{
	$size = $_GET['size'];
	if($size == '23'){
			header('Location: ' . $_GET['img'] );
			exit();
	}
}


$debug = isset($_GET['debug']);
if (!$debug)
header('Content-type: image/jpg');
$original = $_GET['img'];


if (isset($_GET['size']))
{
	$size = $_GET['size'];

	$extra = '_size'.$size;

}
else if (isset($_GET['width'])) {
	$width = $_GET['width'];
	$height = $_GET['height'];

	$extra = '_h'.$height.'_w'.$width;

}
$background = $_GET['background'];
$crop = $_GET['crop'];

if ($background)
    $extra .= '_back';
if ($crop)
    $extra .= '_crop';

	
$new = '..' . str_replace('/UserFiles/Image/', '/UserFiles/Thumbnails/', $original);
$new = preg_replace('/\..{3}$/', $extra.'.jpg', $new);

if (isset($_GET['size'])) {
	switch ($size)
	{
		// two custom ones
		case 1: $height = 500; $width = 230; break;
		case 2: $height = 66; $width = 200; break;

		// available to CMS editors
		case 3: $height = 600; $width = 300; break;
		case 4: $height = 500; $width = 220; break;
		case 5: $height = 300; $width = 120; break;
		case 6: $height = 60; $width = 60; break;

		//Gallery
		case 7: $height = 2000; $width = 726; break;
		case 8: $height = 180; $width = 180; break;
		case 9: $height = 200; $width = 200; break;
		case 10: $height = 159; $width = 238; break;

		// Gallery Admin
		case 11: $height = 500; $width = 230; break;
		case 12: $height = 66; $width = 200; break;

		// other admin
		case 13: $height = 130; $width = 160; break;
		case 14: $height = 200; $width = 200; break;
		case 15: $height = 60; $width = 60; break;
		case 16: $height = 400; $width = 400; break;

		// bespoke
		case 19: $height = 200; $width = 88; break;
		case 20: $height = 100; $width = 100; break;
		case 21: $height = 180; $width = 180; break;
		case 22: $height = 100; $width = 140; break;

		case 23: $height = 3000; $width = 3000; break;
		
		case 26: $height = 240; $width = 80; break;
		case 27: $height = 800; $width = 200; break;

		case 30: $height = 600; $width = 566; break;
		case 31: $height = 100; $width = 108; break;

		case 36: $height = 199; $width = 437; break;
		
		// responsive
		case 40: $height = 1200; $width = 600; break;
		case 41: $height = 1500; $width = 800; break;
		case 50: $height = 1400; $width = 900; break;
		case 51: $height = 1400; $width = 900; break;
	}
}

if (!file_exists($new))
{
	$path = preg_replace('/\/[^\/]*$/', '/', $new );
	umask(0);
	if (!file_exists($path))
		mkdir($path, 0777, 1);

	makeimage('..'.$original, $new, $width, $height, $background, $crop);
}

if ($debug)
	echo $new;

if (file_exists($new))
{
	$the_thumb = imagecreatefromjpeg($new);
	Imagejpeg($the_thumb, null, 90);
}

