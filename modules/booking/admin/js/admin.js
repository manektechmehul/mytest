$(function () {
	// DOM Ready - do your stuff
	updateHospiceEventtype();
	$('[name=hospice_event_ddl]').change(function () {
		updateHospiceEventtype();
	});

	updateEventtype();
	$('[name=event_type_ddl]').change(function () {
		updateEventtype();
	});

	updateShowMapTools();
	$('#show_map').click(function () {
		updateShowMapTools();
	});

});

function hideAllPageElements() {
	/*  $('[name=title]').parents("tr").hide();
	 $('[name=module_id]').parents("tr").hide();
	 $('[name=summary]').parents("tr").hide();
	 $('[name=file]').parents("tr").hide();
	 $('[name=thumb]').parents("tr").hide();
	 $('[name=link]').parents("tr").hide();
	 $('[name=external_link]').parents("tr").hide();
	 $('[name=video_type]').parents("tr").hide();
	 $('[name=video_id]').parents("tr").hide();
	 $('[name=freetext]').parents("tr").hide(); */
}

function updateShowMapTools() {
	//console.log("show map tools");
	speed = 'slow';


	//console.log(  $('#show_map').is(':checked'));


	if ($('#show_map').is(':checked')) {
		$('[name=mapping_address1]').parents("tr").fadeIn(speed);
		$('[name=mapping_address2]').parents("tr").fadeIn(speed);
		$('[name=mapping_address3]').parents("tr").fadeIn(speed);
		$('[name=mapping_postalcode]').parents("tr").fadeIn(speed);
		$('[name=mapping_country_id]').parents("tr").fadeIn(speed);
		$('[name=lat]').parents("tr").fadeIn(speed);
		$('[name=lon]').parents("tr").fadeIn(speed);
		$('#row_lon').prev("tr").fadeIn(speed); // map helper text and links

	} else {
		$('[name=mapping_address1]').parents("tr").fadeOut(speed);
		$('[name=mapping_address2]').parents("tr").fadeOut(speed);
		$('[name=mapping_address3]').parents("tr").fadeOut(speed);
		$('[name=mapping_postalcode]').parents("tr").fadeOut(speed);
		$('[name=mapping_country_id]').parents("tr").fadeOut(speed);
		$('[name=lat]').parents("tr").fadeOut(speed);
		$('[name=lon]').parents("tr").fadeOut(speed);
		$('#row_lon').prev("tr").fadeOut(speed); // map helper text and links

	}

}

function updateEventtype() {
	$('[name=event_type]').val($('[name=event_type_ddl]').val());
	speed = 'slow';
	var _type = $('[name=event_type_ddl]').val();


	if (_type == '3') {
		// case study
		// $('[name=title]').parents("tr").fadeIn(speed);
		$('[name=tickets_available]').parents("tr").fadeOut(speed);
		$('[name=parental_consent_required]').parents("tr").fadeOut(speed);


	} else {
		$('[name=tickets_available]').parents("tr").fadeIn(speed);
		$('[name=parental_consent_required]').parents("tr").fadeIn(speed);
	}
}


function updateHospiceEventtype() {

	hideAllPageElements();
	speed = 'slow';
	//had to add an id here, does seem to like name here
	var _type = $('[name=hospice_event_ddl]').val();
	$('[name=hospice_event]').val($('[name=hospice_event_ddl]').val());
	// 0 = hospice
	// 1 = community
	// console.log(_type);
	if (_type == '0') {
		// case study
		// $('[name=title]').parents("tr").fadeIn(speed);
		$('[name=event_type_ddl]').parents("tr").fadeIn(speed);
	} else {
		$('[name=event_type_ddl]').parents("tr").fadeOut(speed);
	}

}


function set_featured_item(elem, featured, id) {
	/* #module specific */
	module_url = "booking";

	var row = elem.parentNode;
	img = elem.childNodes[0];
	if (featured != 'featured') {
		img.oSrc = img.oSrc.replace(/show/g, 'hide');
		img.src = img.src.replace(/show/g, 'hide');
		img.oSrc = img.oSrc.replace(/not.featured/g, 'featured');
		img.src = img.src.replace(/not.featured/g, 'featured');
		elem.onclick = function () {
			return set_featured_item(this, 'featured', id);
		};
		value = 1;
	}
	else {
		img.oSrc = img.oSrc.replace(/hide/g, 'show');
		img.src = img.src.replace(/hide/g, 'show');
		img.oSrc = img.oSrc.replace(/featured/g, 'not featured');
		img.src = img.src.replace(/featured/g, 'not featured');
		elem.onclick = function () {
			return set_featured_item(this, 'not featured', id);
		};
		value = 0;
	}

	// Create new JS element
	var jsel = document.createElement('SCRIPT');
	jsel.type = 'text/javascript';
	jsel.src = '/modules/' + module_url + '/admin/ajax_functions.php?action=setfeatured&id=' + id + '&value=' + value;

	// Append JS element (thereby executing the 'AJAX' call)
	document.body.appendChild(jsel);
	//document.body.removeChild(jsel);	return false;
	return false
}