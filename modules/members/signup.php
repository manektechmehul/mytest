<?php
if ($page_name == 'sign_up') {
    require dirname(__FILE__).'/classes/join.php';
    $javascript[] = '/js/password_strength_plugin.js';
    $javascript[] = '/js/password_strength_check.js';
    $form_id = $content_row['form_id'];
	$page_form = new join_form($form_id);

	$activationCode = (isset($_GET['activate_code'])) ? $_GET['activate_code'] : "";
	$submit = (isset($_POST['Submit'])) ? $_POST['Submit'] : "";

	if (!empty($activationCode))
	{
        $page_form->activateMember($activationCode);
	}
    else {
    
        if ($submit) {
            $suppress_output = true;
            $page_form->get_data($page_type);

            $page_form->validate_data();
            if ($page_form->has_errors())
                $page_form->display_errors();
            else {
                $page_form->process_data();
                echo "<p>You are almost there. Please check your email and click on the activation link.</p>";
            }
        }

        if (!$suppress_output) {
            echo $content_row["body"];
            $page_form->display_form();
        }
    }
}
