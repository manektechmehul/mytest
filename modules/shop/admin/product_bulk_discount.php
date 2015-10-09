<?php
session_cache_limiter('must-revalidate');
session_start();
$base_path = $_SERVER['DOCUMENT_ROOT'];
// Get Session variables
$session_user_id =  (isset($_SESSION['session_user_id'])) ? $_SESSION['session_user_id'] : "";
$session_user_type_id = (isset($_SESSION['session_user_type_id'])) ? $_SESSION['session_user_type_id'] : "";
$session_access_to_cms = (isset($_SESSION['session_access_to_cms'])) ? $_SESSION['session_access_to_cms'] : "";
include_once ("$base_path/php/databaseconnection.php");
// get all colours for the product to pre load the palette
$path_prefix = "..";
include_once ("$base_path/php/read_config.php");
include_once ("$base_path/admin/cms_functions_inc.php");
include_once $base_path.'/modules/shop/classes/bulk_discount.php';
$bd = new bulk_discount();
$productsArr = $bd->getAllActiveProducts();
//$productColours =  $c->getProductColours($_GET['id']);
// $productColours = window.opener.get_colourDetails();
// Get get and post variables
if (isset($_REQUEST['id'])) $id = $_REQUEST['id'];
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>Direct Colour International</title>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<meta name="keywords" content="Direct Colour International" />
<meta name="description" content="Direct Colour International" />
<meta http-equiv="X-UA-Compatible" content="IE=8" />
<link href="/favicon.ico" rel="shortcut icon" />
<script type="text/javascript" src="http://use.typekit.com/dmm6wgb.js"></script>
<script type="text/javascript">try{Typekit.load();}catch(e){}</script>
<link href="/css/page-format.css" rel="stylesheet" type="text/css" /><!-- main stylesheet -->
<link href="/css/styles.css" rel="stylesheet" type="text/css" /><!-- main stylesheet -->
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.6.2/jquery.min.js"></script>
<script src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8/jquery-ui.min.js"></script>
<link href="/colour_tools/tooltips/jquery.qtip.min.css" rel="stylesheet" type="text/css"/>
<script type="text/javascript" src="/colour_tools/tooltips/jquery.qtip.min.js" ></script>
<style>
.button{
 height: 24px;
 border: 1px solid #ccc;
 border-right: 2px solid #aaa;
 border-bottom: 2px solid #aaa;
 background: #ccc;
 padding: 4px;	
 margin:4px;
 colour: #666;
 cursor: pointer;
}
</style>
<script>
_imagesDir = "/images/shop/colour_tools/";
var allproducts =   <?php   echo $productsArr ?>	;
$(document).ready(function() {	
// add as array .... dont allow duplicates
_chosen_products = Array();
	 createProductList(allproducts);
	 discounted_product_details = window.opener.get_bd_productDetails();
	 discounts= window.opener.get_bd_discounts();
	 preloadCurrentProduct(<?php echo $_GET[id] ; ?>);
	//	alert(' id is <?php echo $_GET[id] ; ?>');
	 preloadProducts(discounted_product_details);	
	 preloadDiscounts(discounts);
	//$('div[title]').qtip();
});
	$(function() {
		$("#chosenproducts").sortable();
		$("#chosenproducts").disableSelection();
	});	
function preloadDiscounts(discounts){
	discArr = discounts.split(',');
	$('#item1').val(discArr[0]);
	$('#dis1').val(discArr[1]);
	$('#item2').val(discArr[2]);
	$('#dis2').val(discArr[3]);
	$('#item3').val(discArr[4]);
	$('#dis3').val(discArr[5]);
}
function preloadCurrentProduct(id){
	//alert(allproducts.length);
	for (i=0; i < allproducts.length; i++) {
		if(id == allproducts[i][0]){
			/// alert('matched');
			addToChosenDiscountGroup(allproducts[i][0], allproducts[i][1], true);
		}
	}
}
function preloadProducts(items){
		// loop though each one in the list and add to the chosen group item list 
		// de we need to unseralise ??? 
		var i = 0;
		while(items.length > i){
			// do something item			
			addToChosenDiscountGroup(items[i][0], items[i][1]);
			i++;
		}
}
function createProductList(allproducts){
	// alert(allproducts.length);
	$('#allproducts').attr('innerHTML','');
	for (i=0; i < allproducts.length; i++) {
		$('#allproducts').append(createProductListItem(allproducts[i][0], allproducts[i][1]));
	}
}
function addToChosenDiscountGroup(id, name, current){
	// chosenproducts	
	// if not in array add it ... 
	if(jQuery.inArray(id,_chosen_products) == '-1' ){
		$('#chosenproducts').append(createChosenProductListItem(id, name, current));
		_chosen_products.push(id);
	}
}
function removeChosenItem(id){
	// todo remove from global array of chosen items ...
	$("#chosen_product_" + id).remove();
	_chosen_products = jQuery.grep(_chosen_products, function(value) {
		  return value != id;
		});
}
function createChosenProductListItem(id, name, current){
	if(current){
		click = "onclick='alert(\"This item cannot be removed, as it is the current product\")'";	
		product = "<li  id='chosen_product_" + id + "' style='cursor: pointer;background: #cc3333;' ><div  " + click + " >  ["  + id  + "] " + name + "</div></li>";
	}else{
		click = "onclick='removeChosenItem("+ id + ")'";	
		product = "<li  id='chosen_product_" + id + "' style='cursor: pointer;' ><div  " + click + " ><img src='" + _imagesDir + "minus.png'> ["  + id  + "] " + name + "</div></li>";
	}
	return product;
}
function createProductListItem(id, name){
	click = "onclick='addToChosenDiscountGroup("+ id + ", \"" + name + "\")'";	
	product = "<li id='product_block_" + id + "' style='cursor: pointer;' ><div  " + click + " ><img src='" + _imagesDir + "plus.png'> ["  + id  + "] " + name + "  </div></li>";
	return product;
}
	function saveColours(){
		$('#_chosen').val(_chosen);		
		returnColourList();
		document.forms[0].submit();	
	}
