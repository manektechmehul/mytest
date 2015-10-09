       <div id="twitter-container">
       {section loop=$tweets name=item }
        <div class="twitter-feed">
          <div id="twitterusertimeline" class="tweets" >
            <div class="tweet">
              <p class="text">{$tweets[item].tweet}<br>
              <span class="time"><a target="_blank" href="{$tweets[item].status_link}">{$tweets[item].tweet_time}</a></span></p>
            </div>
          </div>
        </div>
        <p class="twitterbase"><a target="_blank" href="https://twitter.com{$tweets[item].tweet_url}">Follow us on twitter</a></p>     
      {/section}
      </div>