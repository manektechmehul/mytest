<?php

include './php/classes/abstract_form.php';
include './php/classes/fields/abstract_field.php';
include './php/classes/fields/text_field.php';
include './php/classes/fields/textarea_field.php';
include './php/classes/fields/wysiwyg_field.php';
include './php/classes/fields/checkbox_field.php';
include './php/classes/fields/radiogroup_field.php';
include './php/classes/fields/checkboxgroup_field.php';
include './php/classes/fields/title_field.php';
include './php/classes/fields/databaselookup_field.php';
include './php/classes/fields/databaselookup_checkboxgroup_field.php';
include './php/classes/fields/fileupload_field.php';
include './php/classes/fields/imageupload_field.php';
include './php/classes/fields/captcha_field.php';
include './php/classes/fields/hidden_field.php';
include './php/classes/fields/email_field.php';
include './php/classes/fields/password_field.php';
//include './php/classes/fields/countries_field.php';
include './php/classes/fields/date_field.php';
include './php/classes/fields/dropdown_field.php';
include './php/classes/fields/blank_security.php';

	include './php/classes/fields/databaselookup_field_typeahead.php';


class fieldFactory {

    static $fieldtypes;

    static function registerFieldType($id, $name, $values = 0) {
        self::$fieldtypes[$id] = $name;
    }

    static function newField($data) {
        // creating an oibject type on the fly .. quite clever really
        // get class name here
        $type = self::$fieldtypes[$data['fieldtype']];
        $fieldname = ($data['fieldname']) ? $data['fieldname'] : 'field' . $data['id'];
        // create new object here
        $field = new $type($data['section_id'], $fieldname, $data['title'], $data['required']);
        $field->default = $data['default'];
        if ($field->initialValues)
            $field->values = $data['values'];
        if ($data['keyfield'] == '1')
            $field->keyField = true;
        if ($data['filterfield'] == '1')
            $field->filterValueField = true;
        return $field;
    }

}

class auto_form extends abstract_form {

    var $not_yet_registered;
    public $form_id;
    public $key = '';
    protected $namedFields;

    function __construct($form_id) {
        parent::__construct();
        $this->fields = array();
        $this->form_id = $form_id;
        $this->namedFields = array();
        $this->before_form_message = db_get_single_value("select preamble from forms where id = $form_id");

        // add in the form section header and tabs here 
        $this->section_header = $this->getFormSectionHeader();
        $this->section_footer = $this->getFormSectionFooter();


        $this->registerFieldtypes();

        // $sql = "select * from form_fields where form = '$form_id' and published = 1 order by order_num";
        // need to order by section as well as form order


        $sql = "SELECT ff.*, 
( SELECT order_num FROM form_sections fs WHERE fs.id = ff.`section_id` LIMIT 1 ) -- fs.section
AS `section_order_num` 
FROM form_fields ff
WHERE ff.form = '$form_id' AND ff.published = 1 
ORDER BY `section_order_num` , ff.order_num";



        $result = mysql_query($sql);

        while ($row = mysql_fetch_array($result)) {
            // This should have a factory
            $this->fields[$row['id']] = fieldFactory::newField($row);
            if ($row['fieldname']) {
                $this->namedFields[$row['fieldname']] = & $this->fields[$row['id']];
            }
        }
        
    }

    function getFormSectionFooter() {

        return "form section footer function ";
    }

