<?php

include '../../../admin/classes/template.php';

class form extends template {

    function form() {
        $this->template();
        $this->table = 'forms';
        $this->group_name = 'Forms';
        $this->single_name = 'Form';
        $this->singular = 'a';
        $this->hideable = true;
        $this->ToolbarSet = 'Default';

        $this->buttons = array(
            'edit' => array('text' => 'edit', 'type' => 'standard_edit'),
            'fields' => array('text' => 'manage fields', 'type' => 'button', 'pattern' => '/modules/forms/admin/form_fields.php?form=%s'),
            'sections' => array('text' => 'manage sections', 'type' => 'button', 'pattern' => '/modules/forms/admin/form_sections.php?form=%s'),
            'delete' => array('text' => 'delete', 'type' => 'standard_delete'),
        );

        $this->fields = array(
            'title' => array('name' => 'Title', 'formtype' => 'text', 'list' => true, 'required' => true, 'primary' => true),
            'email' => array('name' => 'Email to', 'formtype' => 'text', 'required' => true),
            'preamble' => array('name' => 'Preamble', 'formtype' => 'fckhtml', 'required' => true),
            'thankyou' => array('name' => 'Thank you Message', 'formtype' => 'fckhtml', 'required' => true), 
            // the email field in the form must have the fieldname set to email_address - not_field
            'encrypted' => array('name' => 'Encrypted Form [will zip data and encrypt before mailing to admin]', 'formtype' => 'checkbox'),            
            'send_response_mail' => array('name' => 'Send a response message <br>[The email field in the form must have the fieldname set to email_address]', 'formtype' => 'checkbox'),
	    'response_mail_title' => array('name' => 'Response email title', 'formtype' => 'text'),
	    'response_mail_message' => array('name' => 'Response email Message', 'formtype' => 'textarea'),            
            'database_insert_function' => array('name' => 'Database insert function', 'formtype' => 'text', 'required' => false),            
            'csmailer_injection' => array('name' => 'Injection into CS Mailer', 'formtype' => 'checkbox'),
            'csmailer_data' => array('name' => 'CS Mailer Data', 'formtype' => 'lookup', 'function' => 'list_cs_mailer_form_fileds', 'not_field' => true),
        );
    }

    function list_cs_mailer_form_fileds($id, $fieldname, $field) {
        $sql = "SELECT title, fieldname FROM form_fields WHERE  published = 1 AND csmailer_injection = 1 AND form ="  . $_GET['id'];
		//echo $sql;
		
        $result = mysql_query($sql);
        $out = ' Fields used in this form for <b>CS Mailer</b> are : <ul>';
        $has_email = false;
        while ($row = mysql_fetch_array($result)) {
            if ($row['fieldname'] == 'email') {
                $has_email = true;
            }
            $out .= '<li><b>' . $row['title'] . '</b>  fieldname=' . $row['fieldname'] . '</li>';
        }
        $warning = 'Fieldnames must exactly match those in the CS mailer List `' . CAKE_LIST_NAME . '` [If this is blank set the list name in CAKE_LIST_NAME]<br>';
        $warning .= 'Check you have completed CAKE_DEV_USER_EMAIL [currently is `' . CAKE_DEV_USER_EMAIL . '`] and CAKE_DEV_USER_PASSWORD [currently is `' . CAKE_DEV_USER_PASSWORD . '`] system vars.<br> Check you have a user in the CS Mailer client account that matches these settings and that the user is has admin rights <br>';
	$warning .= "<p>NOTE: Magic trick - See the Contact list > manage emails > Subscription confirmation email - set to activate<p>";
	    $warning .= "<p>Also remember that new members need to confirm the invitation email, before appearing in a contact list.<p>";
		
        if (!$has_email) {
            $warning .= '<b> WARNING: Your form does not has a field with fieldname email (and is checked for CS Mailer Injection) - this is mandatory, the form WILL NOT WORK WITH CS MAILER.</b>';
        }
        $out = '<p>' . $out . '</ul>' . $warning . '</p>';
        return $out;
    }

    function get_crumbs($page) {
        return "<b>{$this->single_name} Admin</b>";
    }

}
$template = new form();
$main_page = 'index.php';
$main_title = 'Return to main page';
$admin_tab = "forms_admin";
include ("../../../admin/template.php");