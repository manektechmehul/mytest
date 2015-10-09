// Author GL - Feb 2013
// globals
rec_no = 5; // 5 records at a time
rec_start = 0; // start with record 0

// start by assigning click funcs
$(document).ready(function () {
    $("#see_more").on("click", function () {
        getMoreOPL('');
    });
    $('#search_text').on("keypress", function (e) {
        /* ENTER PRESSED*/
        if (e.keyCode == 13) {
            doNewSearch();
        }
    });
    $('#search_text').val("");
    getOPLData(rec_start, rec_no, '');
}); // end doc ready









// fired by search button
function doNewSearch() {
    // reset start record
    rec_start = 0;
    // clear previous results
    $('.downloaditem').slideUp('slow');
    $('.search_message').slideUp('slow');
    $('.seemorewrap').fadeIn('slow');
    // set search text
    search = $('#search_text').val();
    // get data
    getOPLData(rec_start, rec_no, search);
}

// fired by the more button - click function added in template
function getMoreOPL(search) {
// incr record number
    rec_start = rec_start + rec_no;
    search = $('#search_text').val();
    getOPLData(rec_start, rec_no, search);
}

// main ajax function to get data for this page
// called via the doNewSearch + getMoreOPL methods
function getOPLData(rec_start, rec_no, search) {
// output to build up
    var out = "";
    pagename = getPageName();
    // set url param
    data = 'pagename=' + pagename + '&start=' + rec_start + '&rec_no=' + rec_no + '&search=' + search;
    var request = $.ajax({
        url: '/modules/factor/ajax.php',
        data: data,
        async: true,
        dataType: 'json',
        success: function (j) {

            for (i = 0; i < j.length; i++) {
                // loop through retured array - j
                obj = j[i];
                // format the array item, and put result into out
                out = out + createlink(obj);
            }
            if (i < rec_no) {
                // hide show more button
                $('.seemorewrap').slideUp('3000');
            }
            // add output to the bottom of the page
            $(out).insertBefore('.seemorewrap').hide().slideDown();
            $(".video").colorbox();

            audiojs.events.ready(function () {
                var as = audiojs.createAll();
            });

        },
        error: function (xhr, desc, er) {
            // add whatever debug you want here.
            alert("an error occurred" + er + " xhr:" + xhr + " desc:" + desc);
        }
    });
}

