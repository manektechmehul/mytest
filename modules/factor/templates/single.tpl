{literal}
    <link rel="stylesheet" href="//code.jquery.com/ui/1.11.2/themes/smoothness/jquery-ui.css">

    <script src="//code.jquery.com/ui/1.11.2/jquery-ui.js"></script>
    <link rel="stylesheet" href="/resources/demos/style.css">

    <script src="/modules/factor/js/ajax.js"></script> 
    <script src="/js/audiojs/audio.js"></script>
    <script>
        $(function () {
            $("#tabs").tabs();
        });

        audiojs.events.ready(function () {
            var as = audiojs.createAll();
        });

        // used for the on page links - video
        // $(".video").colorbox();

    </script>          
{/literal}

<div id="socialmediafloat">
    {googlep link="`$site_address``$pageName`" image="/images/share-single-g.gif" class="socialmedia"}
    {linkedin link="`$site_address``$pageName`" image="/images/share-single-li.gif" class="socialmedia"}
    {twitter link="`$site_address``$pageName`" image="/images/share-single-tw.gif" class="socialmedia"}
    {facebook link="`$site_address``$pageName`" text=$singleitem.title image="/images/share-single-fb.gif" class="socialmedia"}
</div>

{show_thumb filename=$singleitem.header_image size='300x700' alt='alt="'$singleitem.title'" class="right" border="0"'}



<div class="{$listName}singleitem">{show_thumb filename=$singleitem.thumb size='300x700' alt='alt="'$singleitem.title'" class="right" border="0"'}{$singleitem.body}</div>
<p class="csbutton"><a {if $button_link == "do_js_back"} onclick="javascript:history.back(-1);" href="#" {else}  href='{$button_link}' {/if} >{$button_text}</a>
</p>

<hr>
<p class="csbutton" style="float:left; padding-right:3px; margin-top:-1px;"><a href="/UserFiles/File/{$singleitem.attachment}" target="_blank">Download file</a></p>
<hr>




{if $show_tabs}



    <div id="tabs">

        <ul>
            {if $show_cs }
                <li><a href="#tabs-1">Case Studies</a></li>
                {/if}
                {if $show_resource}
                <li><a href="#tabs-2">Resources</a></li>
                {/if}
                {if $show_qanda }
                <li><a href="#tabs-3">Q & A</a></li>
                {/if}
        </ul>


        {if $show_cs }
            <div id="tabs-1">
                <h2>Some Case Studies about this Factor </h2> 
                {section name=item loop=$cs}
                    <div class="{$cs}left">
                        {assign var="alt" value="alt='`$cs[item].title`'"}
                        <a href="{$pageName}/{$cs[item].page_name}">

                            {show_thumb filename=$cs[item].thumb size='180x180' }</a>
                    </div>
                    <div class="{$listName}right">
                        <h2><a href="/case-studies/{$cs[item].page_name}">{$cs[item].title}</a></h2>
                        <p>{$cs[item].description}</p>
                        <p class="csbutton"><a href="/case-studies/{$cs[item].page_name}">Read more</a></p>
                    </div>
                    <div class="clearfix"></div>
                {/section}
            </div>
        {/if}


        {if $show_resource}
            <div id="tabs-2">
                Resources 

                <div class="associatedlistlarge">

                    <div class="downloaditemsearch">
                        <p>Filter</p>
                        <div id="cse-search-box">

                            <input name="q" type="text" class="form-search" id="search_text" value="" />
                            <input name="sa" value="submit-button" type="image" src="/images/searchbutton.png" class="gsearchbutton" alt="Search" width="19" height="19" hspace="0" vspace="0" border="0" align="top" onclick="doNewSearch();" />
                        </div>
                    </div>

                    <div class="seemorewrap">
                        <div class="seemore" id="see_more"  onclick='' >See more <div class="seemorebutton"></div></div>
                    </div>
                </div>


            </div>

        {/if}    

        {if $show_qanda }
            <div id="tabs-3">
                {$singleitem.qanda}
            </div>
        {/if}


    </div>



{/if}


