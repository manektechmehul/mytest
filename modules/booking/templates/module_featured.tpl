
<h2> Featured Events </h2>
{section name=item loop=$mfeature}
    <div class="listitem">
        <div class="listitemleftholder">
            <div class="listitemleft" style="background-image:url({show_thumb_minimal filename=$mfeature[item].thumb size='600x1000'})"></div>
        </div>
        <div class="listitemright">
            <p class="listitemdate">{if $mfeature[item].end_date < "now"|date_format:'%Y-%m-%d'}Was held on {/if}{$mfeature[item].start_date|format_date:"jS M"}{if $mfeature[item].start_date != $mfeature[item].end_date} to {$mfeature[item].end_date|format_date:"jS M"}{/if}{$mfeature[item].end_date|format_date:" Y"} {$mfeature[item].event_time}</p>
            <h3>{$mfeature[item].title}</h3>
            <p>{$mfeature[item].description|truncate:240:"...":true}</p>
            {if $mfeature[item].hospice_event == '0'}
                <div class="eventtype eventtypehospice">This is a hospice event</div>
            {else}
                <div class="eventtype eventtypecommunity">This is a community event</div>
            {/if}
            {if $mfeature[item].end_date < "now"|date_format:'%Y-%m-%d'}
                <p class="csbutton"><a href="/{$page_url_parts[0]}/{$mfeature[item].page_name}">Find out what happened</a></p>
            {else}
                <p class="csbutton"><a href="/{$page_url_parts[0]}/{$mfeature[item].page_name}">
                        {if $mfeature[item].hospice_event == 0 && $mfeature[item].event_type == 1}Find out more and get tickets{/if}
                        {if $mfeature[item].hospice_event == 0 && $mfeature[item].event_type == 2}Find out more and register{/if}
                        {if $mfeature[item].hospice_event == 0 && $mfeature[item].event_type == 3}Find out more{/if}
                        {if $mfeature[item].hospice_event == 1}Find out more{/if}
                    </a></p>
            {/if}
            <div class="clearfix"></div>
        </div>
    </div>

     
    {sectionelse}
    <p>There are currently no events in our diary, please check back soon.</p>
{/section}
