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

if (!empty($_POST['action'])) {
    $result = members::ProcessPasswordChange();   
}

if ($result->status == -1) {
    foreach ($result->msg as $msg)
        echo "<p>$msg</p>";
}        
    ?>
    <?php if ($result->status < 1) : ?>
<form method="post">
<p><span style="width:100px; display:block; float:left;">Old Password: </span><input type="password" name="oldpassword"/></p>
<p><span style="width:100px; display:block; float:left;">New Password: </span><input type="password" name="newpassword"/></p>
<p><span style="width:100px; display:block; float:left;">&nbsp;</span><input type="submit" value="Change"/></p>
<input type="hidden" value="change password" name ="action"/>
</form>
    <?php else: ?>
        <p>Your password has been changed successfully.</p>
    <?php endif; ?>
