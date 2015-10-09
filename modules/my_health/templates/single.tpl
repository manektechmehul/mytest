<div class="socialmediafloat">
    {googlep link="`$site_address``$pageName`" image="/images/share-single-g.gif" class="socialmedia"}
    {linkedin link="`$site_address``$pageName`" image="/images/share-single-li.gif" class="socialmedia"}
    {twitter link="`$site_address``$pageName`" image="/images/share-single-tw.gif" class="socialmedia"}
    {facebook link="`$site_address``$pageName`" text=$singleitem.title image="/images/share-single-fb.gif" class="socialmedia"}
</div>
<div class="{$listName}singleitem">{show_thumb filename=$singleitem.thumb size='500x1000' class='class="halfwidthright"' alt='alt="Your Health"'}{$singleitem.body}</div>
<p class="csbutton"><a {if $button_link == "do_js_back"} onclick="javascript:history.back(-1);" href="javascript:void(0);" {else}  href='{$button_link}' {/if} >{$button_text}</a></p>