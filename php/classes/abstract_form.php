<?php

include_once "./php/phpmailer/class.phpmailer.php";

abstract class abstract_form
{

    var $required_symbol;
    var $fields;
    var $data;
    var $process_page;
    var $before_form_message;
    var $submit_button_text;
    var $submit_button;
    var $textfield_message_template;
    var $textarea_message_template;
    var $checkbox_message_template;
    var $checkboxgroup_message_template;
    var $section_header;
    var $section_footer;
    var $sections_Arr;

    function __construct()
    {
        global $page;
        $this->required_symbol = '*';
        $this->submit_button_text = 'Submit';
        $this->submit_button = '';
        $this->process_page = $page;

        $this->section_header = "";
        $this->section_footer = "";
        $this->sections_Arr = array();

        $this->before_form_message = '<br/><br/>We would be pleased to hear from you with any questions you may have';
        $this->textfield_message_template = "%s: %s\n";
        $this->textarea_message_template = "%s: %s\n";
        $this->checkbox_message_template = "\n%s\n\n";
        $this->checkboxgroup_message_template = "\nx%s: %s\n";
    }

    function get_data()
    {
        foreach ($this->fields as $fieldname => $field)
            if ($field->dataField)
                $field->setData();
    }

    function has_errors()
    {
        return (count($this->missing) > 0) || (count($this->invalid) > 0) || (isset($custom_validation_result) && ($custom_validation_result == false));
    }

    function display_errors()
    {
        if (count($this->missing) > 0) {
            echo "<b>Error: Missing Information</b>";
            echo "<p>The following fields were left blank in your enquiry form:";
            echo "<ul>";
            foreach ($this->missing as $missingitem)
                printf("<li>$missingitem</li>");
            printf("</ul>");

            printf("<p>Please return to the form to enter the missing information.</p>");
        }
        if (count($this->invalid) > 0) {
            echo "<h3>Errors</h3>";
            foreach ($this->invalid as $invaliditem) {

                if ($invaliditem['message'])
                    echo "<p><strong>" . $invaliditem['message'] . "</strong></p>";
                else {
                    echo "<p><strong>Error: Invalid {$invaliditem['name']}</strong></p>";
                }
            }
            echo "<p>Your data has not yet been submitted because you have entered invalid data.</p>";
            printf("<p>Please click on the <b>Back</b> button to make corrections.</p>");
        }

        printf("<p><INPUT TYPE=\"button\" class=\"csbutton\" VALUE=\"Back\" onClick=\"history.go(-1)\">");
    }

    function validate_data()
    {
        $this->missing = array();
        $this->invalid = array();

        foreach ($this->fields as $fieldname => $field) {
            if ($field->required || $field->validate) {
                $missing = $field->isDataMissing($this->data . $fieldname);

                if ($field->required && $missing)
                    $this->missing[] = $field->name;

                if ($field->validate && !$missing)
                    if ($field->isDataInvalid())
                        $this->invalid[] = array('name' => $field->name, 'message' => $field->invalidDataMessage);
            }
        }
    }

    function process_data()
    {
        //
    }

    function send_formatted_email($email_template, $to_address = '', $from_email_address, $from_name, $cc = '', $bcc = '', $confirm = false, $attachment = '')
    {
        $message = file_get_contents($email_template);

        foreach ($this->fields as $fieldname => $field)
            if ($field->dataField)
                $message = $field->processTemplate($fieldname, $this->data, $message);

        // uses ~ instead of / 
        $tags = array('~<h[123][^>]*>~si', '~<h[456][^>]*>~si', '~<table[^>]*>~si', '~<tr[^>]*>~si', '~<li[^>]*>~si',
            '~<br[^>]*>~si', '~<p[^>]*>~si', '~<div[^>]+>~si');

        $plain_message = preg_replace($tags, "\n", $message);
        $plain_message = strip_tags($plain_message);

        $this->send_mail($to_address, $from_name, $from_email_address, $subject, $message, $plain_message, $cc, $bcc, $confirm, $attachment);
    }

    function save_to_file($line, $filename)
    {
        foreach ($this->fields as $fieldname => $field)
            if ($field->dataField)
                $line = $field->processTemplate($fieldname, $this->data);

        $outfile = fopen($filename, 'a');
        fwrite($outfile, $line);
        fclose($outfile);
    }

    function standard_email($site_address, $to_email_address, $from_email_address, $subject)
    {
        $message = "The following enquiry has been submitted from $site_address.\n\n";

        foreach ($this->fields as $fieldname => $field)
            if ($field->dataField)
                $message .= $field->getEmailMessage();
        //echo  "<pre>$message</pre>";
        mail($to_email_address, $subject, $message, 'FROM: ' . $from_email_address);
    }

