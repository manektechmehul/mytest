<?php
function GetMemberMessage() {
        $message = db_get_single_value("select message from message_blocks where sys_code = 'MEMBER_MESSAGE' and show_on_page = 1 ", 'message');
        if($message != ""){
            $message = "<div class='message_block'>" . $message . "</div>";
        }
        return $message;
 }
 function GetMemberMessageformail() {
        $message = db_get_single_value("select message from message_blocks where sys_code = 'MEMBER_MESSAGE' and show_on_email = 1 ", 'message');
        if($message != ""){
            $message = "<div class='message_block'>" . $message . "</div>";
        }
        return $message;
 }
$membermessageblock = GetMemberMessage();
$membermessageblock_formail = GetMemberMessageformail();
?>
