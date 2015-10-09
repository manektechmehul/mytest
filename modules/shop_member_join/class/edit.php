<?php

include './php/classes/auto_form.php';

class edit_form extends auto_form {

    private $memberId;
    private $formId;

    function __construct($form_id, $memberId) {
        // load fields into view my details
        $this->formId = $form_id;
        $this->memberId = $memberId;
        parent::__construct($form_id);
        $sql = "select * from shop_member_user where id = '{$this->memberId}'";
        $row = db_get_single_row($sql);


        $this->namedFields['name']->default = $row['firstname'];
        $this->namedFields['surname']->default = $row['surname'];
        $this->namedFields['email']->default = $row['email'];
        $this->namedFields['confirm_email']->default = $row['email'];
        $this->namedFields['password']->required = false;
        $this->namedFields['password']->name = 'Password<br>';

        $this->namedFields['password']->outputTemplate = "<tr valign=\"middle\">\n<td width=\"22%%\">\n<div align=\"right\">%s%s</div>\n</td>\n" .
                "<td>\n<input type=\"password\" name=\"%s\" maxlength=\"%s\"> <span class=\"small\">(leave blank to keep the same password)</span></td></tr>\n\n";


      
        $this->namedFields['billing_address1']->default = $row['billing_address1'];
        $this->namedFields['billing_address2']->default = $row['billing_address2'];
        $this->namedFields['billing_address3']->default = $row['billing_address3'];
        $this->namedFields['billing_postalcode']->default = $row['billing_postalcode'];
        $this->namedFields['billing_country']->default = $row['billing_country_id'];
        $this->namedFields['delivery_address1']->default = $row['delivery_address1'];
        $this->namedFields['delivery_address2']->default = $row['delivery_address2'];
        $this->namedFields['delivery_address3']->default = $row['delivery_address3'];
        $this->namedFields['delivery_postalcode']->default = $row['delivery_postalcode'];
        $this->namedFields['delivery_country']->default = $row['delivery_country_id'];

        $this->namedFields['phone']->default = $row['phone'];
    }

    function updateMember() {
		 
		
        $values = array(
            'firstname' => $this->namedFields['name']->getData(),
            'surname' => $this->namedFields['surname']->getData(),
            'email' => $this->namedFields['email']->getData(),          
            'billing_address1' => $this->namedFields['billing_address1']->getData(),
            'billing_address2' => $this->namedFields['billing_address2']->getData(),
            'billing_address3' => $this->namedFields['billing_address3']->getData(),
            'billing_postalcode' => $this->namedFields['billing_postalcode']->getData(),
            'billing_country_id' => $this->namedFields['billing_country']->getData(),
            'delivery_address1' => $this->namedFields['delivery_address1']->getData(),
            'delivery_address2' => $this->namedFields['delivery_address2']->getData(),
            'delivery_address3' => $this->namedFields['delivery_address3']->getData(),
            'delivery_postalcode' => $this->namedFields['delivery_postalcode']->getData(),
            'delivery_country_id' => $this->namedFields['delivery_country']->getData(),
            'phone' => $this->namedFields['phone']->getData(),
            'password' => $this->namedFields['password']->getData(),
        );



        $sql = "update shop_member_user set " .
                " email = '{$values['email']}', " .
                " firstname = '{$values['firstname']}', " .
                " surname = '{$values['surname']}'," .             
                " `billing_address1` = '{$values['billing_address1']}'," .
                " `billing_address2` = '{$values['billing_address2']}'," .
                " `billing_address3` = '{$values['billing_address3']}'," .
                " `billing_postalcode` = '{$values['billing_postalcode']}'," .
                " `billing_country_id` = '{$values['billing_country_id']}'," .
                " `delivery_address1` = '{$values['delivery_address1']}'," .
                " `delivery_address2` = '{$values['delivery_address2']}'," .
                " `delivery_address3` = '{$values['delivery_address3']}'," .
                " `delivery_postalcode` = '{$values['delivery_postalcode']}'," .
                " `delivery_country_id` = '{$values['delivery_country_id']}'," .
                " `phone` = '{$values['phone']}'";



        if (!empty($values['password'])){
            
		    global $base_path;
            require_once $base_path . '/php/password/PasswordHelper.php';
            $pass = new PasswordHelper();
            $hashed_password = $pass->generateHash($values['password'],7);            
            $sql .= " , `password` = '{$hashed_password}'";
		}
		
		$sql .= " where id = {$this->memberId}";

 

        mysql_query($sql);
		
		
    }

    function process_data() {
        $form = db_get_single_row("select * from forms where id = '{$this->form_id}'");
        $this->updateMember();
        echo "<p>Your details have been changed.</p>";
        echo "<p>Click <a href=\"/shop-members\">here</a> to return to your account home.</p>";
    }

    function validate_data() {
        parent::validate_data();

        $email = $this->namedFields['email']->getData();
        $confirm = $this->namedFields['confirm_email']->getData();

        if ($email != $confirm)
            $this->invalid[] = array('name' => $this->namedFields['email']->name, 'message' => 'email addresses do not match');
        else
        if ($this->emailUsedByAnother($email))
            $this->invalid[] = array('name' => 'email', 'message' => 'A member already exists with this email address');
    }

    function emailUsedByAnother($email) {
        $sql = "select count(*) from shop_member_user where email = '$email' and id <> {$this->memberId}";
        return db_get_single_value($sql) > 0;
    }

}