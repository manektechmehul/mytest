{section name=events loop=$f_sidebox_events}

    <div class="sideboxitem">
        <p class="sideboxitemdate">{$f_sidebox_events[events].startdate|format_date:"jS F"}{if $f_sidebox_events[events].startdate != $f_sidebox_events[events].enddate} to {$f_sidebox_events[events].enddate|format_date:"jS F"}{/if}{$f_sidebox_events[events].enddate|format_date:" Y"}</p>
        <h4><a href="{$f_sidebox_events[events].link|escape:"html"}">{$f_sidebox_events[events].title}</a></h4>
        {if $f_sidebox_events[events].thumbnail != '' && $EVENTS_HAS_THUMBNAIL == 1}
            <p><a href="{$f_sidebox_events[events].link|escape:"html"}">{show_thumb filename=$f_sidebox_events[events].thumbnail size='200x66' crop='crop' alt='alt="Events"'}</a></p>
        {/if}
        <p>{$f_sidebox_events[events].summary} <a href="{$f_sidebox_events[events].link|escape:"html"}">Read more</a></p>
    </div>
    {sectionelse}

    <div class="sideboxitem">
        <h4>There are currently no upcoming events</h4>
    </div>
{/section}

<div class="sideboxbase">
    <h4 class="newseventsbase"><a href="/events">View all upcoming events</a></h4>
</div>
