<?php

include_once "./php/phpmailer/class.phpmailer.php";

class form_template {

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

    function form_template() {
        $this->required_symbol = '*';
        $this->submit_button_text = 'Submit';
        $this->submit_button = '';
        $this->process_page = $page;
        $this->before_form_message = '<br/><br/>We would be pleased to hear from you with any questions you may have';
        $this->textfield_message_template = "%s: %s\n";
        $this->textarea_message_template = "%s: %s\n";
        $this->checkbox_message_template = "\n%s\n\n";
        $this->checkboxgroup_message_template = "\nx%s: %s\n";
    }

    function get_data() {
        foreach ($this->fields as $fieldname => $field) {
             
            $this->data[$fieldname] = (isset($_REQUEST[$fieldname])) ? $_REQUEST[$fieldname] : "";

            /* this might be helpful - with the checkbox group later */
            if ($field['formtype'] == 'checkboxgroup') {
                foreach ($field['options'] as $optionname => $option)
                    $this->data[$optionname] = (isset($_REQUEST[$optionname])) ? $_REQUEST[$optionname] : "";
            }
            if ($field['formtype'] == 'date') {
                $this->data[$fieldname] = get_date_from_request($fieldname);
            }
        }
    }

    function has_errors() {
        return (count($this->missing) > 0) || (count($this->invalid) > 0) || (isset($custom_validation_result) && ($custom_validation_result == false));
    }

    function display_errors() {
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
            foreach ($this->invalid as $invaliditem) {
                if ($invaliditem['message'])
                    echo $invaliditem['message'];
                else {
                    echo "<p><strong>Error: Invalid {$invaliditem['name']}</strong></p>";

                    echo "<p>Your enquiry has not yet been submitted because you have entered an invalid {$invaliditem['name']}.</p>";
                    printf("<p>Please click on the <b>Back</b> button to re-enter the {$invaliditem['name']} field.</p>");
                }
            }
        }

