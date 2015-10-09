<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html lang="en" xml:lang="en" xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <title>{$smarty.const.SITE_NAME}</title>
        {literal}
            <style type="text/css">
                body {
                    background:#ffffff;
                }
                p {
                    font-family:Arial, Helvetica, sans-serif;
                    font-size:13px;
                    color:#000000;
                    line-height:20px;
                    margin:10px 0px 10px 0px;
                    text-align:left;
                }
                a {
                    color:#443073;
                    outline:none;
                    text-decoration:underline;
                }
                a:hover {
                    color:#ef3e56;
                    text-decoration:underline;
                }
            </style>
        {/literal}
    </head>
    <body bgcolor="#ffffff" style="background-color: #ffffff; ">
        <div align="center" style="background-color: #ffffff;  width: 582px;">
            <table cellpadding="0" cellspacing="0" style="border: 0pt none;" width="582">
                <tbody>
                    <tr>
                        <td><img align="top" alt="{$smarty.const.SITE_NAME}" border="0" height="116" hspace="0" src="http://www.csmailer.co.uk/editor_images/image_8dddc044/header-top.jpg" style="width: 582px; height: 116px;" vspace="0" width="582" /></td>
                    </tr>
                    <tr>
                        <td><a href="{$smarty.const.SITE_ADDRESS}"><img align="top" alt="{$smarty.const.SITE_NAME}" height="41" hspace="0" src="http://www.csmailer.co.uk/editor_images/image_8dddc044/nav-logo.gif" style="border-width: 0px; border-style: solid; width: 187px; height: 41px;" vspace="0" width="187" /></a>
                            <a href="{$smarty.const.SITE_ADDRESS}"><img align="top" alt="{$smarty.const.SITE_NAME}" border="0" height="41" hspace="0" src="http://www.csmailer.co.uk/editor_images/image_8dddc044/nav-home.gif" vspace="0" width="90" /></a>
                            <a href="{$smarty.const.SITE_ADDRESS}/shop"><img align="top" alt="Direct Colour Shop" height="41" hspace="0" src="http://www.csmailer.co.uk/editor_images/image_8dddc044/nav-shop.gif" style="border-width: 0px; border-style: solid; width: 86px; height: 41px;" vspace="0" width="86" /></a>
                            <a href="{$smarty.const.SITE_ADDRESS}/snazzi"><img align="top" alt="Direct Colour Snazzi" height="41" hspace="0" src="http://www.csmailer.co.uk/editor_images/image_8dddc044/nav-snazzi.gif" style="border-width: 0px; border-style: solid; width: 92px; height: 41px;" vspace="0" width="92" /></a>
                            <a href="{$smarty.const.SITE_ADDRESS}/colours4u"><img align="top" alt="Direct Colour Colours4U" border="0" height="41" hspace="0" src="http://www.csmailer.co.uk/editor_images/image_8dddc044/nav-colours4u.gif" vspace="0" width="127" /></a></td>
                    </tr>
                    <tr>
                        <td><img align="top" alt="{$smarty.const.SITE_NAME}" border="0" height="74" hspace="0" src="http://www.csmailer.co.uk/editor_images/image_8dddc044/header-bottom.png" style="width: 582px; height: 74px;" vspace="0" width="582" /></td>
                    </tr>
                    <tr>
                        <td bgcolor="#ffffff"><table border="0" cellpadding="0" cellspacing="0" style="border-collapse: collapse;" width="582">
                                <tbody>
                                    <tr>
                                        <td bgcolor="#7156a4" rowspan="3" width="2"><img align="top" alt="spacer" border="0" height="2" hspace="0" src="http://www.csmailer.co.uk/editor_images/image_8dddc044/2x2.gif" vspace="0" width="2" /></td>
                                        <td bgcolor="#ffffff" width="20"><img align="top" alt="spacer" border="0" height="1" hspace="0" src="http://www.csmailer.co.uk/editor_images/image_8dddc044/20spacer.gif" vspace="0" width="20" /></td>
                                        <td bgcolor="#ffffff" height="1" width="533"><img align="top" alt="spacer" border="0" height="1" hspace="0" src="http://www.csmailer.co.uk/editor_images/image_8dddc044/widthspacer.gif" vspace="0" width="533" /></td>
                                        <td bgcolor="#ffffff" width="20"><img align="top" alt="spacer" border="0" height="1" hspace="0" src="http://www.csmailer.co.uk/editor_images/image_8dddc044/20spacer.gif" vspace="0" width="20" /></td>
                                        <td bgcolor="#7156a4" rowspan="3" width="3"><img align="top" alt="spacer" border="0" height="3" hspace="0" src="http://www.csmailer.co.uk/editor_images/image_8dddc044/3x3.gif" vspace="0" width="3" /></td>
                                        <td bgcolor="#9273ce" rowspan="3" width="4"><img align="top" alt="spacer" border="0" height="4" hspace="0" src="http://www.csmailer.co.uk/editor_images/image_8dddc044/4x4.gif" vspace="0" width="4" /></td>
                                    </tr>
                                    <tr>
                                        <td bgcolor="#ffffff" rowspan="2"></td>
                                        <td bgcolor="#ffffff">
                                      
                                            available vars
                                               ----   
                                            {$smarty.const.EMAIL_HEADER_IMAGE}
                                            {$smarty.const.EAMIL_HEADER_BG_COLOUR}
                                            
                                            {$smarty.const.EMAIL_FOOTER_LEGISTLATION}
                                            {$smarty.const.EMAIL_FOOTER_TEXT}
                                            {$smarty.const.EMAIL_FOOTER_LINK_COLOUR}
                                            
                                                  
                                            {$smarty.const.SITE_NAME}
                                            {$smarty.const.SITE_ADDRESS}
                                                --- 
                                                <br>
                                          
                                            
                                            
                                            
                                            
                                            
                                            <p>Dear {$orderDetails.customer.firstname},</p>
                                            <p>We have received your order (order number {$orderDetails.id})</p>
                                            <p>Your ordered items are:</p>
                                            <table>
                                                {foreach from=$orderDetails.items item=orderItem}
                                                    <tr><td>{$orderItem.description}</td>
                                                        <td>
                                                            &pound;{math equation="x * y" x=$orderItem.price y=$orderItem.quantity  format="%.2f"}
                                                            {if $orderItem.discount_text != ''}
                                                            </td></tr>
                                                        <tr><td colspan=2>{$orderItem.discount_text}
                                                            {/if}
                                                        </td></tr>
                                                    {/foreach}
                                            </table>
                                            <p>VAT: &pound;{$orderDetails.tax|string_format:"%.2f"}</p>
                                            <p>Shipping: &pound;{$orderDetails.delivery|string_format:"%.2f"}</p>
                                            <p>Total: &pound;{$orderDetails.gross|string_format:"%.2f"}</p>
                                            <p>Thank you</p>
                                            <p>Direct Colour</p></td>
                                        <td bgcolor="#ffffff" rowspan="2"></td>
                                    </tr>
                                    <tr>
                                        <td bgcolor="#ffffff" height="10"><img align="top" alt="spacer" border="0" height="10" hspace="0" src="http://www.csmailer.co.uk/editor_images/image_8dddc044/10x10.gif" vspace="0" width="10" /></td>
                                    </tr>
                                </tbody>
                            </table></td>
                    </tr>
                    <tr>
                        <td><img align="top" alt="spacer" border="0" height="134" hspace="0" src="http://www.csmailer.co.uk/editor_images/image_8dddc044/footer-top.jpg" style="width: 582px; height: 134px;" vspace="0" width="582" /></td>
                    </tr>
                    <tr>
                        <td valign="top"><table border="0" cellpadding="0" cellspacing="0" style="border-collapse: collapse;" width="582">
                                <tbody>
                                    <tr>
                                        <td bgcolor="#7156a4" rowspan="4" width="2"><img align="top" alt="spacer" border="0" height="2" hspace="0" src="http://www.csmailer.co.uk/editor_images/image_8dddc044/2x2.gif" vspace="0" width="2" /></td>
                                        <td bgcolor="#ffffff" width="20"><img align="top" alt="spacer" border="0" height="1" hspace="0" src="http://www.csmailer.co.uk/editor_images/image_8dddc044/20spacer.gif" vspace="0" width="20" /></td>
                                        <td bgcolor="#ffffff" height="1" width="533"><img align="top" alt="spacer" border="0" height="1" hspace="0" src="http://www.csmailer.co.uk/editor_images/image_8dddc044/widthspacer.gif" vspace="0" width="533" /></td>
                                        <td bgcolor="#ffffff" width="20"><img align="top" alt="spacer" border="0" height="1" hspace="0" src="http://www.csmailer.co.uk/editor_images/image_8dddc044/20spacer.gif" vspace="0" width="20" /></td>
                                        <td bgcolor="#7156a4" rowspan="4" width="3"><img align="top" alt="spacer" border="0" height="3" hspace="0" src="http://www.csmailer.co.uk/editor_images/image_8dddc044/3x3.gif" vspace="0" width="3" /></td>
                                        <td bgcolor="#9273ce" rowspan="4" width="4"><img align="top" alt="spacer" border="0" height="4" hspace="0" src="http://www.csmailer.co.uk/editor_images/image_8dddc044/4x4.gif" vspace="0" width="4" /></td>
                                    </tr>
                                    <tr>
                                        <td bgcolor="#ffffff" rowspan="3"></td>
                                        <td bgcolor="#ffffff" height="10"><img align="top" alt="spacer" border="0" height="10" hspace="0" src="http://www.csmailer.co.uk/editor_images/image_8dddc044/10x10.gif" vspace="0" width="10" /></td>
                                        <td bgcolor="#ffffff" rowspan="3"></td>
                                    </tr>
                                    <tr>
                                        <td bgcolor="#ffffff"><p align="left"> {$smarty.const.EMAIL_FOOTER_TEXT} <span style="color: rgb(113, 87, 165); font-family: Arial; line-height: 20px; margin: 0pt;"><span style="font-size: 13px; font-weight: bold; line-height: 24px;">+44 (0)114 255 8555 | <a href="mailto:sales@styleandimage.co.uk?subject=Enquiry from email"><span style="color: rgb(113, 87, 165); text-decoration: underline;">sales@styleandimage.co.uk</span></a></span><br />
                                                    <span style="font-size: 11px;">{$smarty.const.EMAIL_FOOTER_LEGISTLATION}
                                            </span></span></p></td>
                                    </tr>
                                    <tr>
                                        <td bgcolor="#ffffff" height="10"><img align="top" alt="spacer" border="0" height="10" hspace="0" src="http://www.csmailer.co.uk/editor_images/image_8dddc044/10x10.gif" vspace="0" width="10" /></td>
                                    </tr>
                                </tbody>
                            </table></td>
                    </tr>
                    <tr>
                        <td><img align="top" alt="{$smarty.const.SITE_NAME}" border="0" height="19" hspace="0" src="http://www.csmailer.co.uk/editor_images/image_8dddc044/footer-bottom.jpg" style="width: 582px; height: 21px;" vspace="0" width="582" /></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </body>
</html>