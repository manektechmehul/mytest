<div class='sidebox-body'>

{section name=news loop=$news}
<div class='sidebox-item'>	
<span class='sidebox-item-date'>
{$news[news].date|date_format:"%d/%m/%y"}</span><br />
<span class='sidebox-item-title'>{$news[news].title}</span> 
<span class='sidebox-item-text'>{$news[news].description} <a href="/{$news[news].link|escape:"html"}" class="sidebox-link">more</a></span></div>
{sectionelse}
<div class='sidebox-item'>	
{$no_news}
</div>
{/section}
</div>


<div class='sidebox-item'><a href='/news' class="sidebox-base-link">View all news </a> <a href='/rss/newsrss.xml' target='_blank'><img src="/images/rss.gif" alt='news rss feed' class='sidebox-rss'></a></div>
