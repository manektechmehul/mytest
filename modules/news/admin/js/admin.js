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

function set_featured_item(elem, featured, id)
{
	/* #module specific */
	module_url = "news";

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
	jsel.src = '/modules/' + module_url + '/admin/ajax_functions.php?action=setfeatured&id='+id+'&value='+value;

	// Append JS element (thereby executing the 'AJAX' call)
	document.body.appendChild (jsel);
	//document.body.removeChild(jsel);	return false;
	return false
}