<?php
Header('Content-Type: image/png');
function Myttftext($img, $size, $x, $y, $color, $font, $text)
{
  $let_sp = array(
	'a' => '1', 'b' => '1', 'c' => '1', 'd' => '0', 'e' => '0', 'f' => '-1',
	'g' => '0', 'h' => '1', 'i' => '1', 'j' => '-2', 'k' => '1', 'l' => '1', 
	'm' => '2', 'n' => '1', 'o' => '1', 'p' => '2', 'q' => '1', 'r' => '1', 
	's' => '1', 't' => '1', 'u' => '1', 'v' => '0', 'x' => '1', 'y' => '0',
	'z' => '1', 'A' => '1', 'B' => '1', 'C' => '1', 'D' => '1', 'E' => '1',
	'F' => '1', 'G' => '1', 'H' => '1', 'I' => '1', 'J' => '1', 'K' => '1', 
	'L' => '1', 'M' => '2', 'N' => '2', 'O' => '1', 'P' => '1', 'Q' => '1', 
	'R' => '1', 'S' => '1', 'T' => '0', 'U' => '1', 'V' => '1', 'X' => '1', 
	'Y' => '0', 'Z' => '1', ' ' => '1');

  for ($i = 0; $i < strlen($text); $i++) 
  {
    $letter = substr($text, $i, 1);
    $box = imagettfbbox($size,0,$font,$letter);
    $width = abs($box[4] - $box[0]);

	ImageTTFText($img,$size,0,$x,$y,$color,$font,$letter);
      $x += $width + $let_sp[$letter] -1;
  }
  return $x;
}

$fontSize = 10;
$angle = 0;
$font = "MyriadPro-Regular.otf";

// Make the text image
$box = imagettfbbox($fontSize,0,$font,$text.'tfygj');
$width = abs($box[4] - $box[0]);
$height = abs($box[5] - $box[1]) + 10;


$textimage = imageCreateTrueColor($width,$height);
imageantialias($textimage, false);
imageSaveAlpha($textimage, true);

$transparentColor = imagecolorallocatealpha($textimage, 255, 255, 255, 127);
imagefill($textimage, 0, 0, $transparentColor);

$shadowcolor = imagecolorallocatealpha($textimage, 0, 0, 0, 50);
Myttftext($textimage,  $fontSize,  1,  $height -6,  $shadowcolor, $font, $text);

$textColor = imagecolorallocatealpha($textimage, 255, 255, 255, 0);
Myttftext($textimage,  $fontSize, 0,  $height -7,  $textColor, $font, $text);
$new_width = Myttftext($textimage,  $fontSize, 0,  $height -7,  $textColor, $font, $text);

$width = $new_width + 2;

// Build the backgroung
$blank_left = imagecreatefrompng("generic-button-$state-left.png");
$blank_left_width = imagesx($blank_left);

$blank_right = imagecreatefrompng("generic-button-$state-right.png");
$blank_right_width = imagesx($blank_right);

$blank = imagecreatefrompng("generic-button-$state-bg.png");
$blank_width = imagesx($blank);
$blank_height = imagesy($blank);


$buttonwidth = $width + $blank_left_width +$blank_right_width;
$button = imagecreatetruecolor($buttonwidth, $blank_height);
imagesavealpha($button, true);
$transparentColor = imagecolorallocatealpha($button, 255, 255, 255, 127);
imagefill($button, 0, 0, $transparentColor);

imagecopy($button, $blank_left, 0, 0, 0, 0, $blank_left_width, $blank_height); 
$xpos = $blank_left_width;
$w = $buttonwidth - $blank_right_width - $xpos;
while ($w > 0)
{
  $dw = $blank_width;
  if ($dw > $w)
    $dw = $w;
  imagecopy($button, $blank, $xpos, 0, 0, 0, $dw, $blank_height); 
  $xpos += $dw;
  $w -= $dw;
}
imagecopy($button, $blank_right, $buttonwidth - $blank_right_width, 0, 0, 0, $blank_right_width, $blank_height); 

imagecopy($button, $textimage, $blank_left_width, ($blank_height / 2) - ($height /2), 0,0, $width, $height);

imagepng($button, $file_name);
imagepng($button);

?>
