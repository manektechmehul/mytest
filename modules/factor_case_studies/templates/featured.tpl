{*
todo: need to remove the alt Featured Case Study and class casestudyimage
*}
{assign var="listtype" value="Featured"}
<div id="sidebox{$casestudies_listtype}{$casestudies_listName}">
    <h3>{$casestudies_featured_title}</h3>
    {section loop=$casestudies_featured name=item }
         <div class="sidebox{$casestudies_listName}left"> 
{assign var="class" value="class=`$casestudies_listName`image"} 
{assign var="alt" value="alt=`$casestudies_listtype`_`$casestudies_listName`"}
    {show_thumb filename=$casestudies_featured[item].thumb size='140x260' class=$casestudies_class alt=$casestudies_alt}
        </div>
        <div class="sidebox{$casestudies_listName}right">
          <h2><a href="/{$casestudies_module_web_path}/{$casestudies_featured[item].page_name}">{$casestudies_featured[item].title}</a></h2>
          <p>{$casestudies_featured[item].description}</p>
          <p class="csbutton"><a href="/{$casestudies_module_web_path}/{$casestudies_featured[item].page_name}">Read the full {$casestudies_itemName}</a><a href="/{$casestudies_module_web_path}">View all {$casestudies_pluralName}</a></p>
        </div>
        <div class="clearfix"></div>
    {/section}
      </div>