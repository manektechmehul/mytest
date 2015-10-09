{$category_body}
 
{*{if  !empty($products)}
<div class="clearfix"></div>
<p class="csbutton textgoright sortby"><span class="small">Sort by</span> <a href="{$sort_str}desc">&pound; high to low</a><a href="{$sort_str}asc">&pound; low to high</a></p>
{/if}*}
 


{if  !empty($products)}
<div class="listwrapper">
  {section name=products loop=$products}   
  {assign var=pid value=$products[products].id}
  {assign var="point" value=$products[products].price|string_format:"%.2f"|strpos:"."}   
       <div class="col-md-4 col-sm-6">{assign var="alt" value="alt='`$itemList[item].title`'"}
          <div class="listitemtype listitemtype{$number++}">
            <a class="listitemimagewrapper" href="{$products[products].link}">
            
            
             {if $products[products].thumb_preserve_toggle == '1'}                            
              <div class="listitemimage-surround" style="background-color:#{$products[products].rgb};">
              <div class="listitemimage" style="background-image:url(/UserFiles/Image/{$products[products].thumb_preserve_small});"></div>
             {else}
              <div class="listitemimage-surround" style="background-color:#DD1313;">
              <div class="listitemimage" style="background-image:url({get_thumb_for_background filename=$products[products].thumb size=600x600});"></div>               
             {/if}
                
                
              </div>
              <h3>{$products[products].name}</h3>
              {if $products[products].trade_only && !$smarty.session.isTrade}
              <p>Trade product</p>
              {else}
              <p>&pound;{$products[products].price|string_format:"%.2f"|substr:0:$point}.{$products[products].price|string_format:"%.2f"|substr:$point+1}</p>
              {/if}
            </a>
          </div>
        </div>
  
  {/section}
  <div class="clearfix"></div> 
  <div class="pagination-centered">
    <ul class="pagination">
    {$pag_string}
    </ul>
  </div>
</div><!-- /listwrapper -->    
{else}
<hr>
<p>Sorry, there are no products within your search - please try again.</p>
{/if}