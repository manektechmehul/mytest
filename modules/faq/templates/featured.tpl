{*
todo: need to remove the alt Featured Case Study and class casestudyimage
*}
{assign var="listtype" value="Featured"}
<div id="sidebox{$faqs_listtype}{$faqs_listName}">
    <h3>{$faqs_featured_title}</h3>
    {section loop=$faqs_featured name=item }
         <div class="sidebox{$faqs_listName}left"> 
{assign var="class" value="class=`$faqs_listName`image"} 
{assign var="alt" value="alt=`$faqs_listtype`_`$faqs_listName`"}
    {show_thumb filename=$faqs_featured[item].thumb size='140x260' class=$faqs_class alt=$faqs_alt}
        </div>
        <div class="sidebox{$faqs_listName}right">
          <h2><a href="/{$faqs_module_web_path}/{$faqs_featured[item].page_name}">{$faqs_featured[item].title}</a></h2>
          <p>{$faqs_featured[item].description}</p>
          <p class="csbutton"><a href="/{$faqs_module_web_path}/{$faqs_featured[item].page_name}">Read the full {$faqs_itemName}</a><a href="/{$faqs_module_web_path}">View all {$faqs_pluralName}</a></p>
        </div>
        <div class="clearfix"></div>
    {/section}
      </div>