{literal}
    <script>
        $(function () {
            setstart();
            
            
           
            
            var $reset_form_btn = $('<input/>').attr({ type: 'button', name:'reset_form_btn', value:'Reset Form', class:'btn reset' });
            $("form").append($reset_form_btn);
            $('[name=reset_form_btn]').click(function () {
                resetForm();                
            });
            $('[name=reset_form_btn]').hide();
            
            
            
            $('[name=0_choose_something]').change(function () {
                if ($(this).val().toLowerCase().indexOf("resource") >= 0) {
                  //  $('[name=0_choose_something]').hide();
                    $('[name=0_choose_something]').closest("tr").hide();

                    $('[name=reset_form_btn]').show();
                  // $('[name=1_suggest_resource]').show();
                    $('[name=1_suggest_resource]').closest("tr").show();
                }
                if ($(this).val().toLowerCase().indexOf("question") >= 0) {
                    $('[name=0_choose_something]').closest("tr").hide();
                  //  $('[name=0_choose_something]').next().hide();
                    $('[name=reset_form_btn]').show();
                    show3();
                }
                if ($(this).val().toLowerCase().indexOf("case") >= 0) {
                   // $('[name=0_choose_something]').hide();
                    $('[name=0_choose_something]').closest("tr").hide();
                    $('[name=reset_form_btn]').show();
                    show4();
                }
            });

            $('[name=1_suggest_resource]').change(function () {
                if ($(this).val().toLowerCase().indexOf("upload") >= 0) {
                    $('[name=1_suggest_resource]').closest("tr").hide();
                  //  $('[name=1_suggest_resource]').next().hide();
                    show1();
                }
                if ($(this).val().toLowerCase().indexOf("link") >= 0) {
                 //   $('[name=1_suggest_resource]').hide();
                    $('[name=1_suggest_resource]').closest("tr").hide();
                    show2();
                }
            });
        });

        function resetForm(){
            // reset all form elements
            $('form').trigger("reset");
            $('[name=reset_form_btn]').hide();
            setstart();
        }

        function setstart() {
            // show first item
            $('[name=0_choose_something]').closest("tr").show();
         //   $('[name=0_choose_something]').next().show();

            $('[name=Submitbtn]').hide();
            // hf('0_choose_something');
            hidesection1();
            hidesection2();
            hidesection3();
            hidesection4();
            hf('_yourdetails');             
        }

        function show1() {
            sf('1_title');
            sf('1_desc');
            sf('1_myfileupload');
            $('[name=Submitbtn]').show();
        }

        function show2() {
            sf('2_title');
            sf('2_desc');
            sf('2_link');
            $('[name=Submitbtn]').show();
        }

        function show3() {
            sf('3_question');
            $('[name=Submitbtn]').show();
        }

        function show4() {
            sf('4_title');
            sf('4_image');
            sf('4_summary');
            sf('4_content');
            // #cke_4_content
        //    $('.cke_editor_wrapper').show();
        //    $('.cke_editor_wrapper').prev().show();

            $('.cke_editor_wrapper').closest("tr").show();

            $('[name=Submitbtn]').show();
        }
       
        function hidesection1() {
         //  $('[name=1_suggest_resource]').hide();
          // $('[name=1_suggest_resource]').next().hide();

            $('[name=1_suggest_resource]').closest("tr").hide();


            hf('1_title');
            hf('1_desc');
            hf('1_myfileupload');
        }
        function hidesection2() {
            hf('2_title');
            hf('2_desc');
            hf('2_link');
        }
        function hidesection3() {
            hf('3_question');
        }
        function hidesection4() {
            hf('4_title');
            hf('4_image');
            hf('4_summary');
            hf('4_content');
            // #cke_4_content
            //$('.cke_editor_wrapper').hide();
            //$('.cke_editor_wrapper').prev().hide();
            $('.cke_editor_wrapper').closest("tr").hide();
        }
   		// hide field - fn = field name
        function hf(fn) {
          //  $('[name=' + fn + ']').hide();
            $('[name=' + fn + ']').closest("tr").hide();
        }

        function sf(fn) {
        //    $('[name=' + fn + ']').fadeIn('1000');
         //   $('[name=' + fn + ']').prev().fadeIn('1000');
            $('[name=' + fn + ']').closest("tr").fadeIn('1000');
        }
    </script>
{/literal}