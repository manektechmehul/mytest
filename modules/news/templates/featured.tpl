
{section name=news loop=$sidebox_news_f}
                  <div class="img-block"{if $sidebox_news_f[news].thumbnail != '' && $NEWS_HAS_THUMBNAIL == 1} style="background-image:url({show_thumb_minimal filename=$sidebox_news_f[news].thumbnail size=600x600});"{/if}>
                    <a href="{$sidebox_news_f[news].link|escape:"html"}" title="Celebrating our work">
                      <div class="leaf-wrapper">
                        <p>Featured News</p>
                      </div>
                      <div class="caption">{$sidebox_news_f[news].title}</div>
                      <div class="date"> <span>{$sidebox_news_f[news].date|format_date:"j"}</span> {$sidebox_news_f[news].date|format_date:"M"} </div>
                      </a>
                  </div>
                  <p>{$sidebox_news_f[news].summary} <a href="{$sidebox_news_f[news].link|escape:"html"}" title="Read more">Read more</a></p>
{/section}