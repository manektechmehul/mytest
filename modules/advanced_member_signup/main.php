<?php

    include_once "$base_path/modules/$module_path/conf.php";
    include_once "$base_path/modules/$module_path/classes/join.php";
    include_once "$base_path/modules/$module_path/classes/AdvancedMemberSignup.php";
    include_once $base_path . "/php/phpmailer/class.phpmailer.php";
    global $name_parts;
    // Module Landing Page
    if ($name_parts[1] == 'confirm') {
        $title = 'Initiate a new Membership';
        $ams   = new AdvancedMemberSignup();
        $ams->setConfirmCode1( urldecode( $name_parts[2] ) );
        //TODO: check the status of the app ... if it is already at stage 1 . say so and block
        if ($ams->found()) {
            // var_dump($app);
            // need to pass this to a template and say welcome;
            // customise a new version of this form with suitable preamble
            $form_id         = 6;
            $suppress_output = false;
            $page_form       = new join_form( $form_id );
            $page_form->get_data();
            $submit        = ( isset( $_POST['Submit'] ) ) ? $_POST['Submit'] : "";
            $submitConfirm = ( isset( $_POST['submitConfirm'] ) ) ? $_POST['submitConfirm'] : "";
            if ($submit) {
                $suppress_output = true;
                if ( ! empty( $_SESSION['activationCode'] )) {
                    $page_form->removeOld( $_SESSION['activationCode'] );
                }
                $page_form->validate_data();
                if ($page_form->has_errors()) {
                    $page_form->display_errors();
                } else {
                    // inserts member record into member table
                    $applicant_new_member_id = $page_form->process_data();
                    // update the application table with next step code match and set status to 1
                    $ams->updateApplicantCompletedForm( $applicant_new_member_id );
                    // mail the seconder to confirm the application
                    $ams->initiateSeconderConformation();
                    echo "Thanks you for completing the form. Your seconder has been mailed and they can now more to the next phase of your application";
                }
            }
            if ( ! $suppress_output) {
                if (( $form_id > 0 ) && ( ! $submit )) {
                    //   if (!isset($resubscribe)) {
                    echo $content_row['body'];
                    //  }else{
                    $page_form->before_form_message = '';
                    $page_form->display_form();
                    // }
                }
            }
        } else {
            echo "Sorry, we couldn't find your application";
        }
    }
    if ($name_parts[1] == 'seconder') {
        $title = 'Second a new Membership';
        // This will need to look up the seconder email code NOT the first ¬!¬¬
        $ams = new AdvancedMemberSignup();
        //TODO: check the status of the app ... if it is already at stage 2 . say so and block
        $ams->setConfirmCode2( urldecode( $name_parts[2] ) );
        if ($ams->found()) {
            // Show some data (we will need to email this to the management so mayas well lay it up here and add a confirm button !
            $submit        = ( isset( $_POST['Submit'] ) ) ? $_POST['Submit'] : "";
            $submitConfirm = ( isset( $_POST['submitConfirm'] ) ) ? $_POST['submitConfirm'] : "";
            if ($submit) {
                // confirm seconder
                // update the status of the application
                $ams->confirmApplication();
                // output - thanks you message and say its gone to be reviewed
                echo "Thanks for completing the process. The board will now review this application.";
            } else {
                // invite the seconder to review and confirm the application
                $output = $ams->getApplicationDetails();
                echo $output;
                echo $ams->fetch( 'confirm_app.tpl' );
            }
            // if all good email the management to let them know there is one to process
        } else {
            echo "Sorry, we couldn't find your application";
        }
    }