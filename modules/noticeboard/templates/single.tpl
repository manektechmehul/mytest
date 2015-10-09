{*
	Copyright (c): Creative Stream & Jemeno
	Smarty template
*}

<div class="notices">

<div class="notice">
	<h3 class="noticeTitle">{$notice.title}</h3>
	<p class="noticeContent">{$notice.description|nl2br}</p>
	{if $notice.link}
    <p class="noticeContent">Web link: <a target="_blank" href="{$notice.link}">{$notice.linktitle}</a></p>
	{/if}
    <p class="noticeBottom">Posted by {$notice.memberScreenName} on {$notice.date|format_date:"jS F Y"}</p>
</div> 

{if !empty($notice.comments)}
    <h2 style="margin:20px 0 -10px 10px; font-size:22px; line-height:22px;">Comments</h2>
    {assign var='comments' value=$notice.comments}
    {section  name=comments loop=$comments}
        {if $comments[comments].parent == 0}
            <div class="notice_comment">
        {else}
            <div class="notice_comment_reply">
        {/if}
        <p class="commentP">{$comments[comments].text}</p>
        <p class="commentBottom">Posted by {$comments[comments].screenname} on {$comments[comments].datestamp|format_date:"jS F Y"}
        {if $comments[comments].parent == 0}
            <a class="commentReply" href="?replyto={$comments[comments].id}" val="{$comments[comments].id}">  {if $memberid != ''}&nbsp;|&nbsp; Reply to comment    {/if}</a>
        {/if}</p>
        </div>
    {/section}
{else}
    <p style="margin:4px 0 0 10px;">There are currently no comments - be the first to add one!<p>
{/if}

    <h2 style="margin:20px 0 0 10px; font-size:22px; line-height:22px;">Add a new comment</h2>
{if $memberid == 0}
    <p style="margin:4px 0 0 10px;">Please login using the tab at the top of the page or <a href="/sign_up">sign up</a> if you want to leave a comment.</p>
    {else}
    <p style="margin:0 0 0 10px; font-size:12px; line-height:16px;">Add a new comment using the box below. Use "Reply to comment" if you want to directly respond to one of the comments above.</p>
	<div class="addnotice">
    <form method="post" action=''>
        {* please leave span id=membername in as the javascript uses it! *}
	<label style="width:90px;" for="comment_name">Screen name</label><span id="membername">{$memberScreenName}</span><br />
	<div style="margin:10px 0;"><label style="width:90px;" for="comment">Comment</label><textarea name="comment" style="width:595px; height:112px;" cols="40"></textarea></div>
    <input type="hidden" name="action" value="comment"/>
    <input type="hidden" name="form_id" value="{$form_id}"/>
    <div style="margin:10px 0 0;"><label style="width:90px;" for="submit_comment">&nbsp;</label><input type="submit" name="submit_comment" value="Leave Comment" class="comment_button" /></div>
	</form>
    </div>
    {/if}
</div>