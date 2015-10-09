{section name=item loop=$itemList}
    <div class="listitemtype{cycle values="1,2"}">
  
    

    
    <div class="{$listName}right">
      <h2><a href="/{$page_url_parts[0]}/{$itemList[item].page_name}">{$itemList[item].title}</a></h2>  
      
          <a href="/{$page_url_parts[0]}/{$itemList[item].page_name}">{if $itemList[item].video_type == "1" }<img src="http://i1.ytimg.com/vi/{$itemList[item].video_id}/mqdefault.jpg" width="270" height="180" alt="{$itemList[item].title}" />
{/if}{if $itemList[item].video_type == "2" }<img src="{get_vimeo_thumb_url vimeo_id=$itemList[item].video_id }" width="270" height="180" alt="{$itemList[item].title}" />
{/if}</a> 
      
      <p>{$itemList[item].description}</p>
      <p class="csbutton"><a href="/{$page_url_parts[0]}/{$itemList[item].page_name}">Read more</a></p>
	</div>
    <div class="clearfix"></div>
</div>
{sectionelse}
    <p>There are currently no {$pluralName}.</p>
{/section}