    function getFormSectionHeader() {
        // get sections from form id
        $sql = "select * from form_sections where form =" . $this->form_id . " order by order_num";
        // do we need to remove empty sections ? - I guess so.
        $rows = db_get_rows($sql);
        //$out = "<div class='form_section_header_wrapper' >";
        //

        $first = true;
        foreach ($rows as $row) {
            if($first){
                $out = "<ul class=\"nav nav-tabs nav-tabs-markers\" role=\"tablist\">";
                $has_tabs = true;
                //$tab_class = "tab-on";
                $tab_class = "active";
            }else{
                 $tab_class = "";
            }
             $first = false;
            //$out .= "<div class='form_section_tab {$tab_class}' id='tab_form_section_{$row['id']}'>" . $row['title'] . "</div>";
			$out .= "<li class='form_section_tab {$tab_class}' id='tab_form_section_{$row['id']}'><a data-toggle='tab' role='tab' href='#form_section_{$row['id']}'>" . $row['title'] . "</a></li>";
            $sections[] = $row;
            
        }
        //$out .= "</div>";
       if($has_tabs) {
           $out .= "</ul>";
       }
        $this->sections_Arr = $sections;
        // var_dump($sections);
        return $out;
    }

    function registerFieldtypes() {
        /*
         * make sure these ids match those used in the modules/forms/admin/form_fields fieldTypesDropDown function
         */
        fieldFactory::registerFieldType(1, 'text_field');
        fieldFactory::registerFieldType(2, 'textarea_field');
        fieldFactory::registerFieldType(3, 'checkbox_field');
        fieldFactory::registerFieldType(4, 'radiogroup_field');
        fieldFactory::registerFieldType(5, 'checkboxgroup_field');
        fieldFactory::registerFieldType(6, 'dropdown_field');
        fieldFactory::registerFieldType(7, 'title_field');
        fieldFactory::registerFieldType(8, 'databaselookup_field');
        fieldFactory::registerFieldType(12, 'databaselookup_checkboxgroup_field');
        fieldFactory::registerFieldType(9, 'captcha_field');
        fieldFactory::registerFieldType(10, 'fileupload_field'); // should this a be field ??
        fieldFactory::registerFieldType(13, 'imageupload_field');
        fieldFactory::registerFieldType(11, 'email_field');
        fieldFactory::registerFieldType(14, 'wysiwyg_field');
        // used in the shop member system        
        fieldFactory::registerFieldType(15, 'password_field');
        //fieldFactory::registerFieldType(16, 'countries_field'); 
        fieldFactory::registerFieldType(17, 'date_field'); // with calendar
        fieldFactory::registerFieldType(18, 'blank_security');
	    fieldFactory::registerFieldType(19, 'databaselookup_field_typeahead');
    }

    function get_data() {
        foreach ($this->fields as $fieldname => $field)
            if ($field->dataField) {
                $field->setData();
                if ($field->keyField)
                    $this->key .= $field->data;
            }
    }

   function email_data($form, $subject = '', $to_email_address = '') {
        if (empty($to_email_address))
            $to_email_address = $form['email'];
        $site_address = SITE_ADDRESS;
        if (empty($subject))
            $subject = $form['title'];
        
        
        
        if( $form['id'] == MY_HEALTH_INLINE_FORM_ID){
            if($_SESSION['my_health_article_title'] != ''){
                 $subject  .=   ' Ref: ' . $_SESSION['my_health_article_title'];
            }
            
            $_SESSION['my_health_article_title'] = '';
           
        }
        
       
        
        $from_email_address = $form['email'];

        $attachments = '';
        foreach ($this->fields as $fieldname => $field){
            if ($field->dataField) {
                if ($field->attachmentField) {
                    $attachments[] = $field->getData();
                } else {
                    $msg = $field->getEmailMessage() . "\r\n" . PHP_EOL;
                    $body .= "<p>$msg</p>";
                    $body_text .= $msg;
                }
            }
        }
        // need to check which email method we are using here
        //$this->standard_email($site_address, $to_email_address, $from_email_address, $subject);

         
        // check if this form is encrypted. if so zip contents and encrypt
        $encrpyted_form = db_get_single_value("SELECT encrypted FROM forms WHERE id =" . $this->form_id);
        // echo '<br> encrypted = ' . $encrpyted_form . '<br>';
        if ($encrpyted_form) {
            // create encrypted zip
            global $base_path;
            $uuid = uniqid();
            // set password as editable by site
            $password = FORM_ENCRYPTION_KEY;
            $zip = $base_path . '/modules/forms/cache/' . $uuid . '.zip';
            $source = $base_path . '/modules/forms/cache/' . $uuid . '.txt';
            // write the form details to the source file
            $message = "This is a encrypted form message from the website." . "\r\n" . PHP_EOL . $body_text;
            // create the text file with the data in
            file_put_contents($source, '' . $message);
            // Creat Zip file and encrypt it
            exec("7za a -p$password -mem=AES256 -tzip $zip $source");
            // intro message to mail
            $body = "This is an encrypted email - please download the attachment and decrypt.";
            $body_text = $body;
            //echo '<br> the to email address is ' .  $to_email_address . ' from email address is ' .  $from_email_address;
            // if you are tetsing and struggling to get an email - change the from address to something real - else gmail will block it.
            
            $this->send_mail($to_email_address, SITE_NAME, $from_email_address, $subject, $body, $body_text, '', '', '', $zip);
            // delete the temp files for security reasons
            unlink($source); // delete source text file asap
            unlink($zip); // delete zip file asap
        } else {
            $this->send_mail($to_email_address, SITE_NAME, $from_email_address, $subject, $body, $body_text, '', '', '', $attachments);
        }

       // die();
    }

