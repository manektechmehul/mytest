{section name=menu loop=$menu}
{if $smarty.section.menu.first}<ul class="enumenu_ul clearfix">{/if}
    {*TOP LEVEL - HOME - ABOUT - WHAEVER *}
        <li><a{if $menu[menu].on} class="active"{/if} href="{$menu[menu].link}" title="{$menu[menu].name}">{$menu[menu].name} {if $menu[menu].submenu}<span class="hidden-xs"></span>{/if}</a>
            {*SECOND LEVEL*}
            {section name=submenu loop=$menu[menu].submenu}
                {if $smarty.section.submenu.first}<ul{if $smarty.section.menu.last} class="dropdown-child-last"{/if}>{/if}
                 <li class="second-sub"><a href="{$menu[menu].submenu[submenu].link}" title="{$menu[menu].submenu[submenu].name}">{$menu[menu].submenu[submenu].name}</a>
                    {* THIRD LEVEL *}
                    {section name=pagemenu loop=$menu[menu].submenu[submenu].pageMenu}
                        {if $smarty.section.pagemenu.first}<ul>{/if}
                        <li><a class="2" href="{$menu[menu].submenu[submenu].pageMenu[pagemenu].link}"
                               title="{$menu[menu].submenu[submenu].pageMenu[pagemenu].name}">
                                      {$menu[menu].submenu[submenu].pageMenu[pagemenu].name}</a></li>
                        {if $smarty.section.pagemenu.last}</ul>{/if}
                     {/section}
                     {*END THIRD LEVEL*}
                </li>
                {if $smarty.section.submenu.last}</ul>{/if}
            {/section}
            {*END SECOND LEVEL*}
        </li>
    {if $smarty.section.menu.last}</ul>{/if}
{/section}