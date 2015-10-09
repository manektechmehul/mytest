function setAllCategories()
{
    if ($("#all_categories").attr('checked'))
    {
        //$('.category_checkbox').attr('checked', false);
        $('.category_checkbox').attr('disabled', true);
    }
    else
        $('.category_checkbox').attr('disabled', false);
}
/*
 function save_report()
 {
 checkboxes = $('.category_checkbox');
 for (elem in checkboxes)
 var id = $('#saved_report_name').val();
 var jsel = document.createElement('SCRIPT');
 jsel.type = 'text/javascript';
 jsel.src = '/modules/shop/admin/ajax.php?action=load_report&id='+id;
 document.body.appendChild (jsel);         
 } */
function load_report()
{
    var id = $('#saved_report_name').val();
    var jsel = document.createElement('SCRIPT');
    jsel.type = 'text/javascript';
    jsel.src = '/modules/shop/admin/ajax.php?action=load_report&id=' + id;
    document.body.appendChild(jsel);
}
function delete_report()
{
    if (confirm('Are you sure you want to delete this report?'))
    {
        var id = $('#saved_report_name').val();
        var jsel = document.createElement('SCRIPT');
        jsel.type = 'text/javascript';
        jsel.src = '/modules/shop/admin/ajax.php?action=delete_report&id=' + id;
        document.body.appendChild(jsel);
    }
}
function remove_report_from_list()
{
    $('#saved_report_name option:selected').remove();
    //.selected.remove()
}
function set_report_fields(detailed, maker, all_cats, report_name, cats)
{
    $('#all_categories').attr('checked', all_cats);
    $('#maker' + maker).attr('selected', true);
    if (detailed > 0)
        $('#detail_radio').attr('checked', true);
    else
        $('#summary_radio').attr('checked', true);
    if (all_cats)
    {
        $('.category_checkbox').attr('disabled', true);
    }
    else
    {
        $('.category_checkbox').attr('checked', false).attr('disabled', false);
        for (cat in cats)
            $('#cat_' + cat).attr('checked', true);
    }
}
function validate_sales_report_form()
{
    try
    {
        got_cat = $('.category_checkbox').is(":checked");
        if ($('#start-date').val() == "")
            alert('Please enter a start date')
        else if ($('#end-date').val() == "")
            alert('Please enter an end date');
        else if (($('#all_categories').attr('checked') == false) && (got_cat == false))
            alert('Please select at least one category or the all categories option');
        else
            return true;
    }
    catch (excpt)
    {
        alert(excpt.message)
        return false;
    }
    return false;
}
$(function()
{
	alert('start');
    $('#start-date').datePicker({startDate: '01/01/2008'});
    $('#end-date').datePicker({startDate: '01/01/2008'});
    $('#start-date').bind(
            'dpClosed',
            function(e, selectedDates)
            {
                var d = selectedDates[0];
                if (d) {
                    d = new Date(d);
                    $('#end-date').dpSetStartDate(d.addDays(1).asString());
                }
            }
    );
    $('#start-date').bind(
            'dpClosed',
            function(e, selectedDates)
            {
                var d = selectedDates[0];
                if (d) {
                    d = new Date(d);
                    $('#start-date').dpSetEndDate(d.addDays(-1).asString());
                }
            }
    );
});
