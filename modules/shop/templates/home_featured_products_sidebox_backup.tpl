Home page featured shop items
{section name=f loop=$featured_items}
    <div>
        <h3>aaa<a href="#">{$featured_items[f].name} </a></h3>
        
        {show_thumb filename=$featured_items[f].thumb crop=crop size=64x64 alt="alt=\"thumbnail\" width=64 height=64"}
                
        <p>{assign var="point" value=$featured_items[f].price|strpos:"."}</p>
                <div class="pricebuttonleft">from <span class="pricebuttonlarge">&pound;{$featured_items[f].price|substr:0:$point}.</span><span class="pricebuttonmedium">{$featured_items[f].price|substr:$point+1}</span>
        </div>
    </p>
    <div class="pricebuttonright"><a href="/shop/product/{$featured_items[f].id}" >more</a></div>
</div>
{/section}