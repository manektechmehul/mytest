<?php
// declarations
include 'classes/template.php';

class registrants extends template 
{
	function registrants ()
	{
		$this->template();
		$this->table = 'registrants';
		$this->group_name = 'Subscribers';
		$this->single_name = 'Subscriber';
		$this->singular = 'a';
		$this->hidable = false;
		//$this->parent_field = 'portfolio_id';

		$this->fields = array( 
			'firstname' => array('name' => 'First name', 'formtype' => 'text', 'list' => true, 'required' => true, 'primary' => true),
			'surname' => array('name' => 'Surname', 'formtype' => 'text', 'list' => true, 'required' => true),
			'emailaddress' => array('name' => 'email address', 'formtype' => 'text', 'required' => true, 'validation' => 'validate_email'),
		//	'address' => array('name' => 'Address', 'formtype' => 'address', 'lines' => 3, 'required' => false),
		//	'postcode' => array('name' => 'Postcode', 'formtype' => 'text', 'required' => true),
		//	'city' => array('name' => 'City I would like to see painted', 'formtype' => 'text', 'required' => false),
			'receiveemail' => array('name' => 'Register for Newsletter', 'formtype' => 'checkbox', 'required' => false)
		//	'enquiry' => array('name' => 'Enquiry', 'formtype' => 'textarea', 'required' => false, 'rows'=> 3, 'cols'=> 45)
			);			

		$this->list_top_text = '<a href="./registrants.csv" onmouseout=\'button_off(this)\' onmouseover=\'button_over(this)\'><img src="/admin/images/buttons/cmsbutton-Download_CSV-off.gif" alt="Download CSV" name="downloadcsv" /></a>';
		//$this->top_text .= '<br/><a href="./emailer.php"><img src="/admin/images/buttons/cmsbutton-Send_Email-off.gif" alt="Send Email" /></a>';
			
		//$links = array( 'category' => array('link_table' => 'case_study_category', 'table' => 'category', 'name' => 'title') );
		//$this->custom_validation_function = $this->validate_url_body;
	}
}

$template = new registrants();

$admin_tab = "subscriber_admin";

include ("./template.php");
?>


