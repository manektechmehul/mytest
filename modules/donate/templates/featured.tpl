{*
todo: need to remove the alt Featured Case Study and class casestudyimage
*}
{assign var="listtype" value="Featured"}
<div id="sidebox{$[[PLURALLOWER]]_listtype}{$[[PLURALLOWER]]_listName}">
    <h3>{$[[PLURALLOWER]]_featured_title}</h3>
    {section loop=$[[PLURALLOWER]]_featured name=item }
         <div class="sidebox{$[[PLURALLOWER]]_listName}left"> 
{assign var="class" value="class=`$[[PLURALLOWER]]_listName`image"} 
{assign var="alt" value="alt=`$[[PLURALLOWER]]_listtype`_`$[[PLURALLOWER]]_listName`"}
    {show_thumb filename=$[[PLURALLOWER]]_featured[item].thumb size='140x260' class=$[[PLURALLOWER]]_class alt=$[[PLURALLOWER]]_alt}
        </div>
        <div class="sidebox{$[[PLURALLOWER]]_listName}right">
          <h2><a href="/{$[[PLURALLOWER]]_module_web_path}/{$[[PLURALLOWER]]_featured[item].page_name}">{$[[PLURALLOWER]]_featured[item].title}</a></h2>
          <p>{$[[PLURALLOWER]]_featured[item].description}</p>
          <p class="csbutton"><a href="/{$[[PLURALLOWER]]_module_web_path}/{$[[PLURALLOWER]]_featured[item].page_name}">Read the full {$[[PLURALLOWER]]_itemName}</a><a href="/{$[[PLURALLOWER]]_module_web_path}">View all {$[[PLURALLOWER]]_pluralName}</a></p>
        </div>
        <div class="clearfix"></div>
    {/section}
      </div>