    function process_data($subject = '', $to_email_address = '') {
        $form = db_get_single_row("select * from forms where id = '{$this->form_id}'");
        $this->save_data();
        $this->email_data($form, $subject, $to_email_address);
        // if we are adding to csmailer .. do it here


	    foreach ($this->fields as $cs_field) {
		    foreach ($this->fields as $field) {
			    if ($field->dataField) {
				    if ($field->fieldname ==  'subscribe_to_csmailer' ) {
					    $subscribe_to_csmailer = $field->getData();
				    }
			    }
		    }
	    }

	     //if is a field and set to subscribe .. do it

	   // echo "subscribe to csmailer  is " . $subscribe_to_csmailer;

if($subscribe_to_csmailer) {
	if ( $form['csmailer_injection'] == '1' ) {
		$this->send_cs_mailer_data( $form );
	}
}

	   // die();
        //send a response messgae to the person who just filled out the form
        if ($form['send_response_mail'] == '1') {
            $this->send_the_response_mail($form);
        }
        echo $form['thankyou'];
    }

    function send_the_response_mail($form) {

        $subject = $form['response_mail_title'];
        $site_address = SITE_ADDRESS;
        if (empty($subject)) {
            $subject = $form['title'];
        }
        $from_email_address = $form['email'];
        $attachments = '';
        $msg = $form['response_mail_message'];
        $body .= "<p>$msg</p>";
        $body_text .= $msg;
        $to_email_address = '';

        foreach ($this->fields as $fieldname => $field)
            if ($field->dataField) {
                // the email field in the form must have the fieldname set to email_address				
                if ($field->fieldname == 'email_address') {
                    $to_email_address = $field->getData();
                }
            }
        //$this->standard_email($site_address, $to_email_address, $from_email_address, $subject);
        $this->send_mail($to_email_address, SITE_NAME, $from_email_address, $subject, $body, $body_text, '', '', '', $attachments);
    }

    function send_cs_mailer_data($form) {
        /* Get the fields and their data ready to send to CS Mailer */
        $sql = "SELECT * FROM form_fields WHERE  published = 1 AND csmailer_injection = 1 and form =" . $form['id'];
        $cs_fields = array();
        $result = mysql_query($sql);
        while ($row = mysql_fetch_array($result)) {
            $cs_fields[] = $row;
        }
        foreach ($cs_fields as $cs_field) {
            foreach ($this->fields as $field) {
                if ($field->dataField) {
                    if ($field->fieldname == $cs_field['fieldname']) {
                        $cs_data[$field->fieldname] = $field->getData();
                    }
                }
            }
        }
        // To debug your form to match cs mailer uncomment this  to show field names
        // var_dump($cs_data);


        // we must have an email address
        if (isset($cs_data['email'])) {
            $this->signUpOnCake($cs_data);
        } else {
            echo 'Failed to post to CS Mailer system as form does not have a email field <br>';
            echo 'Supplied fields are: ';
            // var_dump($cs_data);
        }
        // die();
    }



