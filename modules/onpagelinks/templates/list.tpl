{if $sidebox_opl}

{literal}
<link href="/css/magnific/magnific.css" rel="stylesheet" type="text/css">
<script src="/js/magnific.min.js"></script>
<script src="/js/audiojs/audio-custom.js"></script>
<script>
$(document).ready(function() {
	$('.popup-youtube, .popup-vimeo, .popup-gmaps').magnificPopup({
		disableOn: 700,
		type: 'iframe',
		mainClass: 'mfp-fade',
		removalDelay: 160,
		preloader: false,
		fixedContentPos: false
	});
});

audiojs.events.ready(function() {
	var as = audiojs.createAll();
});
</script>          
{/literal}

{section name=item loop=$sidebox_opl}

        <a title="{if $sidebox_opl[item].link_type == '1'}{$sidebox_opl[item].cs_title}{else}{$sidebox_opl[item].title}{/if}"
        
        {if $sidebox_opl[item].link_type == '1'}href="/stories/{$sidebox_opl[item].cs_page_name}"{/if}
        {if $sidebox_opl[item].link_type == '2'}{if $sidebox_opl[item].audio == '1'}href="javascript:void(0);"{else}target="_blank" href="/UserFiles/File/{$sidebox_opl[item].file}"{/if}{/if}
        {if $sidebox_opl[item].link_type == '3'}
          {if $sidebox_opl[item].external_link == '1' }target="_blank" {/if}href="{$sidebox_opl[item].link}"
        {/if}
        {if $sidebox_opl[item].link_type == '4'}
          {if $sidebox_opl[item].video_type == "1" }target="_blank" href="http://www.youtube.com/watch?v={$sidebox_opl[item].video_id}"{/if}
          {if $sidebox_opl[item].video_type == "2" }target="_blank" href="https://vimeo.com/{$sidebox_opl[item].video_id}"{/if}
        {/if}
        {if $sidebox_opl[item].link_type == '5'}href="javascript:void(0);"{/if}
        
        class="promo-holder
        {if $sidebox_opl[item].audio == '1' }audioholder {/if}
        {if $sidebox_opl[item].link_type == '4'}
          {if $sidebox_opl[item].video_type == "1" }popup-youtube {/if}
          {if $sidebox_opl[item].video_type == "2" }popup-vimeo {/if}
        {/if}
        {if empty($sidebox_opl[item].thumb)}
          {if $sidebox_opl[item].link_type == '2'}
            {if $sidebox_opl[item].audio == '1'}
              sideboxitemicon sideboxitemicon-audio
            {else}
              sideboxitemicon sideboxitemicon-download
            {/if}
          {/if}
          {if $sidebox_opl[item].link_type == '3'}sideboxitemicon sideboxitemicon-link{/if}
          {if $sidebox_opl[item].link_type == '5'}sideboxitemicon sideboxitemicon-static{/if}
        {/if}"
        
        {if !empty($sidebox_opl[item].thumb)}
          {if $sidebox_opl[item].link_type == '2'}style="background-image:url({show_thumb_minimal filename=$sidebox_opl[item].thumb size=600x1000});"{/if}
          {if $sidebox_opl[item].link_type == '3'}style="background-image:url({show_thumb_minimal filename=$sidebox_opl[item].thumb size=600x1000});"{/if}
          {if $sidebox_opl[item].link_type == '5'}style="background-image:url({show_thumb_minimal filename=$sidebox_opl[item].thumb size=600x1000});"{/if}
        {/if}
        {if $sidebox_opl[item].link_type == '1'}style="background-image:url({show_thumb_minimal filename=$sidebox_opl[item].cs_thumb size=600x1000});"{/if}
        {if $sidebox_opl[item].link_type == '4'}style="background-image:url(
          {if $sidebox_opl[item].video_type == "1" }http://i1.ytimg.com/vi/{$sidebox_opl[item].video_id}/maxresdefault.jpg);"{/if}
          {if $sidebox_opl[item].video_type == "2" }{get_vimeo_thumb_url vimeo_id=$sidebox_opl[item].video_id });"{/if}
        {/if}
        >
        
          <div class="caption-txt">
            <h3>{if $sidebox_opl[item].link_type == '1'}{$sidebox_opl[item].cs_title}{else}{$sidebox_opl[item].title}{/if}</h3>
            <p>{if $sidebox_opl[item].link_type == '1'}{$sidebox_opl[item].cs_desc|truncate:120:"...":true}{else}{if $sidebox_opl[item].link_type == '5'}{$sidebox_opl[item].freetext}{else}{$sidebox_opl[item].summary}{/if}{/if}</p>
            {if $sidebox_opl[item].audio == '1' }
              <div id="audioplayer_{$sidebox_opl[item].id}"></div>
              <audio src="/UserFiles/File/{$sidebox_opl[item].file}"></audio>
            {/if}
          </div>
        </a>
        {if $sidebox_opl[item].audio == '1' }<p class="audiodownload"><a target="_blank" href="/UserFiles/File/{$sidebox_opl[item].file}">Download audio file</a></p>{/if}

{/section}

{/if}