$(function()
{
    $('#report-date').datePicker({startDate:'01/01/2008'}).val(new Date().asString()).trigger('change'); 
});
