<?php

function smarty_facebook($params, &$smarty)
{
	$link = $params['link'];
	$link .= (strpos($link, '?') !== false) ? '&fb' : '?fb';
        $link .= FACEBOOK_CACHE_NUMBER;
	$link = urlencode($link);
	$text = urlencode($params['text']);
	$class = (!empty($params['class'])) ? "class=\"{$params['class']}\"" : '';
	$target = (!empty($params['target'])) ? "target=\"{$params['target']}\"" : 'target="_blank"';
	$image = (!empty($params['image'])) ? $params['image'] : '/images/facebook.jpg';

	$tmplt = "<a $class $target href=\"http://www.facebook.com/sharer.php?u=$link&t=$text\"><img src=\"$image\"></a>";
	echo $tmplt;
}

function smarty_twitter($params, &$smarty)
{
	$link = urlencode($params['link']);
	$text = urlencode($params['text']);
	$class = (!empty($params['class'])) ? "class=\"{$params['class']}\"" : '';
	$target = (!empty($params['target'])) ? "target=\"{$params['target']}\"" : 'target="_blank"';
	$image = (!empty($params['image'])) ? $params['image'] : '/images/twitter.jpg';

	$tmplt = "<a $class $target href=\"http://twitter.com/share?url=$link\"><img src=\"$image\"></a>";
	echo $tmplt;
}

function smarty_linkedin($params, &$smarty)
{
	$link = urlencode($params['link']);
	$text = urlencode($params['text']);
	$class = (!empty($params['class'])) ? "class=\"{$params['class']}\"" : '';
	$target = (!empty($params['target'])) ? "target=\"{$params['target']}\"" : 'target="_blank"';
	$image = (!empty($params['image'])) ? $params['image'] : '/images/linkedin.jpg';

	$tmplt = "<a $class $target href=\"http://www.linkedin.com/shareArticle?mini=true&url=$link&title=$text\"><img src=\"$image\"></a>";
	//http://www.linkedin.com/shareArticle?mini=true&url={articleUrl}&title={articleTitle}&summary={articleSummary}&source={articleSource}
	echo $tmplt;
}

function smarty_googlep($params, &$smarty)
{
	$link = urlencode($params['link']);
	$text = urlencode($params['text']);
	$class = (!empty($params['class'])) ? "class=\"{$params['class']}\"" : '';
	$target = (!empty($params['target'])) ? "target=\"{$params['target']}\"" : 'target="_blank"';
	$image = (!empty($params['image'])) ? $params['image'] : '/images/linkedin.jpg';
	$tmplt = "<a $class $target href=\"https://plus.google.com/share?url=$link\"><img src=\"$image\"></a>";
	echo $tmplt;
}

if ($smarty) {
    $smarty->register_function("facebook", "smarty_facebook");
    $smarty->register_function("twitter", "smarty_twitter");
    $smarty->register_function("linkedin", "smarty_linkedin");
    $smarty->register_function("googlep", "smarty_googlep");
}