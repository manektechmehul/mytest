
{section name=staticPromo loop=$staticPromoLinks}
    <a {if $staticPromoLinks[staticPromo].external}target="_blank" {/if}href="{check_link link=`$staticPromoLinks[staticPromo].link` external=`$staticPromoLinks[staticPromo].external`}">
      <div class="staticpromo staticpromo-{$staticPromoLinks[staticPromo].id}" style="background:url(/UserFiles/Image/{$staticPromoLinks[staticPromo].thumb}) no-repeat;">
        <h3>{$staticPromoLinks[staticPromo].name}</h3>
        <p class="staticpromosummary">{$staticPromoLinks[staticPromo].subheading}</p>
        <p class="staticpromobutton">{$staticPromoLinks[staticPromo].button_text}</p>
      </div>
    </a>
{/section}