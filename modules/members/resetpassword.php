<?php
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

if(SITE_HAS_SHOP){
  require $base_path . '/modules/members/classes/members_shop.php';  
}else{
  require $base_path . '/modules/members/classes/members.php';  
}

$code = empty($_REQUEST['code']) ? '' : $_REQUEST['code'];
$newPassword = empty($_POST['newPassword']) ? '' : $_POST['newPassword'];
$email = empty($_POST['email']) ? '' : $_POST['email'];
$result->status = 0;
if (!empty($newPassword)) {
    if (members::CheckEmailResetCode($email, $code)) {
        if (members::CheckValidPassword($newPassword)) {
            members::ResetPasswordByEmail($email, $code, $newPassword);
            $result->status = 1;
        }else{
            $result->status = -1;
            $result->msg['newPassword'] = 'Please make sure your password has at least 6 characters';
        }
    }else{
        $result->status = -1;
        $result->msg['code'] = 'There has been an error, please make sure you used the correct email.' .
                '<br />Please note a password reset email will only work once - please click ' .
                '<a href="/forgot_password">here</a> to go to the forgotten password page to get a new password reset email';
    }
}

if ($result->status == -1) {
    foreach ($result->msg as $msg)
        echo "<p>$msg</p>";
}

if(SITE_HAS_SHOP){
    $members_login_page_link = "shop-members";
}else{
    $members_login_page_link = "members";
}

?>
<?php if ($result->status < 1) : ?>
    <p>Please confirm your email below and choose the new password for your account.</p>
    <form method="post">
        <table id="form-table">
          <tr>
            <td width="12%" align="right">Email</td>
       	    <td><input type="text" name="email" value="<?= $email ?>"/></td>
         </tr>
       	  <tr>
       	    <td align="right">New Password</td>
            <td><input type="password" name="newPassword"/></td>
       	  </tr>
          <tr>
            <td>&nbsp;</td>
       	    <td><input type="submit" value="Change Password"/><input type="hidden" value="change password" name ="action"/></td>
          </tr>   	  
        </table>
    </form>
<?php else: ?>
     
    <p>Your password has been changed successfully.</p>
    <p>You can now login <a href="/<? echo $members_login_page_link; ?>">here</a>.</p>
<?php endif; ?>