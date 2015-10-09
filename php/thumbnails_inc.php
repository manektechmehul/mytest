<?php
function show_thumb($filename, $size, $onclick='', $alt='', $class='', $background='', $crop=0, $missing='', $title='')
{
	$thumb_filename = get_thumb_address($filename, $size, $background, $crop, $missing);
	$options = trim("$onclick $alt $class $title");
	return "<img src=\"$thumb_filename\" $options />";
}

function get_thumb_for_background($filename, $size){
	// get the required thumb, and create a cached thumb for the image
	$thumb_filename = get_thumb_address($filename, $size);
	return $thumb_filename;
}


function get_thumb_address($filename, $size, $background='', $crop=0, $missing='')
{
    $base_path = $_SERVER['DOCUMENT_ROOT'];
	
    if (($filename == '') || (!file_exists($base_path.'/UserFiles/Image/'.$filename)))
    {
        if (($missing) && file_exists($base_path.$missing))
            $filename = $missing;
        //else
        //    $filename = '/cmsimages/missing-image.png';
            
        return $filename;       
    }
    else
    {
        $thumb_filename = '/UserFiles/Thumbnails/'.$filename;
        $filename = '/UserFiles/Image/'.trim($filename);

		if (is_int($size))
		{
			$extra = '_size'.$size;
			$tag = "&size=$size";

		}
		else
		{
			list($width, $height) = explode('x', $size);
			$extra = '_h'.$height.'_w'.$width;
			$tag = '&height='.$height.'&width='.$width;
		}

        if ($background)
        {
            $extra .= '_back';
            $tag .= "&background=$background";
        }
        if ($crop)
        {
            $extra .= '_crop';
            $tag .= "&crop=$crop";
        }

        $thumb_filename = preg_replace('/\..{3}$/', $extra.'.jpg', $thumb_filename);
	    
	    if (file_exists('.'.$thumb_filename)) 
		    return $thumb_filename;
	    else
		    return "/php/thumbimage.php?img=$filename$tag";
    }
}


function show_thumb_minimal($filename, $size, $crop=0){
	$thumb_filename = get_thumb_address($filename, $size, $background, $crop, $missing);
	$options = trim("$onclick $alt $class $title");
	return $thumb_filename;
}

function smarty_show_thumb_minimal ($params, &$smarty){
    echo show_thumb_minimal($params['filename'], $params['size'], $params['crop']);
}


function smarty_show_thumb($params, &$smarty)
{
    echo show_thumb($params['filename'], $params['size'], $params['onclick'], $params['alt'], $params['class'], $params['background'], $params['crop'], $params['missing'], $params['title']);
}





function smarty_get_thumb_for_background($params, &$smarty){
    echo get_thumb_for_background($params['filename'], $params['size']);
}

// from here down for base please //// 

function get_vimeo_thumb_url($params){
	$id = $params['vimeo_id'];
	$property = 'thumbnail_large';
	if (function_exists('curl_init')){ 	
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, "http://vimeo.com/api/v2/video/$id.php");
		curl_setopt($ch, CURLOPT_HEADER, 0);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_TIMEOUT, 10);
		$output = unserialize(curl_exec($ch));
	}else{
		die('CURL is not installed!');
	}
	
	return $output[0][$property];	
}

function smarty_get_vimeo_thumb_url($params, &$smarty){
	echo get_vimeo_thumb_url($params);
}

if ($smarty){
	$smarty->register_function("show_thumb_minimal", "smarty_show_thumb_minimal"); 
    $smarty->register_function("show_thumb", "smarty_show_thumb"); 
    $smarty->register_function("get_thumb_for_background", "smarty_get_thumb_for_background");
	$smarty->register_function("get_vimeo_thumb_url", "smarty_get_vimeo_thumb_url");

}


?>