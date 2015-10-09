var _imagesDir = "/images/shop/colour_tools/";
var _showQty = false;
 
function getContrastYIQ(hexcolor) {
    hexcolor = hexcolor.substr(1, 7);
    var r = parseInt(hexcolor.substr(0, 2), 16);
    var g = parseInt(hexcolor.substr(2, 2), 16);
    var b = parseInt(hexcolor.substr(4, 2), 16);
    var yiq = ((r * 299) + (g * 587) + (b * 114)) / 1000;
    return (yiq >= 128) ? 'black' : 'white';
}
// loop through array of all colours, use the CreateColourSq helper function to format items
function createColorGrid(allcolors, nolinks) {
    // nolinks is an optional vaiable
    var temp = [];
    var i = 0;
    $('#swatch_container').attr('innerHTML', '');
    counter = 0;
    for (i = 0; i < allcolors.length; i++) {
       // if (jQuery.inArray(allcolors[i][5], _seasons) != -1) { // in the seasons list
           // if (jQuery.inArray(allcolors[i][6], _tones) != -1) { // in the tones list
                counter++;
                if (jQuery.inArray(allcolors[i][1], temp) == -1) {
                    $('#swatch_container').append(createColorSq(allcolors[i], nolinks));
                    temp.push(allcolors[i][1]);
                }
         //   }
      //  }
    }
}
 
var _chosen = [];
// helper for above
function createColorSq(acolor, nolinks) {
    // add item to panone section 	
    id = acolor[0];
    colorname = acolor[1];
    webcolor = acolor[3];
    name = acolor[1];
    textcolour = getContrastYIQ(webcolor);
    newColorBlock = "<a href='#' >"; // title to fp 
    if (nolinks) {
        click = "";
        cursor = "default";
    } else {
        click = "onclick='addToPaletteFromGrid(" + id + ")'";
        cursor = "pointer";
    }
    newColorBlock = "<li id='colour_sq_" + id + "' style='cursor: " + cursor + ";' ><div  title='" + id + "  " + colorname + "'  " + click + "  style='background-color: " + webcolor + ";color: " + textcolour + " ;' > " + id + "    </div></li></a>";
    return newColorBlock;
}
// loop up colour details via id
function findIdInArray(id) {
    // look up colur details via system id NOT DC ID	
    for (var i = 0; i < allcolors.length; i++) {
        if (allcolors[i][0] == id) {
            // alert('found');
            return allcolors[i];
        }
    }
    // colour not found
    return -1;
}
 
