function set_featured_item(elem, featured, id)
{
        /* #module specific */
        module_url = "profiles";
    
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