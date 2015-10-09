{literal}
<style>
.sidenav { display:none; }
@media screen and (min-width:768px) { .sidenav { display:block; } }
</style>
{/literal}

{literal}<script>
    $(document).ready(function() {
        // hide state dropdowns if not US		
        showhidebillingstate();
        showhidedeliverystate();
    });
    function showhidebillingstate() {
        // US states show / hide
        if ($('#VAT_territory').val() == '234') {
            $('#billing_state').closest("tr").show();
            $('#billing_state').val('-1');
        } else {
            $('#billing_state').closest("tr").hide();
        }
        // if - deliver_to_billing_address
        if ($('[name=deliver_to_billing_address]').attr('checked') == 'checked') {
            $('#dt').val($('#VAT_territory').val());
            syncCountryandState();
        }
    }
    function updatedbillingstate() {
        if ($('[name=deliver_to_billing_address]').attr('checked') == 'checked') {
            syncCountryandState();
        }
    }
    function showhidedeliverystate() {
        // US states show / hide
        if ($('#dt').val() == '234') {
            $('#delivery_state').closest("tr").show();
            $('#delivery_state').val('-1');
        } else {
            $('#delivery_state').closest("tr").hide();
        }
    }
    function toggle_delivery(chbx)
    {
        var inputs = document.getElementsByTagName('INPUT');
        var dsbld = chbx.value;
        var i = 0
        while (inputs[i])
        {
            if (inputs[i].name.substring(0, 8) == 'delivery')
            {
                inputs[i].disabled = !inputs[i].disabled;
                if (inputs[i].disabled) {
                    inputs[i].style.background = "#cccccc";
                    $('#dt').val($('#VAT_territory').val());
                    $('#dt').attr("disabled", true);
                    $('#delivery_state').attr("disabled", true);
                } else {
                    inputs[i].style.background = "";
                    $('#dt').attr("disabled", false);
                    $('#delivery_state').attr("disabled", false);
                }
                inputs[i].value = '';
            }
            i++;
        }
        syncCountryandState();
    }
    function syncCountryandState() {
        billing_country = $('#VAT_territory').val();
        if (billing_country == '234') {
            // in usa - so display and sync state drop downs
            // show delivery state 
            $('#delivery_state').closest("tr").show();
            $('#delivery_state').val($('#billing_state').val());
        } else {
            //alert(' enabling del state');
            // non usa - hide and reset state dropdown
            $('#delivery_state').closest("tr").hide();
            $('#delivery_state').val('-1');
            $('#billing_state').val('-1');
        }
        //$('#dt').val(billing_country);
        // manage the state drop downs
        updateValues();
    }
    function updateValues() {
        vatt = $("#VAT_territory").val();
        $("[name=VAT_Territory_value]").val(vatt);
        dtval = $("[name=dt]").val();
        $("[name=dtv]").val(dtval);
    }
</script>{/literal}
{$form_display}
 