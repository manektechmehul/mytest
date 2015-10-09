{literal}
    <style>
        .sidenav {
            display: none;
        }

        @media screen and (min-width: 768px) {
            .sidenav {
                display: block;
            }
        }
    </style>
{/literal}
<p>Please confirm your details below before proceeding to payment.</p>
<h2>Your Details</h2>
{section name=customer_details loop=$confirm->customer_details}

    {if !(($confirm->customer_details[customer_details].name == 'Billing State' && $confirm->customer_details[customer_details].value =='-1') ||
    ($confirm->customer_details[customer_details].name == 'Delivery State' && $confirm->customer_details[customer_details].value =='-1')) }
        <div class='customer-details'>
            <span class='customer-details-title'>{$confirm->customer_details[customer_details].name}: </span>
            <span class='customer-details-data'>{$confirm->customer_details[customer_details].value}</span>
        </div>
    {/if}
{/section}
<p><input type="button" class="buttonback" VALUE="Go Back and Edit Details" onClick="history.go(-1)"></p>
{*{if $smarty.session.collection }
    <form action="/shop/collection?clear_collection=true" method="post" id="collection_form" name="collection_form">
        {$smarty.session.delivery_type} &nbsp; <input type="submit" value="Recalculate with Shipping">
    </form>
{else}
    <h2> Shipping </h2>
    <div> Would you like to collect you products ?</div>
    <form action="/shop/collection" method="post" id="collection_form" name=collection_form">
        <input type="submit" value="I will Collect">
    </form>
{/if}*}





{* only show if they haven't already made a donation
{if $has_donation != '1'}
This basket has donation {$has_donation}
<form id="basket" action="/shop/donate" method="post" >
    <div class="col1">You can donate money to the Hospice here</div>


    <div class="col2">
        <div class="input-group"> <span class="input-group-addon" id="basic-addon1">&pound;</span>
            <input maxlength="8" id="txtnumber price_" type="text" name="price"   class="numbersOnly form-control" placeholder="Enter Amount" aria-describedby="basic-addon1" />
        </div>
    </div>

        <div class="col3">
            <label class="label_check" for="giftaid">I am a UK tax payer, please add Gift Aid
                <input id="giftaid" type="checkbox" name="giftaid" value="giftaid"   />
            </label>
        </div>

    <div class="col4">
        <input type="hidden" name="item_description" value="Checkout Donation">
        <input type="hidden" name="item_description_location" value="Checkout Donation">
        <input type="submit" value="Donate" title="Donate" class="btn donate-btn" />
        {-* this signifies its a donation *-}
        <input type='hidden' name='product_type' value='1'/>
        {-* This records the current url - so we know where its been inspired by *-}
        <input type='hidden' name='donation_location' value='Checkout Donation'/>
        {-* this signifies which type of donation *-}
        <input type='hidden' name='product_id' value='999'/>
        <input type="hidden" name="qty" value="1">
        <input type='hidden' name='stock_available' value='true'/>
        <input type="hidden" name="submit_basket_add" value="submit_basket_add"/>
    </div>
</form>


{/if} *}



<h2>Order Details</h2>
<table width="100%" border="0" cellpadding="0" cellspacing="0">
    <tr class="basketbottomtr">
        <td class="trtitle" width="50%" align="left" valign="top">Items</td>
        <td class="trtitle" width="10%" colspan="-1" align="right" valign="top">Qty</td>
        <td class="trtitle" width="10%" colspan="-1" align="right" valign="top">Item</td>
        <td class="trtitle" width="10%" colspan="-1" align="right" valign="top">Total</td>
    </tr>


    {foreach from=$items key=k item=v}
        {foreach from=$v key=a item=item}
            <tr>
                <td align="left" valign="top">{$item.name} {$item.colour_name} {$item.gender_name} {$item.size_name}
                    {$item.discount_text}</td>
                <td colspan="-1" align="right" valign="top">{$item.qty}</td>
                <td colspan="-1" align="right" valign="top">
                    {if $smarty.const.SHOP_SHOW_VAT}
                        &pound;{math equation="A - (A/120 * B)" A=$item.price  B=$smarty.const.SHOP_VAT_RATE assign="$pretax_price" format="%.2f"}
                    {else}
                        &pound;{$item.price|string_format:"%.2f"}
                    {/if}</td>
                <td colspan="-1" align="right" valign="top">
                    {if $smarty.const.SHOP_SHOW_VAT}
                        &pound;{math equation="A - (A/120 * B)" A=$item.row_price  B=$smarty.const.SHOP_VAT_RATE assign="$pretax_price" format="%.2f"}
                    {else}
                        &pound;{$item.row_price|string_format:"%.2f"}
                    {/if}
                </td>
            </tr>
        {/foreach}
    {/foreach}


    {section name=item loop=$confirm->basket->items}

    {/section}



    {if false}<!-- this site doesn't charge shipping -->
    <tr>
        {if $smarty.session.collection }
            <td colspan="3" align="left">No Shipping - Collection Only</td>
            <td colspan="-1" align="right">
                    &pound;0.00
            </td>
        {else}

            <td colspan="3" align="left">Shipping [Only for Shop Products]</td>
            <td colspan="-1" align="right">
                {if $smarty.const.SHOP_SHOW_VAT}
                    &pound;{math equation="A - (A/100 * B)" A=$smarty.session.deliverycost  B=$smarty.const.SHOP_VAT_RATE assign="$pretax_price" format="%.2f"}
                {else}
                    &pound;{$smarty.session.deliverycost|string_format:"%.2f"}
                {/if}
            </td>
        {/if}
    </tr>
    {/if}
    {if $smarty.const.SHOP_SHOW_VAT}
        <tr class="basketbottomtr">
            <td colspan="3" align="left">Net</td>
            <td colspan="-1"
                align="right">&pound;{math equation="A - (A/100 * B)" A=$confirm->basket->total  B=$smarty.const.SHOP_VAT_RATE assign="$pretax_price" format="%.2f"}</td>
        </tr>
        <tr class="basketbottomtr">
            <td colspan="3" align="left">VAT</td>
            <td colspan="-1" align="right">&pound;{$confirm->basket->tax_total|string_format:"%.2f" }</td>
        </tr>
    {/if}
    <tr>
        <td class="trtitle" colspan="3" align="left"><span class="large">Total</span></td>
        <td colspan="-1" align="right"><strong
                    class="large">&pound;{$confirm->basket->total|string_format:"%.2f" }</strong></td>
    </tr>
</table>
<br>
<form action="{$confirm->purchase_URL}" method="{$confirm->form.method}" id="{$confirm->form.id}"
      name="{$confirm->form.name}">
    {foreach from=$confirm->confirm_fields item=field}
        <input type="hidden" name="{$field.name}" value="{$field.value}">
    {/foreach}
    <input class="formsubmit" style="float:right;" type="submit" value="Continue to Payment"/>
</form>
<div class="clearfix"></div>