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
$email = empty($_POST['email']) ? '' : $_POST['email'];

$result->status = 0;
if (!empty($email)) {
    if (members::CheckIfUserWithEmailExists($email)) {
        members::SendPasswordResetByEmail($email);
        $result->status = 1;
    }
    else {
        $result->status = -1;
        $result->msg['email'] = 'Could not find a record with that email.';
    }     
}

if ($result->status == -1) {
    foreach ($result->msg as $msg)
        echo "<p>$msg</p>";
}        
    ?>
    <?php if ($result->status < 1) : ?>
<form method="post">
<p>Please enter your email address for your account below. An email will be sent to you with a reset password link.</p>

        <table id="form-table">
          <tr>
            <td width="12%" align="right">Email</td>
       	    <td><input type="Text" name="email"></td>
         </tr>
          <tr>
            <td>&nbsp;</td>
       	    <td><input type="submit" value="Send Reset Email"/><input type="hidden" value="send email" name ="action"/></td>
          </tr>    	  
        </table>

</form>
    <?php else: ?>
        <p>An email has been sent to you with a reset password link.</p>
        <p>Check your inbox (note, this may not be immediate, please be patient).</p>
    <?php endif; ?>