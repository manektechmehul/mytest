<?
  //  $clip_id = 'ukJ2WXbrQds';
  //  $width = '800';
  //  $height = '450';

    $clip_id = $_GET['clip_id'];
    $width = $_GET['width'];
    $height = $_GET['height'];



?><iframe width="<? echo $width ?>" height="<? echo $height ?>" src="http://www.youtube.com/embed/<? echo $clip_id ?>" frameborder="0" allowfullscreen></iframe>