<div class="alphabetical">
<h3>Browse Alphabetically</h3>
<p>
    {foreach item=i from='A'|@range:'Z'}
        <a class="{if $smarty.get.alpha eq $i}alphahighlight{/if}" href="/member-directory/results?alpha={$i}" >{$i}</a>
    {/foreach}
    <a href="/member-directory">[ALL]</a>
</p>
</div>