{$body}

<div id="linkspage">

{section name=links loop=$linksData}
{if $linksData[links].new_cat == true}
{if not $smarty.section.links.first}</ul>{/if}
<h2 class="sifr-title-small">{$linksData[links].title}</h2>
<div id="under-title"></div>
<ul>
{/if}
  <li>
	{show_thumb filename=$linksData[links].thumb size=20 alt='alt="'.$linksData[links].name.'"' class='class="left"'}
	<a href="{$linksData[links].link}" target="_blank">{$linksData[links].name}</a>
  <p>{$linksData[links].summary}</p>
</li>
{/section}
</ul>

</div>