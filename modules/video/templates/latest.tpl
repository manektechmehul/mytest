{assign var="listtype" value="Latest"}
<div id="sidebox{$videos_listtype}{$videos_listName}">
    <h3>{$videos_latest_title}</h3>
    {section loop=$videos_latest name=item }
         <div class="sidebox{$videos_listName}left">
{assign var="class" value="class=`$videos_listName`image"} 
{assign var="alt" value="alt=`$videos_listtype`_`$videos_listName`"}
    {show_thumb filename=$videos_latest[item].thumb size='140x260' class=$videos_class alt=$videos_alt}
        </div>
        <div class="sidebox{$videos_listName}right">
          <h2><a href="/{$videos_module_web_path}/{$videos_latest[item].page_name}">{$videos_latest[item].title}</a></h2>
          <p>{$videos_latest[item].description}</p>
          <p class="csbutton"><a href="/{$videos_module_web_path}/{$videos_latest[item].page_name}">Read the full {$videos_itemName}</a><a href="/{$videos_module_web_path}">View all {$videos_pluralName}</a></p>
        </div>
        <div class="clearfix"></div>
    {/section}
</div>
