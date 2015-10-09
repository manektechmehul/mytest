<?php

function Myttftext($img, $size, $x, $y, $color, $font, $text)
{
  $let_sp = array('a' => '15', 'b' => '25', 'c' => '25', 'd' => '25', 'e' => '10', 'f' => '0',
'g' => '25', 'h' => '25', 'i' => '20', 'j' => '25', 'k' => '25', 'l' => '25', 
'm' => '25', 'n' => '25', 'o' => '5', 'p' => '25', 'q' => '25', 'r' => '25', 
's' => '25', 't' => '15', 'u' => '25', 'v' => '-10', 'x' => '25', 'y' => '-10',
'z' => '25', 'A' => '15', 'B' => '25', 'C' => '25', 'D' => '25', 'E' => '25',
'F' => '25', 'G' => '25', 'H' => '25', 'I' => '25', 'J' => '25', 'K' => '25', 
'L' => '25', 'M' => '25', 'N' => '25', 'O' => '25', 'P' => '25', 'Q' => '25', 
'R' => '25', 'S' => '25', 'T' => '5', 'U' => '25', 'V' => '25', 'X' => '25', 
'Y' => '25', 'Z' => '25');

  for ($i = 0; $i < strlen($text); $i++) {
    $letter = substr($text, $i, 1);
    $box = imagettfbbox($size,0,$font,$letter);
    $width = abs($box[4] - $box[0]);
    if ($letter == " ")
      $x += 45;
    else
    {
      ImageTTFText($img,$size,0,$x,$y,$color,$font,$letter);
//      $x += $width + (25 - ($width * .05));
	  
      $x += $width + $let_sp[$letter];
    }
  }
  return $x;
}


$fontSize = 212;
$angle = 0;
$font = "./Gafata-Regular.otf";

$box = imagettfbbox($fontSize,0,$font,$text);
$width = abs($box[4] - $box[0]) + 20;
$height = abs($box[5] - $box[1]) + 40;


$size = imageTTFBBox($fontSize, $angle, $font, $text);
$image = imageCreateTrueColor($width,$height);
imageSaveAlpha($image, true);

$transparentColor = imagecolorallocatealpha($image, 0, 0, 0, 127);
imagefill($image, 0, 0, $transparentColor);

$shadowcolor = imagecolorallocatealpha($image, 20, 20, 20, 80);
Myttftext($image, $fontSize, 35, 250, $shadowcolor, $font, $text);
$shadowcolor = imagecolorallocatealpha($image, 20, 20, 20, 100);
Myttftext($image, $fontSize, 40, 255, $shadowcolor, $font, $text);

$textColor = imagecolorallocate($image, 255, 255, 255);
//Myttftext($image, $fontSize, 4, 225, $textColor, $font, $text);
//Myttftext($image, $fontSize, 5, 225, $textColor, $font, $text);
Myttftext($image, $fontSize, 7, 225, $textColor, $font, $text);
Myttftext($image, $fontSize, 10, 225, $textColor, $font, $text);
//Myttftext($image, $fontSize, 13, 232, $textColor, $font, $text);

$textColor = imagecolorallocate($image, 255, 255, 255);
$width = Myttftext($image, $fontSize, 4, 225, $textColor, $font, $text);

ImageAlphaBlending($image, false);

$im2 = imageCreateTrueColor($width / 20,$height / 20);

imageSaveAlpha($im2, true);
ImageAlphaBlending($im2, false);

$transparentColor2 = imagecolorallocatealpha($im2, 255, 255, 255, 17);
//$transparentColor4 = imagecolortransparent($im2, $transparentColor2);
imagefill($im2, 0, 0, $transparentColor2);

imagecopyresampled($im2, $image, 0,0, 0,0, $width / 20,$height / 20, $width, $height);

$textwidth = ($width + 200) / 20;


// Build the backgroung

$blank = imagecreatefrompng("{$style}-$state.png");
$blank_width = imagesx($blank);
$blank_height = imagesy($blank);


$left_x = 20;

if ((substr($style,0,4) == "hide") || (substr($style,0,4) == "show"))
	$left_x = 40;

$buttonwidth = $textwidth + $left_x - 12;


$button = imagecreatetruecolor($buttonwidth, $blank_height);
$white = ImageColorAllocate($button,255,255,255);
imagefill($button,0,0,$white);

imagecopy($button, $blank, 0, 0, 0, 0, $left_x, $blank_height); 

$dx = $left_x;
$dw = $buttonwidth - 20;
while ($dw > 0)
{
  $w = $dw;
  if ($w > 20)
    $w = 20;
  imagecopy($button, $blank, $dx, 0, 20, 0, $w, $blank_height); 
  $dx += $w;
  $dw -= 20;
}
imagecopy($button, $blank, $buttonwidth - 20, 0, $blank_width - 20, 0, 20, $blank_height); 

ImageAlphaBlending($button,true);
// add the text
imagecopy($button, $im2, $left_x - 12,($blank_height / 2) - 7, 0,0,$width / 20,$height / 20);

Header('Content-Type: image/gif');
imagegif($button, $file_name);
imagegif($button);

?>
