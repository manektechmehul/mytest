<?php
if ($session_member_id)
{
    $memberDetails = db_get_single_row("select firstname as `name`, screenname from member_user where id = $session_member_id", 'name');
    $smarty->assign('membername', $memberDetails['name']);
    $smarty->assign('memberScreenName', $memberDetails['screenname']);
    $smarty->assign('memberid', $session_member_id);
}
else
    $smarty->assign('memberid', $session_member_id);

if (!empty($failstate) && ($failstate > LOGIN_SUCCESSFULL)) {
    include dirname(dirname(__FILE__)).'/members/main.php';
}
else{
    include 'noticeboard.php';
  
}
