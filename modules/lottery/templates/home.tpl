<!-- put in the bask stuff for the lotto -->
{literal}
<script>
    $(function() {
        $('#confirm_1').prop('checked', false);
        $('#confirm_2').prop('checked', false);
    });
function toggle1(){

    if($('#submit1').prop('disabled')){
        $('#submit1').prop('disabled',false);
    }else{
        $('#submit1').prop('disabled',true);
    }
}
function toggle2(){

    if($('#subscription_submit').prop('disabled')){
        $('#subscription_submit').prop('disabled',false);
    }else{
        $('#subscription_submit').prop('disabled',true);
    }
}




</script>
{/literal}
<h2> Buying Lottery Tickets </h2>
<form id="basket" action="" method="post">
    <h2> Option 1 </h2>

    <p>You can buy tickets either as a 'one time purchase' for either 1 week (this weeks draw), the next 13, 26 or 52
        weeks</p>

    <p>Number of Tickets per week

    <p>
        <select name="qty">
            <option value="1">1</option>
            <option value="2">2</option>
            <option value="3">3</option>
            <option value="4">4</option>
            <option value="5">5</option>
            <option value="6">6</option>
            <option value="7">7</option>
            <option value="8">8</option>
            <option value="9">9</option>
            <option value="10">10</option>
            <option value="15">15</option>
            <option value="20">20</option>
        </select>
    <p>Number of weeks</p>
    <select name="weeks">
        <option value="1">1</option>
        <option value="13">13</option>
        <option value="26">26</option>
        <option value="52">52</option>
    </select>
<p>
    I confirm I am over 16 years of age.
    <input type="checkbox" name="confirm_1" id="confirm_1" onclick="toggle1();" ></p>

    <input type="hidden" name="item_description" value="Lottery Tickets">
    <input type="submit" value="Add to Basket" id="submit1" disabled >
    <!-- Add price on the server side -->
    <input type='hidden' name='subscription' value='0'/>
    <!-- this signifies its a non recurring lottery payment -->
    <input type='hidden' name='product_type' value='3'/>
    <!-- this signifies which type of donation -->
    <input type='hidden' name='product_id' value='1'/>
    <input type='hidden' name='stock_available' value='true'/>
    <input type="hidden" name="submit_basket_add" value="submit_basket_add"/>
</form>
<form id="subscription" action="\shop\subscribe" method="post">
    <h2> Option 2 </h2>

    <p>You can buy the blocks of tickets, but as a subscription, continually renewing. </p>

    <p>Number of Tickets per week

    <p>
        <select name="qty">
            <option value="1">1</option>
            <option value="2">2</option>
            <option value="3">3</option>
            <option value="4">4</option>
            <option value="5">5</option>
            <option value="6">6</option>
            <option value="7">7</option>
            <option value="8">8</option>
            <option value="9">9</option>
            <option value="10">10</option>
            <option value="15">15</option>
            <option value="20">20</option>
        </select>
    <p>Number of weeks</p>
    <select name="weeks">
        <!-- don't recur on a weekly basis <option value="1">1</option>-->
        <option value="13">13</option>
        <option value="26">26</option>
        <option value="52">52</option>
    </select>

    <p>I confirm I am over 16 years of age.
    <input type="checkbox" name="confirm_2" id="confirm_2" onclick="toggle2();" ></p>

    <input type="hidden" name="item_description" value="Lottery Tickets Subscription">
    <input type="submit" name="subscription_submit" id="subscription_submit" value="Sign up for Subscription" disabled>
    <!-- Add price on the server side -->
    <input type='hidden' name='subscription' value='1'/>
    <!-- this signifies its a non recurring lottery payment -->
    <input type='hidden' name='product_type' value='3'/>
    <!-- this signifies which type of donation -->
    <input type='hidden' name='product_id' value='2'/>
    <input type='hidden' name='stock_available' value='true'/>
    <!--  <input type="hidden" name="submit_basket_add" value="submit_basket_add" /> -->
</form>


