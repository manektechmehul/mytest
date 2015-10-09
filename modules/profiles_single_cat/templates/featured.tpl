{*
todo: need to remove the alt Featured Case Study and class casestudyimage
*}
{assign var="listtype" value="Featured"}
<div id="sidebox{$profiles_listtype}{$profiles_listName}">
    <h3>{$profiles_featured_title}</h3>
    {section loop=$profiles_featured name=item }
         <div class="sidebox{$profiles_listName}left"> 
{assign var="class" value="class=`$profiles_listName`image"} 
{assign var="alt" value="alt=`$profiles_listtype`_`$profiles_listName`"}
    {show_thumb filename=$profiles_featured[item].thumb size='140x260' class=$profiles_class alt=$profiles_alt}
        </div>
        <div class="sidebox{$profiles_listName}right">
          <h2><a href="/{$profiles_module_web_path}/{$profiles_featured[item].page_name}"> {$profiles_featured[item].title} {$profiles_featured[item].firstname} {$profiles_featured[item].surname}</a></h2>
          <p>{$profiles_featured[item].description|truncate:300:"...":true}</p>


          <p class="csbutton"><a href="/{$profiles_module_web_path}/{$profiles_featured[item].page_name}">Read the full {$profiles_itemName}</a><a href="/{$profiles_module_web_path}">View all {$profiles_pluralName}</a></p>
        </div>
        <div class="clearfix"></div>
    {/section}
      </div>