{literal}



    <!-- get jQuery from the google apis -->
    <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.js"></script>


    <!-- PREVIEW DEMO SITE CSS SETTINGS - Manually added these css files from zip GL -->
    <link rel="stylesheet" type="text/css" href="/js/megafolio/css/style.css" media="screen" />
    <link rel="stylesheet" type="text/css" href="/js/megafolio/css/ccpreview.css" media="screen" />

    <!-- MEGAFOLIO GALLERY  CSS SETTINGS -->
    <link rel="stylesheet" type="text/css" href="/js/megafolio/css/settings.css" media="screen" />

    <!-- MEGAFOLIO PRO GALLERY JS FILES  -->
    <script type="text/javascript" src="/js/megafolio/js/jquery.themepunch.tools.min.js"></script>
    <script type="text/javascript" src="/js/megafolio/js/jquery.themepunch.megafoliopro.min.js"></script>


    <!-- THE FANYCYBOX ASSETS -->
    <link rel="stylesheet" href="/js/megafolio/fancybox/jquery.fancybox.css?v=2.1.3" type="text/css" media="screen" />
    <script type="text/javascript" src="/js/megafolio/fancybox/jquery.fancybox.pack.js?v=2.1.3"></script>

    <!-- Optionally add helpers - button, thumbnail and/or media ONLY FOR ADVANCED USAGE-->
    <script type="text/javascript" src="/js/megafolio/fancybox/helpers/jquery.fancybox-media.js?v=1.0.5"></script>


    <!-- THE GOOGLE FONT LOAD -->
    <link href='http://fonts.googleapis.com/css?family=Open+Sans:400,300,600,700,800' rel='stylesheet' type='text/css'>


{/literal}
<div class="filter_padder" >
    <div class="filter_wrapper" style="max-width:650px;">
        <div class="filter selected" data-category="cat-all">ALL</div>
        {section name=cat loop=$cats}
            <div class="filter" data-category="{$cats[cat].cats}">{$cats[cat].title}</div>
        {/section}
        <div class="clear"></div>
    </div>
</div>
<div class="clear"></div>

<div class="container-fullwidth">
    <!-- The GRID System -->
    <div class="megafolio-container noborder norounded dark-bg-entries">


        {section name=image loop=$images}








            <div class="mega-entry {$images[image].cats} cat-all" data-src="{show_thumb_minimal filename=$images[image].imagename size=577x700}" data-width="577" data-height="700">
                <div class="mega-hover  alone notitle">
                    <a class="fancybox" rel="group" title="{$images[image].title}" href="{show_thumb_minimal filename=$images[image].imagename size=577x700}"><div class="mega-hoverview"></div></a>
                </div>

                <div class="gallerycaption-bottom">{$images[image].title}<div class="gallerysubline">{$images[image].title}</div></div>
            </div>

        {/section}




    </div>
</div>
<div class="divide90"></div>

{literal}


    <!--
    ##############################
     - ACTIVATE THE BANNER HERE -
    ##############################
    -->
    <script type="text/javascript">


        jQuery(document).ready(function() {


            var api=jQuery('.megafolio-container').megafoliopro(
                    {
                        filterChangeAnimation:"rotatescale",			// fade, rotate, scale, rotatescale, pagetop, pagebottom,pagemiddle
                        filterChangeSpeed:600,					// Speed of Transition
                        filterChangeRotate:10,					// If you ue scalerotate or rotate you can set the rotation (99 = random !!)
                        filterChangeScale:0.6,					// Scale Animation Endparameter
                        delay:20,
                        defaultWidth:980,
                        paddingHorizontal:5,
                        paddingVertical:5,
                        layoutarray:[12]		// Defines the Layout Types which can be used in the Gallery. 2-9 or "random". You can define more than one, like {5,2,6,4} where the first items will be orderd in layout 5, the next comming items in layout 2, the next comming items in layout 6 etc... You can use also simple {9} then all item ordered in Layout 9 type.

                    });


            // FANCY BOX ( LIVE BOX) WITH MEDIA SUPPORT
            jQuery(".fancybox").fancybox({
                openEffect  : 'none',
                closeEffect : 'none',
                helpers : {
                    media : {}
                }
            });

            // THE FILTER FUNCTION
            jQuery('.filter').click(function() {
                jQuery('.filter').each(function() { jQuery(this).removeClass("selected")});
                api.megafilter(jQuery(this).data('category'));
                jQuery(this).addClass("selected");
            });




        });

    </script>
    {/literal}