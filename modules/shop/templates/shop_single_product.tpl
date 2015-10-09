
{literal}
   <script type="text/javascript" src="/modules/shop/js/single_product.js"></script> 
    
    <script>
        $(document).ready(function() {
    {/literal}           
    {$showmessage}
    {literal}
        });
    </script>
{/literal}


<h1 class="singleproducth1">{$product.name}</h1>
<h3 class="singleproducth3">Price:  &pound;{$product.price|string_format:"%.2f"}<span style="font-size:60%;"> (inc. VAT)</span></h3>
{$product.description} 
{$product.special_offer}


{if  $smarty.const.SHOP_USE_GENDER == 1 }
    <div class="picker picker-type">                
    {section name=genders loop=$genders}  
        {if trim($genders[genders].available) == '1' } 
            {if $genders[genders].id == '1'}
                <a id="gender_{$genders[genders].id}" onclick="set_gender('{$genders[genders].id}')" class="pickeritem-male"   >{$genders[genders].title}</a>
            {/if}

            {if $genders[genders].id == '2'}
                <a id="gender_{$genders[genders].id}"  onclick="set_gender('{$genders[genders].id}')" class="pickeritem-female"  >{$genders[genders].title}</a>
            {/if}

            {if $genders[genders].id == '3'}
                unisex option ???
                <a id="gender_{$genders[genders].id}"  onclick="set_gender('{$genders[genders].id}')" class="pickeritem-unisex"  >{$genders[genders].title}</a>
            {/if}
            {assign var="use_gender" value="true"}
        {else}
            {* show unavailable items - perhaps greyed out ?
            <a class="pickeritem-size" data="size-{$sizes[sizes].id}"> {$sizes[sizes].title} </a>
            *}
        {/if}  
     {/section}
   </div>
{/if}  



 
            {if  $smarty.const.SHOP_USE_COLOURS == '1' }
                <div class="picker picker-color">
                    {section name=colours loop=$colours}          
                        <a id="colour_{$colours[colours].colour_id}" onclick="set_colour('{$colours[colours].colour_id}');" class="pickeritem-color"  style="background-color:#{$colours[colours].rgb};"  >{$colours[colours].name}</a>    
 {assign var="use_colour" value="true"}                   
{/section}
                </div>
            {/if}




            {if  $smarty.const.SHOP_USE_SIZE == 1 }
            <div class="picker picker-size">
            {section name=sizes loop=$sizes}        
                {if trim($sizes[sizes].available) == '1' } 
                    <a id="size_{$sizes[sizes].id}" onclick="set_size('{$sizes[sizes].id}');" class="pickeritem-size" data="size-{$sizes[sizes].id}" >{$sizes[sizes].title}</a>
                 {assign var="use_size" value="true"} 
                    
{/if}  
            {/section}
            </div>
           {/if}



<hr>


{if $stock_available < 1 && $smarty.const.SHOP_USE_STOCK_CONTROL == 1 }
      <p class="stockcontrol">Sorry, currently out of stock</p>
{else}
      


 		<div class="addproductbox">
          <p><span class="quantitytext">Quantity</span>
          <a class="quanityamount quantityamount-minus" onclick="dec_qty();">-</a><a class="quanityamount quantityamount-plus" onclick="inc_qty();">+</a>
          <input class="quanityamount-field" type="text" value="1"  name="qty" id="qty"  class="qty">
          <span class="quanityamountaddbutton"><a href="#" onclick="javascript:submitform('basket');">Add to Basket</a></p>
        </div>


        
        

    {if  $smarty.const.SHOP_USE_STOCK_CONTROL == 1 }
        <p class="stockcontrol">Currently {$stock_available} in stock</p>
    {/if}
      
          
{/if}

     
{if  $smarty.const.SHOP_USE_COLOURS == '1' }
{* this will be set by the js *}
    {if $use_colour}
        <input type='hidden' name='colour_id' value='' />
    {/if}
{/if}
{if  $smarty.const.SHOP_USE_SIZE == '1' }
    {if $use_size}
    {* this will be set by the js *}
        <input type='hidden' name='size_id' value='' />
    {/if}
{/if}
{if  $smarty.const.SHOP_USE_GENDER == '1' }
{* this will be set by the js *}
    {if $use_gender }
 
        <input type='hidden' name='gender_id' value='' />
    {/if}
{/if}

<input type='hidden' name='product_id' value='{$product.id}' />
<input type='hidden' name='stock_available' value='{$stock_available}' />
<input type="hidden" name="submit_basket_add" value="submit_basket_add" /> 