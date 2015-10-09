{literal}    
    <style>
    img[usemap] {
            border: none;
            height: auto;
            max-width: 100%;
            width: auto;
    }
    </style>
    <style type="text/css">
    div#tipDiv {
        font-size:12px; line-height:1.3;
        color:#000; background-color:#E1E5F1; 
        border:1px solid #667295; 
    }

    div#tipDiv div.topBar {
        background-color:#0D2878;
        min-height:14px; /* for bg color if no caption (close.gif is 14px) */
        text-align:center;  
        }

    /* for ie < 7 */
    * html div#tipDiv div.topBar {
        height:14px; /* ie will expand if needed  */
        }

    div#tipDiv span.caption {
        color:#fff; font-weight:bold; font-size:11px;
        }
    div#tipDiv div.XtipContent {
        padding:4px;
        }

    div#tipDiv p {
        margin:0 0 .7em 0;
        }

    /* used in dw_Tooltip.wrapImageOverText */
    div#tipDiv div.img { 
        text-align:center; 
        margin:4px 0;
        }
    div#tipDiv div.txt { 
        text-align:center; 
        margin:4px 0;
        }
    </style>
    <script src="../js/dw_tooltip_c.js" type="text/javascript"></script>
    <script type="text/javascript">

    dw_Tooltip.defaultProps = {
        supportTouch: true, // set false by default
        sticky: true,
        showCloseBox: true,
        closeBoxImage: '../images/close.gif',
        positionFn: dw_Tooltip.positionWindowCenter,
        wrapFn: dw_Tooltip.wrapToWidth
    }

    // Problems, errors? See http://www.dyn-web.com/tutorials/obj_lit.php#syntax

    dw_Tooltip.content_vars = {        
        L2:  {
            img: '../UserFiles/floorplan/map_1443676935?1443676967718',
            w: 250
        },
        L3: {
            caption: 'Yin-Yang Mandala<br>&pound; 300 per week',
            img: '../UserFiles/floorplan/map_1443676935?1443676967718',            
            w: 250
        }
    }
    </script>
{/literal}
<div id="socialmediafloat">
    {twitter link="`$site_address``$pageName`" image="/images/share-single-tw.gif" class="socialmedia"}
    {facebook link="`$site_address``$pageName`" text=$singleitem.title image="/images/share-single-fb.gif" class="socialmedia"}
</div>

<div class="clearfix"></div>

<div class="{$listName}singleitem">

<div class="propertyinfo">
  <div class="{$listName}status status{$singleitem.property_status_id}"></div>
  <p>Location ::</p>
  <h3>{$singleitem.location}</h3>
  <!--<p>Type ::</p>
  <h3>{$singleitem.type}</h3>-->
  <p>Price ::</p>
  <h3>&pound;{$singleitem.price} per week</h3>
  <p>Bedrooms ::</p>
  <h3>{$singleitem.bedroom}</h3>
  <p>Address ::</p>
  <h3>{$singleitem.address|nl2br}<br>
  {$singleitem.postcode}</h3>
  <p class="csbutton" style="margin:0; bottom:30px; left:25px;"><a href="/UserFiles/File/{$singleitem.attachment}" target="_blank">Download Brochure</a></p>
</div>

