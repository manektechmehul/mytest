<h4 style="margin-top:0; color:#498A60;">{$newsitem.date|format_date:"j F Y"}</h4>
{*if $newsitem.thumbnail != '' && $NEWS_HAS_THUMBNAIL == 1 }
  {show_thumb filename=$newsitem.thumbnail size='600x1200' alt='alt="News"' class='class="halfwidthright"'}
{/if*}
{*$newsitem.summary*}
{$newsitem.body}
{*<div class="socialmediafloat">
    {facebook link="`$site_address``$pageName`" text=$singleitem.title image="/images/icon-t-facebook.png" class="socialmedia"}
    {twitter link="`$site_address``$pageName`" image="/images/icon-t-twitter.png" class="socialmedia"}
    {linkedin link="`$site_address``$pageName`" image="/images/icon-t-linkedin.png" class="socialmedia"}
    {googlep link="`$site_address``$pageName`" image="/images/icon-t-googleplus.png" class="socialmedia"}
</div>*}
{if $viewing_archive}
  <p class="csbutton"><a href="/news/archive">View news archive</a></p>
{else}
  <p class="csbutton"><a href="/news">View current news</a></p>
{/if}