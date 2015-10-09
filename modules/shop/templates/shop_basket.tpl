{literal}
<style>
.sidenav { display:none; }
@media screen and (min-width:768px) { .sidenav { display:block; } }
</style>
{/literal}

 
 

{foreach from=$basket->items key=a item=item}
    
    
          
          

 
    
<table width="100%" border="0" cellpadding="0" cellspacing="0" class="baskettable">
  <tr class="basketbottomtr">
    <td colspan="3">
      <h3>{$item.name}</h3>
      {$item.discount_text}
    </td>
  </tr>
  <tr>
    <td width="20%">Price</td>
    <td colspan="2">&pound;{$item.row_price|string_format:"%.2f"}    </td>
  </tr>
  
  
  {if $item.colour_name != '' }
  <tr>
    <td>Colour</td>
    <td colspan="2">{$item.colour_name}</td>
  </tr>
  {/if}

{if $item.gender_name != '' }
  <tr>
    <td>Sex</td>
    <td colspan="2">{$item.gender_name}</td>
  </tr>
  {/if}
{if $item.size_name != '' }
  <tr>
    <td>Size</td>
    <td colspan="2">{$item.size_name}</td>
  </tr>
  {/if}

  {if $item.product_type == '0' }<!-- only change quantity in a shop product rather than a donation / event etc -->
  <tr>
    <td>Qty</td>
    <td colspan="2">
      <form action='' method='post'>
      <input type='hidden' name='key' value='{$item.key}' />

      <input type='text' class="quantity-box"  name='qty' value='{$item.qty}' size=2 />
      <input style="float:right;" type="submit" name="submit_basket_change" value="Update quantity" /> 


      </form>

    </td>
  </tr>

  {/if}

  <tr>
    <td align="right" colspan="3">
      <form action='' method='post' >
      <input type='hidden' name='key' value='{$item.key}' />
      <input type='hidden' name='qty' value='0' />
      <input type="submit" name="submit_basket_change" value="Remove" />
      </form>
    </td>
  </tr>
</table>
{foreachelse}
{$basket_empty}
{/foreach}
 



<hr>
<table width="100%" border="0" cellpadding="0" cellspacing="0" class="baskettable">
 
     {if $smarty.const.SHOP_SHOW_VAT}
   <tr>
        <td> VAT</td>
        <td colspan="2" align="right"> &pound;{$basket->tax_total|string_format:"%.2f"}</td>
      </tr>
 {/if}
 
 
  <tr>
    <td>
      <h3>Total</h3>
    </td>
    <td colspan="2" align="right">
      <h3>&pound;{$basket->total|string_format:"%.2f"}</h3>
    </td>
  </tr>
  <tr>
    <td colspan="3" align="right">
      <p class="csbutton"><a style="float:right;" href="/shop/checkout">Proceed to Checkout</a></p>
    </td>
  </tr>  
  {if !isset($smarty.session.isTrade) }
  <tr>
    <td colspan="3" align="right">
      <p class="small"><em>*Prices include VAT</em></p>
    </td>
  </tr>
  {/if}
</table>
 




{*/section*}