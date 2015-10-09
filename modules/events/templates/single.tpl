<div class="socialmediafloat">
    {googlep link="`$site_address``$pageName`" image="/images/share-single-g.gif" class="socialmedia"}
    {linkedin link="`$site_address``$pageName`" image="/images/share-single-li.gif" class="socialmedia"}
    {twitter link="`$site_address``$pageName`" image="/images/share-single-tw.gif" class="socialmedia"}
    {facebook link="`$site_address``$pageName`" text=$event.title image="/images/share-single-fb.gif" class="socialmedia"}
</div>
<h3 class="singlelistitem">{$event.startdate|format_date:"jS F"}{if $event.startdate != $event.enddate} to {$event.enddate|format_date:"jS F"}{/if}{$event.startdate|format_date:" Y"}</h3>
{if $event.thumbnail != '' && EVENTS_HAS_THUMBNAIL == 1}
    {show_thumb filename=$event.thumbnail size='200x100' crop='crop' alt='alt="Events"' class='class="left"'}
{/if}
{*$event.summary*}
{$event.body}
<p class="csbutton"><a href="/{$page_url_parts.0}">View all events</a></p>