///////////////////////////////
// Your Selection - Functions
///////////////////////////////
function clearSelection() {
    $("#sortable li").remove();
    _chosen = [];
}
function useMySavedPalette() {
    if (mySavedPalette != '') {
        clearSelection();
        preloadColoursById(mySavedPalette)
    } else {
        alert('You don`t seem to have a saved palette. You can create one on the `Our Colours` page.');
    }
}
function preloadNonColour(acolor) {
    id = acolor[0];
 //   dc_id = acolor[1];
    colorname = acolor[1];
    webcolor = acolor[3];
    textcolour = getContrastYIQ(webcolor);
    // alert(webcolor);
    // need to add cursur to plus / minus buttons
    colorname = "Please Select Quanity ";//dc_id + " " + colorname;
    // uses the global , default to false
    code = '<li id="pallete_' + id + '"  style="background-color: ' + webcolor + '; background-image: url(' + _imagesDir + 'my_pallete_item_overlay_no_hand.png); "> <span style="height:30px; line-height:30px; width:145px; vertical-align:middle; color: ' + textcolour + ';">' + colorname + ' </span>';
    code = code + '<div  class="controls" ><a style="cursor:pointer;" class="minus" onclick="DecPaletteItem(' + id + ')"  ><img src="' + _imagesDir + 'minus.png"></a>';
    code = code + '<a style="cursor:pointer;" class="plus" onclick="IncPaletteItem(' + id + ')"  ><img src="' + _imagesDir + 'plus.png"></a>';
    code = code + '<input class="qty" type="text" id="palette_txt_' + id + '" name="palette_txt_' + id + '"  value="1" />';
    code = code + '<a href="#" style="cursor:pointer;"  style="float:right" class="remove"><img src="/images/1x1.gif" width="12" height="12" ></a></div></li>';
    $("#sortable").append(code);
}
function addAllAvailableColoursToSelection() {
    var i = 0;
    csv = "";
    previous = 0;
    while (allcolors.length > i) {
        // dont do the repeating items that vary in season and tone info 
        if (allcolors[i][0] != previous) {
            // true is for hide Ankmation 
            added = addToPaletteFromGrid(allcolors[i][0], true);
            if (added == -1) {
                alert("Sorry this product is not available in " + allcolors[i][0]);
            }
        }
        previous = allcolors[i][0];
        i++;
    }
}
mySavedPalette_name_number = "";
function preloadColoursById(items) {
    // loop though each one in the cs list and add to the palette selector
    //alert(items);
    var k = 0;
    //alert(items);
    //alert(mySavedPalette_name_number);
    names = mySavedPalette_name_number.split(',');
    while (items.length > k) {
        // do something item	
        // alert(items[k]);
        added = addToPaletteFromGrid(items[k]);
        if (added == -1) {
            alert("Sorry this product is not available in " + names[k]);
        }
        k++;
    }
}
function saveMyPallet() {
    // loop the ul for the li in the new order ...
    var idsInOrderArr = $('#sortable').sortable("toArray");
    var inOrder = "";
    var inOrderNames = "";
    var i = 0;
    while (idsInOrderArr.length > i) {
        //alert($('#' + idsInOrderArr[i] ).text());
        // remove the palette_ from text
        inOrderNames = inOrderNames + $('#' + idsInOrderArr[i]).text() + ",";
        t = idsInOrderArr[i].split('_');
        inOrder = inOrder + t[1] + ",";
        i++;
    }
    inOrder = inOrder.substring(0, inOrder.length - 1);
    inOrderNames = inOrderNames.substring(0, inOrderNames.length - 1);
    $('#_chosen').val(inOrder);
    // saves names to session, so if they fail to load on a product - we can give sensible alert message
    //alert(inOrderNames);
    $('#_chosen_name_number').val(inOrderNames);
    submitform('palette');
}
function addtochosen(id) {
    // alert('addtochosen');
    // ads items to the Your Selection
    //$('#' + id + " .colorblockSwatch").css('border','2px solid #000');				
    //$('#chooser_footer').append(createColorSq(findIdInArray(id)));	
    $('#colourimage').css('display', 'block');
    $('#content').css('display', 'block');
    acolor = findIdInArray(id);
    // item not found
    _Color = acolor;
    getRelatedItems();
    id = acolor[0];
    //dc_id = acolor[1];
    colorname = acolor[1];
    webcolor = acolor[3];
   // season = acolor[5];
   // tone = acolor[6];
    filename = acolor[4];
    $('#preview_color_name').attr('innerHTML', colorname);
    $('#preview_color_pantone').attr('innerHTML', acolor[3]);
    //$('#preview_color_dcid').attr('innerHTML', acolor[1]);
    $('#preview_pane .colour_based_bullet').css('background-color', webcolor);
    arr = findIdInArrayAllSeasonsAndTones(id);
    var seasons = arr[0];
    var tones = arr[1];
    var i = 0;
    $('#seasons li').remove();
    hadseason = [];
    while (seasons.length > i) {
        if (jQuery.inArray(seasons[i], hadseason) == -1) {
            if (seasons[i] == 1) {
                season = 'Spring';
                hadseason.push(1);
            }
            if (seasons[i] == 2) {
                season = 'Summer';
                hadseason.push(2);
            }
            if (seasons[i] == 3) {
                season = 'Autumn';
                hadseason.push(3);
            }
            if (seasons[i] == 4) {
                season = 'Winter';
                hadseason.push(4);
            }
            $('#seasons').append('<li><div class="colourlistbox" style="background:#' + webcolor + ' ;"></div>' + season + ' </li>');
        }
        i++;
    }
    var i = 0;
    $('#tones li').remove();
    hadtone = [];
    while (seasons.length > i) {
        if (jQuery.inArray(tones[i], hadtone) == -1) {
            if (tones[i] == 1) {
                tone = 'Light';
                hadtone.push(1);
            }
            if (tones[i] == 2) {
                tone = 'Dark';
                hadtone.push(2);
            }
            if (tones[i] == 3) {
                tone = 'Bright';
                hadtone.push(3);
            }
            if (tones[i] == 4) {
                tone = 'Muted';
                hadtone.push(4);
            }
            if (tones[i] == 5) {
                tone = 'Cool';
                hadtone.push(5);
            }
            if (tones[i] == 6) {
                tone = 'Warm';
                hadtone.push(6);
            }
            $('#tones').append('<li><div class="colourlistbox" style="background:#' + webcolor + ' ;"></div>' + tone + ' </li>');
        }
        i++;
    }
    /*
     $('#tones li').remove();
     $('#tones').append('<li><div class="colourlistbox" style="background:#' + webcolor + ';"></div>' + tone +  ' </li>');
     tone = acolor[6];
     */
    document.getElementById('colourNameTitle').innerHTML = colorname;
    $('div .colourtitlebox').css('background', webcolor);
    $('div .colourlistbox').css('background', webcolor);
    // $('#colourimage').css('background',webcolor);
    $('#colourimage').css('background-image', 'url(/UserFiles/Image/swatch_images/' + filename + ')');
}
function decAllBasketItems() {
    // for all items in basket .....
    var idsInOrderArr = $('#sortable').sortable("toArray");
    var inOrder = "";
    var inOrderNames = "";
    var i = 0;
    while (idsInOrderArr.length > i) {
        inOrderNames = inOrderNames + $('#' + idsInOrderArr[i]).text() + ",";
        t = idsInOrderArr[i].split('_');
        //alert(t[1]);
        DecPaletteItem(t[1]);
        i++;
    }
}
function incAllBasketItems() {
    // for all items in basket .....
    var idsInOrderArr = $('#sortable').sortable("toArray");
    var inOrder = "";
    var inOrderNames = "";
    var i = 0;
    while (idsInOrderArr.length > i) {
        inOrderNames = inOrderNames + $('#' + idsInOrderArr[i]).text() + ",";
        t = idsInOrderArr[i].split('_');
        // alert(t[1]);
        IncPaletteItem(t[1]);
        i++;
    }
}
function IncPaletteItem(id) {
    $('#palette_txt_' + id).val(parseInt($('#palette_txt_' + id).val()) + 1);
}
function DecPaletteItem(id) {
    if (parseInt($('#palette_txt_' + id).val()) == 1) {
        //  removePaletteItem(id);
    } else {
        $('#palette_txt_' + id).val(parseInt($('#palette_txt_' + id).val()) - 1);
    }
}
function removePaletteItem(id) {
    _chosen = jQuery.grep(_chosen, function(value) {
        return value != id;
    });
    $('#pallete_' + id).remove();
}
// Add a swatch to 'Your Selection' from Swatch grid - need the links for plus/minus/remove
function addToPaletteFromGrid(id, hideAnimation) {
    //hideAnimation -  optional parameter 
    // $("#colour_sq_" + id).effect("transfer", {times:3 }, 300);
    if (hideAnimation) {
    } else {
        $("#colour_sq_" + id).effect('transfer', {to: $('#sortable')}, 600).show();
    }
    if (jQuery.inArray(id, _chosen) == -1) {
        _chosen.push(id);
        acolor = findIdInArray(id);
        if (acolor == -1) {
            //alert('colour not found for this product ');
            return -1;
        }
        id = acolor[0];
        colorname = acolor[1];
        webcolor = acolor[3];
        textcolour = getContrastYIQ(webcolor);
        
        // need to add cursur to plus / minus buttons
        colorname = colorname;
        // uses the global , default to false
        if (_showQty) {
            code = '<li id="pallete_' + id + '"  style="background-color: ' + webcolor + '; background-image: url(' + _imagesDir + 'my_pallete_item_overlay.png); "> <span style="height:30px; line-height:30px; width:145px; vertical-align:middle; color: ' + textcolour + ';">' + colorname + ' </span>';
            code = code + '<div  class="controls" ><a style="cursor:pointer;" class="minus" onclick="DecPaletteItem(' + id + ')"  ><img src="' + _imagesDir + 'minus.png"></a>';
            code = code + '<a style="cursor:pointer;" class="plus" onclick="IncPaletteItem(' + id + ')"  ><img src="' + _imagesDir + 'plus.png"></a>';
            code = code + '<input class="qty" type="text" id="palette_txt_' + id + '" name="palette_txt_' + id + '"  value="1" />';
        } else {
            code = '<li id="pallete_' + id + '"  style="background: url(' + _imagesDir + 'my_pallete_item_overlay_no_qty.png) ' + webcolor + ';background-color: ' + webcolor + ';"> <span style="height:30px; line-height:30px; width:145px; vertical-align:middle; color: ' + textcolour + ';">' + colorname + ' </span>';
            code = code + '<div  class="controls" style="width: 64px;">';
        }
        code = code + '<a style="cursor:pointer;" onclick="removePaletteItem(' + id + ')"  style="float:right" class="remove"><img src="' + _imagesDir + 'remove.png"></a></div></li>';
        $("#sortable").append(code);
    } else {
        alert('This colour is already in `My Palette`');
    }
}
 
 