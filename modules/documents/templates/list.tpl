 
{literal}
    <script src="/js/audiojs/audio.js"></script>
    <script>
      audiojs.events.ready(function() {
        var as = audiojs.createAll();       
      });
      
      $(document).ready(function() {
           $(".video").colorbox();
      });
      
    </script>          
{/literal}

{section name=item loop=$documents}	
    
  {* --- DOWNLOAD ---- *}
  {if $documents[item].link_type == '2'}  
      
    <div class="listitem">
	<p class="listitemdate">{$documents[item].date|format_date:"jS F Y"}</p>
	<h3>{$documents[item].title}</h3>
    {if empty($documents[item].thumb)}
      <p><div class="downloadimage" >&nbsp; </div>{$documents[item].summary}</p>
    {else}
      <p><div class="downloadimage">{show_thumb filename=$documents[item].thumb size=100x100}</div>{$documents[item].summary}</p>
    {/if}
      
     
    {if $documents[item].audio == '1'}             
          <audio src="{$documents[item].file}" preload ></audio>
            {else}
          <p class="csbutton" style="float:left; padding-right:3px; margin-top:-1px;"><a href="{$documents[item].file}" target="_blank">Download file</a></p>
    {/if}
     
     
    {/if}
    
    
    
    
    
 

        
        
       
{if $documents[item].link_type == '3'}
        <!-- Link -->
       
        <a{if $documents[item].external_link == '1' } target="_blank"{/if} href="{check_link link=`$documents[item].download_link` external=`$documents[item].external_link`}" class="associatedlistitem">
          {if $documents[item].thumb != ''}<img src="{get_thumb_for_background filename=$documents[item].thumb size=27}" alt="{$documents[item].title}" />
{/if}
          <div class="associatedlisttext">
            <h3>{$documents[item].title}</h3>
            {$documents[item].summary}
          </div>
        </a>{/if}

        
        
        
{if $documents[item].link_type == '4'}
        <!-- Video -->
        <a class="video associatedlistitem" {if $documents[item].video_type == "1" }href="/php/video_handler/youtube.php?height=450&width=800&clip_id={$documents[item].video_id}"{/if}{if $documents[item].video_type == "2" }href="/php/video_handler/vimeo.php?height=450&width=800&clip_id={$documents[item].video_id}"{/if}>
          {if $documents[item].video_type == "1" }<img src="http://i1.ytimg.com/vi/{$documents[item].video_id}/mqdefault.jpg" width="200" height="113" alt="{$documents[item].title}" />
{/if}
          {if $documents[item].video_type == "2" }<img src="{get_vimeo_thumb_url vimeo_id=$documents[item].video_id }" width="200" height="113" alt="{$documents[item].title}" />
{/if}
          <div class="associatedlisttext">
            <h3>{$documents[item].title}</h3>
            {$documents[item].summary}
          </div>
        </a>{/if}
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    <div class="clearfix"></div>
  </div>
  
  {if $smarty.section.item.last}
	<!--<div id="casearticlebase"><a href="{$member_module_root}/all">List all downloads</a></div>-->
    <div id="casearticlebase">{$pagination}</div>
  {/if}
  
{sectionelse}
<p>There are currently no downloads in this section.</p>
<p class="csbutton"><a href="{$member_module_root}/all">View all downloads</a>.</p>
{/section}