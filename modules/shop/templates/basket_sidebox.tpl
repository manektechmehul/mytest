{literal}
<script>
    $(document).ready(function () {
        {/literal}
        {$show_message}
        {literal}

    });
</script>
{/literal}

{if $smarty.session.session_member_name}
    <p>Welcome {$smarty.session.session_member_details.firstname}</p>
{else}
    <p>You should not see this (<a href="/members">Login</a>)</p>
{/if}
<ul>
    <li><a href="/members" title="Edit your details">Edit details</a></li>
    <li><a href="/members/logout" title="Log out">Log out</a></li>
    {if $basket->total == ""}{else}
        <li>{assign var="point" value=$basket->total|string_format:"%.2f"|strpos:"."}<a href="/shop/basket"
                                                                                        title="Shop basket">
        Basket &pound;{$basket->total|string_format:"%.2f"|substr:0:$point}
        .{$basket->total|string_format:"%.2f"|substr:$point+1}</a></li>{/if}
</ul>