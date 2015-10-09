<?php
include('modules/twitter_blocks/be/twitter.php');
// This needs testing
$t = new cs_twitter();
$tweetConfig = $t->getFeaturedSearchBlock();
$twitter_user_id = 'helenstrust';
$limit = $tweetConfig['no_of_items_to_display'];
$q=$tweetConfig['tags'];
$all_tweets = $t->doSearch($twitter_user_id, $limit, $q);

/* set tweet block header bits */
$smarty->assign('name',$tweetConfig['name']  ); 
$smarty->assign('banner_image',$tweetConfig['banner_image']); 
$smarty->assign('description',$tweetConfig['description']); 

$feed = $all_tweets[0]['feed'];
$smarty->assign('feed',$feed); 

/* array of the tweets from the search */ 
$smarty->assign('all_tweets',$all_tweets);

$twitter_block_sidebox_template_file = "$base_path/modules/twitter_blocks/templates/twitter_block.tpl";
$filters['twitter_block'] = array ('search_string'  => '/<!-- CS Tweet block Start -->.*<!-- CS Tweet block End -->/s',
		'replace_string' => '{include file="'.$twitter_block_sidebox_template_file.'"}');
?>