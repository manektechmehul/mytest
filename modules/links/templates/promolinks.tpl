{assign var=number value=0}
	<ol class="carousel-indicators">
{section name=promo loop=$promoLinks}
      <li data-target="#myCarousel" data-slide-to="{$number++}"{if $smarty.section.promo.first} class="active"{/if}></li>
{/section}
	</ol>
	<div class="carousel-inner">
{section name=promo loop=$promoLinks}
      <div class="item{if $smarty.section.promo.first} active{/if}">
        <img src="/UserFiles/Image/{$promoLinks[promo].thumb}" alt="{$promoLinks[promo].name}">
	    <div class="caption-container">
	      <div class="carousel-caption">
	        <h3><a {if $promoLinks[promo].external}target="_blank" {/if}href="{check_link link=`$promoLinks[promo].link` external=`$promoLinks[promo].external`}">{$promoLinks[promo].name}</a></h3>
	        <p>{$promoLinks[promo].subheading}</p>
	      </div>
	    </div>
	  </div>
{/section}
    </div>