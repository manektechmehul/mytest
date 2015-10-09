{section name=item loop=$itemList}

  <div class="listitem">
    <div class="listitemleftholder">
      <div class="listitemleft" style="background-image:url({show_thumb_minimal filename=$itemList[item].thumb size='600x1000'})"></div>
    </div>
    <div class="listitemright">
      <p class="listitemdate">{if $itemList[item].end_date < "now"|date_format:'%Y-%m-%d'}Was held on {/if}{$itemList[item].start_date|format_date:"j F"}{if $itemList[item].start_date != $itemList[item].end_date} to {$itemList[item].end_date|format_date:"j F"}{/if}{$itemList[item].end_date|format_date:" Y"} {$itemList[item].event_time}</p>
      <h3>{$itemList[item].title}</h3>
      <h4>{$itemList[item].speaker_info}</h4>
      <p>{$itemList[item].description|truncate:240:"...":true}</p>
      <div class="eventtype eventtype{$itemList[item].category_id}">{$itemList[item].category_name}</div>
      {if $itemList[item].end_date < "now"|date_format:'%Y-%m-%d'}
        <p class="csbutton"><a href="/{$page_url_parts[0]}/{$itemList[item].page_name}">Find out what happened</a></p>
      {else}
        <p class="csbutton"><a href="/{$page_url_parts[0]}/{$itemList[item].page_name}">
        {if $itemList[item].hospice_event == 0 && $itemList[item].event_type == 1}Read more &amp; reserve{/if}
        {if $itemList[item].hospice_event == 0 && $itemList[item].event_type == 2}Read more &amp; register{/if}
        {if $itemList[item].hospice_event == 0 && $itemList[item].event_type == 3}Read more{/if}
        {if $itemList[item].hospice_event == 1}Find out more{/if}
        </a></p>
      {/if}
      <div class="clearfix"></div>
    </div>
  </div>

  {if $smarty.section.item.last}
     <div class="pagination-centered"><ul class="pagination">{$pagination}</ul></div>
  {/if}
{sectionelse}
    <p>There are currently no events in our diary, please check back soon.</p>
{/section}
