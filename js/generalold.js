(function(){
	$('.slider li').each(function(){
		var imgSRC = $(this).find('.banner-img').attr('src');
		imgSRC = 'url(' + imgSRC +')';
		$(this).css('background-image',imgSRC);	
	});
	
	if($('#slider').length > 0){
		$('#slider').bxSlider({
			controls:false,
			auto:true
		});
	}
	if($('#masterslider').length > 0){
		var slider = new MasterSlider();
		slider.control('arrows' ,{insertTo:'#masterslider'});	
		slider.control('bullets');	
	
		slider.setup('masterslider' , {
			width:1270,
			height:695,
			view:'basic',
			layout:'fullwidth',
			fullscreenMargin:0,
			speed:20,
			preload:false,
			shuffle:false,
			loop:true
		});
	}
	
	$('.sidebar-menu > ul > li ul').each(function(){
		$(this).parent().addClass('has-child');
	});
	
	// sticky nav
	var followNav = $('#header-main').outerHeight() - 10; // returns number 
	$(window).scroll(function(){ // scroll event
		var windowTop = $(window).scrollTop(); // returns number
		if (followNav < windowTop) {
		  $('header').addClass('fixed');
		}
		else {
		  $('header').removeClass('fixed');
		} 
	});
	
	$('.label_check').click(function(){
		setupLabel();
	});
	setupLabel();
	
	$('.dropdown-menu').each(function(){
		var HTML = '<span class="caret arrow"></span>';
		$(this).parent().prepend(HTML);
	});
	
	$('input:checkbox').focus(function(){
		$(this).parent().addClass('focused'); 
	});
	$('input:checkbox').blur(function(){
		$(this).parent().removeClass('focused'); 
	});
	
	$('.navbar-toggle').click(function(){
		if($(this).hasClass('active')){
			$('html').removeClass('no-scroll');	
			$(this).removeClass('active');
			$('.arrow').removeClass('up');
			$('.dropdown-menu').removeAttr('style');
		}else{
			$('html').addClass('no-scroll');
			$(this).addClass('active');
		}
	});
	
	$('.wrapper').on('click',function(e){
		if($(window).width()<=767){
			if($(e.target).closest(".navbar").length ==0 && !$(e.target).hasClass('active')){
				$('.menu').animate({height:'0'},300);
				$('.navbar-toggle').addClass('collapsed');
				setTimeout(function(){
					$('.menu').removeAttr('style');
					$('.in').removeClass('in');
					$('.no-scroll').removeClass('no-scroll');
					$('.navbar-toggle').removeClass('active');
					$('.arrow').removeClass('up');
					$('.dropdown-menu').removeAttr('style');
				},500);
			}
		}
	});
	$(window).load(function(){
		setSpace();
		setSpaceFooter();
	});
	$(window).resize(function(){
		setSpace();
		setSpaceFooter();
	});
})(jQuery);
function setupLabel() {
	if ($('.label_check input').length) {
		$('.label_check').each(function(){ 
			$(this).removeClass('c_on');
		});
		$('.label_check input:checked').each(function(){ 
			$(this).parent('label').addClass('c_on');
		});                
	};
}
function setSpace(){
	setTimeout(function(){
		if($(window).width() < 767){
			$('#header-main .social-list').css('padding-bottom',$('#header-main .tooltip-data').outerHeight() + 10);	
		}else{
			$('#header-main .social-list').removeAttr('style');
		}	
	},100);
}
function setSpaceFooter(){
	setTimeout(function(){
		if($(window).width() < 992){
			$('.signup-widget .social-share').css('padding-bottom',$('.signup-widget .tooltip-data').outerHeight());	
		}else{
			$('.signup-widget .social-share').removeAttr('style');
		}	
	},100);
}
function setSpaceFooter(){
	setTimeout(function(){
		if($(window).width() < 992){
			$('.signup-widget .social-share').css('padding-bottom',$('.signup-widget .tooltip-data').outerHeight());	
		}else{
			$('.signup-widget .social-share').removeAttr('style');
		}	
	},100);
}
// used to validate donate money
jQuery('.numbersOnly').keyup(function () {
	this.value = this.value.replace(/[^0-9]/g,'');
});