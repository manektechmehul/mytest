{assign var="listtype" value="Latest"}
<div id="sidebox{$faqs_listtype}{$faqs_listName}">
    <h3>{$faqs_latest_title}</h3>
    {section loop=$faqs_latest name=item }
         <div class="sidebox{$faqs_listName}left">
{assign var="class" value="class=`$faqs_listName`image"} 
{assign var="alt" value="alt=`$faqs_listtype`_`$faqs_listName`"}
    {show_thumb filename=$faqs_latest[item].thumb size='140x260' class=$faqs_class alt=$faqs_alt}
        </div>
        <div class="sidebox{$faqs_listName}right">
          <h2><a href="/{$faqs_module_web_path}/{$faqs_latest[item].page_name}">{$faqs_latest[item].title}</a></h2>
          <p>{$faqs_latest[item].description}</p>
          <p class="csbutton"><a href="/{$faqs_module_web_path}/{$faqs_latest[item].page_name}">Read the full {$faqs_itemName}</a><a href="/{$faqs_module_web_path}">View all {$faqs_pluralName}</a></p>
        </div>
        <div class="clearfix"></div>
    {/section}
</div>
