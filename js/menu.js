// #menu13jan2014 :bug
// context :: updated with touchstart event along click menujan13th


// #menu14jan2014 :bug
// context :: disable links not navigating to urls
// 			  



  
$(function(){
/*---------------------Mobile Menu-------------------------*/
// Toggle menu
 $("#menuBlock .menu-icon").on('touchstart click',function(){
		$(this).toggleClass("active");
		if($(this).hasClass('active')){
		$('#menuBlock .arrow').removeClass('up')	
		$('#menuBlock .menubelow').next().slideUp();
		}
		$(this).next().slideToggle();
	});

// defaullt Load
	 if($(window).width()<=767){	
		if($('.arrow').length==0){
			$('#menuBlock a.menubelow').each(function(){
			//$(this).closest('li').prepend('<span class="arrow"></span>')
			});
		}
		$('#menuBlock .menu').removeClass('desk');
		$('#menuBlock .menubelow').next().stop(true,true).slideUp();
		$('.navbar-toggle').removeClass('active');
		$('.collapse').removeClass('in');
		$('.dropdown-menu').removeAttr('style');
	 }else if($(window).width()>=767){ $('#menuBlock .menu').addClass('desk');}
 
// * resize function menu*/
 $(window).resize(function(e){
		if($(window).width()>=768){
				$('#menuBlock a.menubelow').each(function(){
					$(this).next().removeAttr('style');
				});
				//$('.arrow').remove();
				$('#menuBlock .menu').addClass('desk').show();
				$('.navbar-toggle').removeClass('active');
				$('.collapse').removeClass('in');
				$('.dropdown-menu').removeAttr('style');
				
		}else if($(window).width()<=767){
			
				if($('.arrow').length==0){
					$('#menuBlock a.menubelow').each(function(){
					//$(this).closest('li').prepend('<span class="arrow"></span>')
					});
					$('#menuBlock .menu').removeClass('desk');
					$('#menuBlock .menubelow').next().removeAttr('style');
					$('#menuBlock .menubelow').next().slideUp();
				}
				
		}
 });

/*auto close */
	$(document).on('touchstart click',function(e){
		if($(window).width()<=767){
			if($(e.target).closest("#menuBlock").length ==0 && !$(e.target).hasClass('active')){
				$("#menuBlock .menu-icon").next().stop(true,true).slideUp();
				$("#menuBlock .menu-icon").removeClass('active');
				$('#menuBlock ul.menu li ul').stop(true,true).slideUp();
			}
	}
	
/*--------------------------------------------Mobile Menu submenu uncomment this part-------------------------*/	
	/*code for submenu*/
	$('#menuBlock .menubelow').on('touchstart click',function(e){
		if($(this).hasClass('disableClick')){
			if($(this).attr('href') == '#'){e.preventDefalult();}else{ //redirect to url
			}
			
			}else
				{
					e.stopImmediatePropagation();
					e.preventDefault();
					if($(window).width()<=767){
						if($(this).next().length>0){
						$(this).closest('li').siblings().find('ul').stop(true,true).slideUp(); // comment to close
						$(this).closest('li').siblings().find('ul').each(function(){
							$(this).prev().prev().removeClass('up');// 
							});	
						}
						if(!$(this).next().is(':visible')){
							 $(this).closest('li').find('.menubelow').each(function(){
								 $(this).next().stop(true,true).slideUp();
								 $(this).prev().removeClass('up')
							 });// close all
							 $(this).next().stop(true,true).slideDown();
							 $(this).prev().addClass('up');
							 
					 }else{
							// $(this).closest('li').find('.menubelow').next().stop(true,true).slideUp();
							 $(this).next().stop(true,true).slideUp();
							 $(this).prev().removeClass('up');
					}
			}
		}
	
});
/*-	----------------------------------- only click on the menu icon----------------------*/
	$('#menuBlock .arrow').on('touchstart click',function(e){ 
				e.stopImmediatePropagation();
				e.preventDefault();
				if($(window).width()<=767){
					if($(this).next().length>0){
					$(this).closest('li').siblings().find('ul').stop(true,true).slideUp(); // comment to close
					$(this).closest('li').siblings().find('ul').each(function(){
						$(this).prev().prev().removeClass('up');// 
						});	
					}
					if(!$(this).next().next().is(':visible')){
						 $(this).closest('li').find('.menubelow').each(function(){
							 $(this).next().stop(true,true).slideUp();
							 $(this).prev().removeClass('up')
						 });// close all
						 $(this).next().next().stop(true,true).slideDown();
						 $(this).addClass('up');
					}else{
						// $(this).closest('li').find('.menubelow').next().stop(true,true).slideUp();
						 $(this).next().next().stop(true,true).slideUp();
						 $(this).removeClass('up');
				}
			}
		
	
});

/*-	----------------------------------- only click on the menu icon----------------------*/



	});
});
