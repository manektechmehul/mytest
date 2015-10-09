

$(document).ready(function(e) {
				
	$('.carousel').carousel({
        interval: 3000
    })
			
	$('.carousel').touchwipe({
     wipeLeft: function() { $('.carousel').carousel('next'); },
     wipeRight: function() { $('.carousel').carousel('prev'); },
     min_move_x: 20,
     min_move_y: 20,
     wipeUp: function() { $("html, body").stop(true,true).animate({ scrollTop: 0 }, "slow"); },
     wipeDown: function() {
     $("html, body").stop(true,true).animate({ scrollTop: 500 }, "slow");
     }    
        
     
});			
				
		$('ul#top-accordian a.accordion-title').click(function(e){
			e.preventDefault();
			if(!$(this).next().is(':visible')){
			$('ul#top-accordian a.accordion-title').removeClass('active');// close all
			$('ul#top-accordian ul.accordion-content').slideUp();// close all
			$(this).next().slideDown();//open`
			$(this).addClass('active');
			}else{
				$(this).next().slideUp();//current close // only one
				//$('ul#top-accordian a.accordion-title').removeClass('active');
			}
		});
		
		 $('.enumenu_ul').responsiveMenu({
                'mobileResulution': '767',
                'menuIcon_text': '',
                onMenuopen:function(){
                  
                    
                }
                
            });
			
			// sticky nav
var followNav = $('header').offset().top; // returns number 
var isSafari = /Safari/.test(navigator.userAgent) && /Apple Computer/.test(navigator.vendor);
  $(window).scroll(function(){ // scroll event
	var windowTop = $(window).scrollTop(); // returns number
	if (followNav + 180 < windowTop) {
	  
	   if(isSafari){
		    $('header').addClass('follow').css('position' , 'fixed');
			$('.logo').hide();
		}else{	
		     $('header').addClass('follow');
	  }	
	   if(navigator.userAgent.search("MSIE") >= 0) {
                   $('.logo').hide();
                }
	  
	  
	  $('.social-media-section').addClass('scaleDown');
	}
	else {
	  $('header').removeClass('follow');
	  	  $('.social-media-section').removeClass('scaleDown');
		   // $('header').removeClass('').css('position' ,'');
		   if(isSafari){
			      $('.logo').show();
		          $('header').removeClass('follow').css('position' , '');
				}else{	
					 $('header').removeClass('follow');
			  }
			  if(navigator.userAgent.search("MSIE") >= 0) {
                   $('.logo').show();
                }
	  
		   
	} 
});
			
    });
	


