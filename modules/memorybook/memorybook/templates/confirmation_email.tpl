<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="en" xml:lang="en">
<head>
<title>{$smarty.const.SITE_NAME} </title>
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
      <tr><td bgcolor="#336688"><img align="top" alt="{$smarty.const.SITE_NAME}" border="0" hspace="0" src="{$smarty.const.SITE_ADDRESS}{$smarty.const.EMAIL_HEADER_IMAGE}" vspace="0" /></td></tr>
      <tr><td bgcolor="#FFFFFF" style="padding:15px 30px 15px 30px;">




        <p>Dear Site Admin,</p>
        <p>We have received a new memory for the memory book of <strong> {$patient_name}</strong>.</p>

        <p>Please log in to check the message.</p><p> The Rotherham Hospice Website</p>


       

       
       
       

       
        
        
        
        
        
        
        
        
        
        
      
        
        
        
       
        
        
        
        

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