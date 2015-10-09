<?php

include './php/classes/auto_form.php';
//include 'password_field.php';

class passwordForm extends auto_form {

    private $memberId;
    private $formId;

    function __construct($form_id, $memberId) {
        $this->formId = $form_id;
        $this->memberId = $memberId;
        parent::__construct($form_id);
        $sql = "select * from shop_member_user where id = '{$this->memberId}'";
        $row = db_get_single_row($sql);

        $this->namedFields['current']->default = $row['current'];
        $this->namedFields['new']->default = $row['new'];
        $this->namedFields['confirm']->default = $row['confirm'];
    }

    function registerFieldtypes() {
      //  parent::registerFieldtypes();
        //fieldFactory::registerFieldType(16, 'password_field');
    }

    function updateMember() {
        $values = array(
            'new' => $this->namedFields['new']->getData(),
                //'surname' => $this->namedFields['surname']->getData(),
        );


        $sql = "update shop_member_user set password = '{$values['new']}' where id = {$this->memberId}";

        mysql_query($sql);
    }

    function process_data() {
        //$activationCode = sha1( uniqid() );
        $form = db_get_single_row("select * from forms where id = '{$this->form_id}'");
        //$this->save_data();
        //$this->email_data($form);
        $this->updateMember();
        //echo "<p>Activation Code: $activationCode</p>";
        echo $form['thankyou'];
    }

    function validate_data() {
        parent::validate_data();
        $current = $this->namedFields['current']->getData();

        if ($current != db_get_single_value("select password from shop_member_user where id = '{$this->memberId}'"))
            $this->invalid[] = array('name' => $this->namedFields['current']->name, 'message' => 'Current password invalid');



        $new = $this->namedFields['new']->getData();
        $confirm = $this->namedFields['confirm']->getData();

        if ($new != $confirm)
            $this->invalid[] = array('name' => $this->namedFields['confirm']->name, 'message' => 'passwords do not match');
    }

}