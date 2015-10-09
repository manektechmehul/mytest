{section name=news loop=$news}
  <div class="listitem">
    <div class="listitemleftholder">
      <div class="listitemleft" style="background-image:url({if $news[news].thumbnail == ""}/images/logonews.jpg{else}{show_thumb_minimal filename=$news[news].thumbnail size='600x1000'}{/if});{if $news[news].thumbnail == ""} background-size:contain; background-repeat:no-repeat; background-color:#413C5A;{else}{/if}"></div>
    </div>
    <div class="listitemright">
      <p class="listitemdate">{$news[news].date|format_date:"j F Y"}</p>
      <h3>{$news[news].title}</h3>
      <p>{$news[news].summary|truncate:240:"...":true}</p>
      <p class="csbutton"><a href="{$news[news].link}">Read more</a></p>
      <div class="clearfix"></div>
    </div>
  </div>
    {if $smarty.section.news.last}
         <div class="pagination-centered"><ul class="pagination">{$pagination}</ul></div>
    {/if}
    {sectionelse}
    {if $viewing_archive}
        <p>There are currently no archived news articles.</p>
    {else}
        <p>There are currently no news articles.</p>
    {/if}
{/section}
{if $viewing_archive}
    <p class="csbutton"><a href="/news">View current news</a></p>
{else}
    <p class="csbutton"><a href="/news/archive">View news archive</a></p>
{/if}