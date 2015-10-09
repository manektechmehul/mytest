<?php
 
session_cache_limiter('must-revalidate');
session_start();
$base_path = $_SERVER['DOCUMENT_ROOT'];
// Get Session variables
$session_user_id = (isset($_SESSION['session_user_id'])) ? $_SESSION['session_user_id'] : "";
$session_user_type_id = (isset($_SESSION['session_user_type_id'])) ? $_SESSION['session_user_type_id'] : "";
$session_access_to_cms = (isset($_SESSION['session_access_to_cms'])) ? $_SESSION['session_access_to_cms'] : "";
include_once ("$base_path/php/databaseconnection.php");
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    
}
// get all colours for the product to pre load the palette
$path_prefix = "..";
include_once ("$base_path/php/read_config.php");
include_once ("$base_path/admin/cms_functions_inc.php");
include_once $base_path . '/modules/shop/classes/colours.php';
$c = new colours();
$coloursArr = $c->getAllColoursAsJSArray();
//$productColours =  $c->getProductColours($_GET['id']);
// $productColours = window.opener.get_colourDetails();
// Get get and post variables
if (isset($_REQUEST['id']))
    $id = $_REQUEST['id'];
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
        <link href="/css/content.css" rel="stylesheet" type="text/css" /><!-- main stylesheet -->
        <link href="/css/shop.css" rel="stylesheet" type="text/css" /><!-- main stylesheet -->
        <link href="../colour_tools/style.css" rel="stylesheet" type="text/css"/>
        <link href="/css/carousel.css" rel="stylesheet" type="text/css"/>
        <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.5/jquery.min.js"></script>
        <script src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8/jquery-ui.min.js"></script>
        <script type="text/javascript" src="../colour_tools/colour_tools.js"></script>
        <script type="text/javascript" src="/js/carousel.js"></script>
        <link href="../colour_tools/tooltips/jquery.qtip.min.css" rel="stylesheet" type="text/css"/>
        <script type="text/javascript" src="../colour_tools/tooltips/jquery.qtip.min.js" ></script>
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
            $(document).ready(function() {
                allcolors = <?php echo $coloursArr ?>;
                //makeColors();
                createColorGrid(allcolors);
                preloadItems = window.opener.get_colourDetails();
                // window.opener.get_colourDetails();
                preloadColours(preloadItems);
                $('div[title]').qtip();
<?php if ($_SERVER['REQUEST_METHOD'] == 'POST') { ?>
                    alert('Colour Updated');
<?php } ?>
            });
            $(function() {
                $("#sortable").sortable();
                $("#sortable").disableSelection();
            });
            function saveColours() {
                $('#_chosen').val(_chosen);
                returnColourList();
                document.forms[0].submit();
            }
            function preloadColours(items) {
                // loop though each one in the cs list and add to the palette selector
                var i = 0;
                // alert(items.length);
                while (items.length > i) {
                    // do something item			
                    addToPaletteFromGrid(items[i][0]);
                    i++;
                }
            }
            function returnColourList() {
                // look up every colour in the colour list and return the rgb to the opener as an array	
                // loop the ul for the li in the new order ...
                var idsInOrderArr = $('#sortable').sortable("toArray");
                var inOrder = "";
                var inOrderNames = "";
                var i = 0;
                while (idsInOrderArr.length > i) {
                    //alert($('#' + idsInOrderArr[i] ).text());
                    // remove the palette_ from text
                    //inOrderNames = inOrderNames + $('#' + idsInOrderArr[i] ).text() + "," ;
                    t = idsInOrderArr[i].split('_');
                    inOrder = inOrder + t[1] + ",";
                    i++;
                }
                inOrder = inOrder.substring(0, inOrder.length - 1);
                //inOrderNames = inOrderNames.substring(0, inOrderNames.length - 1);
                //$('#_chosen').val(inOrder);
                _chosen = inOrder.split(",");
                i = 0;
                var colourDetails = new Array();
                var acolour = new Array();
                while (_chosen.length > i) {
                    // do something item
                    id = _chosen[i];
                    acolour = findIdInArray(id);
                    // id, colourname, webcolour
                    colourDetails[i] = acolour;
                    i++;
                }
                window.opener.collectColours(colourDetails);
            }
        </script>
        <script type="text/javascript" src="http://use.typekit.com/dmm6wgb.js"></script>
        <script type="text/javascript">try {
        Typekit.load();
    } catch (e) {
    }</script>
    </head>
    <body>
        <form action='' method="" >
            <table style="padding-top: 20px;" width="100%" border="0" cellspacing="2" cellpadding="2">
                <tr>
                    <td rowspan="2" valign="top">
                        <div style="margin: 5px;" id="-holder">
                            
                            <div  id="outer_swatch" >
                                <div id="swatch_filter">
                                    <ul id="swatch_container">
                                    </ul>
                                </div>
                            </div>
                            <!-- - end holder -->
                        </div>
                    </td>
                    <td  valign="top" >
                        <div style="width: 290px; height: 365px; float: right; overflow: auto;overflow-x: hidden; border-left: 1px solid #aaa;"
                             >
                            <div id="sortable_holder">
                                <ul id="sortable"></ul>
                            </div>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td><div id="button_holder" >
                            <button type="button" onClick="saveColours();
        window.close();"> Update Colours for Product </button>
                            <input name="_chosen" id="_chosen" type=hidden value="init" />
                            <button type="button" onClick="clearSelection();"> Clear selection </button>  
                        </div></td>
                </tr>
            </table>
        </form>
    </body>
</html>
<?php
mysql_close($link);
?>