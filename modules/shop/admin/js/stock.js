function adjust_stock(prodform)
{
    try
    {
        var level = prodform.elements['qty'].value;
        var id = prodform.elements['product_id'].value;
        prodform.elements['submit'].disabled = true;
        var jsel = document.createElement('SCRIPT');
        jsel.type = 'text/javascript';
        jsel.src = '/modules/shop/admin/ajax.php?action=set_stock_level&id=' + id + '&level=' + level;
        document.body.appendChild(jsel);
    }
    finally
    {
        return false;
    }
}
function add_stock(prodform)
{
    try
    {
        var change = prodform.elements['qty_add'].value;
        var id = prodform.elements['add_product_id'].value;
        prodform.elements['qty_add'].value = '';
        prodform.elements['submit_add'].disabled = true;
        var jsel = document.createElement('SCRIPT');
        jsel.type = 'text/javascript';
        jsel.src = '/modules/shop/admin/ajax.php?action=change_stock_level&id=' + id + '&change=' + change;
        document.body.appendChild(jsel);
    }
    finally
    {
        return false;
    }
}
function remove_stock(prodform)
{
    try
    {
        var change = prodform.elements['qty_remove'].value;
        var id = prodform.elements['remove_product_id'].value;
        prodform.elements['qty_remove'].value = '';
        prodform.elements['submit_remove'].disabled = true;
        var jsel = document.createElement('SCRIPT');
        jsel.type = 'text/javascript';
        jsel.src = '/modules/shop/admin/ajax.php?action=change_stock_level&id=' + id + '&change=-' + change;
        document.body.appendChild(jsel);
    }
    finally
    {
        return false;
    }
}
function show_stock_level(id, level)
{
    id = "#level_product_id_" + id;
    $(id).html(level).css('color', 'blue').css('font-weight', 'bold');
}
function enable_button(btn_name)
{
    $('#' + btn_name).attr('disabled', false)
}