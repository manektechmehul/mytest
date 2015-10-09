<?php
$javascript[] = '/modules/shop_member_join/js/functions.js';

if (!empty($session_member_id)) {
    $form_id = 6;
    include 'class/edit.php';   
    $suppress_output = false;
    $page_form = new edit_form($form_id, $session_member_id);
    $page_form->get_data();
    $submit = (isset($_POST['Submit'])) ? $_POST['Submit'] : "";
    if ($submit) {
        $suppress_output = true;
        $page_form->validate_data();
        if ($page_form->has_errors())
            $page_form->display_errors();
        else {
            $page_form->process_data();
            //echo "<a href=\"{$_SESSION['returnPage']}\">Return</a>";
        }
    }

    if (!$suppress_output) {
        echo $main_body_row['body'] . "\n";
        if (($form_id > 0) && (!$submit)) {
            $page_form->display_form();
        }
    }
} else {
    if ($failstate > 0)
        echo $login_error;
    echo "<p>You need to be logged in to view this page.</p>";
    echo "<p>Please login on the left to edit your account details.</p>";
}