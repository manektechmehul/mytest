<?php

// Report all PHP errors (see changelog)
// error_reporting(E_ALL);
// ini_set("display_errors", 1);
// This is the forms id in the forms table for this member login form
$form_id = 6;
if ($article_name == 'activate') {
    // this is when the user returns to the site after getting the activation email
  

   
    $activationCode = $_GET['code'];
    $sql = "SELECT * FROM shop_member_user WHERE activation = '$activationCode'";
    $result = mysql_query($sql);
    if (mysql_numrows($result) == 1) {
        $detailsRow = mysql_fetch_array($result);

        $newMemberID = $detailsRow['id'];
        $sql = "update shop_member_user set status = 1 WHERE activation = '$activationCode'";
        mysql_query($sql);
        $session_member_id = $newMemberID;

       

        // log the user in
        $_SESSION["session_member_id"] = $session_member_id;
       //$_SESSION["isTrade"] = 1; // this means member !
        $_SESSION["session_member_name"] = "{$detailsRow['firstname']} {$detailsRow['surname']}";
        $_SESSION["session_member_details"] = $detailsRow;
        
        echo "<p class=\"large\">Welcome {$detailsRow['firstname']}, thank you for registering!</p>";
        echo "<p>Your account has been activated.</p>";
        echo "<p>Now that your account is active, you can:</p>";
        echo "<ul>";
        echo "<li><a href=\"/shop-members\">Manage your account</a>, <a href=\"/shop-members/edit-details\">edit your details</a> and <a href=\"/shop-members/orders-list\">view your order history</a>.</li>";
        echo "<li>Any questions? <a href=\"/get-in-touch\">Get in touch</a>.</li>";
        echo "</ul>";
        echo "<p>We look forward to supporting you<br />";
        //echo "<strong>Direct Colour International</strong></p>";
    }
    else
        echo "<p class=\"large\">Sorry we could not activate your membership. Please <a href=\"/shop-member-join\">try again</a> or <a href=\"/contact_us\">contact us</a>.</p>";
}elseif($article_name == 'edit-details'){
 
    include 'edit_details.php';
    
}else {
 
    $smarty->assign('signup', 'true');
    include 'class/join.php';

    $suppress_output = false;
    $page_form = new join_form($form_id);
    $page_form->get_data();
    $submit = (isset($_POST['Submit'])) ? $_POST['Submit'] : "";
    $submitConfirm = (isset($_POST['submitConfirm'])) ? $_POST['submitConfirm'] : "";

    if ($submit) {
        $suppress_output = true;
        if (!empty($_SESSION['activationCode'])) {
            $page_form->removeOld($_SESSION['activationCode']);
        }
        $page_form->validate_data();
        if ($page_form->has_errors())
            $page_form->display_errors();
        else {
            $memberDetails = $page_form->process_data();
        }
    }

    if (!$suppress_output) {
        if (($form_id > 0) && (!$submit)) {
         //   if (!isset($resubscribe)) {
                echo $content_row['body'];
          //  }else{
                $page_form->before_form_message = '';
                $page_form->display_form();
       // }
        }
    }
}