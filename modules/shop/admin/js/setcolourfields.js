// JavaScript Document
$(function() {

	 	  
	 
	$('input[name=rgb]').parents("tr td").css('background-color',$('input[name=rgb]').val());
	
	$('input[name=rgb]').colpick({
		color: $('input[name=rgb]').val(),
		submit:false,
		onChange: function (hsb,hex,rgb,el,bySetColor) {
		 	$('input[name=rgb]').val(hex);
			$('input[name=rgb]').parents("tr td").css('background-color',hex);
		},
		 
		layout:'hex',
	
	 });
	 
 
	
})

 