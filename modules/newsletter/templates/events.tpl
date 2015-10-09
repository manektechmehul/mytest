{section name='events' loop=$events}
{if $smarty.section.events.first}
<table width="327" border="0" cellspacing="0" cellpadding="0" align="left" class="em_wrapper">
  <tr>
    <td align="left" valign="top">
        <table width="327" border="0" cellspacing="0" cellpadding="0" class="em_wrapper1">
{else}
<table width="315" border="0" cellspacing="0" cellpadding="0" align="right" class="em_wrapper">
  <tr>
    <td align="left" class="em_padtop" valign="top">
        <table width="315" border="0" cellspacing="0" cellpadding="0" class="em_wrapper1">
{/if}
          <tr>
            <td valign="top" width="92">
                <table width="92" border="0" cellspacing="0" cellpadding="0" align="left">
                  <tr>
                    <td height="2" bgcolor="#3c8e77" style="line-height:1px;font-size:1px;"><img src="{$baseUrl}modules/newsletter/templates/images/spacer.gif" width="1" height="2" alt="spacer" style="display:block;" border="0" /></td>
                  </tr>
                  <tr>
                    <td align="center" valign="top">
                        <table width="100%" border="0" cellspacing="0" cellpadding="0">
                          <tr>
                            <td width="2" bgcolor="#3c8e77" style="line-height:1px;font-size:1px;"><img src="{$baseUrl}modules/newsletter/templates/images/spacer.gif" width="2" height="1" alt="spacer" style="display:block;" border="0" /></td>
                            <td align="center" valign="top"><img src="{$baseUrl}php/thumbimage.php?img=/UserFiles/Image/{$events[events].thumbnail}&size=19" width="88" alt="{$events[events].speaker_info}" style="display:block;max-width:88px;" border="0" /></td>
                            <td width="2" bgcolor="#3c8e77" style="line-height:1px;font-size:1px;"><img src="{$baseUrl}modules/newsletter/templates/images/spacer.gif" width="2" height="1" alt="spacer" style="display:block;" border="0" /></td>
                          </tr>
                        </table>

                    </td>
                  </tr>
                  <tr>
                    <td height="2" bgcolor="#3c8e77" style="line-height:1px;font-size:1px;"><img src="{$baseUrl}modules/newsletter/templates/images/spacer.gif" width="1" height="2" alt="spacer" style="display:block;" border="0" /></td>
                  </tr>
                </table>

            </td>
            <td width="15" class="em_width5">&nbsp;</td>
            <td align="left" valign="top">
                <table width="100%" border="0" cellspacing="0" cellpadding="0">
                  <tr>
                    <td align="left" class="em_black" valign="top" style="font-family:'Lucida Sans Unicode', 'Lucida Grande', Arial, sans-serif;font-style:italic;font-size:12px;line-height:16px;color:#000000;">{$events[events].startdate|date_format:"%e %B"} {$events[events].event_time}</td>
                  </tr>
                  <tr><td height="2" style="line-height:1px;font-size:1px;">&nbsp;</td></tr>
                  <tr>
                    <td align="left" class="em_font14" valign="top" style="font-family:'Lucida Sans Unicode', 'Lucida Grande', Arial, sans-serif;font-size:16px;line-height:20px;text-transform:uppercase;color:#ffffff;">{$events[events].title}</td>
                  </tr>
                  <tr><td height="2" style="line-height:1px;font-size:1px;">&nbsp;</td></tr>
                   <tr>
                    <td align="left" valign="top" class="em_font12" style="font-family:'Lucida Sans Unicode', 'Lucida Grande', Arial, sans-serif;font-size:14px;line-height:18px;color:#ffffff;">{$events[events].summary}</td>
                  </tr>
                  <tr><td height="2" style="line-height:1px;font-size:1px;">&nbsp;</td></tr>
                  <tr>
                    <td align="left" valign="top" class="em_font12" style="font-family:'Lucida Sans Unicode', 'Lucida Grande', Arial, sans-serif;font-style:italic;font-size:14px;line-height:18px;color:#000000;">{$events[events].speaker_info}</td>
                  </tr>
                  
                </table>

            </td>
          </tr>
        </table>

    </td>
  </tr>
  <tr>
    <td height="15" style="line-height:1px;font-size:1px;">&nbsp;</td>
  </tr>
  <tr>
    <td align="left" valign="top" class="em_white" style="font-family:'Trebuchet MS', Arial, Helvetica, sans-serif;font-size:14px;text-transform:uppercase;color:#ffffff;letter-spacing:2px;line-height:18px;font-weight:bold;"><a href="{$baseUrl}events/{$events[events].page_name}" target="_blank" style="text-decoration:none;color:#ffffff;">FIND OUT MORE</a></td>
  </tr>
</table>
{/section}