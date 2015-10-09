{$home_body}

<h2>Featured Products</h2>

<div class="listwrapper">
  {section name=f loop=$shop_featured_items}
  
 
  {assign var="point" value=$shop_featured_items[f].price|strpos:"."}
        <div class="col-md-4 col-sm-6">{assign var="alt" value="alt='`$itemList[item].title`'"}
          <div class="listitemtype listitemtype{$number++}">
            <a class="listitemimagewrapper" href="/shop/product/{$shop_featured_items[f].page_name}">
            
            
         
            
            
            
              {if $shop_featured_items[f].thumb_preserve_toggle == '1'}
            
              <div class="listitemimage-surround" style="background-color:#{$shop_featured_items[f].rgb};">
                <div class="listitemimage" style="background-image:url(/UserFiles/Image/{$shop_featured_items[f].thumb_preserve_small});"></div>
              </div>
              
              {else}
              
              <div class="listitemimage-surround" style="background-color:#DD1313;">
                <div class="listitemimage" style="background-image:url({get_thumb_for_background filename=$shop_featured_items[f].thumb size=600x600});"></div>
              </div>
              
              {/if}
              
              
              
              
              <h3>{$shop_featured_items[f].name}</h3>
              <p>&pound;{$shop_featured_items[f].price|substr:0:$point}.{$shop_featured_items[f].price|substr:$point+1}</p>
            </a>
          </div>
        </div>
       
        
  {/section}
  
  <div class="clearfix"></div>
</div>