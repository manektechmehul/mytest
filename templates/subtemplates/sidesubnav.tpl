{if $submenu}

{assign var="thirdlevelmenu" value="false"}  
{section name=submenu loop=$submenu}
{if $submenu[submenu].on || $submenu[submenu].open}          
{section name=pagemenu loop=$submenu[submenu].pageMenu}
    {assign var="thirdlevelmenu" value="true"}   
{/section}{/if}
{/section}
            <div class="acordian-ttl"{if ($thirdlevelmenu == "true")}{/if}>{$sectiontitle}</div>
            <ul id="top-accordian" class="accordion ">
{section name=submenu loop=$submenu}
              <li class="title{if $submenu[submenu].on} active{/if}"> <a href="{$submenu[submenu].link}" class="accordion-title{if $submenu[submenu].on} active{/if}" title="{$submenu[submenu].name}">{$submenu[submenu].name}</a>
{if $submenu[submenu].on || $submenu[submenu].open}
{section name=pagemenu loop=$submenu[submenu].pageMenu}
{if $smarty.section.pagemenu.first}<ul class="accordion-content open-menu">{/if}
                  <li{if $submenu[submenu].pageMenu[pagemenu].on} class="sidesubnavon"{/if}><a href="{$submenu[submenu].pageMenu[pagemenu].link}" title="{$submenu[submenu].pageMenu[pagemenu].name}">{$submenu[submenu].pageMenu[pagemenu].name}</a></li>
{if $smarty.section.pagemenu.last}</ul>{/if}
{/section}              {/if}</li>
{/section}
            </ul>{/if}