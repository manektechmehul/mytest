<?php

if ($session_member_id) {
    $form_id = 6; // ??? should this be a different form id

    include 'class/password.php';
    $suppress_output = false;
    $page_form = new passwordForm($form_id, $session_member_id);
    $page_form->get_data();
    $submit = (isset($_POST['Submit'])) ? $_POST['Submit'] : "";
    if ($submit) {
        $suppress_output = true;
        $page_form->validate_data();
        if ($page_form->has_errors())
            $page_form->display_errors();
        else {
            $page_form->process_data();
            echo "<a href=\"{$_SESSION['returnPage']}\">Return</a>";
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
    include './php/login_inc.php';
}