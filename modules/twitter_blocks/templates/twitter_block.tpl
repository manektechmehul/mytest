<div class="twitter_block">
    <div class="twitter_block_banner_overlay"></div>
    <div class="twitter_block_footer_overlay"></div>
    <div class="twitter_block_name">{$name}</div>
    <div class="twitter_block_header" style="background:url(/UserFiles/Image/{$banner_image}) top left no-repeat;"></div>

     <div id="more_tweets">
     	<!-- <a href="#" onclick="prevTweet();" >Prev Tweet</a> -->
     	<a href="javascript:void(0);" onclick="nextTweet();" >next tweet</a>
     </div>
    <div  id="twitter_block_body"  class="twitter_block_body">
        <!-- start loop  -->
        {section name=all_tweets loop=$all_tweets}
        <!-- Debug: Twitter feed url
        {$all_tweets[all_tweets].feed}
        --><div class="twitter-container" id="feed{$smarty.section.all_tweets.index}" style="display:none;" >
        
             <span class="text"><div class="time"><div style="float:left;"><a href="{$all_tweets[all_tweets].author_link}" target="_blank">{$all_tweets[all_tweets].author}</a>  </div>{$all_tweets[all_tweets].pretty_time}</div>{$all_tweets[all_tweets].tweet_desc}</span>
             
           </div>
         {sectionelse}
        <p>There currently no tweets. Please check back soon!</p>
    {/section}
        <!-- end loop -->


          <div class="twitter-container" id="lastfeed" style="display:none;" >
                    <span class="text">Check out the rest of this feed <a href="{$feed}" target="_blank">@twitter</a>       </span>

           </div>



   </div>



</div><!-- end twitter_block -->