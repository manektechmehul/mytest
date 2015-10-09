
{section name=f loop=$featured_items}
    <div class="homefeatured">      
     
          {if $featured_items[f].thumb_preserve_toggle == '1'}
         <div class="homefeaturedleft" style="background-color: #{$featured_items[f].rgb}; border-radius:12px;" >
          <img src="/UserFiles/Image/{$featured_items[f].thumb_preserve_small}" alt="{$featured_items[f].name}" />
              
              {else}
           <div class="homefeaturedleft">
          <img src="{show_thumb_minimal filename=$featured_items[f].thumb crop=crop size=400x400}" alt="{$featured_items[f].name}" />
          {/if}
      
      
      </div>
      <div class="homefeaturedright">
        <h3><a href="/shop/product/{$featured_items[f].page_name}">What we're wearing</a></h3>
        {$featured_items[f].description}
        <p class="homefeaturedbase"><a href="/shop/product/{$featured_items[f].page_name}">See it in action &gt;</a></p>
      </div>
      <div class="clearfix"></div>
    </div>
{/section}

