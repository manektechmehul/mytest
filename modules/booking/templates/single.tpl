{*<div class="socialmediafloat socialmediafloatbooking">
    {facebook link="`$site_address``$pageName`" text=$singleitem.title image="/images/icon-t-facebook.png" class="socialmedia"}
    {twitter link="`$site_address``$pageName`" image="/images/icon-t-twitter.png" class="socialmedia"}
    {linkedin link="`$site_address``$pageName`" image="/images/icon-t-linkedin.png" class="socialmedia"}
    {googlep link="`$site_address``$pageName`" image="/images/icon-t-googleplus.png" class="socialmedia"}
</div>*}
<div class="eventsinglecat">
  <div class="eventtype eventtype{$singleitem.category_id}">{$singleitem.category_name}</div>
</div>
<h4 style="margin-top:0; color:#498A60;">
  {if $singleitem.start_date == $singleitem.end_date }
    {$singleitem.start_date|format_date:"j F Y"}
  {else}
    {$singleitem.start_date|format_date:"j F"} to {$singleitem.end_date|format_date:"j F Y"}
  {/if}
  {$singleitem.event_time}
</h4>

<div class="{$listName}singleitem">
  <div class="eventtypeimg">
    <div class="eventtypeimgplacement {if $singleitem.hospice_event == 0}eventtypeimghospice{else}eventtypeimgcommunity{/if}"></div>
    <img border="0" class="halfwidthright" alt="{$singleitem.title}" src="{show_thumb_minimal filename=$singleitem.thumb size="600x1200"}">
  </div>
  <h3 style="margin-top:0;">{$singleitem.speaker_info}</h3>
  {$singleitem.body}
  <div class="clearfix"></div>
</div>

<form id="basket" action="" method="post">

{section name=item loop=$tickets}
  {if $smarty.section.item.first}
    <h2 style="margin-bottom:0;">
      {if $singleitem.hospice_event == 0 && $singleitem.event_type == 1}Reserve your place for this event{/if}
      {if $singleitem.hospice_event == 0 && $singleitem.event_type == 2}Register for this event{/if}
    </h2>
    <p>Please note you will need to checkout even if the tickets are free.</p>
  {/if}

  <div class="listitem">
    <div class="listitemfull">
      <h3>{$tickets[item].title}</h3>
      <p>{$tickets[item].description}</p>
      <p>Number required at <strong>&pound;{$tickets[item].price}</strong> each:
      <select class="bookingselect" name="ticket_id_{$tickets[item].id}">
          <option value="0">0</option>
          <option value="1">1</option>
          <option value="2">2</option>
          {*<option value="3">3</option>
          <option value="4">4</option>
          <option value="5">5</option>
          <option value="6">6</option>
          <option value="7">7</option>
          <option value="8">8</option>
          <option value="9">9</option>
          <option value="10">10</option>
          <option value="11">11</option>
          <option value="12">12</option>
          <option value="13">13</option>
          <option value="14">14</option>
          <option value="15">15</option>
          <option value="16">16</option>
          <option value="17">17</option>
          <option value="18">18</option>
          <option value="19">19</option>
          <option value="20">20</option>*}
      </select></p>
      <div class="clearfix"></div>
    </div>
  </div>
{/section}


{section name=item loop=$products}
  {if $smarty.section.item.first}
    <h2>Related products</h2>
  {/if}
  
  <div class="listitem">
    <div class="listitemleftholder">
      <div class="listitemleft" style="background-image:url({show_thumb_minimal filename=$products[item].thumb size='600x1000'})"></div>
    </div>
    <div class="listitemright">
      <h3>{$products[item].title}</h3>
      <p>{$products[item].description}</p>
      <p>Number required at <strong>&pound;{$products[item].price}</strong> each:
      <select class="bookingselect" name="product_id_{$products[item].id}">
          <option value="0">0</option>
          <option value="1">1</option>
          <option value="2">2</option>
          <option value="3">3</option>
          <option value="4">4</option>
          <option value="5">5</option>
          <option value="6">6</option>
          <option value="7">7</option>
          <option value="8">8</option>
          <option value="9">9</option>
          <option value="10">10</option>
          <option value="11">11</option>
          <option value="12">12</option>
          <option value="13">13</option>
          <option value="14">14</option>
          <option value="15">15</option>
          <option value="16">16</option>
          <option value="17">17</option>
          <option value="18">18</option>
          <option value="19">19</option>
          <option value="20">20</option>
      </select></p>
      <div class="clearfix"></div>
    </div>
  </div>
{/section}



{if $tickets || $products}
        <input class="formsubmit" type="submit" value="Add all items to basket">
        <input type="hidden" name="product_type" value="2" />
        <input type="hidden" name="submit_basket_add" value="submit_basket_add" />
{/if}
</form>





{if $singleitem.show_map == 1}
  <h2>{$singleitem.title} location</h2>
  <div id="map-canvas"></div>
{literal}
    <script src="https://maps.googleapis.com/maps/api/js"></script>
    <script>
var mapCanvas;
var map = null, marker = null;
        function initialize() {
            var mapCanvas = document.getElementById('map-canvas');

            var myLatlng = new google.maps.LatLng({/literal}{$singleitem.lat},{$singleitem.lon}{literal});

            var mapOptions = {
                center: myLatlng,
                zoom: 14,
                mapTypeId: google.maps.MapTypeId.ROADMAP
            };
            var map = new google.maps.Map(mapCanvas, mapOptions);

            {/literal}
            {if  $singleitem.hospice_event == '0'}
            var map_pin = "/images/map/pin_hospice.png"; // hospice event
            {/if}
            {if  $singleitem.hospice_event == '1'}
            var map_pin = "/images/map/pin_hospice.png"; // community event
            {/if}
            {literal}
            var marker = new google.maps.Marker({
                position: myLatlng,
                map: map,
                icon: map_pin,
                title: '{/literal}{$singleitem.title|escape}{literal}'
            });
            map.setCenter(myLatlng.getCenter());
        }
        google.maps.event.addDomListener(window, 'load', initialize);
    </script>
{/literal}

{/if}

<p class="csbutton">
    <a href="{$button_link}">View all upcoming events</a>
</p>