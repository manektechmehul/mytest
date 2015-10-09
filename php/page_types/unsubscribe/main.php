<?php


include './php/databaseconnection.php';
include './php/classes/form_template.php';

class unsubscribe_form extends form_template
{
	var $registered;
	
	function unsubscribe_form ()
	{
		$this->form_template();
		$this->fields = array( 
			'emailaddress' => array('name' => 'email address', 'formtype' => 'text', 'required' => true, 'validation' => 'validate_email'),
			//'captcha' => array('name' => 'Security code', 'formtype' => 'captcha', 'required' => true, 'validation' => 'validate_captcha')
			);
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
		$this->delete_data();
		
	  	// -------------------------------
	  	// Save option 4. Standard Email
	  	// -------------------------------
		$subject = 'Enquiry';
		if (!$this->no_enquiry)
			$this->standard_email($site_address, $to_email_address, $from_email_address, $subject);
		
		echo "<p><b>Email address Successfully Removed</b></p>\n<p>Your email address has been removed from our mailing list.</p>";	  	
		
	}
		
	function validate_data()
	{
		$this->registered = $this->check_email_exists();
		form_template::validate_data();
	}
	
	function has_errors() 
	{	
		return form_template::has_errors() || (!$this->registered);
	}
			 
	function delete_data ()
	{
		$data = $this->data;
		$sql = sprintf("delete from registrants where emailaddress = '%s'", $data['emailaddress']);
		$result = mysql_query($sql);
	}

	function check_email_exists()
	{
		$data = $this->data;
		$sql = 'select * from registrants where emailaddress = \''.$data['emailaddress'].'\'' ;
		$result = mysql_query($sql);
		$rows = mysql_num_rows($result);
		if ($rows == 0)
			echo '<p><strong>Not Registered</strong></p><p>This email address is not registered</p>';
		return ($rows > 0);
	}



	//$line_template = "[firstname], [surname], [emailaddress], [address], [postcode], [telephone], [mobile], [receiveemail]\n";
	//$filename = './data/registrants.csv';

}

$template = new unsubscribe_form();

$main_body_sql="select * from content where section_id = '$section_id' AND content_type_id = $content_type_id AND template_type = 'main_body' AND live = 1 order by order_num DESC";
$main_body_result = mysql_query($main_body_sql);	
$main_body_row = mysql_fetch_array($main_body_result);
	
$template->before_form_message = $main_body_row['body']."\n";

include "./php/template_form_inc.php";
?>