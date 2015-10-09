$(document).ready(function(){
  // Your code here...
    $(".first").focus();    
});

function set_category_online(elem, featured, id)
{
    var row = elem.parentNode;
    img = elem.childNodes[0];
    if (featured != '1')
    {
        img.oSrc = img.oSrc.replace(/show/g, 'hide');
        img.src = img.src.replace(/show/g, 'hide');
        img.oSrc = img.oSrc.replace(/not.featured/g, 'featured');
        img.src = img.src.replace(/not.featured/g, 'featured');
        elem.onclick = function() {
            return set_category_online(this, '1', id);
        };
        value = 1;
    }else{
        img.oSrc = img.oSrc.replace(/hide/g, 'show');
        img.src = img.src.replace(/hide/g, 'show');
        img.oSrc = img.oSrc.replace(/featured/g, 'not featured');
        img.src = img.src.replace(/featured/g, 'not featured');
        elem.onclick = function() {
            return set_category_online(this, '0', id);
        };
        value = 0;
    }
    var jsel = document.createElement('SCRIPT');
    jsel.type = 'text/javascript';
    jsel.src = '/modules/shop/admin/ajax.php?action=set_category_online&id=' + id + '&value=' + value;
    // Append JS element (thereby executing the 'AJAX' call)
    document.body.appendChild(jsel);
    //document.body.removeChild(jsel);    return false;
    return false;
    
    
}