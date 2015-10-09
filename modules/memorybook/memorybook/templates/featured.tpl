{*
todo: need to remove the alt Featured Case Study and class casestudyimage
*}
{assign var="listtype" value="Featured"}
<div id="sidebox{$memories_listtype}{$memories_listName}">
    <h3>{$memories_featured_title}</h3>
    {section loop=$memories_featured name=item }
         <div class="sidebox{$memories_listName}left"> 
{assign var="class" value="class=`$memories_listName`image"} 
{assign var="alt" value="alt=`$memories_listtype`_`$memories_listName`"}
    {show_thumb filename=$memories_featured[item].thumb size='140x260' class=$memories_class alt=$memories_alt}
        </div>
        <div class="sidebox{$memories_listName}right">
          <h2><a href="/{$memories_module_web_path}/{$memories_featured[item].page_name}">{$memories_featured[item].title}</a></h2>
          <p>{$memories_featured[item].description}</p>
          <p class="csbutton"><a href="/{$memories_module_web_path}/{$memories_featured[item].page_name}">Read the full {$memories_itemName}</a><a href="/{$memories_module_web_path}">View all {$memories_pluralName}</a></p>
        </div>
        <div class="clearfix"></div>
    {/section}
      </div>