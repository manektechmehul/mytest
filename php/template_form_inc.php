<?php
/*
Copyright 2007 - Creative Stream

Start 
	If $before_submit_message is set then is will be shown before the form is displayed

Form creation
	...

Validation
	...
	
Save
	Once validation is passed there are different "save" options

	  1. send a formatted email
	     required 
	     	$email_template = the name of an email file
	     optional
	     	$to_address_field
	     	$cc
	     	$bcc
	     	$confirm
	     	$attachment
	     	$subject

	  2. write to file 
	     requires $filename and $line_template

	  3. custom function 
	     requires $custom_save_function = a function name (called with param $data)

	  4. standard enquiry email 
	     requires $standard_email = true
     
Save Success
	 Once saved success text is shown this is customised with
	 	$success_text     
     
*/


// start
include("./php/securimage/securimage.php");

$template->get_data();

$submit = (isset($_POST['Submit'])) ? $_POST['Submit'] : "";

if ($submit) 
{
	$template->validate_data();
	
	if ($template->has_errors())
		$template->display_errors();
	else
		$template->process_data();
}
else 
{
	$template->display_form();
}
