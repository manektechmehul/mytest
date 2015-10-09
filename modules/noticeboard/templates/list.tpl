{*
	Copyright: Creative Stream & Jemeno
	Smarty template
*}
{$categoryItem.body}

<h2 class="noticestitle">Discussion topics</h2>
<div id="button-addnotice" onclick="addnotice()"><p><a href="#addnotice">Add a new topic</a></p></div>
<div class="clearfix"></div>

<div class="notices">
{section  name=notice loop=$notices}
    
<div class="notice">
	<h3 class="noticeTitle"><a href="{$noticeBoardBasePage}/notice/{$notices[notice].page_name}">{$notices[notice].title}</a></h3>
	<p class="noticeContent">{$notices[notice].description|nl2br}</p>
	{if $notices[notice].link}
    <p class="noticeContent">Web link: <a target="_blank" href="{$notices[notice].link}">{$notices[notice].linktitle}</a></p>
	{/if}
    <p class="noticeBottom">Posted by {$notices[notice].memberScreenName} on {$notices[notice].date|format_date:"jS F Y"} &nbsp;|&nbsp;
        <a href="{$noticeBoardBasePage}/notice/{$notices[notice].page_name}">{if $notices[notice].comment_count == 0} Click here to post a comment{else} There are {$notices[notice].comment_count} comments - Click here to post yours{/if}</a></p>
</div> 
{sectionelse}
<p>Sorry no posts yet - check back soon.<p> 
{/section}
</div>
