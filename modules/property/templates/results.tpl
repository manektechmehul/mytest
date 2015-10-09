{include file="$pr_filter"}
{section name=item loop=$results_items}	
  <div class="{$listName}listitem">
    <div class="{$listName}status status{$results_items[item].property_status_id}"></div>
    <div class="{$listName}left">
      {assign var="alt" value="alt='`$results_items[item].title`'"}
      <a href="/{$page_url_parts[0]}/{$results_items[item].page_name}">{show_thumb filename=$results_items[item].thumb size='180x600' alt=$alt border="0" class="class=left"'}</a>
    </div>
    <div class="{$listName}right">
      {*<h3><a href="/{$page_url_parts[0]}/{$results_items[item].page_name}">{$results_items[item].address}</a></h3>*}
      <a href="/{$page_url_parts[0]}/{$results_items[item].page_name}">{$results_items[item].address}</a>
      <!--<p>{$results_items[item].description}</p>-->
      <p>&pound;{$results_items[item].price} per week</p>
      <p>{$results_items[item].bedroom} bedrooms</p>
      <p class="csbutton">
          <!--<a href="/{$page_url_parts[0]}/{$results_items[item].page_name}?type={$smarty.get.type}&status={$smarty.get.status}&location={$smarty.get.location}" style="border-top-left-radius:0;">View property details</a>-->
          <a href="/{$page_url_parts[0]}/{$results_items[item].page_name}" style="border-top-left-radius:0;">View property details</a>
      </p>
	</div>
    <div class="clearfix"></div>
  </div>        
  {if $smarty.section.item.last}
    <div id="pagination">{$pagination}</div>
  {/if}  
{sectionelse}
    <p>Sorry, no results found for your search. Please try again and put more of the selected options to "Any".</p>
{/section}