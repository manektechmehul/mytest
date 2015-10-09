{section name=menu loop=$menu} 
{if $smarty.section.menu.first}<ul>{/if}
<li{if $menu[menu].on} class="current"{/if}><a href="{$menu[menu].link}">{$menu[menu].name}</a></li>
{if $smarty.section.menu.last}</ul>{/if}
{/section}