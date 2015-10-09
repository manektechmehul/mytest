
$( document ).ready(function() {
 // set the bg image to the first colour
 id = $('.picker-color :first-child').attr('id');
 // alert(id);
 if(id != null){
    set_colour(id.substring(7));
 }
});

function inc_qty(){
    $('#qty').val(parseInt($('#qty').val()) + 1);
}
function dec_qty(){
    if (parseInt($('#qty').val()) > 1) {
        $('#qty').val(parseInt($('#qty').val()) - 1);
    }
}

function set_gender(id){    
    $('.picker-type a').removeClass('pickeritem-on');
    $('[name="gender_id"]').val(id);  
    $('#gender_' + id).addClass('pickeritem-on');     
}


function set_colour(id){
    $('.picker-color a').removeClass('pickeritem-on');
    $('[name="colour_id"]').val(id);   
    $('#colour_' + id).addClass('pickeritem-on');    
    
    // need to get the rbg number of the colour - can we get it from the css background
    c = hexc($('#colour_' + id).css('backgroundColor'));
    // set required elements to new colour
    // main image
    $('.cloudzoom-holder img').css('background-color',c);    
	   $('.cloudzoom-holder-mobile img').css('background-color',c);
    //thumbnail
    $('.cloudzoom-gallery-holder img').css('background-color',c);
	
	 
	
    // set the big zoom image window with the next function
   
    
}
 var c; // this is the chosen product colour. we need it to be global to access it here
  $(function(){
                $('.cloudzoom').bind('cloudzoom_start_zoom',function() {
                 
                     setTimeout(function(){
                          $('.cloudzoom-zoom').css('background-color',c);},
                     0)
                });
            });



function hexc(colorval) {
    var parts = colorval.match(/^rgb\((\d+),\s*(\d+),\s*(\d+)\)$/);
    delete(parts[0]);
    for (var i = 1; i <= 3; ++i) {
        parts[i] = parseInt(parts[i]).toString(16);
        if (parts[i].length == 1) parts[i] = '0' + parts[i];
    }
    color = '#' + parts.join('');
    return color;
}

function set_size(id){
    // for any a's in picker-size remove select css class
    $('.picker-size a').removeClass('pickeritem-on');   
    // set value of hidden form element to record the required size
    $('[name="size_id"]').val(id);  
    // add selected item css class
    $('#size_' + id).addClass('pickeritem-on');       
}
function pre_submit_check(){
    // check that all required options have bben ticked before allowing the user to add the item to the basket
    // look through hidden items at the end of the form and make sure they are all populated 
    // in out case we need gender + size + colour    
    disable = false;
    if ($('[name="colour_id"]').length) {     
        // it exists 
        if($('[name="colour_id"]').val() ==''){
            // disable button
            disable = true;
        }        
    }        
    if ($('[name="gender_id"]').length) { 
        // it exists 
        if($('[name="gender_id"]').val() ==''){
            // disable button
            disable = true;
        }
    }        
    if ($('[name="size_id"]').length) { 
        if($('[name="size_id"]').val() ==''){
            // disable button
            disable = true;
        }        
    }    
    if(disable){        
       alert('Please choose all options required for your product.');
    }else{
       submitform('basket');
    }   
}

function submitform(myform) {
    document.getElementById(myform).submit();
}

function showFadeInmessage(message, id, displaytime) {
    if (id === undefined) {
        id = 'body';
    } else {
        id = '#' + id;
    }
    if (displaytime === undefined) {
        displaytime = 3000;
    }
    if (id == 'body') {
        messagebox = "<div id='messageBox'>" + message + "</div>";
    } else {
        var position = $(id).position();
        messagebox = "<div id='messageBox'>" + message + "</div>";
    }
    $(id).after(messagebox);
    $('#messageBox').fadeTo("slow", 1).animate({opacity: 0.6}, displaytime).fadeTo("slow", 0, function() {
        $(this).remove();
    });
}
function hideFadeInmessage() {
    // destory fade in message
    $('#messageBox').remove();
}
