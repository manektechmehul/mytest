<div class="notice">
	<div>
	<p><span class="noticeTitle">Title:</span><br />
	<span class="noticeValue">{$emailNotice.title}</span></p>
	</div>
	<div>
	<p><span class="noticeTitle">Date:</span><br />
	<span class="noticeValue">{$emailNotice.date|format_date:"jS F Y"}</span></p>
	</div>
	<div>
	<p><span class="noticeTitle">Posted By:</span><br />
	<span class="noticeValue">{$emailNotice.memberScreenName}</span></p>
	</div>
	<div>
	<p><span class="noticeTitle">Description:</span><br />
	<span class="noticeValue">{$emailNotice.description|nl2br}</span></p>
	</div>
	{if $emailNotice.link}
	<div>
	<p><span class="noticeTitle">Link:</span><br />
	<span class="noticeValue"><a target="_blank" href="{$emailNotice.link}">{$emailNotice.linktitle}</a></span></p>
	</div>
	{/if}
</div>