    function signUpOnCake($user_params) {
        global $base_path;
        $debug = false;
        /***********************************************************
          CAKE MAIL LIBRARY AND CS ACCOUNT DETAILS
         *********************************************************** */
        define("API_CAKE_URL", "http://api.cakemail.com");
        // Path to the PHP5 Cakemail Library
        define("LIB_CAKE", $base_path . "/php/cakemail/");
        // These will always be the same for creative stream
        // Put your API key here
        define("CAKE_INTERFACE_KEY", "9d15b9bcfeec271cf24d393725704922");
        // Put your API ID here
        define("CAKE_INTERFACE_ID", 529);
        // get required lirarys etc
        require_once(LIB_CAKE . "global.php");
        require_once(LIB_CAKE . "cake_User.php");
        require_once(LIB_CAKE . "cake_List.php");
        /***********************************************************
          CLIENT DETAILS
         ************************************************************/
        // The cake mail - email for the automated user for this account
        $my_email = CAKE_DEV_USER_EMAIL; // 'pots_csmailer@creativestream.co.uk';
        // and login password for that user
        $my_password = CAKE_DEV_USER_PASSWORD; // 'pots_csmailer';
        // this list in the automated users contact list
        $cakeListName = CAKE_LIST_NAME; // 'pots';

        /**** Login as Client Developer Account ****/
        $myparams = array('email' => $my_email, 'password' => $my_password);
        $myreturn = cake_user_Login($myparams, "en_US");
        /**** Get Clients Lists ******/
        $listsResult = cake_list_GetList(array('user_key' => $myreturn['user_key']));
        $lists = $listsResult['lists'];
        /* Get the list id - by checking name again the cakeListName var */
        $list_id = 0;
        foreach ($lists as $list) {
            if ($list['name'] == $cakeListName) {
                $list_id = $list['id'];
            }
        }
        /*         * ** Add New Member *** */
        $new_member_email = $user_params['email'];
        // must take this out - else break the data insert
        unset($user_params['email']);

        if ($list_id > 0)
            $res = cake_list_SubscribeEmail(array('user_key' => $myreturn['user_key'],
                'list_id' => $list_id,
                'email' => $new_member_email,
                'data' => $user_params
            ));

        if ($debug) {
            echo ' Debug cs mailer post - list_id = ' . $list_id . ' - email = ' . $new_member_email;
            echo '<ul><li> CAKE_DEV_USER_EMAIL > ' . CAKE_DEV_USER_EMAIL . '</li><li>   CAKE_DEV_USER_PASSWORD > ' . CAKE_DEV_USER_PASSWORD . '</li><li> CAKE_LIST_NAME > ' . CAKE_LIST_NAME . '</li></ul>';
            echo '<p> User Params ';
            var_dump($user_params);
            echo ' </p> ';
            var_dump($res);
          //  die();
        }
    }

    function validate_data() {
        $this->missing = array();
        $this->invalid = array();

        foreach ($this->fields as $fieldname => $field) {
            if ($field->required || $field->validate) {
                $missing = $field->isDataMissing($this->data . $fieldname);

                if ($field->required && $missing)
                    $this->missing[] = $field->name;

                if ($field->validate && !$missing)
                    if ($field->isDataInvalid($this->key))
                        $this->invalid[] = array('name' => $field->name, 'message' => $field->invalidDataMessage);
            }
        }
    }

    function has_errors() {
        return parent::has_errors();
    }

