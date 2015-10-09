<?php

$activationCode = $_GET['code'];
$sql = "SELECT * FROM shop_member_user WHERE activation = '$activationCode'";
$result = mysql_query($sql);
if (mysql_numrows($result) == 1) {
    $myrow = mysql_fetch_array($result);
    $newMemberID = $myrow['id'];
    $sql = "update shop_member_user set status = 1 WHERE activation = '$activationCode'";
    mysql_query($sql);
    $session_member_id = $newMemberID;
    // log the user in
    $_SESSION["session_member_id"] = $session_member_id;
    $_SESSION["session_member_details"] = $session_member_details;
    echo "<p>Welcome {$myrow['firstname']},<br />Your account has been activated</p>";
    echo "<a href=\"/shop-members\">Go to members area</a>";
}else{
    echo "<p>Sorry we could not reset yout password</p>";
}