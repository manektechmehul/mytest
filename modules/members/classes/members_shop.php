<?php

class members {

    function AddMember($values) {
        $sql = "INSERT INTO shop_member_user (
                title,
                email,
                firstname,
                surname,
                screenname,
                `password`,
                activation,
                `status`)
                VALUES (
                        '{$values['title']}',
                        '{$values['email']}',
                        '{$values['firstname']}',
                        '{$values['surname']}',
                        '{$values['screenname']}',
                        '{$values['password']}',
                        '{$values['activation']}',
                        0 
                        )";

        mysql_query($sql);
    }

    function UpdateMember($id, $values) {
        $sql = "update shop_member_user set
                    email = '{$values['email']}',
                    screenname = '{$values['screenname']}',
                    firstname = '{$values['firstname']}',
                    surname = '{$values['surname']}',
                    title = '{$values['title']}',
                    `password` = '{$values['password']}'
                where id = $id";

        mysql_query($sql);
    }

    static function GetDetails($id) {
        $sql = "select * from shop_member_user where status = 1 and id = '$id'";
        return db_get_single_row($sql);
    }

    static function GetDetailsByUsername($username) {
        $sql = "select * from shop_member_user where status = 1 and username = '$username'";
        return db_get_single_row($sql);
    }

    static function GetDetailsByEmail($email) {
        $sql = "select * from shop_member_user where status = 1 and email = '$email'";
        return db_get_single_row($sql);
    }

    static function CheckIfUserWithEmailExists($email) {
        $sql = "select count(*) from shop_member_user where email = '$email'";
        return db_get_single_value($sql) > 0;
    }

    static function CheckIfUserWithUsernameExists($username) {
        $sql = "select count(*) from shop_member_user where username = '$username'";
        return db_get_single_value($sql) > 0;
    }

    static function CheckIfUserWithScreenNameExists($screenName) {
        $sql = "select count(*) from shop_member_user where screenname = '$screenName'";
        return db_get_single_value($sql) > 0;
    }

    static function CheckUsernameResetCode($username, $code) {
        $sql = "select count(*) from shop_member_user where status = 1 and username = '$username' and resetcode = '$code'";
        $count = db_get_single_value($sql);
        return $count > 0;
    }

    static function CheckEmailResetCode($email, $code) {
        $sql = "select count(*) from shop_member_user where status = 1 and email = '$email' and resetcode = '$code'";
        $count = db_get_single_value($sql);
        return $count > 0;
    }

    static function CheckValidPassword($password) {
        return strlen($password) >= 6;
    }

    static function GetNewPasswordHash($password) {
        global $base_path;
        require_once $base_path . '/php/password/PasswordHelper.php';
        $pass = new PasswordHelper();
        return $pass->generateHash($password);
    }

    static function ResetPasswordByUsername($username, $code, $password) {
        $hash = self::GetNewPasswordHash($password);
        $sql = "update shop_member_user set password = '$hash', resetcode = '' where status = 1 and username = '$username' and resetcode = '$code'";
        mysql_query($sql);
    }

    static function ResetPasswordByEmail($email, $code, $password) {
        $hash = self::GetNewPasswordHash($password);
        $sql = "update shop_member_user set password = '$hash', resetcode = '' where status = 1 and email = '$email' and resetcode = '$code'";
        mysql_query($sql);
    }

    static function SendPasswordResetByUsername($username) {
        $userDetails = self::GetDetailsByUsername($username);
        self::SendPasswordReset($userDetails);
    }

    static function SendPasswordResetByEmail($email) {
        $userDetails = self::GetDetailsByEmail($email);
        self::SendPasswordReset($userDetails);
    }

    static function SetupResetCode($userId) {
        $resetCode = md5(time());
        $sql = "update shop_member_user set resetcode = '$resetCode' where status = 1 and id = '$userId'";
        mysql_query($sql);
        return $resetCode;
    }

    static function SendPasswordReset($userDetails) {
        global $base_path;
        global $smarty;
        include_once $base_path . '/php/html2text.php';
        include_once $base_path . '/php/functions/form_functions.php';

        $resetCode = self::SetupResetCode($userDetails['id']);

        $fromAddress = MEMBER_PASSWORD_EMAIL_FROM;
        $fromName = EMAIL_INVITE_FROM_NAME;
        $link = SITE_ADDRESS;
        $link .= (substr($link, -1) !== '/') ? '/' : '';
        $link .= 'resetpassword?code=' . $resetCode;
        $message = "<p>Dear {$userDetails['firstname']},</p>" .
                "<p>You have been send this email because you requested a password reset.</p>" .
                "<p>Please <a href=\"$link\">click here</a> to reset your password.</p>" .
                "<p>You can only use this link once. If you need to reset your password again please use the reset your password page again.</p>" .
                "<p>Thanks,<br />$fromName</p>";
        
        $smarty->assign('content', $message);        
        $message = $smarty->fetch($base_path . '/modules/shop/templates/email_template.tpl');
        $title = 'Password Reset';                      
        $messageText = convert_html_to_text($message);
        send_email($userDetails['email'], $fromName, $fromAddress, $title, $message, $messageText);
    }

    function ProcessPasswordChange() {
        $action = !empty($_POST['action']) ? $_POST['action'] : '';
        $result = (object) array('status' => -1, 'msg' => array());

        if ($action == 'change password') {
            // check old password
            $oldPassword = empty($_POST['oldpassword']) ? '' : $_POST['oldpassword'];
            if (empty($oldPassword))
                $result->msg['oldpassword'] = 'Please enter your current password';
            else {
                if (!self::CheckCurrentPassword($oldPassword)) {
                    $result->msg['oldpassword'] = 'You have not entered your current password correctly';
                }
            }

            // check new password is suitable
            $newPassword = empty($_POST['newpassword']) ? '' : $_POST['newpassword'];
            if (!empty($newPassword)) {
                $passwordValid = self::CheckValidPassword($oldPassword);
                if (!$passwordValid)
                    $result->msg['newpassword'] = 'Password must be at least 6 characters';
            }
            else
                $result->msg['newpassword'] = 'Please enter your new password';
            $errCount = count($result->msg);
            if ($errCount == 0)
                $result->status = 1;

            // set password
            if ($result->status == 1)
                $result->status = self::SetPassword($newPassword);
        }
        else
            $result->status = 0;
        return $result;
    }

    static function CheckCurrentPassword($password) {
        global $base_path;
        require_once $base_path . '/php/password/PasswordHelper.php';

        $id = $_SESSION['session_member_id'];
        $hash = db_get_single_value('select password from shop_member_user where id = ' . $id);
        $pass = new PasswordHelper();
        $success = $pass->compareToHash($password, $hash);
        return $success;
    }

    static function SetPassword($password) {
        global $base_path;
        include_once $base_path . '/php/password/PasswordHelper.php';
        $id = $_SESSION['session_member_details']['id'];
        $pass = new PasswordHelper();
        $hash = $pass->generateHash($password);
        $sql = "update shop_member_user set password = '$hash' where id = $id";
        $_SESSION["session_member_details"]['password'] = $hash;
        mysql_query($sql);
        return 1;
    }

}
