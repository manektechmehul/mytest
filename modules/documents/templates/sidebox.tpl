{section name=documents loop=$sideboxdocuments}
{if $smarty.section.documents.first}
	<div id="sidebox-top">
		<div style="float: right;"><a href="/latest_documents" class="sidebox-link-small">View all</a> <a href="/rss/documentsrss.xml" target="_blank"><img src="/images/rss.gif" alt="Subscribe to RSS feed" align="absmiddle" style="padding-bottom: 2px;" /></a></div>
		Latest Documents
	</div>
	<div id="sidebox-documents">
{/if}
<div class='sidebox-item'>	
		<span class='sidebox-item-title'><a href="{$sideboxdocuments[documents].link|escape:"html"}" class="sidebox-link">{$sideboxdocuments[documents].title}</a></span><br />
		<span class='sidebox-item-summary'>{$sideboxdocuments[documents].summary}</span>
	</div>
{if $smarty.section.documents.last}</div>{/if}
{/section}