$(document).ready(function () {
    $('[name=same_as_billing]').click(function () {
        if ($('[name=same_as_billing]').is(':checked')) {
            $('[name=delivery_address1]').val($('[name=billing_address1]').val());
            $('[name=delivery_address2]').val($('[name=billing_address2]').val());
            $('[name=delivery_address3]').val($('[name=billing_address3]').val());
            $('[name=delivery_postalcode]').val($('[name=billing_postalcode]').val());
            $('[name=delivery_country]').val($('[name=billing_country]').val());
        } else {
            $('[name=delivery_address1]').val('');
            $('[name=delivery_address2]').val('');
            $('[name=delivery_address3]').val('');
            $('[name=delivery_postalcode]').val('');
        }
    });
});