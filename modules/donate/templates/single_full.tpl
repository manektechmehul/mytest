<h2>{$donate.title}</h2>
<p>{$donate.body}</p>
<form id="basket" action="" method="post">

    {if $donate.giftaid_checkbox == "1"}
        <p>
            Gift Aid this donation
            <input type="checkbox" name="giftaid" id="giftaid" value="giftaid" style="-webkit-appearance: block;"><br>
            - css needs fixing to show this. see -webkit-appearance in style.css<br>
        </p>
    {/if}

    {if $donate.fixed_values_display == "1"}
        {* if we have the dropdown and the free value options,
        clear the free values when you select the dropdown and reset this if the other is entered *}
    {if $donate.free_values == "1"}
    {literal}
        <script>
            $(document).ready(function() {
                $("#fixed_values").change(clear_price);
                $("#price").change(reset_fixed_values);
            });
            function clear_price(){
                $("#price").val("");
            }
            function reset_fixed_values(){
                $("#fixed_values").val("0");
            }
        </script>
    {/literal}
    {/if}
        <p>Please choose one of the following donation values<br>
            {$fixedvalues}</p>
    {/if}


    {if $donate.free_values == "1"}
        <p><label> Please Enter the amount you wish to donate </label><br>
            £<input type="text"  maxlength="4" size="4" name="price" id="price">
        </p>
    {/if}
    <!-- will need to update basket to process these need price elements -->


    {if $donate.show_block1 == "1"}
        <p>{$donate.text_block1}</p>
        <textarea name="donate_user_response1"></textarea>
    {/if}

    {if $donate.show_block2 == "1"}
        <p>{$donate.text_block2}</p>
        <textarea name="donate_user_response2"></textarea>
    {/if}


    <input type="hidden" name="item_description" value="{$donate.title}">
    <input type="hidden" name="item_description_location" value="{$donate.title}">
    <input type="submit" value="Donate">
    {* this signifies its a donation *}
    <input type='hidden' name='product_type' value='1'/>
    {* This records the current url - so we know where its been inspired by *}
    <input type='hidden' name='donation_location' value='{$donate_location}'/>
    {* this signifies which type of donation *}
    <input type='hidden' name='product_id' value='{$donate.id}'/>
    <input type="hidden" name="qty" value="1">
    <input type='hidden' name='stock_available' value='true'/>
    <input type="hidden" name="submit_basket_add" value="submit_basket_add"/>
</form>