    function save_data() {
        $filter = 0;
        // var_dump($fields);

        foreach ($this->fields as $id => $field) {
            // var_dump($field);


            if (get_class($field) === "databaselookup_checkboxgroup_field") {
                // need to queue up an insert for the new record  categoy values
                $checkbox_array = $field->getData();

                // there is a special excetion for the cae study module as the naming is all wrong                
                if ($field->table_name == 'category') {
                    $checkbox_sql = " insert into case_study_category (case_study_id, category_id) values ";
                } else {
                    $checkbox_sql = " insert into " . $field->table_name . "_lookup (item_id, category_id) values ";
                }
                foreach ($checkbox_array as $item) {
                    $checkbox_sql .= "(@item_id, " . $item . "),";
                }
                $checkbox_sql = rtrim($checkbox_sql, ",");
            } else {
                if ($field->dataField) {
                    $data[$field->name] = $field->getData();
                }
                if ($field->filterValueField)
                    $filter = $field->getData();
            }
        }
        
           // check if this form is encrypted. if so zip contents and encrypt
        $encrpyted_form = db_get_single_value("SELECT encrypted FROM forms WHERE id =" . $this->form_id);
        // echo '<br> encrypted = ' . $encrpyted_form . '<br>';
        if ($encrpyted_form) {
        
        }else{
            $sql = sprintf("insert into form_values (form, `values`, `filter`, added) values ('%s', '%s', '%s', now()) ", $this->form_id, serialize($data), $filter);
            $result = mysql_query($sql);
        }

        /*
         * This is all hard coded for the profiles form ... trick to make it less bespoke...
         * insert function name into a new form field called database_insert_function 
         */

        $database_insert_function = db_get_single_value("SELECT database_insert_function FROM forms WHERE id=" . $this->form_id);

        //echo '$database_insert_function is ' . $database_insert_function;
        if ($database_insert_function != '') {
            // this is in place of the call_user_func
            $this->{$database_insert_function}();
        }

        if (isset($checkbox_sql)) {
            $checkbox_sql = str_replace('@item_id', mysql_insert_id(), $checkbox_sql);
            $result = mysql_query($checkbox_sql);
        }
    }

    /*     * *******************
     * 
     * The rest of these functions are used when competing a form should be inserted into the module database table
     */

	function advanced_user_signup_process_form1(){
		global $base_path;
	  	$filter = 0;
		foreach ($this->fields as $id => $field) {
			if ($field->dataField)
				$data[$field->fieldname] = $field->getData();
			if ($field->filterValueField)
				$filter = $field->getData();
		}

        include_once($base_path . "/modules/advanced_member_signup/classes/AdvancedMemberSignup.php");
        $ams = new AdvancedMemberSignup();
        $ams->startApplication($data);






	}




    function insert_into_news() {

        $filter = 0;
        foreach ($this->fields as $id => $field) {
            if ($field->dataField)
                $data[$field->fieldname] = $field->getData();
            if ($field->filterValueField)
                $filter = $field->getData();
        }
        // need to make sure the page_name is unique
        $page_name = trim($data['title']);
        $page_name = preg_replace("![^a-z0-9]+!i", "-", $page_name);
        $append = "";
        // we first try the ideal page name - then try again with incremental ints
        do {
            $page_name = $page_name . $append;
            $pn_sql = "SELECT count(*) FROM news WHERE page_name = '{$page_name}'";
            $count = db_get_single_value($pn_sql);
            $i++;
            $append = $i;
        } while ($count != '0');

        // format date from friendly front end format
        $date = date("Y-m-d H:i:s", strtotime($data['date']));

        if ($data['thumbnail'] != '') {
            $thumbnail_path = $_SESSION['last_image_upload_location'];
        }



        $sql = "  INSERT INTO  `news`
            (  `page_name`, `title`, `date`, `summary`, `body`,  `published`, `archive`, `thumbnail`)
VALUES (  '{$page_name}', '{$data['title']}', '{$date}',  '{$data['summary']}', '{$data['body']}',   '0', '0',   '{$thumbnail_path}') ";



        $result = mysql_query($sql);
    }

