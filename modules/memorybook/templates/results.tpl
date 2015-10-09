<h1>Memories Search</h1>
<div class="listwrapper">
{assign var=number value=1}
{section name=item loop=$results_items}
<div class="col-md-4 col-sm-6">
    <div class="listitemtype listitemtype{$number++}">    
      <div class="listitemimagewrapper"{if empty($results_items[item].thumb)}{else} style="background-image:url({show_thumb_minimal filename=$results_items[item].thumb size='600x1200'});"{/if}>
        <h4>{$results_items[item].title} - {$results_items[item].description}</h4>
      </div>
      <p class="csbutton"><a href="/{$page_url_parts[0]}/{$itemList[item].page_name}">View Memory Book page</a></p>
      <p class="listsummary">&nbsp;</p>
    </div>
</div>
{if $smarty.section.item.last}
     <div class="pagination-centered"><ul class="pagination">{$pagination}</ul></div>
{/if}
{sectionelse}
<p>Sorry, no results found for your Memory Book search.</p>
{/section}
</div>
<div class="clearfix"></div>
<p class="csbutton"><a href="/memory-book">Search again</a><a href="/add-to-memory-book">Create a Memory Book page</a></p>