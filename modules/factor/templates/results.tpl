{section name=item loop=$results_items}	
  <div class="listitem">
    <p class="listitemdate">{$results_items[item].date|format_date:"jS F Y"}</p>
    <h3>{$results_items[item].title}</h3>        
    {if empty($results_items[item].thumb)}
      <p><div class="downloadimage" >&nbsp; </div>{$results_items[item].description}</p>
    {else}
      <p><div class="downloadimage">{show_thumb filename=$results_items[item].thumb size=100x100}</div>{$results_items[item].description}</p>
    {/if}    
    <p class="csbutton" style="float:left; padding-right:3px; margin-top:-1px;"><a href="{$results_items[item].page_name}" >See more</a></p>	       
    <div class="clearfix"></div>
  </div>  
  {if $smarty.section.item.last}
	<!--<div id="casearticlebase"><a href="{$member_module_root}/all">List all downloads</a></div>-->
    <div id="casearticlebase">{$pagination}</div>
  {/if}  
{sectionelse}
<p>Sorry, no results found for your search.</p>
{/section}