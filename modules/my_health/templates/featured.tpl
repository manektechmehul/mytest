{*
todo: need to remove the alt Featured Case Study and class casestudyimage
*}
{assign var="listtype" value="Featured"}
<div id="sidebox{$properties_listtype}{$properties_listName}">
    <h3>{$properties_featured_title}</h3>
    {section loop=$properties_featured name=item }
         <div class="sidebox{$properties_listName}left"> 
{assign var="class" value="class=`$properties_listName`image"} 
{assign var="alt" value="alt=`$properties_listtype`_`$properties_listName`"}
    {show_thumb filename=$properties_featured[item].thumb size='140x260' class=$properties_class alt=$properties_alt}
        </div>
        <div class="sidebox{$properties_listName}right">
          <h2><a href="/{$properties_module_web_path}/{$properties_featured[item].page_name}">{$properties_featured[item].title}</a></h2>
          <p>{$properties_featured[item].description}</p>
          <p class="csbutton"><a href="/{$properties_module_web_path}/{$properties_featured[item].page_name}">Read the full {$properties_itemName}</a><a href="/{$properties_module_web_path}">View all {$properties_pluralName}</a></p>
        </div>
        <div class="clearfix"></div>
    {/section}
      </div>