    function insert_into_events() {

        $filter = 0;
        foreach ($this->fields as $id => $field) {
            if ($field->dataField)
                $data[$field->fieldname] = $field->getData();
            if ($field->filterValueField)
                $filter = $field->getData();
        }
        // need to make sure the page_name is unique
        $page_name = trim($data['title']);
        $page_name = preg_replace("![^a-z0-9]+!i", "-", $page_name);
        $append = "";
        // we first try the ideal page name - then try again with incremental ints
        do {
            $page_name = $page_name . $append;
            $pn_sql = "SELECT count(*) FROM events WHERE page_name = '{$page_name}'";
            $count = db_get_single_value($pn_sql);
            $i++;
            $append = $i;
        } while ($count != '0');


        // format date from friendly front end format
        $startdate = date("Y-m-d H:i:s", strtotime($data['startdate']));
        // format date from friendly front end format
        $enddate = date("Y-m-d H:i:s", strtotime($data['enddate']));
        if ($data['thumbnail'] != '') {
            $thumbnail_path = $_SESSION['last_image_upload_location'];
        }

        $sql = " INSERT INTO  `events` ( `page_name`, `title`, `startdate`, `enddate`, `summary`, `body`,  `published`, `archive`,  `thumbnail`)
VALUES ( '{$page_name}', '{$data['title']}', '{$startdate}', '{$enddate}', '{$data['summary']}',  '{$data['body']}',  '0', '0',   '{$thumbnail_path}') ";

        $result = mysql_query($sql);
    }

    function insert_into_casestudies() {

        $filter = 0;
        foreach ($this->fields as $id => $field) {
            if ($field->dataField)
                $data[$field->fieldname] = $field->getData();
            if ($field->filterValueField)
                $filter = $field->getData();
        }
        // need to make sure the page_name is unique
        $page_name = trim($data['title']);
        $page_name = preg_replace("![^a-z0-9]+!i", "-", $page_name);
        $append = "";
        // we first try the ideal page name - then try again with incremental ints
        do {
            $page_name = $page_name . $append;
            $pn_sql = "SELECT count(*) FROM case_study WHERE page_name = '{$page_name}'";
            $count = db_get_single_value($pn_sql);
            $i++;
            $append = $i;
        } while ($count != '0');


        $order_num = db_get_single_value("SELECT MAX(order_num) FROM case_study") + 10;

        $sql = " INSERT INTO `case_study`
            (`title`, `description`, `body`, `thumb`, `page_image`, `published`, `order_num`, `page_name`, `featured`)
VALUES ('{$data['title']}', '{$data['description']}', '{$data['body']}', '{$_SESSION['last_image_upload_location']}', '{$_SESSION['last_image_upload_location']}', '0', $order_num, '{$page_name}', '0')";



        $result = mysql_query($sql);
    }





	function insert_into_memorybook() {

		$filter = 0;
		foreach ($this->fields as $id => $field) {
			if ($field->dataField)
				$data[$field->fieldname] = $field->getData();
			if ($field->filterValueField)
				$filter = $field->getData();
		}



		// format date from friendly front end format
		$date_of_birth = date("Y-m-d H:i:s", strtotime($data['date_of_birth']));
		$date_of_death = date("Y-m-d H:i:s", strtotime($data['date_of_death']));

		// need to make sure the page_name is unique
		$page_name = trim($data['title']);
		$page_name = preg_replace("![^a-z0-9]+!i", "-", $page_name);
		$append = "";

		// we first try the ideal page name - then try again with incremental ints
		do {
			$page_name = $page_name . $append;
			$pn_sql = "SELECT count(*) FROM memorybook WHERE page_name = '{$page_name}'";
			$count = db_get_single_value($pn_sql);
			$i++;
			$append = $i;
		} while ($count != '0');


		$order_num = db_get_single_value("SELECT MAX(order_num) FROM memorybook") + 10;


		if($_SESSION['last_image_upload_location'] ==''){
			$no = rand(1,8);
			$image_loc = "/images/memorybook/default" . $no . ".jpg";
		}else{
			$image_loc = $_SESSION['last_image_upload_location'];
		}


		$sql = " INSERT INTO `memorybook`
            (`title`, `description`, `body`, `thumb`, `page_image`, `published`, `order_num`, `page_name`, `featured`, allow_comments, date_of_birth, date_of_death)
VALUES ('{$data['title']}', '{$data['description']}', '{$data['body']}', '{$image_loc}', '{$image_loc}', '0', $order_num, '{$page_name}', '0', '{$data['allow_comments']}','{$date_of_birth}','{$date_of_death}')";

		$result = mysql_query($sql);
		// echo $sql;

	}























