{assign var="listtype" value="Latest"}
<div id="sidebox{$properties_listtype}{$properties_listName}">
    <h3>{$properties_latest_title}</h3>
    {section loop=$properties_latest name=item }
         <div class="sidebox{$properties_listName}left">
{assign var="class" value="class=`$properties_listName`image"} 
{assign var="alt" value="alt=`$properties_listtype`_`$properties_listName`"}
    {show_thumb filename=$properties_latest[item].thumb size='140x260' class=$properties_class alt=$properties_alt}
        </div>
        <div class="sidebox{$properties_listName}right">
          <h2><a href="/{$properties_module_web_path}/{$properties_latest[item].page_name}">{$properties_latest[item].title}</a></h2>
          <p>{$properties_latest[item].description}</p>
          <p class="csbutton"><a href="/{$properties_module_web_path}/{$properties_latest[item].page_name}">Read the full {$properties_itemName}</a><a href="/{$properties_module_web_path}">View all {$properties_pluralName}</a></p>
        </div>
        <div class="clearfix"></div>
    {/section}
</div>
