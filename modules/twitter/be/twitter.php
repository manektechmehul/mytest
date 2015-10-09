<?php

/*
  Relative Time Function
  based on code from http://stackoverflow.com/questions/11/how-do-i-calculate-relative-time/501415#501415
  For use in the "Parse Twitter Feeds" code below
 */
 
require_once('twitteroauth/twitteroauth.php');


class cs_twitter {
	
	var $connection;
	
	function cs_twitter(){
		$this->connection = new TwitterOAuth (TWIITER_CONSUMER_KEY, TWIITER_CONSUMER_SECRET, TWIITER_OAUTH_TOKEN, TWIITER_OAUTH_TOKEN_SECRET); 
		global $basepath;		
		// flush twitter cache when site cache is flushed
		if(isset($_GET['flush'])){				
				$dir = getcwd() . '/modules/twitter/twittercache/';								
				foreach (new DirectoryIterator($dir) as $fileInfo){								
					unlink($dir . $fileInfo->getFilename());					
				}
		}
	}


	function getUserTimeline($args){
	// args =  array('screen_name' => $twitterUser, 'count' => $count, 'include_rts' => $inc_retweets);
	
		$tweets_type = "statuses/user_timeline";	
		$interval = TWIITER_CACHE_TIME; // 600 = ten minutes
		$filename = str_replace(' ','-', $args['screen_name']);  
			   
		$cache_file = './modules/twitter/twittercache/' . $filename . '-twitter-usertime-cache';   					
		$statuses = $this->getfeed_cache($cache_file, $interval, $tweets_type, $args);				
		$feed = $args['screen_name'];
		
		foreach ($statuses  as $tweet) {			
		 		 	$tweet_desc = $this->makeLinks($tweet->text);				
					$tweet_time = strtotime($tweet->created_at);
					$pretty_time = $this->relativeTime($tweet_time); // Render the tweet.                           
					$status_link = 'http://twitter.com/' . $twitter_user_id; // . '/statuses/'; // . $id_help[2];
					$author_link = '/' . $tweet->user->screen_name;
					$author = $tweet->user->name;										 
					$all_tweets[] = array('tweet'=> $tweet_desc, 'tweet_time'=>$pretty_time, 'status_link'=>$status_link,'feed'=>$feed, 'author'=>$author, 'tweet_url'=>$author_link);	 		
		}		
		return $all_tweets;		
	}


	function doSearch($twitter_user_id, $limit, $q = '') {		
				
		$interval = TWIITER_CACHE_TIME; // 600 = ten minutes
		$filename = str_replace(' ','-', $q);     
		$cache_file = './modules/twitter_blocks/twittercache/' . $filename . '-twitter-cache';   
		$tweets_type="search/tweets";		
		$args = array('q' => $q, 'count' => $count);			
		$statuses = $this->getfeed_cache($cache_file, $interval, $tweets_type, $args);				
		$feed = 'https://twitter.com?q=' . $q;
		
		if($tweets_type=="search/tweets"){	
			foreach ($statuses  as $x) {	
				foreach($x as $tweet){
					$tweet_desc = $this->makeLinks($tweet->text);				
					$tweet_time = strtotime($tweet->created_at);
					$pretty_time = $this->relativeTime($tweet_time); // Render the tweet.                           
					$status_link = 'http://twitter.com/' . $twitter_user_id; // . '/statuses/'; // . $id_help[2];
					$author_link = 'https://twitter.com/' . $tweet->user->screen_name;
					$author = $tweet->user->name;										 
					$all_tweets[] = array('tweet_desc'=> $tweet_desc, 'pretty_time'=>$pretty_time, 'status_link'=>$status_link,'feed'=>$feed, 'author'=>$author, 'author_link'=>$author_link);	
				}	
			// don't read the second object - its the date and executiontime ect
			break 1;
			}
		}
		return $all_tweets;		
	}


