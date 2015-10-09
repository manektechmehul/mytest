{literal}
    <link rel="stylesheet" type="text/css" href="/css/slick/slick.css"/>
    <link rel="stylesheet" type="text/css" href="/css/slick/slick-theme.css"/>
    <link rel="stylesheet" type="text/css" href="/css/magnific/magnific.css"/>
    <script src="/js/slick.min.js"></script>
    <script src="/js/magnific.min.js"></script>
    <script>
        $(document).ready(function(){

            $('.responsive').slick({
                dots: false,
				arrows: true,
                infinite: true,
                speed: 300,
                slidesToShow: 3,
                slidesToScroll: 3,
				adaptiveHeight: false,
                responsive: [
                    {
                        breakpoint: 1200,
                        settings: {
                            slidesToShow: 3,
                            slidesToScroll: 3,
                            infinite: true,
                            dots: false
                        }
                    },
                    {
                        breakpoint: 992,
                        settings: {
                            slidesToShow: 2,
                            slidesToScroll: 2
                        }
                    },
                    {
                        breakpoint: 480,
                        settings: {
                            slidesToShow: 1,
                            slidesToScroll: 1
                        }
                    }
                    // You can unslick at a given breakpoint now by adding:
                    // settings: "unslick"
                    // instead of a settings object
                ]
            });

            $('.popup-gallery').magnificPopup({
                delegate: 'a',
                type: 'image',
                tLoading: 'Loading image #%curr%...',
                mainClass: 'mfp-with-zoom mfp-img-mobile',
                gallery: {
                    enabled: true,
                    navigateByImgClick: true,
                    preload: [0,1] // Will preload 0 - before current, and 1 after the current image
                },
                image: {
                    //verticalFit: true,
                    tError: '<a href="%url%">The image #%curr%</a> could not be loaded.',
					verticalFit: true, // Fits image in area vertically
                    titleSrc: function(item) {
                        return item.el.attr('title') + '<small>' + item.el.attr('alt') + '</small>';
                    }
                },
                zoom: {
                    enabled: true,
                    duration: 300, // don't foget to change the duration also in CSS
                    opener: function(element) {
                        return element.find('img');
                    }
                }
            });

        });
    </script>
{/literal}
<div class="inline_gallery">
  <div class="gallerycontentwrap">
    <h2>{$gallery.title}</h2>
    <p>{$gallery.description} <em>Drag the images left and right to view the gallery and click an image to see it in full.</em></p>
  </div>
  <div class="responsive popup-gallery">
  {section name=image loop=$images}
    <div class="slickimage" style="background-image:url({show_thumb_minimal filename=$images[image].imagename size=600x600});"><a title="{$images[image].title}" href="{show_thumb_minimal filename=$images[image].imagename size=1280x1280}" alt="{$images[image].description}"><img class="" src="{show_thumb_minimal filename=$images[image].imagename size=600x600}"></a></div>
  {/section}
  </div>
</div>