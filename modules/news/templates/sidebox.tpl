
                    <div class="title"> Other news <a href="#" title="View all news">View all news</a> </div>
                    <ul class="clearfix event-list">
{section name=news loop=$sidebox_news}

                      <li>
                        <div class="thumb" style="background-image:url({show_thumb_minimal filename=$sidebox_news[news].thumbnail size=200x200});"> <a href="{$sidebox_news[news].link|escape:"html"}" title="{$sidebox_news[news].title}">
                          <div class="date"> <span>{$sidebox_news[news].date|format_date:"j"}</span> {$sidebox_news[news].date|format_date:"M"} </div>
                          </a> </div>
                        <div class="thumb-info"> <a href="{$sidebox_news[news].link|escape:"html"}" title="{$sidebox_news[news].title}">{$sidebox_news[news].title}</a>
                          <p>{$sidebox_news[news].summary|truncate:120:"...":true}</p>
                        </div>
                      </li>
{sectionelse}

                      <li>
                        <p style="text-align:center; padding:140px 30px;">There are currently no news articles</p>
                      </li>
{/section}