    function display_form($echo_output = true)
    {
        // are we looking at a long section form or just a single table form ?
        $multpage_form = true;
        if (sizeof($this->sections_Arr) == 0) {
            $multpage_form = false;
        }
        // if form has sctions - output section header
		$output .= "<div class=\"mainform\">";
        $output .= $this->before_form_message;
        $output .= $this->section_header;


        $output .= "<form enctype='multipart/form-data' method='post' action='{$this->process_page}'>\n<div class='tab-content tab-content-form'>\n";
        //  $output .= "<input type='hidden' name='section_id' value='$section_id'>\n";
        if (!$multpage_form) {
            //$output .= "<div class='form_section_panel' style='display: block'  >";
            $output .= "<table border='0' cellspacing='5' cellpadding='3' style='margin:0' id='form-table'>\n";
        }else{
           // $output .= "<table border='0' cellspacing='5' cellpadding='3' style='margin:0' id='form-table'>\n";
        }

        $section_count = 0;
        $first_section = true;
        $previous_section_id = 0;
        foreach ($this->fields as $fieldname => $field) {

            if ($multpage_form) {
                $section_id = $field->getFormSection();
                if ($section_id != $previous_section_id) {
                    if (!$first_section) {
                        $output .= "</table>";

                        if ($section_count > 1) {
                            $output .= "<a class='form_section_prev_btn' href='javascript:void(0)' onclick='prev_form_section(form_section_" . $previous_section_id . ", form_section_" . $previous_previous_section_id . ");'><< Back</a>";
                        }
                        $output .= "<a class='form_section_next_btn' href='javascript:void(0)' onclick='next_form_section(form_section_" . $section_id . ",form_section_" . $previous_section_id . ");'>Next >></a>";
                        // end form section div
                        $output .= "</div>";
                        $output .= "<div class='form_section_panel tab-pane fade' id='form_section_{$section_id}' name='first tab'>";
                        
                    } else {
                        //    $output .= "<table border='0' cellspacing='5' cellpadding='3' style='margin:0' id='form-table' name='not first tab'>\n";
                        $output .= "<div class='form_section_panel tab-pane fade active in' id='form_section_{$section_id}' >";
                    }
                    //$output .= "<div class='form_section_title'> " . $this->sections_Arr[$section_count]["title"] . " </div>";
                    $output .= "<div class='form_section_desc'> " . $this->sections_Arr[$section_count]["description"] . " </div>";
                    // this is the bit before the multi-form
                    $output .= "<table border='0' cellspacing='5' cellpadding='3' style='margin:0' id='form-table'>\n";
                    $section_count = $section_count + 1;
                    $first_section = false;
                }
                $previous_section_id = $section_id;
                $previous_previous_section_id = $previous_section_id;
            }
            $output .= $field->output();

        }


        /*
          if (!isset($hide_required_text) || ($hide_required_text == false))
          {

          $output .= '<tr valign="middle">
          <td width="22%">&nbsp;</td>
          <td><i>Please note that an * indicates a mandatory field</i>
          </td>
          </tr>';

          }
         */


        if ($this->submit_button) {
            $output .= '<tr valign="middle"> 
                    <td width="22%">&nbsp;</td>
                    <td>' . $this->submit_button . '</td>
                  </tr>';
        } else {
            $output .= '<tr valign="middle"> 
					<td width="22%">&nbsp;</td>
					<td> 
					  <input type="hidden" name="Submit" value="1">
					  <input type="submit" class="formsubmit" name="Submitbtn" value="' . $this->submit_button_text . '">
					</td>
				  </tr>';
        }


        // section help
        // tidy up final table + div
        $output .= "</table>";
        // for single one $output .= "</div>";

        if ($multpage_form) {
            // end form section div
            if ($section_count > 1) {
                $output .= "<a class='form_section_prev_btn' href='javascript:void(0)' onclick='prev_form_section(form_section_" . $previous_section_id . ");'><< Back</a>";

            }
            $output .= "</div>";
            // this should be BEFORE the non-multi-page form, not after!
            //  $output .= "<table border='0' cellspacing='5' cellpadding='3' style='margin:0' id='form-table' name='trailing table' >\n";
        } else {

        }


        // end section help

        $output .= '</div></form></div>';


        if ($echo_output) {
            echo $output;
        }

        return $output;
    }

    function validate_email($email)
    {
        return ereg('^[-!#$%&\'*+\\./0-9=?A-Z^_`a-z{|}~]+' .
            '@' .
            '[-!#$%&\'*+\\/0-9=?A-Z^_`a-z{|}~]+\.' .
            '[-!#$%&\'*+\\./0-9=?A-Z^_`a-z{|}~]+$', $email);
    }

    function validate_captcha($code)
    {
        $img = new securimage();
        return $img->check($code);
    }

    function send_mail($emailaddress, $fromname, $fromaddress, $emailsubject, $body, $body_text, $cc = "", $bcc = "", $confirm = "", $attachment = '')
    {
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

        if (is_array($attachment)) {
            foreach ($attachment as $att) {
                //echo "xxxxxxxxxxxxxx $att";
                $mail->AddAttachment($att);
            }
        } else if (!empty($attachment)) {
            $mail->AddAttachment($attachment);
        }

        $mail->WordWrap = 50;                                 // set word wrap to 50 characters
        $mail->IsHTML(true);                                  // set email format to HTML

        $mail->Subject = $emailsubject;
        $mail->Body = $body;
        $mail->AltBody = $body_text;

        return $mail->Send();
    }

}
