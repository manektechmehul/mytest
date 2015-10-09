// globals
$(document).ready(function () {    
    $( "#pr_bedroom, #pr_location, #pr_year" ).change(function() {                
        doNewSearch();        
    });
}); // end doc ready

// fired by change dropdown of bedrrom location or year.
function doNewSearch() {   
    
    var bedroom=$('#pr_bedroom').val();     
    var location1=$('#pr_location').val();    
    var year=$('#pr_year').val();   
    getPropData(bedroom, location1, year);
}


// main ajax function to get data for this page
// called via the doNewSearch + getPropData methods
function getPropData(bedroom,location1,year) {
// output to build up

    var out = "";    
    // set url param
    var data = 'bedroom=' + bedroom + '&location=' + location1 + '&year=' + year;
    var request = $.ajax({        
        url: '/modules/property/ajax.php',
        data: data,
        async: true,
        dataType: 'json',
        success: function (j) {           
           // $("#Container").html(j);
           for (i = 0; i < j.length; i++) {
                // loop through retured array - j
                obj = j[i];
                // format the array item, and put result into out
                out = out + createlink(obj);
            }
            $("#Container").html(out);
        },
        error: function (xhr, desc, er) {
            // add whatever debug you want here.
            alert("an error occurred" + er + " xhr:" + xhr + " desc:" + desc);
        }
    });
}


function createlink(obj) {
    var out = "";
    // empty search result
    if (obj.link_type == '-1') {        
        out = '<div class="search_message"> ' + obj.title + ' </div>';
    } else {
             out = out + '<div class="'+listname+'listitem" >';
             out = out + '<div class="'+listname+'status status'+obj.property_status_id+'"></div>';
             out = out + '<div class="'+listname+'left">';                
             //out = out + '<a href="/{/literal}{$page_url_parts[0]}{literal}/'+obj.page_name+'">{/literal}{show_thumb filename=$itemList[item].thumb size="180x600" alt=$alt border="0" class="class=left"}{literal}</a>';
             //out = out + '<a href="/'+page_url_parts+'/'+obj.page_name+'"><img class="left" alt="'+obj.title+'" item" src="/UserFiles/Thumbnails/Test_Images/gallery/nature10_h600_w180.jpg"></a>';
            thumimg=obj.thumb;
            sp_str=thumimg.split(".");            
            out = out + '<a href="/'+page_url_parts+'/'+obj.page_name+'"><img class="left" alt="'+obj.title+'" item" src="/UserFiles/Thumbnails/' + sp_str[0] + '_h600_w180.'+ sp_str[1] +'"></a>';
            out = out + '</div>';
            
             out = out + '<div class="'+listname+'right">';
             out = out + '<a href="/'+page_url_parts+'/'+obj.page_name+'">'+obj.address+'</a>';
             out = out + '<p>&pound;'+obj.price+' per week</p>';
             out = out + '<p>'+obj.bedroom+' bedrooms</p>';
             out = out + '<p class="csbutton"><a href="/'+page_url_parts+'/'+obj.page_name+'" style="border-top-left-radius:0;">View property details</a></p>';
             out = out + '</div>';
             out = out + '<div class="clearfix"></div>';
             out = out + '</div>';
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