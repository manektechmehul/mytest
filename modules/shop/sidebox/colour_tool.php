<?php
include_once $base_path . '/modules/shop/classes/colours.php';
$c = new colours();
$coloursArr = $c->getAllColoursAsJSArray();
$showmessage = '';
if (isset($_POST['_chosen'])) {
    $showmessage = "showFadeInmessage(' Pallete Updated ');";
    $_SESSION['my_palette'] = $_POST['_chosen'];
    $_SESSION['my_palette_name_number'] = $_POST['_chosen_name_number'];
}
$my_palette = $_SESSION['my_palette'];
$smarty->assign('my_pallete', $my_palette);
$smarty->assign('coloursArr', $coloursArr);
$smarty->assign('showmessage', $showmessage);
$shop_colour_tool_sidebox_template_file = "$base_path/modules/shop/templates/colour_tool.tpl";
$filters['colour_tool'] = array('search_string' => '/<!-- CS Colour Tool Start -->(.*)<!-- CS Colour Tool End -->/s',
    'replace_string' => '{include file="file:' . $shop_colour_tool_sidebox_template_file . '"}');
?>