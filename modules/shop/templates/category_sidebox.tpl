{if $cats_list}
<div class="acordian-ttl">Shop items</div>
<ul id="shop-accordian" class="accordion ">
        {section name=cats loop=$cats_list}
        {if $cats_list[cats].page_type == 'shop'}
        {if $cats_list[cats].level == '1'}
          <li class="title"><a href="{$cats_list[cats].link}" class="accordion-title">{$cats_list[cats].name}</a></li>
        {/if}
        {/if}
        {if $cats_list[cats].page_type == 'category' || $cats_list[cats].page_type == 'subcategory'}
        {if $cats_list[cats].cur_root_cat == 'true'}
          <li class="title active"><a href="{$cats_list[cats].link}" class="accordion-title active">{$cats_list[cats].name}</a>
          {if  $cats_list[cats].cat_items > 1}
            <ul class="accordion-content open-menu">
            {section name=subcats loop=$subcats_list}
              <li{if $subcats_list[subcats].current_cat == 'true' } class="sidesubnavon"{/if}><a href="{$subcats_list[subcats].link}">{$subcats_list[subcats].name}</a></li>
            {/section}
            </ul>
          {/if}
          </li>
        {else}
          <li class="title"><a href="{$cats_list[cats].link}" class="accordion-title">{$cats_list[cats].name}</a></li>
        {/if}   
        {else}  			
        {if $cats_list[cats].cur_root_cat == 'true'}   
          <li class="title active"><a href="{$cats_list[cats].link}" class="accordion-title active">{$cats_list[cats].name}</a></li>
        {/if}
        {/if}	
        {/section}
</ul>
{/if}