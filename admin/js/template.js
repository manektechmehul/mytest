function use_reserve_image(event, show, toggle_fieldname){
    $(event.target).parent().closest( "div" ).hide();
    if(show){
        // show prever options
       $(event.target).parent().next("div").show();       
    }else{
        $(event.target).parent().prev("div").show(); 
    }
    $('[name="' + toggle_fieldname + '"]').val(show);
}

function start_preserve_image_layout(show, block_id){
      // alert("show is " + show + ' + the the block id is ' + block_id);
      if(show=='0'){    
         $('#' + block_id + " .preserve_image").hide();
      }else{          
         $('#' + block_id + " .regular_image").hide(); 
      }       
}


function selectimage(elem, size){
	var myFile = '/php/filecontroller/imagecontroller.php?field='+elem+'&size='+size;
	var myName = 'ImageManager'
	var image = window.open(myFile, myName, "width=870,height=600,status=no,toolbar=no,directories=no,scrollbars=no,location=no,resizable=no,menubar=no");
	return false;
}
function clear_image(hidden_field_id){
	missing_image = "/cmsimages/missing-image.png";
	span_image_id = hidden_field_id + '_img';
	$('[name=' + hidden_field_id + ']').val('');
	// do not show the not found img, set back	
	$('#' + hidden_field_id + '_img img').attr('src',''); 	
	// if we wanted to show the missing image graphic
	// 	$('#' + hidden_field_id + '_img img').attr('src','/cmsimages/missing-image.png'); 	
	//to prevent postback
	return false;
}

function clear_file(hidden_field_id){       
	// clear the hidden file
        $('[name=' + hidden_field_id + ']').val('');
        $('#' + hidden_field_id + '_file').hide();
        $('#' + hidden_field_id + '_file').empty();        
        // to prevent postback
	return false;
}

function selectfile(elem, size)
{
	var myFile = '/php/filecontroller/filecontroller.php?field='+elem;
	var myName = 'FileManager'

	var image = window.open(myFile, myName, "width=900,height=600,status=no,toolbar=no,directories=no,scrollbars=no,location=no,resizable=no,menubar=no");
	return false;
}

function call_item_show_hide(type, id, action)
{
    // Create new JS element
    var jsel = document.createElement('SCRIPT');
    jsel.type = 'text/javascript';
    jsel.src = '/admin/ajax_functions.php?type='+type+'&id='+id+'&action='+action;
    // Append JS element (thereby executing the 'AJAX' call)
    document.body.appendChild (jsel);
    //document.body.removeChild(jsel);
}

function show_item(elem, type, id)
{
	var row = elem.parentNode;   
	img = elem.childNodes[0];
	img.oSrc = img.oSrc.replace(/show/g, 'hide');
	img.src = img.src.replace(/show/g, 'hide');
	elem.onclick = function () { return hide_item(this, type, id); };
	var namefield = row.firstChild;
	namefield.style.backgroundImage =  'url(/admin/images/content-field-active.gif)';
	call_item_show_hide(type, id, 'show');
	return false;
}

function hide_item(elem, type, id)
{
	var row = elem.parentNode;	
	img = elem.childNodes[0];
	img.oSrc = img.oSrc.replace(/hide/g, 'show');
	img.src = img.src.replace(/hide/g, 'show');
	elem.onclick = function () { return show_item(this, type, id); };
	var namefield = row.firstChild;
	namefield.style.backgroundImage =  'url(/admin/images/content-field-inactive.gif)';
	call_item_show_hide(type, id, 'hide');
	return false;
}

function button_off (elem)
{
	MM_swapImgRestore();
}

function button_over(elem)
{
	if (elem.nodeName == 'A')
	{
		var newscr = elem.childNodes[0].src.replace('off.', 'over.');  
		MM_swapImage(elem.childNodes[0].name,'',newscr,0)
	}
	else
	{
		var newscr = elem.src.replace('off.', 'over.');
		MM_swapImage(elem.name,'',newscr,0)
	}
	//alert(newscr);
}

