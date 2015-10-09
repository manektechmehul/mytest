<div id="button-addnotice" onclick="addnotice()"><p><a href="#addnotice">Add a new topic</a></p></div>
{if $memberid == 0}
<h2 style="margin:20px 0 0 10px; font-size:22px; line-height:22px;"><a name="addnotice" ></a>Add a new topic</h2>
    <p style="margin:4px 0 0 10px;">Please login using the tab at the top of the page or <a href="/sign_up">sign up</a> if you want to post a topic.</p>
{else}

<h2 class="addnotice_title" style="margin:20px 0 0 10px; font-size:22px; line-height:22px;display:none;"><a name="addnotice" ></a>Add a new topic</h2>
<div class="addnotice" style="display:none;">

<form action="" method="post" class="noticeboardform">
	<div {if $dataError.title}class="error"{/if}>
	<label style="width:90px;" for="title">Topic title</label><input style="width:400px;" type="text" name="title" id="title" value="{$noticeBoardFormData.title}"/>
	{if $dataError.title{literal}<script>addnotice();</script>{/literal}}<span>{$errorMessage.title}</span>{/if}
	</div>
	<div {if $dataError.description}class="error"{/if}>
	<label style="width:90px;" for="description">Topic content</label><textarea style="width:590px; height:112px;" cols="40" name="description" id="description">{$noticeBoardFormData.description}</textarea>
	{if $dataError.description}{literal}<script>addnotice();</script>{/literal}<span>{$errorMessage.description}</span>{/if}
	</div>
	<div {if $dataError.linktitle}class="error"{/if}>
	<label style="width:90px;" for="linktitle">Web link title</label><input style="width:400px;" type="text" name="linktitle" id="linktitle" value="{$noticeBoardFormData.linktitle}"/> (optional)
	{if $dataError.linktitle}{literal}<script>addnotice();</script>{/literal}<span>{$errorMessage.linktitle}</span>{/if}
	</div>
	<div {if $dataError.link}class="error"{/if}>
	<label style="width:90px;" for="link">Web link</label><input style="width:400px;" type="text" name="link" id="link" value="{$noticeBoardFormData.link}" /> (optional)<br /><span style="font-size:11px; padding-left:90px;"> Include the http:// prefix, for example: http://www.youttube.com/</span>
	{if $dataError.link}{literal}<script>addnotice();</script>{/literal}<span>{$errorMessage.link}</span>{/if}
	</div>
    {*
	<div style="clear:both;">
		<label style="width:90px;" for="category">Page to post</label>
		{html_options name=category options=$noticeBoardCategories selected=$noticeBoardCurrentCategory }
	</div>
    *}
	<input type="hidden" name="category" value="{$noticeBoardCurrentCategory}" />
	<input type="hidden" name="action" value="add notice" />
	<div>
	<label style="width:90px;" for="send">&nbsp;</label><input type="submit" class="addnoticebutton" name="send" id="send" value="Add Topic" />
	</div>
</form>
</div>
    {/if}