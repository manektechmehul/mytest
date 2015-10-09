<?php

include './php/classes/auto_form.php';
include 'password_field.php';
include "members.php";


class join_form extends auto_form
{
	function  __construct($form_id)
	{
		parent::__construct($form_id);
	}
    
    function get_data()
    {
        parent::get_data();
    }
    
    function activateMember($activationCode) {
		$sql = "SELECT * FROM member_user WHERE activation = '$activationCode'";
		$result = mysql_query($sql);
		if (mysql_numrows($result) == 1)
		{
			$myrow = mysql_fetch_array($result);

			$newMemberID = $myrow['id'];
			$sql = "update member_user set status = 1 WHERE activation = '$activationCode'";
			mysql_query($sql);
			$session_member_id = $newMemberID;

			// log the user in
            $_SESSION["session_member_id"] = $session_member_id;
			$_SESSION["session_member_details"] = $session_member_details;

            echo "<p>Welcome {$myrow['firstname']}, thank you for registering!</p>";
			echo "<p>Your account has been activated.</p>";
			echo "<p>Go straight to <a href=\"/discussions\">the discussions</a>.</p>";
		}
		else
			echo "<p>Sorry we could not activate your membership. Please <a href=\"/sign_up\">try again</a> or <a href=\"/contact_us\">contact us</a>.</p>";
    }
    
    function loadEditData($id) {
        $sql = "select * from member_user where status = 1 and id = $id";
        $row = db_get_single_row($sql);
        $valid = (!empty($row));
        if ($valid) {
            $this->namedFields['title']->default = $row['title'];
            $this->namedFields['firstname']->default = $row['firstname'];
            $this->namedFields['surname']->default = $row['surname'];
            $this->namedFields['email']->default = $row['email'];
            $this->namedFields['confirmemail']->default = $row['email'];
            $this->namedFields['screenname']->default = $row['screenname'];
        }
        return $valid;
    }
        
    function hideFieldsForEdit() {
        foreach ($this->fields as $fieldId => $field)
        {
            if (in_array($field->fieldname, array('password', 'retypepassword','security', 'acceptrules','viewrules')))
                unset($this->fields[$fieldId]);
        }
        $this->capchaField = false;
    }    
    
	function createMember($activationCode)
	{
		$values = array (
			'title' => $this->namedFields['title']->getData(),
			'firstname' => $this->namedFields['firstname']->getData(),
			'surname' => $this->namedFields['surname']->getData(),
			'screenname' => $this->namedFields['screenname']->getData(),
			'email' => $this->namedFields['email']->getData(),
			'password' => $this->namedFields['password']->getData(),
            'activation' => $activationCode
		);

        members::AddMember($values);
	}

	function registerFieldtypes()
	{
		parent::registerFieldtypes();
		fieldFactory::registerFieldType(51, 'password_field');
	}

	function email_activation($activationCode,$form)
	{
		// ($emailaddress, $fromname, $fromaddress, $emailsubject, $body, $body_text, $cc="", $bcc="", $confirm="", $attachment='')
		$emailaddress = $this->namedFields['email']->getData();
		$site_address = SITE_ADDRESS;
        $site_address .= (substr(SITE_ADDRESS, -1) == '/') ? '' : '/';
		$link = $site_address.'sign_up?activate_code='.$activationCode;
		$body = "<!DOCTYPE html PUBLIC \"-//W3C//DTD XHTML 1.0 Transitional//EN\" \"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd\"><html xmlns=\"http://www.w3.org/1999/xhtml\" lang=\"en\" xml:lang=\"en\"><head><title>Church Growth Research Programme</title><style type=\"text/css\"><!-- body { background:#ffffff; margin:0; padding:0; } p { font-family:Arial, Helvetica, sans-serif; font-size:14px; color:#000000; line-height:22px; margin:10px 0px 10px 0px; text-align:left; } h1, h2, h3, h4, h5, h6 { font-family:Arial, Helvetica, sans-serif; } a { color:#2a6307; outline:none; text-decoration:underline; } a:hover { color:#569823; text-decoration:underline; } --> </style></head><body bgcolor=\"#fff\"><div align=\"center\"><table width=\"600\" cellspacing=\"0\" cellpadding=\"0\" style=\"border:none;\"><tbody><tr><td><img width=\"600\" vspace=\"0\" hspace=\"0\" height=\"188\" border=\"0\" align=\"top\" src=\"http://www.churchgrowthresearch.org.uk/images/emailheader.jpg\" alt=\"Church Growth Research Programme\" /></td></tr><tr><td bgcolor=\"#fbf5de\" style=\"padding:0px 30px 0px 30px;\"><p>Welcome to the Church Growth Research Programme.</p><p>Please click on the link below to activate your account:<p>".
			"<p><a href=\"$link\">$link</a></p><p>Thank you, the Church Growth Reseach Programme website team.</p>".
			"</td></tr><tr><td><img width=\"600\" vspace=\"0\" hspace=\"0\" height=\"33\" border=\"0\" align=\"top\" src=\"http://www.churchgrowthresearch.org.uk/images/emailfooter.jpg\" alt=\"Church Growth Research Programme\" /></td></tr></tbody></table></div></body></html>";
		$from_email_address = $form['email'];
		$this->send_mail($emailaddress, SITE_NAME, $from_email_address, 'Account Activation', $body, $body_text);

	}

