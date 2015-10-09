function setAllChecks(groupname, flag) {
    if (flag) {
        $.each($("input[name='" + groupname + "']"), function() {
            $(this).attr('checked', 'checked');
        });
    } else {
        $.each($("input[name='" + groupname + "']"), function() {
            $(this).removeAttr('checked');
        });
    }
}
function checkAll(field)
{
}

function collect_discount_product_ids(ret_discount_product_ids) {
    $('#discount_product_ids').val(ret_discount_product_ids);
}
function collectProducts(ret_discount_products) {
    // aray of id - desc 
    // need to set form val and js item as array.
    discount_products = ret_discount_products;
}
function collect_discounts(discounts) {
    $('#discounts').val(discounts);
}
function get_bd_discounts() {
    return $('#discounts').val();
}
function get_bd_productDetails() {
    return window.discount_products;
}
function get_colourDetails() {
    //alert(' getting colours from js');	
    return window._colourDetails;
}
function createColorSq(acolour) {
    // add item to panone section 	
    id = acolour[0];
    colorname = acolour[1];
    webcolor = acolour[3];
    name = acolour[1];
    newColorBlock = "<a href='#' >"; // title to fp 
    newColorBlock = "<li id='colour_sq_" + id + "' style='cursor: pointer;' ><div  title='" + colorname + "'  style='background-color: " + webcolor + "' > " + id + " </div></li></a>";
    return newColorBlock;
}
_colourDetails = "";
function collectColours(colourDetails) {
    _colourDetails = colourDetails;
    var acolour = new Array();
    var i = 0;
    var content = "";
    var justIds = "";
    while (colourDetails.length > i) {
        // do something item
        acolour = colourDetails[i];
        //alert(acolour);
        // id = 0, colourname = 1, webcolour = 2
        // TODO: make colour squares in the colour area of the admin page
        content = content + createColorSq(acolour);
        justIds = justIds + acolour[0] + ",";
        i++;
    }
    //justIds = substring(0, (justIds.length - 1)) ;
    //alert(justIds);
    $('#swatch_container li').remove(); // clear all previous items
    $('#swatch_container').append(content);
    $('#colour_ids').val(justIds);
    // set hidden form item ... 
    // alert('wait');
}
function makeColourBlock(acolour) {
    code = "<div title='" + acolour[1] + "' class='colourBlock' style='background:" + acolour[2] + "; width: 100px; padding: 5px; height: 24px; display: block;'>" + acolour[0] + "</div>";
    return code;
}
function apply_fields_logic()
{
    id = $('#maker_id').val();
    if (id == 0)
    {
        $('#row_commission').hide();
        $('#commission').val(0);
    }
    else
    {
        $('#row_commission').show();
    }
    $("#maker_vat_message").html('*** Reading ***');
    var jsel = document.createElement('SCRIPT');
    jsel.type = 'text/javascript';     
    // Append JS element (thereby executing the 'AJAX' call)
    document.body.appendChild(jsel);
}

function set_shipping_value_name()
{
    if ($('#shipping_id').val() == 3)
        $('#row_shipping_value').hide();
    else
        $('#row_shipping_value').show();
    if ($('#shipping_id').val() == 1)
        $('#row_shipping_value td:first').html('Shipping Weight');
    if ($('#shipping_id').val() == 2)
        $('#row_shipping_value td:first').html('Per Item Shipping Charge');
}
function change_online()
{
    if ($('#online').attr("checked"))
    {
        $('#shipping_id').parent().parent().show();
        $('#row_shipping_value').show();
    }
    else
    {
        $('#shipping_id').parent().parent().hide();
        $('#row_shipping_value').hide();
    }
}
function round(value, dp)
{
    return Math.round(value * Math.pow(10, dp)) / Math.pow(10, dp);
}
function get_tax_rate()
{
    tax_id = $('#tax_id').val();
    return parseFloat($('#tax_value_' + tax_id).val());
}

