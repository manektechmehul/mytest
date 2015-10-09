{* Smarty Template *}


{literal}
    <style>
        .sidenav { display:none; }
        @media screen and (min-width:768px) { .sidenav { display:block; } }
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

<h2>Order Details</h2>



<h3> {$item_description} </h3>

{$qty} Tickets per week for {$weeks} weeks.

Cost of £{$total}

<br><br><br><br>
<form action="{$confirm->purchase_URL}" method="{$confirm->form.method}" id="{$confirm->form.id}" name="{$confirm->form.name}">
    {foreach from=$confirm->confirm_fields item=field}
        <input type="hidden" name="{$field.name}" value="{$field.value}">
    {/foreach}

<div class="clearfix"></div>

<p><strong>IMPORTANT:</strong> This is a <u>subscription service</u>.<br/>
    Your subscription fee will be automatically taken each period. You can cancel your subscription renewal via WorldPay
    at any time. You will receive an email with your WorldPay account details after you've made your first payment.</p>
<p><img src="/images/cardsaccepted.png" width="199" height="37" alt="Cards accepted"/></p>

    <input class="formsubmit" style="float:right;" type="submit" value="Continue to Payment" />
</form>
