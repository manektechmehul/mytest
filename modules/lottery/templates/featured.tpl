{*
todo: need to remove the alt Featured Case Study and class casestudyimage
*}
{assign var="listtype" value="Featured"}
<div id="sidebox{$lottery_listtype}{$lottery_listName}">
    <h3>{$lottery_featured_title}</h3>
    {section loop=$lottery_featured name=item }
         <div class="sidebox{$lottery_listName}left"> 
{assign var="class" value="class=`$lottery_listName`image"} 
{assign var="alt" value="alt=`$lottery_listtype`_`$lottery_listName`"}
    {show_thumb filename=$lottery_featured[item].thumb size='140x260' class=$lottery_class alt=$lottery_alt}
        </div>
        <div class="sidebox{$lottery_listName}right">
          <h2><a href="/{$lottery_module_web_path}/{$lottery_featured[item].page_name}">{$lottery_featured[item].title}</a></h2>
          <p>{$lottery_featured[item].description}</p>
          <p class="csbutton"><a href="/{$lottery_module_web_path}/{$lottery_featured[item].page_name}">Read the full {$lottery_itemName}</a><a href="/{$lottery_module_web_path}">View all {$lottery_pluralName}</a></p>
        </div>
        <div class="clearfix"></div>
    {/section}
      </div>