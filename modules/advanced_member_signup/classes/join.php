<?php

    include './php/classes/auto_form.php';
    require_once $base_path . '/php/password/PasswordHelper.php';
    include_once "$base_path/modules/$module_path/classes/member_details.php";
    $javascript[] = "js/functions.js";

    class join_form extends auto_form
    {

        function __construct( $form_id )
        {
            parent::__construct( $form_id );
        }

        function removeOld( $activationCode )
        {
            $sql = "delete from shop_member_user where status = -1 and activation = '$activationCode'";
            mysql_query( $sql );
        }

        function createMember( $activationCode )
        {
            global $base_path;
            $pass            = new PasswordHelper();
            $hashed_password = $pass->generateHash( $this->namedFields['password']->getData(), 7 );
            $values          = array(
                'firstname' => $this->namedFields['name']->getData(),
                'surname' => $this->namedFields['surname']->getData(),
                'email' => $this->namedFields['email']->getData(),
                'password' => $hashed_password,
                'billing_address1' => $this->namedFields['billing_address1']->getData(),
                'billing_address2' => $this->namedFields['billing_address2']->getData(),
                'billing_address3' => $this->namedFields['billing_address3']->getData(),
                'billing_postalcode' => $this->namedFields['billing_postalcode']->getData(),
                'billing_country' => $this->namedFields['billing_country']->getData(),
                'billing_country_id' => $this->namedFields['billing_country']->getData(), // need to get the id
                'delivery_address1' => $this->namedFields['delivery_address1']->getData(),
                'delivery_address2' => $this->namedFields['delivery_address2']->getData(),
                'delivery_address3' => $this->namedFields['delivery_address3']->getData(),
                'delivery_postalcode' => $this->namedFields['delivery_postalcode']->getData(),
                'delivery_country' => $this->namedFields['delivery_country']->getData(),
                'delivery_country_id' => $this->namedFields['delivery_country']->getData(), // need to get the id
                'phone' => $this->namedFields['phone']->getData(),
                'activation' => $activationCode,
                'status' => 0
            );
            $sql             = "insert into shop_member_user
				(`firstname`,
				`surname`,
				`billing_address1`,
				`billing_address2`,
				`billing_address3`,
				`billing_postalcode`,
				`phone`,
				`email`,
				`delivery_address1`,
				`delivery_address2`,
				`delivery_address3`,
				`delivery_postalcode`,
				`status`,
				`billing_country`,
				`billing_country_id`,
				`delivery_country`,
				`delivery_country_id`,				
				activation,
				password,
				username
                )
			VALUES (
				'{$values['firstname']}',
				'{$values['surname']}',
				'{$values['billing_address1']}',
				'{$values['billing_address2']}',
				'{$values['billing_address3']}',
				'{$values['billing_postalcode']}',
				'{$values['phone']}',
				'{$values['email']}',
				'{$values['delivery_address1']}',
				'{$values['delivery_address2']}',
				'{$values['delivery_address3']}',
				'{$values['delivery_postalcode']}',
				'{$values['status']}',
				'{$values['billing_country']}',
				'{$values['billing_country_id']}',
				'{$values['delivery_country']}',
				'{$values['delivery_country_id']}',				
				'{$values['activation']}',
				'{$values['password']}',
				'{$values['email']}'
			)";

            return mysql_query( $sql );
        }

        function email_activation( $activationCode, $form )
        {
            $emailaddress = $this->namedFields['email']->getData();
            $link         = SITE_ADDRESS . '/shop-member-join/activate?code=' . $activationCode;
            if (substr( $link, 0, 4 ) != 'http') {
                $link = 'http://' . $link;
            }
            // TODO: insert a smarty template file for a nice formated site email
            /*   $body = '<p>Welcome to ' . SITE_NAME . '</p><p>Please click on the link below to activate your account<p>' .
                       "<p><a href=\"$link\">$link</a></p>";
             *
             */
            $from_email_address = $form['email'];
            $content            = $this->Fetch( 'templates/member_join_email.tpl', array(
                'link' => $link
            ) );
            $body               = $this->Fetch( '../shop/templates/email_template.tpl', array(
                'content' => $content
            ) );
            $this->send_mail( $emailaddress, SITE_NAME, $from_email_address, 'Account Activation', $body, $body_text );
        }

        function process_data()
        {
            global $smarty;
            // Create Member
            $activationCode = sha1( uniqid() );
            $this->save_data();
            $this->createMember( $activationCode );
            $form      = db_get_single_row( "select * from forms where id = '{$this->form_id}'" );
            //$this->email_activation($activationCode, $form);
            // this will email the site owner
            // $this->email_data($form);
            $_SESSION['activationCode'] = $activationCode;
            echo $form['thankyou'];

            $member_id = db_get_single_value("select id from shop_member_user where activation='$activationCode'");

            return $member_id;
        }

        function validate_data()
        {
            parent::validate_data();
            $email   = $this->namedFields['email']->getData();
            $confirm = $this->namedFields['confirm_email']->getData();
            if ($email != $confirm) {
                $this->invalid[] = array(
                    'name' => $this->namedFields['email']->name,
                    'message' => 'email addresses do not match'
                );
            } else if ($this->emailAlreadyUsed( $email )) {
                $this->invalid[] = array(
                    'name' => 'email',
                    'message' => 'A member already exists with this email address'
                );
            }
        }

        function emailAlreadyUsed( $email )
        {
            $sql = "select count(*) from shop_member_user where email = '$email'";

            return db_get_single_value( $sql ) > 0;
        }

        function DisplayConfirm( $type )
        {
            global $smarty;
        }

        function Fetch( $template, $variables = null )
        {
            global $smarty;
            global $base_path;
            if (is_array( $variables )) {
                foreach ($variables as $variable => $value) {
                    $smarty->assign( $variable, $value );
                }
            }
            $templateFile = "$base_path/modules/shop_member_join/$template";

            return $smarty->fetch( "file:" . $templateFile );
        }
    }