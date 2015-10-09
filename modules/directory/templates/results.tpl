{include file="$alpha_template"}

{section name=item loop=$results_items}
    <div class="listitem listitemdirectory">
        <h3>{$results_items[item].firstname} {$results_items[item].surname}</h3>
        <p>{$results_items[item].email}</p>
        <p>{$results_items[item].billing_address1} </p>
        <p>{$results_items[item].billing_address2} </p>
        <p>{$results_items[item].billing_address3} </p>
        <p>{$results_items[item].postalcode} </p>
        <p>{$results_items[item].phone} </p>
    </div>
    {if $smarty.section.item.last}
      <div class="pagination-centered"><ul class="pagination">{$pagination}</ul></div>
    {/if}
    {sectionelse}
    <p style="text-align:center;">Sorry, no results found for your search or letter</p>
{/section}