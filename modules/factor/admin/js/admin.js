

$(function(){
   // DOM Ready - do your stuff

    updateLookuptype();

     jQuery('[name=link_type]').change(function(){
         updateLookuptype();
     });

});

function hideAllPageElements(){
           $('[name=title]').parents("tr").hide();
           $('[name=module_id]').parents("tr").hide();
           $('[name=summary]').parents("tr").hide();
           $('[name=file]').parents("tr").hide();
           $('[name=thumb]').parents("tr").hide();
           $('[name=link]').parents("tr").hide();
           $('[name=external_link]').parents("tr").hide();
           $('[name=video_type]').parents("tr").hide();
           $('[name=video_id]').parents("tr").hide();
           $('[name=freetext]').parents("tr").hide();
           $('[name=audio]').parents("tr").hide();            
}

function updateLookuptype(){

        hideAllPageElements();
        speed = 'slow';
        //had to add an id here, does seem to like name here
        var _type = $('#link_type').val();
        //   alert(_type);
        if(_type=='1'){
            // case study
           // $('[name=title]').parents("tr").fadeIn(speed);
            $('[name=module_id]').parents("tr").fadeIn(speed);

        }
        if(_type=='2'){
            // downloads
            $('[name=title]').parents("tr").fadeIn(speed);
            $('[name=thumb]').parents("tr").fadeIn(speed);
            $('[name=summary]').parents("tr").fadeIn(speed);
            $('[name=audio]').parents("tr").fadeIn(speed);            
            $('[name=file]').parents("tr").fadeIn(speed);


        }
        if(_type=='3'){
            // Links
            $('[name=title]').parents("tr").fadeIn(speed);
            $('[name=thumb]').parents("tr").fadeIn(speed);
            $('[name=summary]').parents("tr").fadeIn(speed);
            $('[name=link]').parents("tr").fadeIn(speed);
            $('[name=external_link]').parents("tr").fadeIn(speed);
        }
        if(_type=='4'){
            // Video
            $('[name=title]').parents("tr").fadeIn(speed);
            $('[name=summary]').parents("tr").fadeIn(speed);
            $('[name=video_type]').parents("tr").fadeIn(speed);
            $('[name=video_id]').parents("tr").fadeIn(speed);

        }
        if(_type=='5'){
            // static
            $('[name=title]').parents("tr").fadeIn(speed);
            $('[name=freetext]').parents("tr").fadeIn(speed);
            $('[name=thumb]').parents("tr").fadeIn(speed);

        }



        //   $('[name=summary]').parents("tr").hide();
        //   $('[name=file]').parents("tr").hide();
        //   $('[name=thumb]').parents("tr").hide();
        //   $('[name=link]').parents("tr").hide();
         //  $('[name=external_link]').parents("tr").hide();
         //  $('[name=video_type]').parents("tr").hide();
         //  $('[name=video_id]').parents("tr").hide();
         //  $('[name=freetext]').parents("tr").hide();



         /*
            * 'link_type' => array('name' => 'Link Type', 'formtype' => 'lookup','required' => false,'function'=>'linktypelookup'),
                    'module_id' => array('name' => 'module_id', 'formtype' => 'text','required' => false),
                    'title' => array('name' => 'title', 'formtype' => 'text','required' => false, 'list' => true,),
                    'summary' => array('name' => 'summary', 'formtype' => 'fckhtml','required' => false),
                    'file' => array('name' => 'file', 'formtype' => 'file','required' => false),
                    'thumb' => array('name' => 'thumb', 'formtype' => 'image','required' => false),
                    'link' => array('name' => 'link', 'formtype' => 'text','required' => false),
                    'external_link' => array('name' => 'external_link', 'formtype' => 'checkbox','required' => false),
                    'video_type' => array('name' => 'video_type', 'formtype' => 'lookup','required' => false,'function'=>'videotypelookup'),
                    'video_id' => array('name' => 'video_id', 'formtype' => 'shorttext','required' => false),
                    'freetext
            *
            *
            */









}

function set_featured_item(elem, featured, id)
{
        /* #module specific */
        module_url = "factor";
    
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