<div class="">
<div>
  <!--
   <div id="slides">
    <div class="slide">{show_thumb filename=$singleitem.thumb crop=crop size=420x281 alt="alt=\"Slide\" width=420 height=281"}</div>
    {if $singleitem.image_1 != '' }
        <div class="slide">{show_thumb filename=$singleitem.image_1 crop=crop size=420x281 alt="alt=\"Slide\" width=420 height=281"}</div>
    {/if}
    {if $singleitem.image_2 != '' }
        <div class="slide">{show_thumb filename=$singleitem.image_2 crop=crop size=420x281 alt="alt=\"Slide\" width=420 height=281"}</div>
    {/if}
    {if $singleitem.image_3 != '' }
        <div class="slide">{show_thumb filename=$singleitem.image_3 crop=crop size=420x281 alt="alt=\"Slide\" width=420 height=281"}</div>
    {/if}
    {if $singleitem.image_4 != '' }
        <div class="slide">{show_thumb filename=$singleitem.image_4 crop=crop size=420x281 alt="alt=\"Slide\" width=420 height=281"}</div>
    {/if}
    {if $singleitem.image_5 != '' }
        <div class="slide">{show_thumb filename=$singleitem.image_5 crop=crop size=420x281 alt="alt=\"Slide\" width=420 height=281"}</div>
    {/if}
  </div>
   -->  
   <ul class="bxslider">
    <li>{show_thumb filename=$singleitem.thumb crop=crop size=420x281 alt="alt=\"Slide\" width=420 height=281"}</li>
    {if $singleitem.image_1 != '' }
        <li>{show_thumb filename=$singleitem.image_1 crop=crop size=420x281 alt="alt=\"Slide\" width=420 height=281"}</li>
    {/if}
    {if $singleitem.image_2 != '' }
        <li>{show_thumb filename=$singleitem.image_2 crop=crop size=420x281 alt="alt=\"Slide\" width=420 height=281"}</li>
    {/if}
    {if $singleitem.image_3 != '' }
    <li>{show_thumb filename=$singleitem.image_3 crop=crop size=420x281 alt="alt=\"Slide\" width=420 height=281"}</li>
    {/if}
    {if $singleitem.image_4 != '' }
        <li>{show_thumb filename=$singleitem.image_4 crop=crop size=420x281 alt="alt=\"Slide\" width=420 height=281"}</li>
    {/if}
    {if $singleitem.image_5 != '' }
        <li>{show_thumb filename=$singleitem.image_5 crop=crop size=420x281 alt="alt=\"Slide\" width=420 height=281"}</li>
    {/if}
   </ul>
        
  <div id="bx-pager">
   <a data-slide-index="0" href="">{show_thumb filename=$singleitem.thumb size=150x64 alt="alt=\"thumbnail\" height=64"}</a>
     {if $singleitem.image_1 != '' }
      <a data-slide-index="1" href="">{show_thumb filename=$singleitem.image_1 size=150x64 alt="alt=\"thumbnail\" height=64"}</a>
     {/if}
{if $singleitem.image_2 != '' }
      <a data-slide-index="2" href="">{show_thumb filename=$singleitem.image_2 size=150x64 alt="alt=\"thumbnail\" height=64"}</a>
     {/if}
{if $singleitem.image_3 != '' }
      <a data-slide-index="3" href="">{show_thumb filename=$singleitem.image_3 size=150x64 alt="alt=\"thumbnail\" height=64"}</a>
     {/if}
{if $singleitem.image_4 != '' }
      <a data-slide-index="4" href="">{show_thumb filename=$singleitem.image_4 size=150x64 alt="alt=\"thumbnail\" height=64"}</a>
     {/if}
{if $singleitem.image_5 != '' }
      <a data-slide-index="5" href="">{show_thumb filename=$singleitem.image_5 size=150x64 alt="alt=\"thumbnail\" height=64"}</a>
     {/if}
   
  </div>
</div>
</div>
 
<div class="clearfix"></div>



{$singleitem.body}
 <!--
{if $singleitem.map_url != ''}
<div style="margin-top:20px;">
	<iframe width="710" scrolling="no" height="480" frameborder="0" marginheight="0" marginwidth="0" src="{$singleitem.map_url}&output=embed" marginwidth="0" marginheight="0"></iframe><br />
	<div class="small" style="text-align:right;"><a href="{$singleitem.map_url}" target="_blank">View larger map on Google Maps</a></div>
</div>
{/if} -->

</div>
<div>
         <p>Floor Plan ::</p>
         <img src="http://localhost/UserFiles/floorplan/map_1443676935?1443676967718" width="" height="" usemap="#imgmap2015930162719" alt="" />
         <map id="imgmap2015930162719" name="imgmap2015930162719"><area shape="rect" alt="" title="" coords="28,88,177,205" href="" target="" class="showTip L2" /><area shape="rect" alt="" title="" coords="20,415,180,599" href="" target="" class="showTip L3" /><area shape="rect" alt="" title="" coords="331,19,480,174" href="" target="" class="showTip L2" /></map>
     </div>    

<p class="csbutton"  style="margin-top:20px;"><a {if $button_link == "do_js_back"} onclick="javascript:history.back(-1);" href="#" {else}  href='{$button_link}' {/if} >{$button_text}</a></p>
<div class="clearfix"></div>
{literal}
    <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
    <script src="../js/jquery.rwdImageMaps.min.js"></script>
    <script>
    $(document).ready(function(e) {
            $('img[usemap]').rwdImageMaps();            
    });
    </script>
    <script type="text/javascript">
        $(document).ready(function(){
            $('.bxslider').bxSlider({
              pagerCustom: '#bx-pager'
            });
        });
      </script>
{/literal}