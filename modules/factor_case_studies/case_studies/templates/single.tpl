<div id="socialmediafloat">
    {googlep link="`$site_address``$pageName`" image="/images/share-single-g.gif" class="socialmedia"}
    {linkedin link="`$site_address``$pageName`" image="/images/share-single-li.gif" class="socialmedia"}
    {twitter link="`$site_address``$pageName`" image="/images/share-single-tw.gif" class="socialmedia"}
    {facebook link="`$site_address``$pageName`" text=$singleitem.title image="/images/share-single-fb.gif" class="socialmedia"}
</div>




<div class="{$listName}singleitem">{show_thumb filename=$singleitem.thumb size='300x700' alt='alt="'$singleitem.title'" class="right" border="0"'}{$singleitem.body}</div>


{section name=item loop=$factors_links}
    <p class="csbutton">  <a href="/factor/{$factors_links[item].page_name}" >   Back to factor `{$factors_links[item].title}` </a> 

</p>


{/section}
<p class="csbutton"><a     href='{$button_link}'   >{$button_text}</a>
</p>

