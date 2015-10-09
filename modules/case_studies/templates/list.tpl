<div class="listwrapper">
{assign var=number value=1}
{section name=item loop=$itemList}
<div class="col-md-4 col-sm-6">
    <div class="listitemtype listitemtype{$number++}">    
      <div class="listitemimagewrapper" style="background-image:url({show_thumb_minimal filename=$itemList[item].thumb size='600x1200'});">
        <h4>{$itemList[item].title}</h4>
      </div>
      <p class="csbutton"><a href="/{$page_url_parts[0]}/{$itemList[item].page_name}">Read case study</a></p>
      <p class="listsummary">{$itemList[item].description|truncate:120:"...":true}</p>
    </div>
</div>
{sectionelse}
    <p>There are currently no case studies, please check back soon.</p>
{/section}
</div>
<div class="clearfix"></div>
<p class="csbutton"><a href="/{$page_url_parts[0]}">View all case studies</a></p>