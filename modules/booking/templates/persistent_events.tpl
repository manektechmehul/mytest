{assign var="listtype" value="persistent"}
                    <div class="title"> Other Past Events <a href="/events" title="View all events">View all events</a> </div>
                    <ul class="clearfix event-list">
{section loop=$peristent_events name=item }

                      <li>
                        <div class="thumb" style="background-image:url({show_thumb_minimal filename=$peristent_events[item].thumb size=200x200});"> <a href="/events/{$peristent_events[item].page_name}" title="{$peristent_events[item].title}">
                          <div class="date"> <span>{$peristent_events[item].start_date|format_date:"j"}</span> {$peristent_events[item].start_date|format_date:"M"} </div>
                          </a> </div>
                        <div class="thumb-info"> <a href="/events/{$peristent_events[item].page_name}" title="{$peristent_events[item].title}">{$peristent_events[item].title}</a>
                          <p>{$peristent_events[item].description|truncate:120:"...":true}</p>
                        </div>
                      </li>
{sectionelse}

                      <li>
                        <p style="text-align:center; padding:140px 30px;">There are currently no upcoming events</p>
                      </li>
{/section}