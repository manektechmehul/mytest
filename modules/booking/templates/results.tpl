{section name=item loop=$results_items}
  
  <div class="listitem">
    <div class="listitemleftholder">
      <div class="listitemleft" style="background-image:url({show_thumb_minimal filename=$results_items[item].thumb size='600x1000'})"></div>
    </div>
    <div class="listitemright">
      <p class="listitemdate">{if $results_items[item].end_date < "now"|date_format:'%Y-%m-%d'}Was held on {/if}{$results_items[item].start_date|format_date:"j F"}{if $results_items[item].start_date != $results_items[item].end_date} to {$results_items[item].end_date|format_date:"j F"}{/if}{$results_items[item].end_date|format_date:" Y"} {$results_items[item].event_time}</p>
      <h3>{$results_items[item].title}</h3>
      <h4>{$results_items[item].speaker_info}</h4>
      <p>{$results_items[item].description|truncate:240:"...":true}</p>
      <div class="eventtype eventtype{$results_items[item].category_id}">{$results_items[item].category_name}</div>
      {if $results_items[item].end_date < "now"|date_format:'%Y-%m-%d'}
        <p class="csbutton"><a href="/{$page_url_parts[0]}/{$results_items[item].page_name}">Find out what happened</a></p>
      {else}
        <p class="csbutton"><a href="/{$page_url_parts[0]}/{$results_items[item].page_name}">
        {if $results_items[item].hospice_event == 0 && $results_items[item].event_type == 1}Read more &amp; reserve{/if}
        {if $results_items[item].hospice_event == 0 && $results_items[item].event_type == 2}Read more &amp; register{/if}
        {if $results_items[item].hospice_event == 0 && $results_items[item].event_type == 3}Read more{/if}
        {if $results_items[item].hospice_event == 1}Find out more{/if}
        </a></p>
      {/if}
      <div class="clearfix"></div>
    </div>
  </div>
  
{sectionelse}
    <p>Sorry, no results found for your search.</p>
{/section}
