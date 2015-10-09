<?
  //  $clip_id = '46034161';
  //  $width = '800';
  //  $height = '450';

    $clip_id = $_GET['clip_id'];
    $width = $_GET['width'];
    $height = $_GET['height'];


?>
<html>
<head></head>
    <body>
        <div style="width:<? echo $width  ?>px; height:<? echo $height ?>px; overflow:hidden;">
       		<iframe src="http://player.vimeo.com/video/<? echo $clip_id ?>" width="<? echo $width  ?>" height="<? echo $height ?>" frameborder="0" webkitAllowFullScreen mozallowfullscreen allowFullScreen></iframe>
        </div>
    </body>
</html>