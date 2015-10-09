
{if $categoryImage <> ''}
<div style="background-image:url(/UserFiles/Image/{$categoryImage});" class="pageimageholder">
  <div style="background-image:url(/UserFiles/Image/{$categoryIconImage});" class="yourhealthiconoverlay"></div>
</div>
{/if}
 
<div class="listwrapper">
{assign var=number value=1}
{section name=item loop=$itemList}
<div class="col-md-4 col-sm-6">{assign var="alt" value="alt='`$itemList[item].title`'"}
    <div class="listitemtype listitemtype{$number++}">    
      <div class="listitemimagewrapper">
        <a href="/{$page_url_parts[0]}/{$itemList[item].page_name}">
          <div class="listitemoverlay">
            <div class="listitemoverlayicon" style="background-image:url(/UserFiles/Image/{$itemList[item].icon});"></div>
            <h2>{$itemList[item].title}</h2>
          </div>
          {show_thumb filename=$itemList[item].thumb crop='crop' size='400x225' alt=$alt border="0"}
        </a> 
      </div>
    </div>
</div>
        
{if $smarty.section.item.last}
    <div class="pagination-centered">
      <ul class="pagination">
        {$pagination}
      </ul>
    </div>
{/if}

{sectionelse}
<p>There are currently no health articles.</p>
{/section}
</div>
