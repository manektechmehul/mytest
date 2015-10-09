<div class='sidebox-body'>
{section name=events loop=$events}	
<div class='sidebox-item'>	
<span class='sidebox-item-date'>
{$events[events].date|date_format:"%d/%m/%y"}</span><br />
<span class='sidebox-item-title'>{$events[events].title}</span> 
<span class='sidebox-item-text'>{$events[events].description} <a href="{$events[events].link|escape:"html"}" class="sidebox-link">more</a></span></div>
{sectionelse}
<div class='sidebox-item'>	
{$no_events}
</div>
{/section}

<div class="sidebox-item">
<a class="sidebox-base-link" href="/events">View all upcoming events</a>
</div>
</div>