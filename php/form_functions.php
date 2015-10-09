<?php
function display_date_form_field($base_field_name, $date, $year_start, $year_end) {
    $months = array('Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec');
    $current_day = date('j', $date);
    $current_month = date('n', $date);
    $current_year = date('Y', $date);
// display day drop-down
    $out = sprintf(" <select name=\"%s\"><option value=\"\">day</option>", $base_field_name . "_day");
    for ($day_num = 1; $day_num < 32; $day_num++)
        $out .= sprintf("<option value=\"%02d\" %s>%02d</option>", $day_num, ($day_num == $current_day) ? "selected" : "", $day_num);
    $out .= sprintf("</select>");
// display month drop-down
    $out .= sprintf(" <select name=\"%s\"><option value=\"\">month</option>", $base_field_name . "_month");
    $month_num = 1;
    for ($month_num = 1; $month_num < 13; $month_num++)
        $out .= sprintf("<option value=\"%s\" %s>%s</option>", $month_num, ($month_num == $current_month) ? "selected" : "", $months[$month_num - 1]);
    $out .= sprintf("</select>");
// display year drop-down
    $out .= sprintf(" <select name=\"%s\"><option value=\"\">year</option>", $base_field_name . "_year");
    $year_num = $year_start;
    for ($year_num = $year_start; $year_num <= $year_end; $year_num++)
        $out .= sprintf("<option value=\"%s\" %s>%s</option>", $year_num, ($year_num == $current_year) ? "selected" : "", $year_num);
    $out .= sprintf("</select>");
    return $out;
}

function validate_email($email) {
    return ereg('^[-!#$%&\'*+\\./0-9=?A-Z^_`a-z{|}~]+' .
            '@' .
            '[-!#$%&\'*+\\/0-9=?A-Z^_`a-z{|}~]+\.' .
            '[-!#$%&\'*+\\./0-9=?A-Z^_`a-z{|}~]+$', $email);
}

function validate_captcha($code) {
    $img = new securimage();
    return $img->check($code);
}

function send_mail($emailaddress, $fromname, $fromaddress, $emailsubject, $body, $body_text, $cc = "", $bcc = "", $confirm = "", $attachment = '') {
    $mail = new PHPMailer();
    $mail->IsMail();
    $mail->From = $fromaddress;
    $mail->FromName = $fromname;
    if ($cc)
        $mail->AddCC($cc);
    if ($bcc)
        $mail->AddBCC($bcc);

    $mail->AddAddress($emailaddress);
    $mail->AddReplyTo($fromaddress, $fromname);
    if ($confirm)
        $mail->ConfirmReadingTo = $fromaddress;

    if ($attachment)
        $mail->AddAttachment($attachment);
    $mail->WordWrap = 50;                                 // set word wrap to 50 characters
    $mail->IsHTML(true);                                  // set email format to HTML
    $mail->Subject = $emailsubject;
    $mail->Body = $body;
    $mail->AltBody = $body_text;
    return $mail->Send();
}

?>
