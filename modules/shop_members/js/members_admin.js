
$(function() {
    SetOtherVisibilityFromDropDown('business_type', 9);
    SetOtherVisibilityFromDropDown('hear_about', 9);
    // setParnersDropDown();     
    updatePartners('show_on_map', '3', 'partner_id');
});
function SetOtherVisibilityFromDropDown(field, otherVal) {
    if ($('[name=' + field + ']').val() != '9')
        $('[name=' + field + '_other]').parents("tr").hide();
    $('[name=' + field + ']').change(function() {
        $('[name=' + field + '_other]').parents("tr").toggle($(this).val() == '9');
    });
}
function updatePartners(field, otherVal, childElement) {
    if ($('[name=' + field + ']').val() != otherVal) {
        $('[name=' + childElement + ']').parents("tr").hide();
    }
    $('[name=' + field + ']').change(function() {
        $('[name=' + childElement + ']').parents("tr").toggle($(this).val() == otherVal);
    });
}
function copyFromBillingAddress() {
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
function copyFromDeliveryAddress() {
    $('[name=mapping_address1]').val($('[name=delivery_address1]').val());
    $('[name=mapping_address2]').val($('[name=delivery_address2]').val());
    $('[name=mapping_address3]').val($('[name=delivery_address3]').val());
    $('[name=mapping_postalcode]').val($('[name=delivery_postalcode]').val());
    $('[name=mapping_country_id]').val($('[name=delivery_country_id]').val());
    $('[name=mapping_email]').val($('[name=email]').val());
}

