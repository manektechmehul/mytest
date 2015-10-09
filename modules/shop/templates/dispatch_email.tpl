<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="en" xml:lang="en">
<head>
<title>{$smarty.const.SITE_NAME} Invoice</title>
{literal}
<style type="text/css">
<!--
body { background:#ffffff; margin:20px 0; padding:0; }
p, td { font-family:Arial, Helvetica, sans-serif; font-size:17px; color:#000000; line-height:22px; margin:10px 0px 10px 0px; text-align:left; }
h1, h2, h3, h4, h5, h6 { font-family:Arial, Helvetica, sans-serif; }
a { color:#{/literal}{$smarty.const.EMAIL_LINK_COLOUR}{literal}; outline:none; text-decoration:none; }
a:hover { color:#{/literal}{$smarty.const.EMAIL_LINK_COLOUR}{literal}; text-decoration:underline; }
-->
</style>
{/literal}
</head>

<body bgcolor="#FFFFFF">
<div align="center">
  <table width="450" cellspacing="0" cellpadding="0" style="border:1px solid #000000;">
    <tbody>
      <tr><td bgcolor="#{$smarty.const.EMAIL_HEADER_BG_COLOUR}"><img align="top" alt="{$smarty.const.SITE_NAME}" border="0" hspace="0" src="{$smarty.const.SITE_ADDRESS}{$smarty.const.EMAIL_HEADER_IMAGE}" vspace="0" /></td></tr>
      <tr><td bgcolor="#FFFFFF" style="padding:15px 30px 15px 30px;">




        <p>Dear {$orderDetails.customer.firstname},</p>
        
        
        <p>We wanted to update you on the progress of your order. You order has now been packed and will soon be dispatched.
                                    
           Your order (number {$orderDetails.id}).</p>
      
        
        
        
        
        <p>Your items are:</p>
        <table cellspacing="0" cellpadding="0" style="border:none; border-right:1px solid #000000;" width="100%">
          {foreach from=$orderDetails.items item=orderItem}
            <tr><td style="padding:2px 10px; border:1px solid #000000; border-right:none; text-align:left;">{$orderItem.description} x {$orderItem.quantity}</td>
            <td style="padding:2px 10px; border:1px solid #000000; border-right:none; text-align:left;">
            
            {if $shop_show_vat == '1'} 
                       
             &pound;{math equation="(x * y)-((x * y)/100 * B)" x=$orderItem.price y=$orderItem.quantity B=$shop_vat_rate format="%.2f"} 
             
        	{else}
        	 &pound;{math equation="x * y" x=$orderItem.price y=$orderItem.quantity  format="%.2f"}
        	{/if}
            
            
            
            {if $orderItem.discount_text != ''}
            </td></tr>
            <tr><td colspan="2" style="padding:2px 10px; border:1px solid #000000; border-right:none; text-align:left;">{$orderItem.discount_text}
            {/if}
            </td></tr>
          {/foreach}
       
       
       <tr>
        <tr><td style="padding:2px 10px; border:1px solid #000000; border-right:none; text-align:right;">  Shipping </td>
        <td style="padding:2px 10px; border:1px solid #000000; border-right:none; text-align:left;">
        {if $shop_show_vat == '1'} 
        	&pound;{math equation="A - (A/100 * B)" A=$orderDetails.delivery  B=$shop_vat_rate assign="$pretax_price" format="%.2f"} 	 
        {else}
        	&pound;{$orderDetails.delivery|string_format:"%.2f"}
        {/if}
        
        </td>       
       </tr>
       
    {if $shop_show_vat == '1'}              
       <tr>
        <tr><td style="padding:2px 10px; border:1px solid #000000; border-right:none; text-align:right;">  Net </td>
        <td style="padding:2px 10px; border:1px solid #000000; border-right:none; text-align:left;">&pound;{math equation="A - (A/100 * B)" A=$orderDetails.gross  B=$shop_vat_rate assign="$pretax_price" format="%.2f"}</td>       
       </tr>
       <tr>
        <tr><td style="padding:2px 10px; border:1px solid #000000; border-right:none; text-align:right;"> VAT </td>
        <td style="padding:2px 10px; border:1px solid #000000; border-right:none; text-align:left;">&pound;{$orderDetails.tax|string_format:"%.2f"}</td>       
       </tr>
  	{/if}
       
       
       
        <tr>
        <tr><td style="padding:2px 10px; border:1px solid #000000; border-right:none; text-align:right;">  <p style="font-size:30px;"><strong>Total: </strong></p> </td>
        <td style="padding:2px 10px; border:1px solid #000000; border-right:none; text-align:left;"> <p style="font-size:30px;"><strong> &pound;{$orderDetails.gross|string_format:"%.2f"}</strong></p></td>       
       </tr>
        </table>
       
        
        
        
        
        
        
        
        
        
        
      
        
        
        
       
        
        
        
        
        <p>Thank you for your order,<br />
        <strong><a href="{$smarty.const.SITE_ADDRESS}" style="color:#{$smarty.const.EMAIL_LINK_COLOUR};">{$smarty.const.SITE_NAME}</a></strong></p>

      </td></tr>
      <tr><td bgcolor="#{$smarty.const.EMAIL_HEADER_BG_COLOUR}" style="padding:15px 30px 15px 30px;">
        <p style="color:#{$smarty.const.EMAIL_FOOTER_TEXT_COLOUR};">{$smarty.const.EMAIL_FOOTER_TEXT}</p>
        <p style="color:#{$smarty.const.EMAIL_FOOTER_TEXT_COLOUR}; font-size:14px; line-height:16px;">{$smarty.const.EMAIL_FOOTER_LEGISTLATION}</p>
      </td></tr>
    </tbody>
  </table>
</div>
</body>
</html>