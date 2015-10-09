<?php

include 'classes/join.php';
//$javascript[] = '/js/groupusernamecheck.js';
//$javascript[] = '/js/password_strength_plugin.js';
//$javascript[] = '/js/password_strength_check.js';
$suppress_output = false;

$form_id = 2;

$joinForm = new join_form($form_id);
$joinForm->before_form_message = '<p>To change your password <a href="/members/password">click here</a>.</p><p>You may edit your other details below:</p>';
$joinForm->hideFieldsForEdit();

$submit = (isset($_POST['Submit'])) ? $_POST['Submit'] : "";

if ($submit) {
    $joinForm->get_data();
    $suppress_output = true;

    $joinForm->validate_data(false);
    if ($joinForm->has_errors()) {
        $joinForm->display_errors();
    }
    else {
        $joinForm->process_edit_data($session_member_id);
        echo "<p>Your changes have been saved</p>";
    }
}

if (!$suppress_output) {
    if (($form_id > 0) && (!$submit)) {
        $valid = true;

        $valid = $joinForm->loadEditData($session_member_id);
        //$joinForm->process_page = $page;

        $joinForm->display_form();
    }
}
