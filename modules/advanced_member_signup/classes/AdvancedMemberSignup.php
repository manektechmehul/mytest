<?php

    /**
     * Created by PhpStorm.
     * User: glen
     * Date: 23/06/2015
     * Time: 15:54
     */
    class AdvancedMemberSignup
    {

        protected $all;
        protected $confirmCode2;
        protected $email_from;
        protected $email_replyto;
        protected $email_subject;
        protected $id;
        protected $status;
        protected $applicant_firstname;
        protected $applicant_surname;
        protected $applicant_email;
        protected $confirmCode1;
        protected $base_link;
        protected $admin_email;

        function __construct()
        {
            $this->email_from    = "National Club";
            $this->email_replyto = "noreply@codelab.software";
            $this->email_subject = "Membership Application";
            $this->base_link     = "http://www.local-rothhosp.com/membership-application";
            $this->admin_email   = "glen@codelab.software";
        }

        public function setConfirmCode1( $confirmCode1 )
        {
            $this->confirmCode1 = $confirmCode1;
            $this->all          = db_get_single_row( "select * from advanced_member_signup where email_confirm_code_1 = '{$confirmCode1}'" );
            $this->populateObj();
        }

        public function setConfirmCode2( $confirmCode2 )
        {
            $this->confirmCode2 = $confirmCode2;
            $this->all          = db_get_single_row( "select * from advanced_member_signup where email_confirm_code_2 = '{$confirmCode2}'" );
            $this->populateObj();
        }

        private function populateObj()
        {
            $this->id                  = $this->all['id'];
            $this->status              = $this->all['status'];
            $this->applicant_firstname = $this->all['firstname'];
            $this->applicant_surname   = $this->all['surname'];
            $this->applicant_email     = $this->all['email'];
        }

        public function getApplicationDetails()
        {
            $sql = "SELECT CONCAT(ams.firstname,' ',ams.surname) AS applicant, CONCAT(smu2.firstname,' ',smu2.surname) AS proposer,
CONCAT(smu.firstname,' ',smu.surname) AS seconder,smu.`email`AS second_email, ams.*, smu3.billing_address1, smu3.billing_address2, smu3.billing_address3  FROM advanced_member_signup ams
INNER JOIN `shop_member_user` smu ON smu.id = ams.`seconder_id`
INNER JOIN `shop_member_user` smu2 ON smu2.id = ams.`initiator_id`
INNER JOIN shop_member_user smu3 ON smu3.id = ams.applicant_new_member_id
WHERE ams.id = '{$this->id}'";
            $row = db_get_single_row( $sql );
            foreach ($row as $key => $value) {
                $values[$key] = $value;
            }
            $output = $this->fetch( 'app_details.tpl', $values );

            return $output;
        }

        public function found()
        {
            if ($this->all != '') {
                return true;
            } else {
                return false;
            }
        }

        // ## Phase 1 ##
        // A current member suggests a new applicant and asks for a seconder
        // This is called from auto form around line 4704
        function startApplication( $data )
        {
            $initiator_id         = $_SESSION['session_member_id'];
            $seconder_id          = $data['seconder'];
            $status               = 0; //  starting
            $email_confirm_code_1 = md5( uniqid( rand(), true ) );
            $sql                  = "INSERT INTO `advanced_member_signup`
            ( `initiator_id`, `seconder_id`, `status`, `email_confirm_code_1`, `firstname`, `surname`, `email`, applicant_new_member_id)
VALUES ( '{$initiator_id}', '{$seconder_id}', '{$status}', '{$email_confirm_code_1}', '{$data['firstname']}', '{$data['surname']}', '{$data['applicant_email']}', '{$applicant_new_member_id}');";
            $result               = mysql_query( $sql );
            if ($result) {
                // TODO: might want to change to the member_user table
                // get the information of the current usr- he is the initiator
                $m                = db_get_single_row( "select firstname, surname from shop_member_user where id=" . $_SESSION['session_member_id'] );
                $link             = $this->base_link . "/confirm/" . urlencode( $email_confirm_code_1 );
                $vars['proposer'] = $m['firstname'] . ' ' . $m['surname'];
                $vars['link']     = $link;
                $email_body       = $this->fetch( 'applicant_email.tpl', $vars );
                $this->sendMail( $data['applicant_email'], $email_body );
            } else {
                return false;
            }
        }

        // applicant has been invited back to the site, and have completed the details form.
        // so update their app status to the next level
        public function updateApplicantCompletedForm( $applicant_new_member_id )
        {
            // set status to 1 and generate new email confirmation code
            $this->confirmCode2 = md5( uniqid( rand(), true ) );

            return db_update( "UPDATE `advanced_member_signup` SET STATUS = 1, `email_confirm_code_2` = '{$this->confirmCode2}', applicant_new_member_id ='{$applicant_new_member_id}' WHERE `email_confirm_code_1` = '{$this->confirmCode1}'" );
        }

        // invite the seconder to confirm the application
        public function initiateSeconderConformation()
        {
            // look up initiator and seconders details
            $sql = "SELECT CONCAT(ams.firstname,' ',ams.surname) AS applicant, CONCAT(smu2.firstname,' ',smu2.surname) AS proposer,
smu.firstname AS seconder,smu.`email`AS second_email  FROM advanced_member_signup ams
INNER JOIN `shop_member_user` smu ON smu.id = ams.`seconder_id`
INNER JOIN `shop_member_user` smu2 ON smu2.id = ams.`initiator_id`
WHERE ams.`email_confirm_code_1` = '{$this->confirmCode1}'";
            $app = db_get_single_row( $sql );
            // now send a mail to the seconder to confirm the application of this person
            $link              = $this->base_link . "/seconder/" . urlencode( $this->confirmCode2 );
            $vars['link']      = $link;
            $vars['applicant'] = $app['applicant'];
            $vars['proposer']  = $app['proposer'];
            $vars['seconder']  = $app['seconder'];
            $email_body        = $this->fetch( 'seconder_email.tpl', $vars );

            return $this->sendMail( $app['second_email'], $email_body );
        }

        public function confirmApplication()
        {
            // the sender has confirmed the application
            // update the app status
            $sql = "UPDATE `advanced_member_signup` SET `status` = '2' WHERE `id` = '{$this->id}';";
            db_update( $sql );
            // email the details to the board the details


            $email_preamble = "There has been a new membership application. Details are as follows.<br>";
            $email_body = $this->getApplicationDetails();
            $email = $email_preamble . $email_body;


            return $this->sendMail( $this->admin_email, $email );
        }

        private function sendMail( $send_to, $email_body )
        {
            global $base_path;
            include_once( $base_path . "/php/mailgun/mailgun.php" );
            $mailgun = new mailgun();

            return $mailgun->send( $send_to, $this->email_from, $this->email_replyto, $this->email_subject,
                $email_body );
        }

        function fetch( $template, $variables = null )
        {
            global $smarty;
            global $base_path;
            if (is_array( $variables )) {
                foreach ($variables as $variable => $value) {
                    $smarty->assign( $variable, $value );
                }
            }
            $templateFile = "$base_path/modules/advanced_member_signup/templates/$template";

            return $smarty->fetch( "file:" . $templateFile );
        }
    }