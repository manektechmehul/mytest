<?php

include './php/classes/auto_form.php';
include 'password_field.php';
include 'username_field.php';
include 'countries_field.php';
include "member_details.php";
include "payment_details.php";
require_once $base_path . '/modules/promo_codes/class/promotion_code.php';

class join_form extends auto_form {

    function __construct($form_id) {
        parent::__construct($form_id);
        //$this->fields[6]->value = 'test';
    }

    function loadIncomplete($form_id, $memberId) {
        $this->formId = $form_id;
        $this->memberId = $memberId;
        parent::__construct($form_id);
        $sql = "select * from member_user where id = '{$this->memberId}'";
        $row = db_get_single_row($sql);
        $this->namedFields['firstname']->default = $row['firstname'];
        $this->namedFields['surname']->default = $row['surname'];
        $this->namedFields['email']->default = $row['email'];
        $this->namedFields['confirmemail']->default = $row['email'];
        $this->namedFields['address']->default = $row['address'];
        $this->namedFields['country']->default = $row['country_id'];
        $this->namedFields['postcode']->default = $row['postcode'];
        $this->namedFields['phone']->default = $row['phone'];
        $this->namedFields['receive_replies']->default = $row['receive_replies'];
        $this->namedFields['speciality']->default = $row['speciality'];
        $this->namedFields['username']->default = $row['username'];
    }

    function get_data($pageType) {
        parent::get_data();
        $kind = db_get_single_value("select case when is_org_landing then id else 0 end from page_type where id = '$pageType'");
        $this->fields[29]->setValues('speciality where kind = ' . $kind . ' order by order_num');
    }

    function removeOld($activationCode) {
        $sql = "delete from member_user where status = -1 and activation = '$activationCode'";
        //$sql = "update member_user set status = -2 where status = -1 and activation = '$activationCode'";
        mysql_query($sql);
    }

    function createMember($activationCode) {
        global $base_path;
        require_once $base_path . '/php/password/PasswordHelper.php';
        $pass = new PasswordHelper();
        $hash = $pass->generateHash($this->fields[17]->getData(), 7);

        //$promotionCodeId = 0;
        $promotionCode = PromotionCode::checkPromotionCode(str_replace('%','',$this->namedFields['promocode']->getData()));
		echo  $promotionCode;
		//die();
		
		
        $promotionCodeId = PromotionCode::getPromotionId($promotionCode);
        if ($promotionCodeId) {
            PromotionCode::updatePromotionNumberRemaining($promotionCodeId);
        }

        $values = array(
            'firstname' => $this->fields[6]->getData(),
            'surname' => $this->fields[7]->getData(),
            'email' => $this->fields[11]->getData(),
            'username' => $this->fields[16]->getData(),
            'address' => $this->fields[8]->getData(),
            'country_id' => $this->fields[9]->getData(),
            'postcode' => $this->fields[28]->getData(),
            'phone' => $this->fields[10]->getData(),
            'speciality' => $this->fields[29]->getData(),
               'vdp_scheme' => $this->fields[170]->getData(),
               'vdp_trainer' => $this->fields[171]->getData(),
               'vdp_trainer_email_address' => $this->fields[172]->getData(),
               'vdp_expected_finish_date' => $this->fields[173]->getData(),
            'hash' => $hash,
            'gdc' => $this->fields[39]->getData(),
            'promo' => $promotionCodeId,
            'freetrial' => 0
        );

        $values['freetrial'] = $this->namedFields['freetrial']->getData();

        $sql = "INSERT INTO member_user (
				email,
				username,
				firstname,
				surname,
				address,
				country_id,
				postcode,
				phone,
				speciality,
                gdc,
				`hash`,
				`freetrial`,
				`activation`,
				`promo`,
				`status`,
                                vdp_scheme,
                                vdp_trainer,
                                vdp_trainer_email_address,
                                vdp_expected_finish_date )
				VALUES (
					'{$values['email']}',
					'{$values['username']}',
					'{$values['firstname']}',
					'{$values['surname']}',
					'{$values['address']}',
					'{$values['country_id']}',
					'{$values['postcode']}',
					'{$values['phone']}',
					'{$values['speciality']}',
					'{$values['gdc']}',
					'{$values['hash']}',
					'{$values['freetrial']}',
					'$activationCode',
					'{$values['promo']}',
					-1,
                                        '{$values['vdp_scheme']}',
                                        '{$values['vdp_trainer']}',
                                        '{$values['vdp_trainer_email_address']}',
                                        '{$values['vdp_expected_finish_date']}'                                                
					)";

