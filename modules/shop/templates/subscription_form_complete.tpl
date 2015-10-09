<form method="POST" action="{$payment->url}">
    {if $payment->test}
        <input type=hidden name="testMode" value="100">
    {/if}
    <input type="hidden" name="instId" value="{$payment->instId}"/>
    <input type="hidden" name="cartId" value="{$payment->cartId}"/>
    <input type="hidden" name="amount" value="{$payment->amount}"/>
    <input type="hidden" name="currency" value="GBP"/>
    <input type="hidden" name="name" value="{$details->name}"/>
    <input type="hidden" name="address" value="{$details->address}"/>
    <input type="hidden" name="country" value="{$details->countryCode}"/>
    <input type="hidden" name="email" value="{$details->email}"/>
    <input type="hidden" name="phone" value="{$details->phone}"/>
    <input type="hidden" name="postcode" value="{$details->postcode}"/>
    <input type="hidden" name="signatureFields" value="{$payment->signatureFields}"/>
    <INPUT type="hidden" name="MC_action" VALUE="{$payment->action}">
    <INPUT type="hidden" name=futurePayType VALUE="regular">
    <INPUT type="hidden" name=option VALUE=1>
    {*  <INPUT type="hidden" name=startDate VALUE={$subscriptionDateStr}> *}
    <INPUT type="hidden" name=noOfPayments VALUE=0>
    <INPUT type="hidden" name=startDelayMult VALUE=1>
    <INPUT type="hidden" name=startDelayUnit VALUE=4>
    <INPUT type="hidden" name=intervalMult VALUE=1>
    <INPUT type="hidden" name=intervalUnit VALUE=4>
    <INPUT type="hidden" name=normalAmount VALUE="{$payment->amount}">
    <input type="hidden" name="signature" value="{$payment->signature}"/>
    <p>Your recurring payment will cost: &pound;{$payment->amount}</p>
    <input type="image" src="/images/proceedtopayment.png" value="Proceed to Payment"/>
</form>
<p><strong>IMPORTANT:</strong> This is a <u>subscription service</u>.<br/>
    Your subscription fee will be automatically taken each period. You can cancel your subscription renewal via WorldPay
    at any time. You will receive an email with your WorldPay account details after you've made your first payment.</p>
<p><img src="/images/cardsaccepted.png" width="199" height="37" alt="Cards accepted"/></p>

