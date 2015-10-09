<?php
global $smarty;

global $name_parts;


if($name_parts[1] != 'logout'){
	$smarty->display("file:" . $base_path . "/modules/shop_members/templates/submenu.tpl");
}
// include js file 
// $javascript[] = '/modules/shop_members_join/js/functions.js';

define('MEMBER_PAGE', 1);
define('MEMBER_ARTICLE', 2);
define('MEMBER_ORDER', 3);
define('MEMBER_EDIT_DETAILS', 4);
define('MEMBER_LOGOUT', 9);

$memberPageType = MEMBER_PAGE;
if ($session_member_id) {
    // show submenu

    $memberName = db_get_single_value("select trim(concat(firstname,' ', surname)) as `name` from member_user where id = $session_member_id", 'name');
    $smarty->assign('membername', $memberName);

    if ($article_name == 'edit-details') {
        $title = 'Edit Your Details';
        $module_path = 'documents';
        array_shift($name_parts);
        $levels = count($name_parts);
        include $base_path . '/modules/shop_member_join/edit_details.php';
        $memberPageType = MEMBER_EDIT_DETAILS;
    }

    if ($article_name == 'order-details') {
        $title = 'Order Details';
        include 'order_details.php';
        $memberPageType = MEMBER_ORDER;
    } ///////// end merge


    if ($article_name == 'orders-list') {
        $title = 'Order List';
        include 'order_list.php';
        $memberPageType = MEMBER_ORDER;
    }
    if ($article_name == 'logout') {
        $title = 'Logged Out';
        include $base_path . '/php/page_types/logout/main.php';
        $memberPageType = MEMBER_LOGOUT;
    } else if ($article_name) {
        $main_body_sql = "select * from shop_member_page where page_name = '$article_name'";
    }
    else
        $main_body_sql = "select * from shop_member_page where parent_id = 0 order by order_num limit 1";

    if (($memberPageType == MEMBER_PAGE) || ($memberPageType == MEMBER_ARTICLE)) {
       
        $main_body_result = mysql_query($main_body_sql);
        $main_body_row = mysql_fetch_array($main_body_result);
        //if (empty($title))
        $title = $main_body_row['title'];
        //$template_file = 'members.htm';
        echo $main_body_row['body'] . "\n";
        //echo "<br /><a class=\"button-purple\" href=\"/logout\" >logout</a>";
        if ($memberPageType == MEMBER_ARTICLE) {
            $pageName = db_get_single_value("select page_name from shop_member_page where id = {$main_body_row['page_id']}", 'page_name');
            echo "<br /><a href= \"/shop-members/$pageName\">Back</a>";
        };
    };
} else {
    if ($article_name == 'logout') {
        header('Location: /shop-members');
        exit();
    }

    /* default login page */
    //    echo $_SERVER['REMOTE_ADDR'];
    $template_file = "public.htm";
    echo $content_row["body"];
    if (!empty($failstate)) {
        echo $login_error;

    }


    include './php/login_inc.php';
}

