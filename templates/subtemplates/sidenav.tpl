<ul class="sidenav">

{section name=menu loop=$menu} 

<li><a class="sidenav{if $menu[menu].on}on{/if}{if $smarty.section.menu.first} sidenavtop{/if}" href="{$menu[menu].link}">{$menu[menu].name}</a></li>
	{section name=submenu loop=$menu[menu].submenu}
	{if $smarty.section.submenu.first}<ul class="sidesubnav">{/if}
		<li><a class="sidesubnav" href="{$menu[menu].submenu[submenu].link}">{$menu[menu].submenu[submenu].name}</a></li>
	{if $smarty.section.submenu.last}</ul>{/if}
	{/section}
{/section}
</ul>
