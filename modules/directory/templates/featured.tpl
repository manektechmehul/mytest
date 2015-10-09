{*
todo: need to remove the alt Featured Case Study and class casestudyimage
*}
{assign var="listtype" value="Featured"}
<div id="sidebox{$directories_listtype}{$directories_listName}">
    <h3>{$directories_featured_title}</h3>
    {section loop=$directories_featured name=item }
         <div class="sidebox{$directories_listName}left">
{assign var="class" value="class=`$directories_listName`image"}
{assign var="alt" value="alt=`$directories_listtype`_`$directories_listName`"}
    {show_thumb filename=$directories_featured[item].thumb size='140x260' class=$directories_class alt=$directories_alt}
        </div>
        <div class="sidebox{$directories_listName}right">
          <h2><a href="/{$directories_module_web_path}/{$directories_featured[item].page_name}">{$directories_featured[item].title}</a></h2>
          <p>{$directories_featured[item].description}</p>
          <p class="csbutton"><a href="/{$directories_module_web_path}/{$directories_featured[item].page_name}">Read the full {$directories_itemName}</a><a href="/{$directories_module_web_path}">View all {$directories_pluralName}</a></p>
        </div>
        <div class="clearfix"></div>
    {/section}
      </div>