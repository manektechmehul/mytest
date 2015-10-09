{assign var="listtype" value="Latest"}
<div id="sidebox{$memories_listtype}{$memories_listName}">
    <h3>{$memories_latest_title}</h3>
    {section loop=$memories_latest name=item }
         <div class="sidebox{$memories_listName}left">
{assign var="class" value="class=`$memories_listName`image"} 
{assign var="alt" value="alt=`$memories_listtype`_`$memories_listName`"}
    {show_thumb filename=$memories_latest[item].thumb size='140x260' class=$memories_class alt=$memories_alt}
        </div>
        <div class="sidebox{$memories_listName}right">
          <h2><a href="/{$memories_module_web_path}/{$memories_latest[item].page_name}">{$memories_latest[item].title}</a></h2>
          <p>{$memories_latest[item].description}</p>
          <p class="csbutton"><a href="/{$memories_module_web_path}/{$memories_latest[item].page_name}">Read the full {$memories_itemName}</a><a href="/{$memories_module_web_path}">View all {$memories_pluralName}</a></p>
        </div>
        <div class="clearfix"></div>
    {/section}
</div>
