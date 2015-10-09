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

<div class="associatedlist">
{section name=item loop=$sidebox_opl}

    <div class="sideboxitem sideboxitemsection">
        <div class="sideboxitemthumb{if empty($sidebox_opl[item].thumb)}{if $sidebox_opl[item].link_type == '4'} sideboxitemthumbpresence" style="background-image:url({if $sidebox_opl[item].video_type == "1" }http://i1.ytimg.com/vi/{$sidebox_opl[item].video_id}/maxresdefault.jpg);{/if}{if $sidebox_opl[item].video_type == "2" }{get_vimeo_thumb_url vimeo_id=$sidebox_opl[item].video_id });{/if}{/if}{else} sideboxitemthumbpresence{/if}"{if empty($sidebox_opl[item].thumb)}{else} style="background-image:url({show_thumb_minimal filename=$sidebox_opl[item].thumb size=800x1500});"{/if}{if $sidebox_opl[item].link_type == '1'}{if $sidebox_opl[item].cs_thumb != ''} style="background-image:url({show_thumb_minimal filename=$sidebox_opl[item].cs_thumb size=800x1500});"{/if}{/if}>
          <div class="sideboxitemoverlay {if $sidebox_opl[item].link_type == '1'}sideboxitemicon-case{/if}{if $sidebox_opl[item].link_type == '2'}{if $sidebox_opl[item].audio == '1'}sideboxitemicon-audio{else}sideboxitemicon-download{/if}{/if}{if $sidebox_opl[item].link_type == '3'}sideboxitemicon-link{/if}{if $sidebox_opl[item].link_type == '4'}sideboxitemicon-video{/if}{if $sidebox_opl[item].link_type == '5'}sideboxitemicon-static{/if}">
            <div class="sideboxiteminner">
                <p class="listitemdate">{$sidebox_opl[item].date|format_date:"jS F Y"}</p>
                <h4>{if $sidebox_opl[item].link_type == '1'}{$sidebox_opl[item].cs_title}{else}{$sidebox_opl[item].title}{/if}</h4>
                <p>{if $sidebox_opl[item].link_type == '1'}{$sidebox_opl[item].cs_desc}{else}{if $sidebox_opl[item].link_type == '5'}{$sidebox_opl[item].freetext}{else}{$sidebox_opl[item].summary}{/if}{/if}</p>
  {if $sidebox_opl[item].audio == '1' }               <div id="audioplayer_{$sidebox_opl[item].id}"></div>{/if}
                <p>{if $sidebox_opl[item].link_type == '5'}{else}<a {if $sidebox_opl[item].link_type == '1'}href="/stories/{$sidebox_opl[item].cs_page_name}">{/if}{if $sidebox_opl[item].link_type == '2'}{if $sidebox_opl[item].audio == '1'}target="_blank" href="{$sidebox_opl[item].file}">Download audio file{else}target="_blank" href="/UserFiles/File/{$sidebox_opl[item].file}">Download file{/if}{/if}{if $sidebox_opl[item].link_type == '3'}{if $sidebox_opl[item].external_link == '1' } target="_blank" {/if}href="{$sidebox_opl[item].link}">Visit link{/if}{if $sidebox_opl[item].link_type == '4'}{if $sidebox_opl[item].video_type == "1" }class="popup-youtube" target="_blank" href="http://www.youtube.com/watch?v={$sidebox_opl[item].video_id}"{/if}{if $sidebox_opl[item].video_type == "2" }class="popup-vimeo" target="_blank" href="https://vimeo.com/{$sidebox_opl[item].video_id}"{/if}>View the video{/if}{if $sidebox_opl[item].link_type == '1'}Read story{/if}</a>{/if}</p>
                {if $sidebox_opl[item].audio == '1'}<audio src="/UserFiles/File/{$sidebox_opl[item].file}"></audio>{/if}
  <div class="clearfix"></div>
            </div>
          </div>
        </div>
    </div>

{/section}

</div><!-- end of id="associatedlist" -->
{/if}