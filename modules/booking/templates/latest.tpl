{assign var="listtype" value="Latest"}
{section loop=$bookings_latest name=item }

        <div class="event-inner">
          <h3><a href="/events/{$bookings_latest[item].page_name}" title="{$bookings_latest[item].title}">{$bookings_latest[item].title}</a></h3>
          <h4>{if $bookings_latest[item].end_date < "now"|date_format:'%Y-%m-%d'}Was held on {/if}{$bookings_latest[item].start_date|format_date:"j F"}{if $bookings_latest[item].start_date != $bookings_latest[item].end_date} to {$bookings_latest[item].end_date|format_date:"j F"}{/if}{$bookings_latest[item].end_date|format_date:" Y"}</h4>
          <p>{$bookings_latest[item].description|truncate:120:"...":true} {if $bookings_latest[item].speaker_info == ""}{else}<span>{$bookings_latest[item].speaker_info}</span>{/if}</p>
        </div>
{sectionelse}

        <div class="event-inner">
          <h3>No upcoming events</h3>
          <p>There are currently no upcoming events, please check back soon.</p>
        </div>
{/section}