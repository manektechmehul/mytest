
{assign var="listtype" value="Featured"}
{section loop=$bookings_featured name=item }
                  <div class="img-block" style="background-image:url({show_thumb_minimal filename=$bookings_featured[item].thumb size=600x600});">
                    <a href="/{$bookings_module_web_path}/{$bookings_featured[item].page_name}" title="Celebrating our work">
                      <div class="leaf-wrapper">
                        <p>Featured Events</p>
                      </div>
                      <div class="caption">{$bookings_featured[item].title}</div>
                      <div class="date"> <span>{$bookings_featured[item].start_date|format_date:"j"}</span> {$bookings_featured[item].start_date|format_date:"M"} </div>
                      </a>
                  </div>
                  <p>{$bookings_featured[item].description} <a href="/{$bookings_module_web_path}/{$bookings_featured[item].page_name}" title="Read more">Read more</a></p>
{/section}