{literal}

<link rel="stylesheet" href="//code.jquery.com/ui/1.11.1/themes/smoothness/jquery-ui.css">
<!-- <script src="//code.jquery.com/jquery-1.10.2.js"></script> --->
<script src="//code.jquery.com/ui/1.11.1/jquery-ui.js"></script>    
     <style>
.ui-autocomplete-loading {
background: white url("http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.2/themes/smoothness/images/ui-anim_basic_16x16.gif") right center no-repeat;
}
</style>
    
<script>
$(function() {
 
$( "#ajax_search" ).autocomplete({
source: "/modules/my_health/ajax_search.php",
// minLength: 2,
select: function( event, ui ) {
/*log( ui.item ?
"Selected: " + ui.item.value + " aka " + ui.item.id :
"Nothing selected, input was " + this.value );
}*/

page_name = ui.item.id;
// goto selected page
// window.location.href =  location.href.substring(0, location.href.lastIndexOf("/")+1) + page_name;

//alert("/my-health/" + page_name);
window.location.href = "/your-health/" + page_name;
}

});
});
</script>

{/literal}

<a class="yourhealthbutton" href="/your-health">Your Health Home</a>
<div class="ui-widget">
<label for="ajax_search">Search Your Health</label>
<input id="ajax_search">
</div>
 