{literal}
<script type="text/javascript" src="/js/audio-player.js"></script><!-- audio player -->
<script type="text/javascript">
    AudioPlayer.setup("/swf/player.swf", {
        width: 290
    });
</script>
{/literal}
{section name=item loop=$documents}	
  <div class="listitem">
	<p class="listitemdate">{$documents[item].date|format_date:"jS F Y"}</p>
	<h3>{$documents[item].title}</h3>
    {if empty($documents[item].thumb)}
      <p><div class="downloadimage" >&nbsp; </div>{$documents[item].summary}</p>
    {else}
      <p><div class="downloadimage">{show_thumb filename=$documents[item].thumb size=100x100}</div>{$documents[item].summary}</p>
    {/if}
    
	  <p class="csbutton" style="float:left; padding-right:3px; margin-top:-1px;"><a href="{$documents[item].file}" target="_blank">Download file</a></p>
	
    {if $documents[item].audio == '1'}
    <p><div id="audioplayer_{$documents[item].title}"></div></p>
    {literal}<script type="text/javascript">AudioPlayer.embed("audioplayer_{/literal}{$documents[item].title}{literal}", {soundFile: "{/literal}{$documents[item].file}{literal}", titles: "{/literal}{$documents[item].title}{literal}", animation: "no", bg: "d8d8d8", leftbg: "0093D0", rightbg: "0093D0", lefticon: "ffffff", righticon: "ffffff", rightbghover: "21ace6", righticonhover: "ffffff", loader: "0093D0", tracker: "cfe8f3", volslider: "cfe8f3"});</script>{/literal}
    {/if}
    
    <div class="clearfix"></div>
  </div>
  
  {if $smarty.section.item.last}
	<!--<div id="casearticlebase"><a href="{$member_module_root}/all">List all downloads</a></div>-->
    <div id="casearticlebase">{$pagination}</div>
  {/if}
  
{sectionelse}
<p>Sorry, no results found for your search.</p>
{/section}