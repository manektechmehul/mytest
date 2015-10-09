
{section name=item loop=$itemList}
    <div class="{$listName}left">
        {assign var="alt" value="alt='`$itemList[item].title`'"}
      <a href="/{$page_url_parts[0]}/{$itemList[item].page_name}">{show_thumb filename=$itemList[item].thumb size='180x600' alt=$alt border="0" class="class=left"'}</a>
    </div>
    <div class="{$listName}right">
      <h2><a href="/{$page_url_parts[0]}/{$itemList[item].page_name}">{$itemList[item].title}</a></h2>
      <p>{$itemList[item].description}</p>
      <p class="csbutton"><a href="/{$page_url_parts[0]}/{$itemList[item].page_name}">Read more</a></p>
	</div>
        
  {if $smarty.section.item.last}
         <div id="casearticlebase">{$pagination}</div>
  {/if}
        
    <div class="clearfix"></div>
{sectionelse}
    <p>There are currently no {$pluralName}.</p>
{/section}
