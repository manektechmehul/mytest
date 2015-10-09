<?php

include './php/databaseconnection.php';

$fields = array( 
	'firstname' => array('name' => 'First name', 'formtype' => 'text', 'required' => true),
	'surname' => array('name' => 'Surname', 'formtype' => 'text', 'required' => true),
	'emailaddress' => array('name' => 'email address', 'formtype' => 'text', 'required' => true, 'validation' => 'validate_email'),
//	'address' => array('name' => 'Address', 'formtype' => 'address', 'lines' => 3, 'required' => false),
//	'postcode' => array('name' => 'Postcode', 'formtype' => 'text', 'required' => true),
//	'telephone' => array('name' => 'Tel (day)', 'formtype' => 'text', 'required' => false),
//	'mobile' => array('name' => 'Mobile', 'formtype' => 'text', 'required' => false),
//	'receiveemail' => array('name' => 'I would like receive email information and special offers from Principal Beauty. I understand that my personal information will not be shared and will not be used for any other purpose.',
		 //'formtype' => 'checkbox', 'required' => false),
    'enquiry' => array('name' => 'Enquiry', 'formtype' => 'textarea', 'required' => false),
	'captcha' => array('name' => 'Security code', 'formtype' => 'captcha', 'required' => true, 'validation' => 'validate_captcha')
		 );
		 

function save_data ($data)
{
	$sql = sprintf("insert into registrants (emailaddress, added) values ('%s', '%s') ", 
	       $data['emailaddress'], date('Y-m-d'));
	$result = mysql_query($sql);
}

function check_email_doesnt_exist($data)
{
	$sql = 'select * from registrants where emailaddress = \''.$data['emailaddress'].'\'' ;
	$result = mysql_query($sql);
	$rows = mysql_num_rows($result);
	if ($rows > 0)
		echo '<p><strong>Already Registered</strong></p><p>This email address is already registered</p>';
	return ($rows == 0);
}

//No Database
$site_row = array (
	'site_contact_email' => SITE_CONTACT_EMAIL,
	'site_name' => SITE_NAME, 
	'site_address' => SITE_ADDRESS
	);			
//$site_row = array ('site_contact_email' => 'ian@creativestream.co.uk',  'site_name' => '', 'site_address' => '');			
			


$before_submit_message = '<br/><br/>We would be pleased to here from you with any questions you may have'; 

//$line_template = "[firstname], [surname], [emailaddress], [address], [postcode], [telephone], [mobile], [receiveemail]\n";
//$filename = './data/registrants.csv';

//$email_template = "./php/register_email.html";
$to_address_field =  'emailaddress';
//$bcc = 'cliff@creativestream.co.uk';
//$bcc = 'info@lesliecass.com';
$subject = 'Registration';
$from_name = 'Eyefinity';

//$custom_save_function = 'save_data';
//$custom_validation_function = 'check_email_doesnt_exist';

$required_symbol = '*';
$hide_required_text = false;
$standard_email = true;

$submit_button_text = 'Send';

$process_page = $page;

$success_text = "<p><b>Enquiry sent Successfully</b></p>\n<p>Thankyou</p>";

include "./php/template_form_inc.php";
?>
