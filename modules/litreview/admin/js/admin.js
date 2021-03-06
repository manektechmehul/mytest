function set_archive_item(elem, archive, id)
{
    var row = elem.parentNode;
	img = elem.childNodes[0];
	if (archive != 'archive')
	{
		img.oSrc = img.oSrc.replace(/show/g, 'hide');
		img.src = img.src.replace(/show/g, 'hide');
		img.oSrc = img.oSrc.replace(/not.archive/g, 'archive');
		img.src = img.src.replace(/not.archive/g, 'archive');
		elem.onclick = function () { return set_archive_item(this, 'archive', id); };
		value = 1;
	}
	else
	{
		img.oSrc = img.oSrc.replace(/hide/g, 'show');
		img.src = img.src.replace(/hide/g, 'show');
		img.oSrc = img.oSrc.replace(/archive/g, 'not archive');
		img.src = img.src.replace(/archive/g, 'not archive');
		elem.onclick = function () { return set_archive_item(this, 'not archive', id); };
		value = 0;
	}

    // Create new JS element
    var jsel = document.createElement('SCRIPT');
    jsel.type = 'text/javascript';
    jsel.src = '/modules/news/admin/ajax_functions.php?action=setarchive&id='+id+'&value='+value;

    // Append JS element (thereby executing the 'AJAX' call)
    document.body.appendChild (jsel);
    //document.body.removeChild(jsel);	return false;

	return false
}



$('select[name^="startdate"]').live('change', function () {
    startday = $('select[name="startdate_day"]').val();
    endday = $('select[name="enddate_day"]').val();
    startmonth = $('select[name="startdate_month"]').val();
    endmonth = $('select[name="enddate_month"]').val();
    startyear = $('select[name="startdate_year"]').val();
    endyear = $('select[name="enddate_year"]').val();
    
    if (startyear > endyear) {
       $('select[name="enddate_year"]').val(startyear)
    }

    if ((startmonth > endmonth) && (startyear == endyear)) {
       $('select[name="enddate_month"]').val(startmonth)
    }

    if ((startday > endday) && (startmonth == endmonth) && (startyear == endyear)) {
       $('select[name="enddate_day"]').val(startday)
    }

})