var $ = jQuery.noConflict();
var slider1;
$(function() {
    var headerHeeight, animatedScroll = false,
        orgElementTop;
    $(document).ready(function() {
        if ($('.bxslider').length) {
            slider1 = $('.bxslider').bxSlider({
                mode: 'fade',
                Pager: 'false',
                auto: 'true',
				speed: 1500,
				pause: 11000,
                controls: 'false'
            });
        }
        $("<div class='dummy-height'></div>").insertAfter(".temp");
        /* if ($.browser.webkit) {
    $('input').attr('autocomplete', 'off');
}*/
 /*       $('ul#top-accordian a.accordion-title').click(function(e) {
            e.preventDefault();
            if (!$(this).next().is(':visible')) {
                $('ul#top-accordian a.accordion-title').removeClass('active'); // close all
                $('ul#top-accordian ul.accordion-content').slideUp(); // close all
                $(this).next().slideDown(); //open`
                $(this).addClass('active');
            } else {
                $(this).next().slideUp(); //current close // only one
                //$('ul#top-accordian a.accordion-title').removeClass('active');
            }
        });*/

        $('.enumenu_ul').responsiveMenu({
            'mobileResulution': '767',
            'menuIcon_text': '',
            onMenuopen: function() {}
        });

        /*if ($("body").hasClass("desk")) {
            headerHeeight = $("header").outerHeight(true);
        } else {
            headerHeeight = $("header").outerHeight(true) + $(".logo-inner").height();
        }*/
        headerHeeight = $("header").outerHeight(true);
        console.log(headerHeeight);
        coordsOrgElement = $("header .container").offset();
        if ($('body').hasClass("desk")) {
            leftOrgElement = coordsOrgElement.left + 15;
        } else {
            leftOrgElement = coordsOrgElement.left;
        }


        var maxHeight = 0;
        $(".evnet-area > div").each(function() {
            if ($(this).children().height() > maxHeight) {
                maxHeight = $(this).children().height();
            }
        });
        $(".evnet-area > div").children().height(maxHeight);
        $(window).resize(function() {
            setTimeout(function() {
                headerHeeight = $("header").outerHeight(true);

                if ($('.bxslider').length) {
                    slider1.reloadSlider();
                }
                if ($('body').hasClass('fixed-header')) {
                    coordsOrgElement = $("header .container").offset();
                    if ($('body').hasClass("desk")) {
                        leftOrgElement = coordsOrgElement.left + 15;
                    } else {
                        leftOrgElement = coordsOrgElement.left;
                    }
                    $('.enumenu_ul').css("paddingLeft", leftOrgElement);
                    $(".dummy-height").css({
                        "height": $(".temp").height()
                    })
                } else {
                    $('.enumenu_ul').css("paddingLeft", 0);
                    $(".dummy-height").css({
                        "height": "0"
                    })
                }
                var maxHeight = 0;
                $(".evnet-area > div > div").removeAttr("style");
                $(".evnet-area > div").each(function() {
                    if ($(this).children().height() > maxHeight) {
                        maxHeight = $(this).children().height();
                    }
                });
                $(".evnet-area > div").children().height(maxHeight);
            }, 200);

            //slider1.reloadSlider();
            /* if ($("body").hasClass("desk")) {
                 headerHeeight = $("header").height();
                 $("body").removeClass("fixed-header");
                 $(".enumenu_ul.cloned").removeClass("mob");
                 $(window).scrollTop(0);
             } else {
                 $('.cloned').slideUp();
                $('.original').css('visibility', 'visible');
                 headerHeeight = $("header").height() + $(".logo-inner").height();
             }
             orgElementTop = orgElementPos.top;*/
        })
        stickyHeader();
        $(window).scroll(function() { // scroll event
            stickyHeader();
        });

        // Create a clone of the menu, right next to original.
        /*$('.enumenu_ul').addClass('original').clone().insertAfter('.enumenu_ul').addClass('cloned').css('position', 'fixed').css('top', '0').css('margin-top', '0').css('z-index', '500').removeClass('original').hide();
         
         scrollIntervalID = setInterval(stickIt, 10);*/
    });

    function stickyHeader() {
        var isChrome = window.navigator.userAgent.indexOf("WebKit") !== -1;
        if (isChrome) {
            var doc = $('body');
        } else {
            var doc = $('html');
        }

        //if ($("body").hasClass("mob")) {
        if ((doc.scrollTop() > headerHeeight+30) && !animatedScroll) {
            if (!$('body').hasClass('fixed-header')) {
                $(".dummy-height").css({
                    "height": $(".temp").height()
                })
                coordsOrgElement = $("header .container").offset();
                if ($('body').hasClass("desk")) {
                    leftOrgElement = coordsOrgElement.left + 15;
                } else {
                    leftOrgElement = coordsOrgElement.left;
                }
                $('.enumenu_ul').css("paddingLeft", leftOrgElement);
                $('body').addClass('fixed-header');
                animatedScroll = true;
            }
        } else if ((doc.scrollTop() <= headerHeeight) && animatedScroll) {
            $(".dummy-height").css({
                "height": "0"
            })
            $('.enumenu_ul').css("paddingLeft", 0);
            if ($('.enumenu_container').height() > $(window).scrollTop() && $('.menu-icon').hasClass('active')) {
                $('body').removeClass('fixed-header');
                animatedScroll = false;

            } else if (!$('.menu-icon').hasClass('active')) {
                $('body').removeClass('fixed-header');
                animatedScroll = false;

            }



        }
        /* } else {
             console.log("else")
             $('body').removeClass('fixed-header');
             animatedScroll = false;
         }*/
    }

    function stickIt() {

        if ($("body").hasClass("desk")) {
            if ($(window).scrollTop() >= (orgElementTop)) {
                // scrolled past the original position; now only show the cloned, sticky element.

                // Cloned element should always have same left position and width as original element.     
                orgElement = $('.original');
                coordsOrgElement = orgElement.closest(".container").offset();
                leftOrgElement = coordsOrgElement.left + 15;
                widthOrgElement = orgElement.css('width');
                $('.cloned').css('left', '0px').css('top', 0).css("padding-left", leftOrgElement + 'px').css('right', '0px').slideDown(function() {
                    $('.original').css('visibility', 'hidden');
                });
            } else {
                // not scrolled past the menu; only show the original menu.
                $('.cloned').slideUp();
                $('.original').css('visibility', 'visible');
            }
        }
    }
});