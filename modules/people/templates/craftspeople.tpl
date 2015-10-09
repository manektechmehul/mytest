
{section name='maker' loop=$makers}
    <div class='craft-thumb'>
        <img src="{$makers[maker].thumbnail}" alt="{$makers[maker].name}" class="content-left" />
    </div>
    <div class='craft-details'>
        <span class="article_header">{$makers[maker].name}</span><br />
        {$makers[maker].summary}<br />
     <div class="button-row"><a href="{$makers[maker].link}" class="button-green">Find out more</a></div> </td>
     </div>
{/section}         