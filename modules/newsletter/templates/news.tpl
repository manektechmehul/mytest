{section name='news' loop=$news}
<tr>
  <td align="left" valign="top" style="font-family:'Lucida Sans Unicode', 'Lucida Grande', Arial, sans-serif;"><span style="font-size:12px;line-height:18px;color:#cdc8e6;font-style:italic;">{$news[news].date|date_format:"%e %B %Y"}</span>&nbsp;<span style="font-size:14px;line-height:18px;color:#cdc8e6;">| <a href="{$baseUrl}news/{$news[news].page_name}" style="font-size:14px;line-height:18px;color:#cdc8e6; text-decoration:none;">{$news[news].title}</a></span></td>
</tr>
<tr>
  <td height="2" style="line-height:1px;font-size:1px;">&nbsp;</td>
</tr>
<tr>
  <td align="left" valign="top" style="font-family:'Lucida Sans Unicode', 'Lucida Grande', Arial, sans-serif;font-style:italic;font-size:14px;line-height:18px;color:#ffffff;">{$news[news].summary}</td>
</tr>
<tr>
  <td height="8" style="line-height:1px;font-size:1px;">&nbsp;</td>
</tr>
{/section}
{*$news[news].date|date_format:"%A, %B %e, %Y"*}