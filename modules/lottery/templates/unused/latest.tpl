{assign var="listtype" value="Latest"}
<div id="sidebox{$lottery_listtype}{$lottery_listName}">
    <h3>{$lottery_latest_title}</h3>
    {section loop=$lottery_latest name=item }
         <div class="sidebox{$lottery_listName}left">
{assign var="class" value="class=`$lottery_listName`image"} 
{assign var="alt" value="alt=`$lottery_listtype`_`$lottery_listName`"}
    {show_thumb filename=$lottery_latest[item].thumb size='140x260' class=$lottery_class alt=$lottery_alt}
        </div>
        <div class="sidebox{$lottery_listName}right">
          <h2><a href="/{$lottery_module_web_path}/{$lottery_latest[item].page_name}">{$lottery_latest[item].title}</a></h2>
          <p>{$lottery_latest[item].description}</p>
          <p class="csbutton"><a href="/{$lottery_module_web_path}/{$lottery_latest[item].page_name}">Read the full {$lottery_itemName}</a><a href="/{$lottery_module_web_path}">View all {$lottery_pluralName}</a></p>
        </div>
        <div class="clearfix"></div>
    {/section}
</div>
