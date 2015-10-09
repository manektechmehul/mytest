// globals

function checkTemp(val){
   var cval = $("#"+val).find('.img_coords').val();
   alert(cval);
   var data = 'coords=' + cval + '&location=' + location1 + '&year=' + year;
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
