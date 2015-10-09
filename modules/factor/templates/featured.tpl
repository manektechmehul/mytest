{*
todo: need to remove the alt Featured Case Study and class casestudyimage
*}
{assign var="listtype" value="Featured"}
<div id="sidebox{$factors_listtype}{$factors_listName}">
    <h3>{$factors_featured_title}</h3>
    {section loop=$factors_featured name=item }
         <div class="sidebox{$factors_listName}left"> 
{assign var="class" value="class=`$factors_listName`image"} 
{assign var="alt" value="alt=`$factors_listtype`_`$factors_listName`"}
    {show_thumb filename=$factors_featured[item].thumb size='140x260' class=$factors_class alt=$factors_alt}
        </div>
        <div class="sidebox{$factors_listName}right">
          <h2><a href="/{$factors_module_web_path}/{$factors_featured[item].page_name}">{$factors_featured[item].title}</a></h2>
          <p>{$factors_featured[item].description}</p>
          <p class="csbutton"><a href="/{$factors_module_web_path}/{$factors_featured[item].page_name}">Read the full {$factors_itemName}</a><a href="/{$factors_module_web_path}">View all {$factors_pluralName}</a></p>
        </div>
        <div class="clearfix"></div>
    {/section}
      </div>