function show_price()
{
    wholesale_price = parseFloat($('#price').val());
    comm = $('#commission').val();
    comm_val = wholesale_price * comm / 100
    price = wholesale_price + comm_val
    set_maker_vat(wholesale_price)
    set_commission_vat(comm_val)
    $('#comm_amount_calc').val(money_format(comm_val));
    $('#price_calc').val(round(price, 2));
}
function change_commission()
{
    wholesale_price = parseFloat($('#price').val());
    price = parseFloat($('#price_calc').val());
    comm_val = price - wholesale_price
    comm = (comm_val * 100) / wholesale_price
    set_maker_vat(wholesale_price)
    set_commission_vat(comm_val)
    $('#commission').val(round(comm, 3));
}
function money_format(amount)
{
    amount = '�' + round(amount, 2);
    if (amount.indexOf('.') == -1)
        amount += '.';
    amount += '00';
    return amount.substr(0, amount.indexOf('.') + 3);
}



function set_shop_featured(elem, featured, id)
{
    var row = elem.parentNode;
    img = elem.childNodes[0];
    if (featured != 'shop featured')
    {
        img.oSrc = img.oSrc.replace(/show/g, 'hide');
        img.src = img.src.replace(/show/g, 'hide');
        img.oSrc = img.oSrc.replace(/shop.not.featured/g, 'shop featured');
        img.src = img.src.replace(/shop.not.featured/g, 'shop featured');
        elem.onclick = function() {
            return set_shop_featured(this, 'shop featured', id);
        };
        value = 1;
    }else{
        img.oSrc = img.oSrc.replace(/hide/g, 'show');
        img.src = img.src.replace(/hide/g, 'show');
        img.oSrc = img.oSrc.replace(/shop.featured/g, 'shop not featured');
        img.src = img.src.replace(/shop.featured/g, 'shop not featured');
        elem.onclick = function() {
            return set_shop_featured(this, 'shop not featured', id);
        };
        value = 0;
    }
    var jsel = document.createElement('SCRIPT');
    jsel.type = 'text/javascript';
    jsel.src = '/modules/shop/admin/ajax.php?action=set_shop_featured&id=' + id + '&shop_featured=' + value;
    // Append JS element (thereby executing the 'AJAX' call)
    document.body.appendChild(jsel);
    //document.body.removeChild(jsel);    return false;
    return false;
    
}

function set_featured(elem, featured, id)
{
    var row = elem.parentNode;
    img = elem.childNodes[0];
    if (featured != 'featured')
    {
        img.oSrc = img.oSrc.replace(/show/g, 'hide');
        img.src = img.src.replace(/show/g, 'hide');
        img.oSrc = img.oSrc.replace(/not.featured/g, 'featured');
        img.src = img.src.replace(/not.featured/g, 'featured');
        elem.onclick = function() {
            return set_featured(this, 'featured', id);
        };
        value = 1;
    }
    else
    {
        img.oSrc = img.oSrc.replace(/hide/g, 'show');
        img.src = img.src.replace(/hide/g, 'show');
        img.oSrc = img.oSrc.replace(/featured/g, 'not featured');
        img.src = img.src.replace(/featured/g, 'not featured');
        elem.onclick = function() {
            return set_featured(this, 'not featured', id);
        };
        value = 0;
    }
    var jsel = document.createElement('SCRIPT');
    jsel.type = 'text/javascript';
    jsel.src = '/modules/shop/admin/ajax.php?action=set_featured&id=' + id + '&value=' + value;
    // Append JS element (thereby executing the 'AJAX' call)
    document.body.appendChild(jsel);
    //document.body.removeChild(jsel);    return false;
    return false;
}
function clear_image(hidden_field_id) {
    missing_image = "/cmsimages/missing-image.png";
    span_image_id = hidden_field_id + '_img';
    // alert(hidden_field_id  + " >> " +  span_image_id);
    //alert('resetting ' + hidden_field_id );
    $('[name=' + hidden_field_id + ']').val('');
    $('#' + hidden_field_id + '_img img').attr('src', '/cmsimages/missing-image.png');
    //to prevent postback
    return false;
}
$(document).ready(function() {
    // Your code here...
    apply_fields_logic();
    set_shipping_value_name();
    // php will populate the _colourDetails from the product class
    collectColours(_colourDetails);
    $(".first").focus();
});
