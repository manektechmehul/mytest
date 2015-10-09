{assign var="listtype" value="Latest"}
<div id="sidebox{$profiles_listtype}{$profiles_listName}">
    <h3>{$profiles_latest_title}</h3>
    {section loop=$profiles_latest name=item }
         <div class="sidebox{$profiles_listName}left">
{assign var="class" value="class=`$profiles_listName`image"} 
{assign var="alt" value="alt=`$profiles_listtype`_`$profiles_listName`"}
    {show_thumb filename=$profiles_latest[item].thumb size='140x260' class=$profiles_class alt=$profiles_alt}
        </div>
        <div class="sidebox{$profiles_listName}right">
          <h2><a href="/{$profiles_module_web_path}/{$profiles_latest[item].page_name}">{$profiles_latest[item].title} {$profiles_latest[item].firstname} {$profiles_latest[item].surname}</a></h2>
          <p>{$profiles_latest[item].description|truncate:300:"...":true}</p>
          <p class="csbutton"><a href="/{$profiles_module_web_path}/{$profiles_latest[item].page_name}">Read the full {$profiles_itemName}</a><a href="/{$profiles_module_web_path}">View all {$profiles_pluralName}</a></p>
        </div>
        <div class="clearfix"></div>
    {/section}
</div>