        printf("<p><input type=\"button\" class=\"buttonback\" VALUE=\"Back\" onClick=\"history.go(-1)\"></p>");
    }

    function validate_data() {
        $this->missing = array();
        $this->invalid = array();
        foreach ($this->fields as $fieldname => $field) {
            if ((isset($field['required'])) && ($field['required'])) {
                if ($field['formtype'] == 'address') {
                    if ((!isset($this->data[$fieldname])) || (!$this->data[$fieldname][0]))
                        $this->missing[] = $field['name'];
                }
                else {
                    if ((!isset($this->data[$fieldname])) || (!$this->data[$fieldname]))
                        $this->missing[] = $field['name'];
                }
            }
            if (($field['validation']) && (isset($this->data[$fieldname])) && (!$this->$field['validation']($this->data[$fieldname]))) {

                $message = (isset($field['invalid_message'])) ? $field['invalid_message'] : '';
                $this->invalid[] = array('name' => $field['name'], 'message' => $message);
            }
        }
    }

    function process_data() {
        //
    }

    function send_formatted_email($email_template, $to_address = '', $from_email_address, $from_name, $cc = '', $bcc = '', $confirm = false, $attachment = '') {
        $message = file_get_contents($email_template);

        foreach ($this->fields as $fieldname => $field) {
            if ($field['formtype'] == 'text') {
                $message = str_replace("[FC:$fieldname]", ucwords($this->data[$fieldname]), $message);
                $message = str_replace("[$fieldname]", $this->data[$fieldname], $message);
            }

            if ($field['formtype'] == 'textarea') {
                $text = str_replace("\'", "'", $this->data[$fieldname]);
                $message = str_replace("[$fieldname]", $text, $message);
            }

            if ($field['formtype'] == 'address') {
                $text = implode(',', $this->data[$fieldname]);
                $message = str_replace("[$fieldname]", $text, $message);
            }

            /*
              if ($field['formtype'] == 'checkbox')
              $message = str_replace("[$fieldname]", $field['name'], $message);
             */
        }

        // uses ~ instead of / 
        $tags = array('~<h[123][^>]*>~si', '~<h[456][^>]*>~si', '~<table[^>]*>~si', '~<tr[^>]*>~si', '~<li[^>]*>~si',
            '~<br[^>]*>~si', '~<p[^>]*>~si', '~<div[^>]+>~si');

        $plain_message = preg_replace($tags, "\n", $message);
        $plain_message = strip_tags($plain_message);

        $this->send_mail($to_address, $from_name, $from_email_address, $subject, $message, $plain_message, $cc, $bcc, $confirm, $attachment);
    }

    function save_to_file($line, $filename) {
        foreach ($this->fields as $fieldname => $field) {
            if ($field['formtype'] == 'text') {
                $line = str_replace("[FC:$fieldname]", ucwords($this->data[$fieldname]), $line);
                $line = str_replace("[$fieldname]", $this->data[$fieldname], $line);
            }

            if ($field['formtype'] == 'textarea') {
                $text = str_replace("\'", "'", $this->data[$fieldname]);
                $line = str_replace("[$fieldname]", $text, $line);
            }

            if ($field['formtype'] == 'address') {
                if (is_array($this->data[$fieldname]) && (count($this->data[$fieldname]) > 0)) {
                    $text = implode(',', $this->data[$fieldname]);
                    $line = str_replace("[$fieldname]", $text, $line);
                }
            }

            if ($field['formtype'] == 'checkbox') {
                $text = 'no';
                if ($this->data['$fieldname'])
                    $text = 'yes';
                $line = str_replace("[$fieldname]", $text, $line);
            }
        }
        $outfile = fopen($filename, 'a');
        fwrite($outfile, $line);
        fclose($outfile);
    }

    function standard_email($site_address, $to_email_address, $from_email_address, $subject) {
        $message = "The following enquiry has been submitted from $site_address.\n\n";

        foreach ($this->fields as $fieldname => $field) {
            if ($field['formtype'] == 'text')
                $message .= sprintf($this->textfield_message_template, $field['name'], $this->data[$fieldname]);
            if ($field['formtype'] == 'hidden')
                $message .= sprintf($this->textfield_message_template, $field['name'], $this->data[$fieldname]);
            if ($field['formtype'] == 'date')
                $message .= sprintf($this->textfield_message_template, $field['name'], date('jS M Y', $this->data[$fieldname]));
            if ($field['formtype'] == 'textarea')
                $message .= sprintf($this->textarea_message_template, $field['name'], str_replace("\'", "'", $this->data[$fieldname]));
            if ($field['formtype'] == 'checkbox') {
                $checkbox_msg = $field['name'] . ': ';
                $checkbox_msg .= ($this->data[$fieldname]) ? 'yes' : 'no';
                $message .= sprintf($this->checkbox_message_template, $checkbox_msg);
            }
            if ($field['formtype'] == 'checkboxgroup') {
                foreach ($field['options'] as $optionname => $option)
                    if ($this->data[$optionname])
                        $message .= sprintf($this->checkboxgroup_message_template, $field['name'], $this->data[$optionname]);
            }
        }
        //echo  $message;
        mail($to_email_address, $subject, $message, 'FROM: ' . $from_email_address);
    }

    function display_form($echo_output = true) {
        $output = '';
        $output .= $this->before_form_message;

        $output .= "<form method='post' action='{$this->process_page}'  onSubmit='reset_image()'>";
        $output .= "<input type='hidden' name='section_id' value='$section_id'>";
        $output .= "<table border='0' cellspacing='5' cellpadding='3' style='margin:0' id='form-table'>";

        /*
          $textfield_template = "<tr valign=\"middle\">\n<td width=\"22%%\">\n<div align=\"right\">%s</div>\n</td>\n" .
          "<td>\n<input type=\"text\" name=\"%s\" maxlength=\"%s\"> %s </td></tr>\n\n";

          $textfield_titleright_template = "<tr valign=\"middle\">\n<td width=\"22%%\"></td>\n" .
          "<td><br/>%s<br/>\n<input type=\"text\" name=\"%s\" maxlength=\"%s\"> %s </td></tr>\n\n";
         */
        $textfield_template = "<tr valign=\"middle\">\n<td width=\"22%%\">\n<div align=\"right\">%s%s</div>\n</td>\n" .
                "<td>\n<input type=\"text\" name=\"%s\" maxlength=\"%s\" value=\"%s\">   </td></tr>\n\n";

        $textfield_titleright_template = "<tr valign=\"middle\">\n<td width=\"22%%\"></td>\n" .
                "<td><br/>%s<br/>\n<input type=\"text\" name=\"%s\"  value=\"%s\" maxlength=\"%s\"> %s </td></tr>\n\n";


        $textfield_short_template = '<tr valign=top><td>%s</td>
			<td><input type=text name=%s size=30 value="%s"></td></tr></td></tr>';
        $textarea_template = '<tr valign=top><td width="22%%"><div align="right">%s</div></td>
			<td><textarea name=%s rows="%s" cols="%s"></textarea>%s</td></tr></td></tr>';
        $grouptitle_template = '<tr valign=middle><td width="22%%"><div align="right">%s</div></td>
			<td></td></tr>';

        $titleright_template = '<tr valign=middle><td width="22%%"></td>
			<td>%s</td></tr>';

        $simple_template = '<tr valign=top><td width="22%%"><div align="right">%s</div></td><td>%s</td></tr>';

        $fixed_template = '<tr valign=middle><td width="22%%"><div align="right"><label for="form-%s">%s</label></div></td><td>%s<input type="hidden" id="form-%s" name="%s" value="%s"/></td></tr>';

        $hidden_template = '<input type="hidden" name="%s" value="%s"/>';


        $address_outer_template = '<tr valign=top><td width="22%%"><div align="right">%s</div></td><td>%s</td></tr>';
        $address_inner_template = '<input type=text name=%s[] size=30 value="%s"> %s<br />';
        $checkbox_template = '<tr valign=top><td></td>
				<td><input type=checkbox name=%s %s %s value=%s> %s</td></tr></td></tr>';

        $checkboxgroup_template = '<tr valign=top><td><div align="right">%s</div></td><td>%s</td></tr></td></tr>';
        $checkboxgroup_titleright_template = '<tr valign=top><td></td><td><br/>%s<br />%s</td></tr></td></tr>';
        $checkboxinner_template = '<input type=checkbox name=%s %s value=%s>%s<br/>';

        $htmlfield_template = '<tr valign=top><td>%s</td><td width=100%%>%s</td></tr>';

        $captcha_str = '<tr valign="middle"> 
					<td width="22%"> 
					  <div align="right"><label for="form-captcha">Security Code</label></div>
					</td>
					<td><span id="captcha_flag" style="display:none"></span>
					<span id="captchaImg"></span>
					<img src="/php/securimage/securimage_show.php"><br />
					<input type="text" id="form-captcha" name="captcha" /> * 
				  </tr>';

        foreach ($this->fields as $fieldname => $field) {
            $required = "";
            $len = 65;
            if (isset($field['required']) && ($field['required'] == true))
                $required = $this->required_symbol;
            if (isset($field['length']) && ($field['length'] == true))
                $len = 65;

            $value = (isset($field['defaultValue'])) ? $field['defaultValue'] : '';

            if ($field['formtype'] == 'text') {
                if (isset($field['titlepos']) && ($field['titlepos'] == 'right'))
                    $output .= sprintf($textfield_titleright_template, $field['name'], $fieldname, $len, $value, $required);
                else
                    $output .= sprintf($textfield_template, $field['name'], $required, $fieldname, $len, $value );
            }

            if ($field['formtype'] == 'hidden') {
                $output .= sprintf($hidden_template, $fieldname, $field['value']);
            }

            if ($field['formtype'] == 'textarea')
                $output .= sprintf($textarea_template, $field['name'], $fieldname, 3, 20, $required);

            if ($field['formtype'] == 'grouptitle')
                $output .= sprintf($grouptitle_template, $field['name']);
            if ($field['formtype'] == 'fixed')
                $output .= sprintf($fixed_template, $fieldname, $field['name'], $field['value'], $fieldname, $fieldname, $field['value']);

            if ($field['formtype'] == 'date') {
                $inner = make_date_menus(date('Y-m-d'), $fieldname, 1900, date('Y'));
                $output .= sprintf($simple_template, $field['name'], $inner);
            }

            if ($field['formtype'] == 'checkboxgroup') {
                $inner = "";
                foreach ($field[options] as $optionname => $option) {
                    $checked = ((isset($option['checked'])) && ($option['checked'])) ? "checked" : "";
                    $inner .= sprintf($checkboxinner_template, $optionname, $checked, $option['value'], $option['name']);
                }
                if (isset($field['titlepos']) && ($field['titlepos'] == 'right'))
                    $output .= sprintf($checkboxgroup_titleright_template, $field['name'], $inner);
                else
                    $output .= sprintf($checkboxgroup_template, $field['name'], $inner);
            }

            if ($field['formtype'] == 'titleright')
                $output .= sprintf($titleright_template, $field['name']);

            if ($field['formtype'] == 'captcha')
                $output .= $captcha_str;

            if ($field['formtype'] == 'checkbox') {

                $onclick = (isset($field['onclick'])) ? 'onclick="' . $field['onclick'] . '"' : '';
                $checked = ((isset($field['checked'])) && ($field['checked'])) ? "checked" : "";
                $output .= sprintf($checkbox_template, $fieldname, $checked, $onclick, 1, $field['name']);
            }

            if ($field['formtype'] == 'address') {
                $lines = (isset($field['lines'])) ? $field['lines'] : 3;
                $inner = "";
                for ($i = 0; $i < $lines; $i++) {
                    $inner .= sprintf($address_inner_template, $fieldname, "", ($i == 0) ? $required : "");
                }
                $output .= sprintf($address_outer_template, $field['name'], $inner);
            }

			if ($field['formtype'] == 'lookup')
			{
				$func = $field['function'];
				//if ($field['not_field'])
					//$fieldname = $field['lookup_field'];
				$value = $content_row[$fieldname];
				if (!$value) 
					$value = $field['defaultValue'];
				//$display_value = $this->$func($value);
				//if (!$field['not_field'])
				//	printf($hidden_template, $fieldname, $value);
				// printf($simple_template, $field['name'], $display_value);
				
				 $output .= sprintf($htmlfield_template, '<div align="right">' .  $field['name'] . '</div>', $this->$func($value));    
				
				
			}
				
            //if ($field['formtype'] == 'textarea')
            //	printf($textarea_template, $field['name'], $fieldname, $field['rows'], $field['cols'], $content_row[$fieldname]);
            //if ($field['formtype'] == 'shorttext')
            //	printf($textfield_short_template, $field['name'], $fieldname, $content_row[$fieldname]);
            //if ($field['formtype'] == 'checkbox') {
            //	$checked = ($content_row[$fieldname]) ? "checked" : "";
            //	printf($checkbox_template, $field['name'], $fieldname, $checked, 1);
            //}
        }

        if (!isset($hide_required_text) || ($hide_required_text == false)) {

            $output .= '';
          //  $output .= '<tr valign="middle"> 
			//		<td width="22%">&nbsp;</td>
			//		<td><i>Please note that an * indicates a mandatory field</i> 
			//		</td>
			//	  </tr>';
        }

        if ($this->submit_button)
            $output .= '<tr valign="middle"> 
                    <td width="22%">&nbsp;</td>
                    <td>' . $this->submit_button . '</td>
                  </tr>';
        else
            $output .= '<tr valign="middle"> 
					<td width="22%">&nbsp;</td>
					<td> 
					  <input type="submit" name="Submit" value="' . $this->submit_button_text . '">
					</td>
				  </tr>';
        $output .= '
        		</table>
			  </form>';

        if ($echo_output)
            echo $output;

        return $output;
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

}

?>
