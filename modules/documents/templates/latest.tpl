{literal}
    <script src="/js/audiojs/audio_small_player.js"></script>
    <script>
      audiojs.events.ready(function() {
        var as = audiojs.createAll();       
      });
    </script>          
{/literal}
 <h3><a href="/downloads">Latest Download</a></h3>
{section name=item loop=$documents}
    <div class="sideboxitem">
        <p class="sideboxitemdate">{$documents[item].date|format_date:"jS F Y"}</p>
        <h4><a href="/{$docs_module_url}">{$documents[item].title}</a></h4>
        <p>{if empty($documents[item].thumb)}{else}{show_thumb filename=$documents[item].thumb crop=crop size=200x80}<br />{/if}
        {$documents[item].summary}<br />
        <p><div id="audioplayer_{$documents[item].title}"></div></p>      
        {if $documents[item].audio == '1'}             
          <audio src="/UserFiles/File/{$documents[item].file}" preload></audio>
            {else}
          <a href="/UserFiles/File/{$documents[item].file}">Download file</a></p>
        {/if}
        <div class="clearfix"></div>
    </div>
{sectionelse}
    <div class="sideboxitem">
        <h4 class="newseventsbase"><a href="/{$docs_module_url}">There are currently no downloads available</a></h4>
    </div>
{/section}
<div class="sideboxitem">
    <h4 class="newseventsbase"><a href="/{$docs_module_url}">View all downloads</a></h4>
</div>

