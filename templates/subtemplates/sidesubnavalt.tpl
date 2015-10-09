{if $submenu}
            <div id="sidenav">
                <ul>
{section name=submenu loop=$submenu}
                    <li{if $submenu[submenu].on} class="sidenavon"{/if}><a href="{$submenu[submenu].link}">{$submenu[submenu].name}</a>

	{if $submenu[submenu].on}
	{section name=pagemenu loop=$submenu[submenu].pageMenu}
	{if $smarty.section.pagemenu.first}<ul>{/if}
		<li{if $submenu[submenu].pageMenu[pagemenu].on} class="sidesubnavon"{/if}><a href="{$submenu[submenu].pageMenu[pagemenu].link}">{$submenu[submenu].pageMenu[pagemenu].name}</a></li>
	{if $smarty.section.pagemenu.last}</ul>{/if}
	{/section}
	{/if}</li>
	
{/section}
					<li><a href="/donate">Donate</a></li>
                </ul>
            </div>
{/if}