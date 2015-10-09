<?php

//include './php/databaseconnection.php';
include './php/classes/form_template.php';

class contact_form extends form_template
{
	var $not_yet_registered;
	
	function contact_form ()
	{
		$this->form_template();
		$this->fields = array();
        $this->fields['firstname'] = array('name' => 'First name', 'formtype' => 'text', 'required' => true);
		$this->fields['surname'] = array('name' => 'Surname', 'formtype' => 'text', 'required' => true);
		$this->fields['emailaddress'] = array('name' => 'email address', 'formtype' => 'text', 'required' => true, 'validation' => 'validate_email');
        // $this->fields['address'] = array('name' => 'email address', 'formtype' => 'address', 'required' => false);
		// $this->fields['postcode'] = array('name' => 'Postcode', 'formtype' => 'text', 'required' => true);
		// $this->fields['telephone'] = array('name' => 'Tel (day)', 'formtype' => 'text', 'required' => false);
		// $this->fields['mobile'] = array('name' => 'Mobile', 'formtype' => 'text', 'required' => false);
        if (SITE_HAS_MAILING)
        	$this->fields['receiveemail'] = array('name' => 'Register for Newsletter', 'formtype' => 'checkbox', 'required' => false);
        if (SITE_HAS_CAPTCHA)
            $this->fields['captcha'] = array('name' => 'Security code', 'formtype' => 'captcha', 'required' => true, 'validation' => 'validate_captcha');
        $this->fields['enquiry'] = array('name' => 'Enquiry', 'formtype' => 'textarea', 'required' => false);

	}
			 

	function process_data()
	{
  		$to_email_address = SITE_CONTACT_EMAIL;
		$site_address = SITE_ADDRESS;
		$subject = SITE_NAME. " enquiry from " . $site_address;
	  	$from_email_address = SITE_CONTACT_EMAIL;

		// -------------------------------
	  	// Save option 1. Formatted Email
	  	// -------------------------------
		//$this->send_formatted_email("./php/register_email.html", $this->data['emailaddress'], $from_email_address, $from_email_address);
		
		// -------------------------------
		// Save Option 2 - save to file
		// -------------------------------
		
	  	// -------------------------------
	  	// Save option 3. Custom function
	  	// -------------------------------
		$this->save_data();
		
	  	// -------------------------------
	  	// Save option 4. Standard Email
	  	// -------------------------------
		$subject = 'Enquiry';
		if (!$this->no_enquiry)
			$this->standard_email($site_address, $to_email_address, $from_email_address, $subject);
		
		echo "<p><b>Enquiry Successfully Submitted</b></p>\n<p>Thank you for your enquiry.</p>";	  	
		
	}
		
	function validate_data()
	{
		form_template::validate_data();
	}
	
	function has_errors() 
	{	
		return form_template::has_errors();
	}
			 
	function save_data ()
	{
		$data = $this->data;
		$sql = sprintf("replace into registrants (firstname, surname, emailaddress, receiveemail, added) values ('%s', '%s', '%s', '%s', '%s') ", 
			   $data['firstname'], $data['surname'], $data['emailaddress'], $data['receiveemail'], date('Y-m-d'));
		$result = mysql_query($sql);
	}

	function check_email_doesnt_exist()
	{
		$data = $this->data;
		if ($data['receiveemail']) 
		{
			$sql = 'select * from registrants where emailaddress = \''.$data['emailaddress'].'\'' ;
			$result = mysql_query($sql);
			$rows = mysql_num_rows($result);
			if ($rows > 0)
				echo '<p><strong>Already Registered</strong></p><p>This email address is already registered</p>';
			return ($rows == 0);
		}
		else
			return true;
	}



	//$line_template = "[firstname], [surname], [emailaddress], [address], [postcode], [telephone], [mobile], [receiveemail]\n";
	//$filename = './data/registrants.csv';

}

$template = new contact_form();

$main_body_sql="select * from content where section_id = '$section_id' AND content_type_id = $content_type_id AND template_type = 'main_body' AND live = 1 order by order_num DESC";
$main_body_result = mysql_query($main_body_sql);	
$main_body_row = mysql_fetch_array($main_body_result);
	
$template->before_form_message = $main_body_row['body']."\n";

include "./php/template_form_inc.php";

if (SITE_HAS_MAILING)
    echo "<a href='/unsubscribe'>unsubscribe</a>";
?>