function call_item_move(type, id, direction)
{
    // Create new JS element
    var jsel = document.createElement('SCRIPT');
    jsel.type = 'text/javascript';
    jsel.src = '/admin/ajax_functions.php?type='+type+'&id='+id+'&direction='+direction;

    // Append JS element (thereby executing the 'AJAX' call)
    document.body.appendChild (jsel);
    //document.body.removeChild(jsel);
}

function call_items_swap(type, id, otherid)
{
    // Create new JS element
    var jsel = document.createElement('SCRIPT');
    jsel.type = 'text/javascript';
	jsel.src = '/admin/ajax_functions.php?type='+type+'&id='+id+'&otherid='+otherid+'&action=swap';

    // Append JS element (thereby executing the 'AJAX' call)
    document.body.appendChild (jsel);
    //document.body.removeChild(jsel);
}

function swaprows (first, second)
{
	var parent = second.parentNode;
	var newrow = second.cloneNode(true);
	parent.removeChild(second);
	parent.insertBefore(newrow, first);	
}

function move_item_up(elem, type, id)
{
	var row = elem.parentNode.parentNode.parentNode;
	var prevRow = row.previousSibling;
	if ((prevRow) && (prevRow.className.search(/fixed/) == -1))
	{
        prev_id = prevRow.id.replace(/.*item-/, '');
		elem.src = elem.src.replace('over.', 'off.');
		elem.oSrc = elem.src;
		call_items_swap(type, id, prev_id);
        swaprows(prevRow,row);
		
		//var inner = row.innerHTML;
		//alert(inner);
		//var prevInner = prevRow.innerHTML;
		//row.innerHTML = prevInner;
		//prevRow.innerHTML = inner;
	}
}

function move_item_down(elem, type, id)
{
	var row = elem.parentNode.parentNode.parentNode;
	var nextRow = row.nextSibling;
	if (nextRow)
	{
		next_id = nextRow.id.replace(/.*item-/, '');
		elem.src = elem.src.replace('over.', 'off.');
		elem.oSrc = elem.src;
		call_items_swap(type, id, next_id);
        swaprows(row,nextRow);	

//		var inner = row.innerHTML;
//		row.innerHTML = nextRow.innerHTML;
//		nextRow.innerHTML = inner;
	}
}

function callback()
{
    alert('ehh');
}



function set_colour_for_equality (elem)
{
	hiddenField = document.getElementById(elem.id+'_default');
	if (elem.value == hiddenField.value)
		elem.style.color = 'gray';
	else
		elem.style.color = 'black';
}

function show_hidden_text_grey(elem_id)
{
	field = document.getElementById(elem_id);
	if (field.value == '')
	{
		hiddenField = document.getElementById(elem_id+'_default');
		field.value = hiddenField.value;
		field.style.color = 'gray';
		field.onkeyup = function () { set_colour_for_equality(this); }
		field.onclick = function () { set_colour_for_equality(this); }
		field.onblur = function () { set_colour_for_equality(this); }
	}
}
function set_tag_fields()
{
	show_hidden_text_grey('tags_title');
	show_hidden_text_grey('tags_description');
	show_hidden_text_grey('tags_keywords');
}

function pop_up (elem)
{
	var image = window.open(elem.href, elem.name ,"width=870, height=600,status=no,toolbar=no,directories=no,scrollbars=no,location=no,resizable=no,menubar=no");
	return false;
}

function doPreview()
{
	window.open("preview.php","myNewWin","width=994,height=790,toolbar=0"); 
    var a = window.setTimeout("delayed_submit();",500); 
	return false;
}

function delayed_submit ()
{
	orig_target = document.forms.page_form.target;
	orig_action = document.forms.page_form.action;
	document.forms.page_form.target = 'myNewWin';
	document.forms.page_form.action = 'preview.php';	document.forms.page_form.submit();
	document.forms.page_form.target = orig_target;
	document.forms.page_form.action = orig_action;
}