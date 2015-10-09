<?php
include($basepath . 'modules/twitter/be/twitter.php');

$t = new cs_twitter();

$tweet_friendly_name =  TWITTER_FREINDLY_NAME;
$twitterUser = TWITTER_USERNAME; 
$count = TWITTER_ITEMS;
$inc_retweets = false; 

$args = array('screen_name' => $twitterUser, 'count' => $count, 'include_rts' => $inc_retweets);
$tweets  = $t->getUserTimeline($args);
//var_dump($tweets);

$tweet_url = "/" . $twitterUser;
$smarty->assign('tweets',$tweets);
$smarty->assign('tweet_friendly_name', $tweet_friendly_name);
$twitter_sidebox_template_file = "$base_path/modules/twitter/templates/twitter.tpl";
$filters['twitter'] = array ('search_string'  => '/<!-- CS Tweet Start -->.*<!-- CS Tweet End -->/s',
		'replace_string' => '{include file="'.$twitter_sidebox_template_file.'"}');
		
?>