<?php

$_link_prefix = "/modules/shop/admin/";


include_once($base_path . '/php/databaseconnection.php');
$shop_use_stock_control = db_get_single_value("SELECT value FROM configuration WHERE `name` = 'SHOP_USE_STOCK_CONTROL'");
$shop_use_colours = db_get_single_value("SELECT `value` FROM configuration WHERE `name` = 'SHOP_USE_COLOURS'");
$shop_use_gender = db_get_single_value("SELECT `value` FROM configuration WHERE `name` = 'SHOP_USE_GENDER'");
$shop_use_size = db_get_single_value("SELECT `value` FROM configuration WHERE `name` = 'SHOP_USE_SIZE'");



$second_level_navigavtion = '';
//$on = ($second_admin_tab == 'members') ? 'over' : 'off';
//$second_level_navigavtion .= '<a href="/modules/shop_members/admin/members.php" onmouseout="button_off(this)" onmouseover="button_over(this)"><img src="/admin/images/buttons/2ndrowtab-members-' . $on . '.gif" alt="members" name="members"  /></a>';
if ($shop_use_colours == '1') {
    $on = ($second_admin_tab == 'colour') ? 'over' : 'off';
    $second_level_navigavtion .= '<a href="' . $_link_prefix . 'colour.php" onmouseout="button_off(this)" onmouseover="button_over(this)"><img src="/admin/images/buttons/2ndrowtab-colour-' . $on . '.gif" alt="colours" name="colours"  /></a>';
}


if ($shop_use_gender == '1') {
    $on = ($second_admin_tab == 'gender') ? 'over' : 'off';
    $second_level_navigavtion .= '<a href="' . $_link_prefix . 'gender.php" onmouseout="button_off(this)" onmouseover="button_over(this)"><img src="/admin/images/buttons/2ndrowtab-gender-' . $on . '.gif" alt="gender" name="gender"  /></a>';
}

if ($shop_use_size == '1') {
    $on = ($second_admin_tab == 'size') ? 'over' : 'off';
    $second_level_navigavtion .= '<a href="' . $_link_prefix . 'size.php" onmouseout="button_off(this)" onmouseover="button_over(this)"><img src="/admin/images/buttons/2ndrowtab-size-' . $on . '.gif" alt="size" name="size"  /></a>';
}

$on = ($second_admin_tab == 'orders') ? 'over' : 'off';
$second_level_navigavtion .= '<a href="' . $_link_prefix . 'orders.php" onmouseout="button_off(this)" onmouseover="button_over(this)"><img src="/admin/images/buttons/2ndrowtab-orders-' . $on . '.gif" alt="Orders" name="orders"  /></a>';
$on = ($second_admin_tab == 'categories') ? 'over' : 'off';
$second_level_navigavtion .= '<a href="' . $_link_prefix . 'categories.php" onmouseout="button_off(this)" onmouseover="button_over(this)"><img src="/admin/images/buttons/2ndrowtab-categories-' . $on . '.gif" alt="Categories" name="categories"  /></a>';
if ($shop_use_stock_control == '1') {
    $on = ($second_admin_tab == 'stock_adjust') ? 'over' : 'off';
    $second_level_navigavtion .= '<a href="' . $_link_prefix . 'stock_adjustment.php" onmouseout="button_off(this)" onmouseover="button_over(this)"><img src="/admin/images/buttons/2ndrowtab-stock_adjustment-' . $on . '.gif" alt="Stock" name="stock"  /></a>';
}
$on = ($second_admin_tab == 'product') ? 'over' : 'off';
$second_level_navigavtion .= '<a href="' . $_link_prefix . 'product.php" onmouseout="button_off(this)" onmouseover="button_over(this)"><img src="/admin/images/buttons/2ndrowtab-products-' . $on . '.gif" alt="Products" name="products"  /></a>';
?>
