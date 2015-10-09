{include file="$alpha_template"}

{section name=item loop=$itemList}
    <div class="listitem listitemdirectory">
        <h3>{$itemList[item].firstname} {$itemList[item].surname}</h3>
        <p>{$itemList[item].email}</p>
        <p>{$itemList[item].billing_address1} </p>
        <p>{$itemList[item].billing_address2} </p>
        <p>{$itemList[item].billing_address3} </p>
        <p>{$itemList[item].postalcode} </p>
        <p>{$itemList[item].phone} </p>
    </div>

  {if $smarty.section.item.last}
     <div class="pagination-centered"><ul class="pagination">{$pagination}</ul></div>
  {/if}
{sectionelse}
    <p style="text-align:center;">There are currently no members in the directory</p>
{/section}