	function getFeaturedSearchBlock(){        
        $sql = 'SELECT * FROM twitter_block WHERE featured = 1 ';        
        $result = mysql_query($sql);       
        $num_rows = mysql_num_rows($result);     
        
        $first = true;
        while ($row = mysql_fetch_array($result))
        {               
            $tweetConfig[] = array('name'=> $row['name'], 
                'banner_image' => $row['banner_image'],
                'tags' => $row['tags'],
                'description' => $row['description'],
                'no_of_items_to_display' => $row['no_of_items_to_display']                
                );       
        }
        if($num_rows == 0){
            $tweetConfig = false;
        }
        return $tweetConfig[0];
    }
	
	
	
	function getfeed_cache($cache_file, $interval, $tweets_type, $args){
		/*** Caching **/
			
		if (file_exists($cache_file)) {
            $last = filemtime($cache_file);
        } else {
            $last = 0;
        }
        $now = time();      		
	
		if (!$last || (( $now - $last ) > $interval)) {
			// cache file doesn't exist, or is old, so refresh it
			// GETTING FEED HERE 
            $cache_json = $this->connection->get($tweets_type, $args);
			if (!$cache_json) {
            // we didn't get anything back from twitter
            //  echo "<!-- ERROR: Twitter feed was blank! Using cache file. -->";
            } else {
            // we got good results from twitter
            // echo "<!-- SUCCESS: Twitter feed used to update cache file -->";
                $cache_static = fopen($cache_file, 'wb');
                fwrite($cache_static, serialize($cache_json));
                fclose($cache_static);
            }
// read from the cache file
            $statuses = @unserialize(file_get_contents($cache_file));
        } else {
// cache file is fresh enough, so read from it
//echo "<!-- SUCCESS: Cache file was recent enough to read from -->";
            $statuses = @unserialize(file_get_contents($cache_file));
        }
		/*************************************/
		
		return $statuses;
	}
	

	
	
	function makeLinks($tweet_desc){
	  $tweet_desc = preg_replace('/(https?:\/\/[^\s"<>]+)/', '<a href="$1">$1</a>', $tweet_desc);
	  $tweet_desc = preg_replace('/(^|[\n\s])@([^\s"\t\n\r<:]*)/is', '$1<a href="http://twitter.com/$2">@$2</a>', $tweet_desc);
	  $tweet_desc = preg_replace('/(^|[\n\s]).@([^\s"\t\n\r<:]*)/is', '$1<a href="http://twitter.com/$2">@$2</a>', $tweet_desc);
	  $tweet_desc = preg_replace('/(^|[\n\s])#([^\s"\t\n\r<:]*)/is', '$1<a href="http://twitter.com/search?q=%23$2">#$2</a>', $tweet_desc);							
	return $tweet_desc;

}
	
    function relativeTime($time) {

        define("SECOND", 1);
        define("MINUTE", 60 * SECOND);
        define("HOUR", 60 * MINUTE);
        define("DAY", 24 * HOUR);
        define("MONTH", 30 * DAY);

        $delta = strtotime('+1 seconds') - $time;

        if ($delta < 2 * MINUTE) {
            return "1 min ago";
        }
        if ($delta < 45 * MINUTE) {
            return floor($delta / MINUTE) . " mins ago";
        }
        if ($delta < 90 * MINUTE) {
            return "1 hour ago";
        }
        if ($delta < 24 * HOUR) {
            return floor($delta / HOUR) . " hours ago";
        }
        if ($delta < 48 * HOUR) {
            return "yesterday";
        }
        if ($delta < 30 * DAY) {
            return floor($delta / DAY) . " days ago";
        }
        if ($delta < 12 * MONTH) {
            $months = floor($delta / DAY / 30);
            return $months <= 1 ? "1 month ago" : $months . " months ago";
        } else {
            $years = floor($delta / DAY / 365);
            return $years <= 1 ? "1 year ago" : $years . " years ago";
        }
    }

    
}

// end class 
?>