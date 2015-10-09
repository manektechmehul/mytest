{*
todo: need to remove the alt Featured Case Study and class casestudyimage
*}
{assign var="listtype" value="Featured"}
<div id="sidebox{$videos_listtype}{$videos_listName}">
  <h3>{$videos_featured_title}</h3>
  {section loop=$videos_featured name=item }
  <div class="sidebox{$videos_listName}left"> 

{assign var="class" value="class=`$videos_listName`image"} 
    {assign var="alt" value="alt=`$videos_listtype`_`$videos_listName`"}    

    {*show_thumb filename=$videos_featured[item].thumb size='140x260' class=$videos_class alt=$videos_alt*}

    {if $videos_featured[item].video_type == "1" }<img src="http://i1.ytimg.com/vi/{$videos_featured[item].video_id}/mqdefault.jpg" width="270" height="180" alt=$videos_alt /> {/if}{if $videos_featured[item].video_type == "2" }<img src="{get_vimeo_thumb_url vimeo_id=$videos_featured[item].video_id }" width="270" height="180" alt=$videos_alt />{/if} </div>
  <div class="sidebox{$videos_listName}right">
    <h2><a href="/{$videos_module_web_path}/{$videos_featured[item].page_name}">{$videos_featured[item].title}</a></h2>
    <p>{$videos_featured[item].description}</p>
    <p class="csbutton"><a href="/{$videos_module_web_path}/{$videos_featured[item].page_name}">See the full {$videos_itemName}</a><a href="/{$videos_module_web_path}">View all {$videos_pluralName}</a></p>
  </div>
  <div class="clearfix"></div>
  {/section}</div>