function returnProductList(){
	// look up every colour in the colour list and return the rgb to the opener as an array	
	var discount_products = [];
	// discount_products = array[];
	// GL TODO: do some vaidation on the discoutn percentages 
	ids = "";
	//product_ids = _chosen_products.toString();
	//alert(
	//alert(_chosen_products.length);
	//arr = _chosen_products.split(',');
	for(j=0; j< _chosen_products.length; j++){
	for (i=0; i < allproducts.length; i++) {
		ids = allproducts[i][0];
		if(allproducts[i][0]== _chosen_products[j] ){
			discount_products.push(allproducts[i]);
		}
		//$('#allproducts').append(createProductListItem(allproducts[i][0], allproducts[i][1]));
	}
	}// end _chosen_products loop
	//alert(_chosen);
	_discounts = $('#item1').val()  + "," +  $('#dis1').val();
	_discounts = _discounts + "," + $('#item2').val()  + "," +  $('#dis2').val();
	_discounts = _discounts + "," + $('#item3').val()  + "," +  $('#dis3').val();
	// alert(discount_products);
	// --  will need to get the names to match these !!
   window.opener.collectProducts(discount_products);
   window.opener.collect_discounts(_discounts);
   window.opener.collect_discount_product_ids(_chosen_products.toString());
   window.close();
}
</script>
	<script type="text/javascript" src="http://use.typekit.com/dmm6wgb.js"></script>
<script type="text/javascript">try{Typekit.load();}catch(e){}</script>
</head>
<body>
<style>
.holder{
	border: 0px solid #ccc;
	height: 400px;
	overflow-y: auto;
}
.holder ul li {
 border: 1px solid #ccc;
 list-style-type: none;
 padding: 2px;
 border-top: 0px;
 background: #fff;
}
.holder ul{
	margin:0px;
	padding: 0px;
}	
.header{
	background: #ccc;
}
body{
	background: #E1E7EB;
}
</style>
 <table width="100%" border="1" cellspacing="0" cellpadding="0" bgcolor="">
      <tr class="header">
        <td colspan="2"><strong>All Products (Click an item to add to group) </strong></td>
        <td width="48%"><strong>Discount Group (Click to remove an item from the Group) </strong></td>
      </tr>
      <tr>
        <td colspan="2" valign="top" ><div  class="holder"> <ul id="allproducts">  </ul></div> </td>
        <td valign="top" ><div class="holder"><ul id="chosenproducts"> </ul></div></td>
      </tr>
      <tr class="header" >
        <td align="center">no of items for discount (0 = inactive)</td>
        <td align="center"> % discount</td>
        <td>&nbsp;</td>
      </tr>
      <tr>
        <td align="center"><label for="item1"></label>
        <input type="text" name="item1" id="item1"  size=4  value="0" /></td>
        <td align="center"><input type="text" name="dis1" id="dis1"  size="4"  value="0" />
          %</td>
        <td>&nbsp;</td>
      </tr>
      <tr>
        <td align="center"><input type="text" name="item2" id="item2"  size="4"  value="0" /></td>
        <td align="center"><input type="text" name="dis2" id="dis2"  value="0"  size="4"/>
          %</td>
        <td>&nbsp;</td>
      </tr>
      <tr>
        <td width="26%" align="center"><input type="text" name="item3" id="item3"  value="0"  size="4"/></td>
        <td width="22%" align="center"><input type="text" name="dis3" id="dis3"  value="0"  size="4"/>
          %</td>
        <td align="right"><input type="button" name="btn_cancel" id="btn_cancel" value="Cancel" onclick="window.close();" />
        <input type="button" name="btn_update" id="btn_update" value="Update"  onclick="returnProductList();"  /></td>
      </tr>
    </table>
</body>
</html>
<?php
mysql_close ( $link );
?>