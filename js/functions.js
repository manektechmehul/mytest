/* preload facility */
function MM_preloadImages()
{
	var d=document; if(d.images){ if(!d.MM_p) d.MM_p=new Array();
	var i,j=d.MM_p.length,a=MM_preloadImages.arguments; for(i=0; i<a.length; i++)
	if (a[i].indexOf("#")!=0){ d.MM_p[j]=new Image; d.MM_p[j++].src=a[i];}}
}

/* clear data */
function clearField(me)
{
	if (me.defaultValue==me.value) me.value = '';
}

/* popup */
function popUp(myFile, myName, myWidth, myHeight) {
	//var image = window.open(myFile, myName ,"width=" + myWidth + ",height=" + myHeight + ",status=no,toolbar=no,directories=no,scrollbars=no,location=no,resizable=no,menubar=no")

	var t = null
	if ((myName) && (myName != '_blank'))
		t = myName;
	var a = myFile;
	var g = false;
	tb_show(t,a,g);
	//this.blur();
	//return false;
	
}

function popUpPage(myFile, myName, myWidth, myHeight) {
	var image = window.open(myFile, myName ,"width=" + myWidth + ",height=" + myHeight + ",status=no,toolbar=no,directories=no,scrollbars=no,location=no,resizable=no,menubar=no")

	return false;
}

/* expandable area*/
$(document).ready(function() {

	$('.readmore').hide();
	$('<div class="read_more_header"></div>').insertBefore('.readmore');		
	$(".read_more_header").on('click', function(){ 	
	// if you hammer the button - the animation queue is stopped. This seems to get the graphich out of sync
	$(this).next(".readmore").slideToggle('slow', function() {
			if ($(this).prev().hasClass('toggled')){					 
				 $(this).prev().css("background-image", "url(/images/showhide-smallmore.png)"); 
				 $(this).prev().removeClass('toggled');					 
			 }else{
				 $(this).prev().css("background-image", "url(/images/showhide-smallclose.png)"); 
				 $(this).prev().addClass('toggled');
			 }				
			})				
	});
	
}); 

/* popup */
$(document).ready(function() {
	
	$("a.socialmedia").on("click", function () {
		window.open(this.href, 'Share',"width=640, height=410,status=no,toolbar=no,directories=no,scrollbars=no,location=no,resizable=no,menubar=no")
		return false;
	});
		
});

// display image caption on image
//$(document).ready(function() {
//  $("#content img").each(function() {
//	var imageCaption = $(this).attr("title");
//	if (imageCaption != '') {
//	  $( this ).wrap( "<figure></figure>" );
//      $("<figcaption>"+imageCaption+"</figcaption>").insertAfter(this);
//	}
//  });
//});

previous_panels = [];
function prev_form_section(hide_id){
    // alert('show section ' + show_id.id + ' hide section ' + hide_id.id);
    $('#' + hide_id.id).hide();
    show_id = previous_panels.pop();
    $('#' + show_id).show();
    
    $('#tab_' +  show_id).addClass('active');
    $('#tab_' +  hide_id.id).removeClass('active');
}

function next_form_section(show_id, hide_id){
    // alert('show section ' + show_id.id + ' hide section ' + hide_id.id);
    $('#' + hide_id.id).hide();
     
    previous_panels.push(hide_id.id);
    $('#' + show_id.id).show();
    console.log('trying to add class to ' + '#tab_' +  show_id.id );
    $('#tab_' +  show_id.id).addClass('active');
    $('#tab_' +  hide_id.id).removeClass('active');
}
  
// sticky nav
/*
var followNav = $('header').offset().top; // returns number 
  $(window).scroll(function(){ // scroll event
	var windowTop = $(window).scrollTop(); // returns number
	if (followNav + 180 < windowTop) {
	  $('header').addClass('follow');
	}
	else {
	  $('header').removeClass('follow');
	} 
});
*/

// windows phone viewport fix
(function() {
	if ("-ms-user-select" in document.documentElement.style && navigator.userAgent.match(/IEMobile\/10\.0/)) {
		var msViewportStyle = document.createElement("style");
		msViewportStyle.appendChild(
			document.createTextNode("@-ms-viewport{width:auto!important}")
		);
		document.getElementsByTagName("head")[0].appendChild(msViewportStyle);
	}
})();

// large form support
// hide id - is just the current tab id
// get the prev tab id and hit show
function prev_form_section(hide_id){   
   prev_id = $('#tab_' + hide_id.id).prev('li').attr('id');
   $('#' + prev_id + ' a').tab('show');  
}

function next_form_section(show_id, hide_id){   
	// by using the tab('show') bootstrap handles the hiding of the other items ect 
	$('#tab_' +  show_id.id + ' a').tab('show');    
}

// shop related helper
function showFadeInmessage(message, id, displaytime) {
	if (id === undefined) {
		id = 'body';
	} else {
		id = '#' + id;
	}
	if (displaytime === undefined) {
		displaytime = 1500;
	}
	if (id == 'body') {
		messagebox = "<div id='messageBox'>" + message + "</div>";
	} else {
		var position = $(id).position();
		messagebox = "<div id='messageBox'>" + message + "</div>";
	}
	$(id).after(messagebox);
	$('#messageBox').slideDown("fast", 1).animate({opacity: 1}, displaytime).fadeTo("fast", 0, function() {
		$(this).remove();
	});
}
function hideFadeInmessage() {
	// destory fade in message
	$('#messageBox').remove();
}
function submitform(myform) {
	document.getElementById(myform).submit();
}
