

            <div class="how-can-block">
              <h2>{$donate.display_text}</h2>
              <div class="funo-banner">
                {$donate.body}
              </div>
              <div class="donate-wrapper">
                <form id="basket" action="" method="post" class="clearfix">
                  <div class="col1">You can donate money to the hospice here</div>
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
                    <div class="col1b">{$fixedvalues}</div>
                  {/if}
                  {if $donate.free_values == "1"}
                  <div class="col2">
                    <div class="input-group"> <span class="input-group-addon" id="basic-addon1">&pound;</span>
                      <input maxlength="8" id="txtnumber price_" type="text" name="price" class="numbersOnly form-control" placeholder="Enter amount" aria-describedby="basic-addon1" />
                    </div>
                  </div>
                  {/if}
                  {if $donate.show_block1 == "1"}
                  <div class="col2b">
                    <p>{$donate.text_block1}</p>
                    <textarea name="donate_user_response1"></textarea>
                  </div>
                  {/if}
                  {if $donate.show_block2 == "1"}
                  <div class="col2c">
                    <p>{$donate.text_block2}</p>
                    <textarea name="donate_user_response2"></textarea>
                  </div>
                  {/if}
                  {if $donate.giftaid_checkbox == "1"}
                  <div class="col3">
                    <label class="label_check" for="giftaid">I am a UK tax payer, please add Gift Aid
                      <input id="giftaid" type="checkbox" name="giftaid" value="giftaid" style="-webkit-appearance: block;" />
                    </label>
                  </div>
                  {/if}
                  <div class="col4">
                    <input type="hidden" name="item_description" value="{$donate.title}">
                    <input type="hidden" name="item_description_location" value="{$donate.title}">
                    <input type="submit" value="Donate" title="Donate" class="btn donate-btn" />
                    {* this signifies its a donation *}
                    <input type='hidden' name='product_type' value='1'/>
                    {* This records the current url - so we know where its been inspired by *}
                    <input type='hidden' name='donation_location' value='{$donate_location}'/>
                    {* this signifies which type of donation *}
                    <input type='hidden' name='product_id' value='{$donate.id}'/>
                    <input type="hidden" name="qty" value="1">
                    <input type='hidden' name='stock_available' value='true'/>
                    <input type="hidden" name="submit_basket_add" value="submit_basket_add"/>
                  </div>
                </form>
              </div>
            </div>