        mysql_query($sql);
        $values['id'] = mysql_insert_id();
        return $values;
    }

    function registerFieldtypes() {
        parent::registerFieldtypes();
        fieldFactory::registerFieldType(50, 'username_field');
        fieldFactory::registerFieldType(51, 'password_field');
        fieldFactory::registerFieldType(52, 'countries_field');
    }

    function email_activation($activationCode, $form) {
        // ($emailaddress, $fromname, $fromaddress, $emailsubject, $body, $body_text, $cc="", $bcc="", $confirm="", $attachment='')
        $emailaddress = $this->fields[11]->getData();
        //$site_address = SITE_ADDRESS . (substr(SITE_ADDRESS, -1) == '/') ? '' : '/';
        //$link = $site_address.'join/activate?code='.$activationCode;
        $link = 'http://www.prodentalcpd.com/join/activate?code=' . $activationCode;
        $body = '<p>Welcome to RD Surgery</p><p>Please click on the link below to activate your account</p>' .
                '<p>Take advantage of our Premium Membership (discounted introductory offer) of only £25 per year - Upgrade Now.</p>' .
                "<p><a href=\"$link\">$link</a></p><p>Thankyou, the RD Surgery Team</p>";
        $body_text = strip_tags(str_replace('</p>', "\r\n", $body));
        $from_email_address = $form['email'];
        $this->send_mail($emailaddress, SITE_NAME, $from_email_address, 'Account Activation', $body, $body_text);
    }

    function process_data() {
        global $smarty;
        $promocode = $this->fields[72]->getData();
        $country = $this->fields[9]->getData();
        $speciality = $this->fields[29]->getData();

        $freeTrial = $this->namedFields['freetrial']->getData();

        $activationCode = sha1(uniqid());
        $form = db_get_single_row("select * from forms where id = '{$this->form_id}'");
        //$this->save_data();
        $oldActivationCode = (!empty($_SESSION['activationCode'])) ? $_SESSION['activationCode'] : '';
        if ($oldActivationCode)
            $this->removeOld($oldActivationCode);
        $memberData = $this->createMember($activationCode);
        //$this->email_activation($activationCode,$form);
        $this->email_data($form);
        $_SESSION['activationCode'] = $activationCode;
        //echo "<p>Activation Code: $activationCode</p>";

        $hinariInitiativeRow = db_get_single_row("select * from countries where id = '{$country}'");
        $hinariInitiative = ($hinariInitiativeRow['hinari_initiative'] == 1);

        $student = ($speciality == 12);

        $memberDetails = new memberDetails();
        $memberDetails->SetFormMemberData($memberData);     
        
       
        
        
        if ($hinariInitiative) {
            $memberDetails->Display();
            $this->DisplayConfirm('hinari');
        } else if ($student) { // student
            // set end date
            //$today = date('Y-m-d');
            //mysql_query("update member_user set lastpayment = '$today' where activation = '$activationCode'");
            $memberDetails->Display();
            $this->DisplayConfirm('student');
        } else if ($freeTrial) {
            $memberDetails->Display();
            $this->DisplayConfirm('trial');
        } else {
            //if (($promocode == '') || (!$this->isValidPromoCode($promocode))){
            echo $form['thankyou'];
            $memberDetails->Display();
            $paymentDetails = new paymentDetails($activationCode, 1, $this->fields[29]->getData(), 0, $memberDetails->promotionCodeId);
            $paymentDetails->outputHiddenForm();
            
            
            
            
            //}
            /* else
              {
              $memberDetails->Display();
              $smarty->assign('promocode', $promocode);
              $this->DisplayConfirm('promo');
              }
             */
        }
    }

    function validate_data() {
        parent::validate_data();
        $email = $this->fields[11]->getData();
        $confirm = $this->fields[18]->getData();
        $promocode = str_replace('%','',$this->fields[72]->getData());

        
        
        // check email isn't being currently used
        
        
        
        
        if ($email != $confirm){
               
            
            $this->invalid[] = array('name' => $this->fields[11]->name, 'message' => 'email addresses do not match');
        }else{
            // if email match, thats good. now lets check if the email is unique
            $count = db_get_single_value("SELECT COUNT(*) FROM member_user WHERE email = '$email'");
            if($count > 0){
                 $this->invalid[] = array('name' => $this->fields[11]->name, 'message' => 'It looks like there is already an account with that email address.');
            }
            
        }
        
        
        
        if (($promocode != '') && (!$this->isValidPromoCode($promocode))) {
            $this->invalid[] = array('name' => $this->fields[72]->name, 'message' => 'promotion code is not valid');
        }
    }

    function isValidPromoCode($code) {
        // should this check for the places left ?????
        $count = db_get_single_value("select count(*) from promo_codes where code = '$code'");
        return ($count == 1);
    }

    /*
      function hiddenForm()
      {
      $firstname = $this->fields[6]->getData();
      $surname = $this->fields[7]->getData();
      $address = $this->fields[8]->getData();
      $surname = $this->fields[7]->getData();
      $email = $this->fields[11]->getData();
      $phone = $this->fields[10]->getData();
      $postcode = $this->fields[28]->getData();
      $speciality = $this->fields[29]->getData();
      $countryId = $this->fields[9]->getData();

      $countryId = (is_numeric($countryId)) ? $countryId : 234;
      //$countryId = 234;

      $name = "$firstname $surname";

      $countryCode = db_get_single_value("select code from countries where id = '$countryId'");


      $paymentUrl = PAYMENT_URL;
      $instId = PAYMENT_INSTALLATION_ID;
      $cartId = $_SESSION['activationCode'];

      $amount = db_get_single_value("select price from speciality where id = '$speciality'");


      $currency = SUBSCRIPTION_CURRENCY;
      $secret = PAYMENT_SECRET;


      $subscriptionDate = mktime(0,0,0,date('n'),date('j'), date('Y')+1);
      $subscriptionDateStr = date('Y-m-d', $subscriptionDate);

      $signatureFields =  "instId:amount:currency:cartId";
      $fieldsCode = "$secret;$signatureFields;$instId;$amount;$currency;$cartId";

      $signature = md5($fieldsCode);
      //echo "<p>{$fieldsCode}</p>";

      $str = "\n\n".'<form method="POST" action="'.$paymentUrl.'">'."\n";
      $str .= '<input type=hidden name="testMode" value="100">'."\n";
      $str .= '<input type="hidden" name="instId" value="'.$instId.'" />'."\n";
      $str .= '<input type="hidden" name="cartId" value="'.$cartId.'" />'."\n";
      $str .= '<input type="hidden" name="amount" value="'.$amount.'" />'."\n";
      $str .= '<input type="hidden" name="currency" value="GBP" />'."\n";

      $str .= '<input type="hidden" name="name" value="'.$name.'" />'."\n";
      $str .= '<input type="hidden" name="address" value="'.$address.'" />'."\n";
      $str .= '<input type="hidden" name="country" value="'.$countryCode.'" />'."\n";
      $str .= '<input type="hidden" name="email" value="'.$email.'" />'."\n";
      $str .= '<input type="hidden" name="phone" value="'.$phone.'" />'."\n";
      $str .= '<input type="hidden" name="postcode" value="'.$postcode.'" />'."\n";
      $str .= '<input type="hidden" name="signatureFields" value="'.$signatureFields.'" />'."\n";

      $str .=   "
      <INPUT TYPE=HIDDEN NAME=\"MC_newuser\" VALUE=\"1\">
      <INPUT TYPE=HIDDEN NAME=futurePayType VALUE=\"regular\">
      <INPUT TYPE=HIDDEN NAME=option VALUE=1>".
      //<INPUT TYPE=HIDDEN NAME=startDate VALUE={$subscriptionDateStr}>
      "<INPUT TYPE=HIDDEN NAME=noOfPayments VALUE=0>
      <INPUT TYPE=HIDDEN NAME=startDelayMult VALUE=1>
      <INPUT TYPE=HIDDEN NAME=startDelayUnit VALUE=4>
      <INPUT TYPE=HIDDEN NAME=intervalMult VALUE=1>
      <INPUT TYPE=HIDDEN NAME=intervalUnit VALUE=4>
      <INPUT TYPE=HIDDEN NAME=normalAmount VALUE=".$amount.">";


      $str .= '<input type="hidden" name="signature" value="'.$signature.'" />'."\n";
      $str .= '<input type="submit" value="Proceed to Payment" />'."\n";

      $str .= '</form>'."\n";
      return $str;

      //  'username' => $this->fields[16]->getData(),
      //	'address' => $this->fields[8]->getData(),
      //	'country' => $this->fields[9]->getData(),
      //	'postcode' => $this->fields[28]->getData(),
      //	'speciality' => $this->fields[29]->getData(),
      //	'other' => $this->fields[30]->getData(),
      //	'password' => $this->fields[17]->getData(),


      }
     */

    function display_data($echo_output = true) {
        $output = "<table border='0' cellspacing='5' cellpadding='3' style='margin:0' id='form-table'>\n";

        foreach ($this->fields as $fieldname => $field) {
            if (get_class($field) == 'title_field')
                continue;
            $data = $field->getData();
            $output .= "<tr><td>{$field->name}</td><td>{$data}</td></tr>";
        }
        $output .= '</table>';

        return $output;
    }

    function DisplayConfirm($type) {
        global $smarty;

        $smarty->assign('details', $this);
        $detailsTemplateFile = dirname(dirname(__FILE__));
        switch ($type) {
            case 'promo':
                $detailsTemplateFile .= '/templates/promoconfirm.tpl';
                break;
            case 'student':
                $detailsTemplateFile .= '/templates/studentconfirm.tpl';
                break;
            case 'hinari':
                $detailsTemplateFile .= '/templates/hinariconfirm.tpl';
                break;
            case 'trial':
                $detailsTemplateFile .= '/templates/trialconfirm.tpl';
                break;
        }
        $smarty->display("file:" . $detailsTemplateFile);
    }

    function OtherJoinComplete() {
        $promoid = 0;
        $activationCode = $_SESSION['activationCode'];
        $sql = "update member_user set status = 1, premium = 1, promo = '$promoid', promo_end_date = '$promoEndDate' where activation = '$activationCode'";
        mysql_query($sql);

        return db_get_single_row("select * from member_user  where activation = '$activationCode'");
    }

     function TrialJoinComplete(){
        $activationCode = $_SESSION['activationCode'];
		//echo 'Activation code (tjc):: ' . $activationCode;
		
		// under no curcumstance update everything with activiiation code = '' 
		if($activationCode != ''){
			//echo ' activating account - ' .  $activationCode ;
			$sql = "update member_user set status = 1, premium = 0 where activation = '$activationCode'";
			mysql_query($sql);			
			$new_member_id = db_get_single_value("select id from member_user where activation = '$activationCode'");
			$this->email_free_account_details($new_member_id);
		}else{
			$new_member_id = false;
		}
        return $new_member_id;
    }

    function email_free_account_details($member_id) {
        // ($emailaddress, $fromname, $fromaddress, $emailsubject, $body, $body_text, $cc="", $bcc="", $confirm="", $attachment='')
        
        $member = db_get_single_row("select * from member_user where id =" . $member_id);
     
        $emailaddress = $member['email'];
        //$site_address = SITE_ADDRESS . (substr(SITE_ADDRESS, -1) == '/') ? '' : '/';
        $link = $site_address;      
                
        $body = '<p>Welcome to ProDentalCPD.</p><p>Thank you for signing up to your account.</p>' .
                '<p>Your username is: ' . $member['username']  . '</p>' .
                '<p>Please keep this in a safe place so you can always access your account. If you ever forget your password then this can be retrieved at any point from the website as long as you have your username and this email address.</p>' .
                "<p>Thank you, the ProDentalCPD team</p><p><a href=\"$link\">$link</a></p>" ;
        
        $body_text = strip_tags(str_replace('</p>', "\r\n", $body));
        $from_email_address = 'info@prodentalcpd.com';
        $this->send_mail($emailaddress, SITE_NAME, $from_email_address, 'Your Account Activation', $body, $body_text);
    }
}