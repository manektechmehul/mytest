{section name=events loop=$events}	

	<div class="events-item">
		<span class="sidebox-item-date">{$events[events].date|format_date:"jS F Y"}</span><br />
		<span class="sidebox-item-title"><a href="{$events[events].link|escape:"html"}" class="sidebox-link">{$events[events].title}</a></span>
	</div>
{sectionelse}
<div class='sidebox-item'>{$no_events}</div>
{/section}
<div id="events-base"><a href="/courses/all_dates" class="eventsbase-link" style="color: #fff;">View all upcoming courses</a></div>
