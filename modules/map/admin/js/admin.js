/**
 * Created by glen on 12/03/2015.
 */

function copyFromBillingAddress(){
    //alert('bingo');
    //alert($('[name=billing_address1]').val());

    $('[name=mapping_address1]').val($('[name=billing_address1]').val());
    $('[name=mapping_address2]').val($('[name=billing_address2]').val());
    $('[name=mapping_address3]').val($('[name=billing_address3]').val());
    $('[name=mapping_postalcode]').val($('[name=billing_postalcode]').val());
    $('[name=mapping_country_id]').val($('[name=billing_country_id]').val());
    $('[name=mapping_email]').val($('[name=email]').val());



    // need to set country

}
function copyFromDeliveryAddress(){
    $('[name=mapping_address1]').val($('[name=delivery_address1]').val());
    $('[name=mapping_address2]').val($('[name=delivery_address2]').val());
    $('[name=mapping_address3]').val($('[name=delivery_address3]').val());
    $('[name=mapping_postalcode]').val($('[name=delivery_postalcode]').val());
    $('[name=mapping_country_id]').val($('[name=delivery_country_id]').val());
    $('[name=mapping_email]').val($('[name=email]').val());

}

function clearMappingAddress(){
    $('[name=mapping_address1]').val('');
    $('[name=mapping_address2]').val('');
    $('[name=mapping_address3]').val('');
    $('[name=mapping_postalcode]').val('');
    $('[name=mapping_country_id]').val('first thing');
    //   $('[name=mapping_email]').val('');
}


function set_featured_item(elem, featured, id)
{
    var row = elem.parentNode;
    img = elem.childNodes[0];
    if (featured != 'featured')
    {
        img.oSrc = img.oSrc.replace(/show/g, 'hide');
        img.src = img.src.replace(/show/g, 'hide');
        img.oSrc = img.oSrc.replace(/not.featured/g, 'featured');
        img.src = img.src.replace(/not.featured/g, 'featured');
        elem.onclick = function () { return set_featured_item(this, 'featured', id); };
        value = 1;
    }
    else
    {
        img.oSrc = img.oSrc.replace(/hide/g, 'show');
        img.src = img.src.replace(/hide/g, 'show');
        img.oSrc = img.oSrc.replace(/featured/g, 'not featured');
        img.src = img.src.replace(/featured/g, 'not featured');
        elem.onclick = function () { return set_featured_item(this, 'not featured', id); };
        value = 0;
    }

    // Create new JS element
    var jsel = document.createElement('SCRIPT');
    jsel.type = 'text/javascript';
    jsel.src = '/modules/map/admin/ajax_functions.php?action=setfeatured&id='+id+'&value='+value;

    // Append JS element (thereby executing the 'AJAX' call)
    document.body.appendChild (jsel);
    //document.body.removeChild(jsel);	return false;

    return false
}
