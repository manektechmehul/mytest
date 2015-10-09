!function(e){e.fn.responsiveMenu=function(n){e.fn.responsiveMenu.defaultOptions={mobileResulution:767,menuIcon_text:""};var n=e.extend({},e.fn.responsiveMenu.defaultOptions,n);return this.each(function(s){function i(e){e.removeClass("desk"),v.next().stop(!0,!0).slideUp(),f.removeClass("up"),e.slideUp(),e.find(".menu-icon").removeClass("active")}function t(s){0==s.prev(".menu-icon").length&&(s.wrapAll('<div class="enumenu_container"></div>'),e('<div class="menu-icon">'+n.menuIcon_text+"</div>").insertBefore(s),s.find("ul").prev("a").addClass("menubelow")),v=s.find("a.menubelow"),0==s.find(".arrow").length&&(v.each(function(){e(this).closest("li").prepend('<span class="arrow"></span>')}),f=s.find(".arrow"))}function o(n){n.find(".arrow").on("touchstart click",function(n){n.stopImmediatePropagation(),n.preventDefault();var s=e(this).closest("li").find(">ul"),i=e(this).closest("li").siblings(),t=e(this).closest("li");e(".menu-icon").is(":visible")&&(s.length>0&&(i.find("ul").stop(!0,!0).slideUp(),i.find("ul").each(function(){e(this).closest("li").find(">span").removeClass("up")})),s.is(":visible")?(s.slideUp(),t.find(">span").removeClass("up")):(s.find("ul").each(function(){e(this).stop().slideUp(),e(this).closest("li").find("span").removeClass("up")}),s.stop().slideDown(),t.find(">span").addClass("up")))})}function l(n){n.find(".menubelow").each(function(){e(this).removeAttr("style"),e(this).next().removeAttr("style")}),n.find(".arrow").remove(),n.prev(".menu-icon").removeClass("active"),n.addClass("desk").removeAttr("style"),n.removeAttr("style"),w=!1}function a(n){e(".menu-icon").is(":visible")?C||(i(n),t(n),o(n),C=!0,w=!1,n.removeClass("desk"),e("body").removeClass("desk"),n.addClass("mob"),e("body").addClass("mob")):w||(l(n),C=!1,w=!0,n.removeClass("mob"),e("body").removeClass("mob"),n.addClass("desk"),e("body").addClass("desk"))}function d(e){e.stop().slideUp(),e.prev(".menu-icon").removeClass("active"),e.find(".arrow").removeClass("up"),e.find("ul").stop(!0,!0).slideUp()}function u(){var e=window.innerWidth;if(!e){var n=document.documentElement.getBoundingClientRect();e=n.right-Math.abs(n.left)}c=document.body.clientWidth<e,m=r(),c&&(h-=m)}function r(){var e=document.createElement("div");e.className="scrollbar-measure",p.append(e);var n=e.offsetWidth-e.clientWidth;return p[0].removeChild(e),n}var c,m,v,f,p=e(document.body),h=n.mobileResulution,b=e(this),C=(b.prev(".menu-icon"),!1),w=!1;n.mobileResulution,u(),e(window).resize(function(){h=n.mobileResulution,u()}),t(b),a(b),e(window).resize(function(e){a(b)}),e(document).on("click touchstart",".menu-icon",function(n){n.stopPropagation(),n.preventDefault(),e(this).hasClass("active")?d(b):(e(this).next().slideDown(),e(this).addClass("active"))}),e("body").on("click touchstart",function(n){e(".menu-icon").is(":visible")&&(0!=e(n.target).closest(".enumenu_container").length||e(n.target).hasClass("active")||d(b))})})}}(jQuery);

/**
 * jQuery Plugin to obtain touch gestures from iPhone, iPod Touch and iPad, should also work with Android mobile phones (not tested yet!)
 * Common usage: wipe images (left and right to show the previous or next image)
 * 
 * @author Andreas Waltl, netCU Internetagentur (http://www.netcu.de)
 * @version 1.1.1 (9th December 2010) - fix bug (older IE's had problems)
 * @version 1.1 (1st September 2010) - support wipe up and wipe down
 * @version 1.0 (15th July 2010)
 */
(function($) { 
   $.fn.touchwipe = function(settings) {
     var config = {
    		min_move_x: 20,
    		min_move_y: 20,
 			wipeLeft: function() { },
 			wipeRight: function() { },
 			wipeUp: function() { },
 			wipeDown: function() { },
			preventDefaultEvents: true
	 };
     
     if (settings) $.extend(config, settings);
 
     this.each(function() {
    	 var startX;
    	 var startY;
		 var isMoving = false;

    	 function cancelTouch() {
    		 this.removeEventListener('touchmove', onTouchMove);
    		 startX = null;
    		 isMoving = false;
    	 }	
    	 
    	 function onTouchMove(e) {
    		 if(config.preventDefaultEvents) {
    			 e.preventDefault();
    		 }
    		 if(isMoving) {
	    		 var x = e.touches[0].pageX;
	    		 var y = e.touches[0].pageY;
	    		 var dx = startX - x;
	    		 var dy = startY - y;
	    		 if(Math.abs(dx) >= config.min_move_x) {
	    			cancelTouch();
	    			if(dx > 0) {
	    				config.wipeLeft();
	    			}
	    			else {
	    				config.wipeRight();
	    			}
	    		 }
	    		 else if(Math.abs(dy) >= config.min_move_y) {
		    			cancelTouch();
		    			if(dy > 0) {
		    				config.wipeDown();
		    			}
		    			else {
		    				config.wipeUp();
		    			}
		    		 }
    		 }
    	 }
    	 
    	 function onTouchStart(e)
    	 {
    		 if (e.touches.length == 1) {
    			 startX = e.touches[0].pageX;
    			 startY = e.touches[0].pageY;
    			 isMoving = true;
    			 this.addEventListener('touchmove', onTouchMove, false);
    		 }
    	 }    	 
    	 if ('ontouchstart' in document.documentElement) {
    		 this.addEventListener('touchstart', onTouchStart, false);
    	 }
     });
 
     return this;
   };
 
 })(jQuery);




