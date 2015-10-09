<?
include '../../../admin/classes/template.php';

class members extends template 
{
	function members()
	{
		$this->template();
		$this->table = 'member_user';
		$this->group_name = 'Members';
		$this->single_name = 'Member';
		$this->singular = 'a';
		$this->hidable = true;
		$this->order_clause = ' id';
        $this->delete_field = 'status';
        $this->where_clause = ' where status >= 0 ';
		
		$this->ToolbarSet = 'Default';

        $this->buttons = array(
                            'edit' => array( 'text' => 'edit', 'type' => 'standard_edit'),
                            //'ratings' => array( 'text' => 'ratings', 'type' => 'button', 'pattern' => '/modules/members/admin/ratings.php?member=%s'),
                            'delete' => array( 'text' => 'delete', 'type' => 'standard_delete'),
                            );
        
        
		$this->fields = array( 
/*			'name' => array('name' => 'Title', 'formtype' => 'text', 'list' => true, 'required' => true, 'primary' => true),
            'business_name' => array('name' => 'Business Name', 'formtype' => 'text', 'required' => true),
            'phone' => array('name' => 'Contact phone number', 'formtype' => 'shorttext', 'required' => true),
            'email' => array('name' => 'Email', 'formtype' => 'text', 'required' => true),
            'address' => array('name' => 'Address', 'formtype' => 'textarea', 'required' => true, 'rows' => '3', 'cols' => '45'),
            'description' => array('name' => 'Description', 'formtype' => 'textarea', 'required' => true,  'rows' => '3', 'cols' => '45'),
            'area_id' => array('name' => 'Primary geographical area', 'formtype' => 'lookup', 'function' => 'arealookup', 'required' => true),
            'trade_id' => array('name' => 'Primary trade', 'formtype' => 'lookup', 'function' => 'tradelookup', 'required' => true),
*/
			//'thumb' => array('name' => 'Thumbnail', 'formtype' => 'image', 'list' => false, 'required' => true, 'size' => 2),
			//'category' => array('name' => 'Categories', 'formtype' => 'checklink', 'list' => false, 'required' => true, 'not_field' => true, 'link' => 'category'),
			//'description' => array('name' => 'Description', 'formtype' => 'textarea', 'list' => false, 'rows' => 3, 'cols' => 60, 'required' => true),
			//'username' => array('name' => 'Username', 'list' => true, 'formtype' => 'shorttext', 'required' => true),
            //'type_id' => array('name' => 'Member type', 'formtype' => 'lookup', 'function' => 'typelookup', 'required' => true),
			'email' => array('name' => 'Email', 'list' => true, 'formtype' => 'text', 'required' => true),
			'title' => array('name' => 'Title', 'formtype' => 'shorttext', 'required' => true),
			'firstname' => array('name' => 'First name', 'formtype' => 'shorttext', 'required' => true),
            'surname' => array('name' => 'Surname', 'formtype' => 'shorttext', 'required' => true),
            'screenname' => array('name' => 'Screen Name', 'formtype' => 'shorttext', 'required' => true),
            'password' => array('name' => 'Password', 'formtype' => 'password', 'required' => true),
			'status' => array('name' => 'Active', 'formtype' => 'checkbox', 'list' => false, 'required' => false, 'link' => 'category'),
            //'body' => array('name' => 'Main Content', 'formtype' => 'fckhtml', 'list' => false, 'required' => true)
			'sendemail' => array('name' => 'Email activation confirmation', 'formtype' => 'checkbox', 'not_field' => true),
		);			
		
		
		//$this->links = array( 'category' => array('link_table' => 'case_study_category', 'table' => 'category', 'name' => 'title') );
	}	

	function delete_item($id)
	{
        $delete_content_sql = "update {$this->table} set {$this->delete_field} = -1, email = '' WHERE id=$id";
        return mysql_query($delete_content_sql);
	}    
    
	function validate_data($id, $data)
	{
		$nonUniqueFields = '';
		$plural = false;
		$sql = "select count(*) as matches from member_user where screenname = '{$data['screenname']}'";
		if ($id)
			$sql .= " and id <> $id";
		if (db_get_single_value($sql, 'matches') > 0)
			$nonUniqueFields = 'Screen Name';

		$sql = "select count(*) as matches from member_user where email = '{$data['email']}'";
		if ($id)
			$sql .= " and id <> $id";
		if (db_get_single_value($sql, 'matches') > 0)
		{
			if (empty($nonUniqueFields))
				$nonUniqueFields = 'Email Address';
			else
			{
				$nonUniqueFields .=  ' and Email Address';
				$plural = true;
			}
		}
		
		if (!empty($nonUniqueFields))
		{	
			if ($plural)
				$msg = "<p>This $nonUniqueFields are in use</p>";
			else
				$msg = "<p>This $nonUniqueFields is in use</p>";
			$msg .= "<p>Please use a different $nonUniqueFields</p>";
		}

		return ($msg);
	}
 
    function get_crumbs()
    {
        return "<b>{$this->single_name} Admin</b>";
    }
        
    function arealookup($id)
    {
        return $this->lookup('area', 'area_id', $id);
    }
    
    function typelookup($id)
    {
        return $this->lookup('member_type', 'type_id', $id);
    }
    
    function get_area_name($area_id)
    {
        return db_get_single_value("select name from area where id = '$area_id'", 'name');
    }

    function get_trade_name($trade_id)
    {
        return db_get_single_value("select name from trade where id = '$trade_id'", 'name');
    }
	function emailDetails($id)
	{
		$user_row = db_get_single_row("select * from member_user where id = $id");
		$to_email_address = $user_row["email"];

		$subject = SITE_NAME .": Account manually activated";
		$from_email_address = SITE_CONTACT_EMAIL;

		//$message ="Your member account details are:\n\n";
		//$message .="Your username is: " . $user_row["username"] . "\n\n";
		$message ="Your membership account for the discussion forums has been activated by the website team.\n\n";
		$message ="You can now login with the email address and password you set the account up with.\n\n";
        $message .= "Click here to access the " . SITE_NAME . " web site - " . SITE_ADDRESS . "\n";

		mail($to_email_address,$subject,$message,'FROM: '.$from_email_address);
	}

	function process_submit($id, $parent_id = false)
	{
		$result = parent::process_submit($id, $parent_id);

		if ($result == '')
		{
			// if asked to, send the user their account details
			if ($_REQUEST['sendemail'])
			{
				if (empty($id))
						$id = $this->id;
				$this->emailDetails($id);
			}
		}
        return $result;
	}
}

$template = new members(); 

$main_page = 'index.php';
$main_title = 'Return to main page';
			
$admin_tab = "member_admin";
//$second_admin_tab = "members";
//include 'second_level_navigavtion.php';

include ("../../../admin/template.php");