// format output for page
function createlink(obj) {
    out = "";
    // empty search result

    obj.summary = removeHTMLTags(obj.summary);
    obj.summary = truncString(obj.summary, 350);
    if (obj.link_type == '-1' || obj.link_type == '-2') {


        $('.search_message').slideUp('slow');
        out = '<div class="search_message"> ' + obj.title + ' </div>';
    } else {

        if (obj.link_type == '1') {
            type_text = 'casestudies';
            id = obj.cs_id;
            obj.title = obj.cs_title;
            obj.summary = obj.cs_desc;
            obj.summary = removeHTMLTags(obj.summary);
            obj.summary = truncString(obj.summary, 150);
            out = out + "<div class='downloaditem' style='background-image:url(/images/policylinksbg-blank.png);' >"
            if (obj.cs_thumb != '') {
                out = out + "<div class='downloadcontent' style='background-image:url(/php/thumbimage.php?img=/UserFiles/Image/" + obj.cs_thumb + "&size=20);' >";
            } else {
                out = out + "<div class='downloadcontent' >";
            }

            out = out + "<h4 class='downloadtitle-case'>Case Study</h4> ";
            out = out + "<h3>" + obj.title + "</h3>";
            out = out + "<p>" + obj.summary + "<br />";
            out = out + "<a href='/case_studies/" + obj.module_id + "' >Read more</a></p>";
            out = out + "</div>";
            out = out + "<div class='clearfix'></div>";
            out = out + "</div>";
        }

        if (obj.link_type == '2') {
            type_text = 'download';
            out = out + "<div class='downloaditem' style='background-image:url(/images/policylinksbg-file.png);' >";
            if (obj.thumb != '') {
                out = out + "<div class='downloadcontent' style='background-image:url(/php/thumbimage.php?img=/UserFiles/Image/" + obj.thumb + "&size=20);' >";
            } else {
                out = out + "<div class='downloadcontent' >";
            }

            out = out + "<h4 class='downloadtitle-file'>Link</h4> ";
            out = out + "<h3>" + obj.title + "</h3>";
            out = out + "<p>" + obj.summary + "<br />";


            if (obj.audio == '1') {
                out = out + "<audio src=\"/UserFiles/File/" + obj.file + "\" preload > </audio>";

            } else {

                out = out + "<a target='blank' href='/UserFiles/File/" + obj.file + "' >Download file</a></p>";
            }



            out = out + "</div>";
            out = out + "<div class='clearfix'></div>";
            out = out + "</div>";
        }
        if (obj.link_type == '3') {
            type_text = 'link';
            out = out + "<div class='downloaditem' style='background-image:url(/images/policylinksbg-link.png);' >"

            if (obj.thumb != '') {
                out = out + "<div class='downloadcontent' style='background-image:url(/php/thumbimage.php?img=/UserFiles/Image/" + obj.thumb + "&size=20);' >";
            } else {
                out = out + "<div class='downloadcontent' >";
            }
            out = out + "<h4 class='downloadtitle-link'>Link</h4> ";
            out = out + "<h3>" + obj.title + "</h3>";
            out = out + "<p>" + obj.summary + "<br />";
            // add external switch
            if (obj.external_link == '1') {
// format link for external use
                link = makeExternalLink(obj.link);
                target = "target=_blank";
            } else {
                link = obj.link;
                target = "";
            }
            out = out + "<a href='" + link + "' " + target + " >Follow Link</a></p>";
            out = out + "</div>";
            out = out + "<div class='clearfix'></div>";
            out = out + "</div>";
        }
        if (obj.link_type == '4') {
            type_text = 'video';
            bg = "";
            out = out + "<div class='downloaditem ' style='background-image:url(/images/policylinksbg-video.png);' >"


            if (obj.video_type == "1") {
                out = out + "<div class='downloadcontent' style='background-repeat: no-repeat; background-size: 300px Auto; background-position: right;  background-image:url(http://img.youtube.com/vi/" + obj.video_id + "/1.jpg);  '   >";
            } else {
                
               out = out + "<div class='downloadcontent' style='background-repeat: no-repeat; background-size: 300px Auto; background-position: right;  background-image:url( " + obj.vimeo_thumb_url + ");  '   >";
            }

            out = out + "<h4 class='downloadtitle-video '>Video</h4> ";
            out = out + "<h3>" + obj.title + "</h3>";
            out = out + "<p >" + obj.summary + "<br />";
            // you tube
            // obj.video_type == "1"
            // vimeo
            if (obj.video_type == "1") {
                link = "/php/video_handler/youtube.php?height=450&width=800&clip_id=" + obj.video_id;
                //   bg = "<img src='http://img.youtube.com/vi/" + obj.video_id  + "/0.jpg' width='100'  border=0  />";


            }


            if (obj.video_type == "2") {
                link = "/php/video_handler/vimeo.php?height=450&width=800&clip_id=" + obj.video_id;
            }

            out = out + "<a class='video' href='" + link + "' >" + " Watch video</a></p>";
            out = out + "</div>";
            out = out + "<div class='clearfix'></div>";
            out = out + "</div>";
        }
        if (obj.link_type == '5') {
            type_text = 'profile';
            out = out + "<div class='downloaditem' style='background-image:url(/images/policylinksbg-file-generic.png);' >"
            // " + obj.thumb + "


            out = out + "<div class='downloadcontent' style='background-image:url(/php/thumbimage.php?img=/UserFiles/Image/" + obj.thumb + "&size=20);'  >";
            out = out + "<h4 class='downloadtitle'>static</h4> ";
            out = out + "<h3>" + obj.title + "</h3>";
            out = out + "<p>" + obj.freetext + "</p>";
            //out = out + "<a href='" + obj.link + "' >Watch video</a></p>";
            out = out + "</div>";
            out = out + "<div class='clearfix'></div>";
            out = out + "</div>";
        }



    }

    return out;
}



function truncString(theString, trunc_length) {
    if (theString != undefined) {
// minus the length of the ... ending
        trunc_length = trunc_length - 3;
        // if the string doesn't need to be trunc - just return it unchanged
        if (theString.length > trunc_length) {
            return jQuery.trim(theString).substring(0, trunc_length).split(" ").slice(0, -1).join(" ") + "...";
        } else {
            return theString;
        }
    }

}

function removeHTMLTags(item_html) {
    if (item_html != undefined) {
// remove html
// item_html = item_html.replace(/<\/?[^>]+>/gi, '');
        item_html = item_html.replace('<(?!\/?a(?=>|\s.*>))\/?.*?>', '');
        // remove white space
        item_html = jQuery.trim(item_html);
        return item_html;
    }
}

function makeExternalLink(link) {
// clean off any ref http:// www and clean up
    link = link.replace("http://", "");
    link = link.replace("www.", "");
    link = "http://www." + link;
    return link;
}

// JQuery method to get current url - pagename -
// might be useful for functions inc or similar
function getPageName() {
    var path = window.location.pathname;
    return path.substring(path.lastIndexOf('/') + 1);
}