	function insert_into_profiles() {
        $filter = 0;
        // var_dump($this->fields);
        foreach ($this->fields as $id => $field) {
            if ($field->dataField)
                $data[$field->fieldname] = $field->getData();
            if ($field->filterValueField)
                $filter = $field->getData();
        }
        // need to make sure the page_name is unique
        $page_name = trim($data['title']) . '-' . trim($data['firstname']) . '-' . trim($data['surname']);
        $page_name = preg_replace("![^a-z0-9]+!i", "-", $page_name);
        // need to check page_name is unique
        $i = 0;
        $append = "";

        do {
            $page_name = $page_name . $append;
            $pn_sql = "SELECT count(*) FROM profiles WHERE page_name = '{$page_name}'";
            $count = db_get_single_value($pn_sql);
            $i++;
            $append = $i;
        } while ($count != '0' || $i > 10);

        $order_num = db_get_single_value("SELECT MAX(order_num) FROM profiles") + 10;

        $sql = "insert into `profiles` ( `title`, `description`, `thumb`, `order_num`, `page_name`, `featured`, `firstname`, `surname`, `gender_id`, `age_group_id`, `phone`, `email`, `specialist_topic`, `organisation`, `organisation_position`, `website_social_link`, published)
values ( '{$data['title']}', '{$data['description']}', '{$_SESSION['last_image_upload_location']}',   '{$order_num}', '{$page_name}', '0', '{$data['firstname']}', '{$data['surname']}', '{$data['gender_id']}', '{$data['age_group_id']}', '{$data['phone']}', '{$data['email']}', '{$data['specialist_topic']}', '{$data['organisation']}', '{$data['organisation_position']}', '{$data['website_social_link']}', 0);";


        $result = mysql_query($sql);
    }


	function insert_into_booking() {
		$filter = 0;
		// var_dump($this->fields);
		foreach ($this->fields as $id => $field) {
			if ($field->dataField)
				$data[$field->fieldname] = $field->getData();
			if ($field->filterValueField)
				$filter = $field->getData();
		}
		// need to make sure the page_name is unique
		$page_name = trim($data['title']);
		$page_name = preg_replace("![^a-z0-9]+!i", "-", $page_name);
		// need to check page_name is unique
		$i = 0;
		$append = "";

		do {
			$page_name = $page_name . $append;
			$pn_sql = "SELECT count(*) FROM booking WHERE page_name = '{$page_name}'";
			$count = db_get_single_value($pn_sql);
			$i++;
			$append = $i;
		} while ($count != '0' || $i > 10);

		$order_num = db_get_single_value("SELECT MAX(order_num) FROM booking") + 10;



		// format date from friendly front end format
		$start_date = date("Y-m-d H:i:s", strtotime($data['start_date']));
		$end_date = date("Y-m-d H:i:s", strtotime($data['end_date']));



		$sql = "insert into `booking` (thumb, page_image, `title`, `description`, body, start_date, end_date, `order_num`, `page_name`, `featured`,  published, hospice_event, event_type, show_map)
values ('{$_SESSION['last_image_upload_location']}', '{$_SESSION['last_image_upload_location']}','{$data['title']}', '{$data['description']}',  '{$data['body']}','{$start_date }','{$end_date}',  '{$order_num}', '{$page_name}', 0, 0, 1, 1, 0);";

echo $sql;
		var_dump($_POST);
		$result = mysql_query($sql);
	}

}