	function process_data()
	{
        global $smarty;

        $activationCode = sha1( uniqid() );
		$form = db_get_single_row("select * from forms where id = '{$this->form_id}'");
		//$this->save_data();
		$this->createMember($activationCode);
		$this->email_activation($activationCode,$form);
		//$this->email_data($form);
        $_SESSION['activationCode'] = $activationCode;
		//echo "<p>Activation Code: $activationCode</p>";

        //$memberDetails = new memberDetails();
        //$memberDetails->SetFormFields($this->fields);
	}
    
	function process_edit_data($id)
    {
		$values = array (
			'title' => $this->namedFields['title']->getData(),
			'firstname' => $this->namedFields['firstname']->getData(),
			'surname' => $this->namedFields['surname']->getData(),
			'screenname' => $this->namedFields['screenname']->getData(),
			'email' => $this->namedFields['email']->getData(),
			'password' => $this->namedFields['password']->getData(),
		);

        members::UpdateMember($id,$values);
    }    

	function validate_fields()
	{
        global $session_member_id;
		parent::validate_fields();
        $isNew = empty($session_member_id);
        
		$screenName = $this->namedFields['screenname']->getData();
        
        $email = $this->namedFields['email']->getData();
		$confirm = $this->namedFields['confirmemail']->getData();

        $password = $this->namedFields['password']->getRawData();
		$retypepassword = $this->namedFields['retypepassword']->getRawData();
	
		if ($email != $confirm)
			$this->invalid[] = array('name' => 'email', 'message' => 'email addresses do not match');
        
		if ($password != $retypepassword)
			$this->invalid[] = array('name' => 'password', 'message' => 'passwords differ');

        if (!$isNew) {
            $details = members::GetDetails($session_member_id);
        }
        
        if ($isNew || ($email != $details['email'])) {
            // check email unique
            if (members::CheckIfUserWithEmailExists($email))
                $this->invalid[] = array('name' => 'email', 'message' => 'A user already exists with this email address');
        }
        
        if ($isNew || ($screenName != $details['screenname'])) {
            // check screen name unique
            if (members::CheckIfUserWithScreenNameExists($screenName))
                $this->invalid[] = array('name' => 'screenname', 'message' => 'A user already exists with this screen name');
        }
        
        
	}
    
    
    
	function display_data($echo_output = true)
	{
		$output .=  "<table border='0' cellspacing='5' cellpadding='3' style='margin:0' id='form-table'>\n";

		foreach ($this->fields as $fieldname => $field) 
        {
            if (get_class($field) == 'title_field')
                continue;
            $data = $field->getData();
			$output .= "<tr><td>{$field->name}</td><td>{$data}</td></tr>";
        }
		$output .=  '</table>';
              
        return $output; 
	}    
    
    function DisplayConfirm($type)
    {
        global $smarty;
        
        $smarty->assign('details', $this);        
        $detailsTemplateFile =  dirname(dirname(__FILE__));
        $smarty->display("file:".$detailsTemplateFile);        
    }
 
}