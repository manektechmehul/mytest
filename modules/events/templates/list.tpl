{section name=events loop=$events}
  <div class="listitem">
	<p class="listitemdate">{$events[events].startdate|format_date:"jS F"}
	{if $events[events].startdate != $events[events].enddate} to {$events[events].enddate|format_date:"jS F"}{/if}{$events[events].enddate|format_date:" Y"}</p>
    {if $events[events].thumbnail != '' && EVENTS_HAS_THUMBNAIL == 1}
                   <p><a href="{$events[events].link|escape:"html"}">{show_thumb filename=$events[events].thumbnail size='200x66' crop='crop' alt='alt="Events"'}</a></p>
          {/if}
    
	<h3><a href="{$events[events].link}">{$events[events].title}</a></h3>
	<p>{$events[events].summary}</p>
	<p class="csbutton"><a href="{$events[events].link}">Read more</a></p>
  </div>
    {if $smarty.section.events.last}
        <div id="pagination"><ul>{$pagination}</ul></div>
    {/if}
{sectionelse}
<p>There are currently no upcoming events.</p>
{/section}