<?
include '../../../admin/classes/template.php';

/* TODO: need to make sure there is only one member per partner page - 
 * also - when the show_on_map is not on profile - set partner_id = 0 */

class members extends template
{
	function members()
	{
            $this->template();
            $this->table = 'map';
            $this->group_name = 'Locations';
            $this->single_name = 'Location';
            $this->singular = 'a';
            $this->order_clause = ' id';
            $this->ToolbarSet = 'Default';
			$this->hideable = true;


            $this->buttons = array(
                            'edit' => array( 'text' => 'edit', 'type' => 'standard_edit'),
                            //'ratings' => array( 'text' => 'ratings', 'type' => 'button', 'pattern' => '/modules/members/admin/ratings.php?member=%s'),
                            'delete' => array( 'text' => 'delete', 'type' => 'standard_delete'),
                            'hide' => array('text' => 'hide', 'type' => 'standard_hide'),
                            //'see click counter' => array( 'text' => 'click count', 'type' => 'button','pattern' => '/modules/members/admin/clicks.php?member=%s'),
                            //'click counter' => array( 'type' =>'function', 'function' => 'click_counter'),
                            //'Geo Status' => array( 'type' =>'function', 'function' => 'getGeoStatus'),
                            );
        
          
            $this->javascript_file = array('/modules/map/admin/js/admin.js', '/modules/map/js/maphelper.js');

		$this->has_page_name = true;
            $this->fields = array(
	         //   'email' => array('name' => 'Email', 'list' => true, 'formtype' => 'text', 'required' => true),
	         //   'firstname' => array('name' => 'First name', 'formtype' => 'shorttext', 'required' => true),
	         //   'surname' => array('name' => 'Surname', 'formtype' => 'shorttext', 'required' => true),
	         //   'password' => array('name' => 'password', 'formtype' => 'password', 'required' => true),
	        //    'status' => array('name' => 'Active', 'formtype' => 'checkbox', 'list' => false, 'required' => false, 'link' => 'category'),
	            'title' => array('name' => 'Title', 'formtype' => 'text', 'list' => true, 'required' => true),
	            'map_business_type' => array('name' => 'Location Type', 'formtype' => 'lookup', 'function' => 'businessTypeLookup', 'required' => true),
	          //  'business_type_other' => array('name' => 'Business Type Other', 'formtype' => 'text', 'required' => false),
	    //        'billing_address1' => array('name' => ' Address Line 1', 'formtype' => 'text', 'required' => true),
	     //       'billing_address2' => array('name' => ' Address Line 2', 'formtype' => 'text', 'required' => false),
	      //      'billing_address3' => array('name' => ' Address Line 3', 'formtype' => 'text', 'required' => false),
	     //       'billing_postalcode' => array('name' => ' Postcode', 'formtype' => 'text', 'required' => true),
	     //       'billing_country_id' => array('name' => ' Country', 'formtype' => 'lookup', 'function' => 'billingCountryLookup', 'required' => true),


	       //     'delivery_address1' => array('name' => 'Delivery Address Line 1', 'formtype' => 'text', 'required' => true),
	         //   'delivery_address2' => array('name' => 'Delivery Address Line 2', 'formtype' => 'text', 'required' => false),
	        //    'delivery_address3' => array('name' => 'Delivery Address Line 3', 'formtype' => 'text', 'required' => false),
	       //     'delivery_postalcode' => array('name' => 'Delivery Postcode', 'formtype' => 'text', 'required' => true),
	       //     'delivery_country_id' => array('name' => 'Delivery Country', 'formtype' => 'lookup', 'function' => 'deliveryCountryLookup', 'required' => true),

	      //      'vat_number' => array('name' => 'VAT Number', 'formtype' => 'text', 'required' => false),
	     //      'hear_about' => array('name' => 'Heard about us', 'formtype' => 'lookup', 'function' => 'hearAboutLookup', 'required' => true),
	    //        'hear_about_other' => array('name' => 'Heard about us: other', 'formtype' => 'text', 'required' => false),
	     //       'year_trained' => array('name' => 'Year Trained', 'formtype' => 'text', 'required' => false),
	        //    'trained_by' => array('name' => 'Trained by', 'formtype' => 'text', 'required' => false),


	      //      'geo_status' => array('name' => 'Geo Look up Status', 'formtype' => 'shorttext', 'required' => false,),

	            //   'updateLocation' => array('name' => 'Update Location Details', 'formtype' => 'shorttext', 'required' => false, 'list'=>true),
	     //       'show_on_map' => array('name' => 'Show on Map', 'formtype' => 'lookup', 'function' => 'showOnMapLookup', 'required' => true),
	         //   'partner_id' => array('name' => 'Profile Link', 'formtype' => 'lookup', 'function' => 'lookupPartners', 'required' => false),

	          //  'copyAddress' => array('name' => '.', 'formtype' => 'lookup','not_field' => true, 'function'=>'copyString'),
	          //  'copyAddress' => array('name' => '.', 'formtype' => 'lookup','not_field' => true, 'function'=>'copyString'),


	            'mapping_address1' => array('name' => 'Address Line 1', 'formtype' => 'text', 'required' => false),
	            'mapping_address2' => array('name' => 'Address Line 2', 'formtype' => 'text', 'required' => false),
	            'mapping_address3' => array('name' => ' Address Line 3', 'formtype' => 'text', 'required' => false),
	            'mapping_postalcode' => array('name' => 'Postcode', 'formtype' => 'text', 'required' => false),
	            'mapping_country_id' => array('name' => 'Country', 'formtype' => 'lookup', 'function' => 'mappingCountryLookup', 'required' => true),
	            'phone' => array('name' => 'Telephone', 'formtype' => 'text', 'required' => true),
	            'mapping_email' => array('name' => 'email', 'formtype' => 'text', 'required' => false),
	          //  'mapping_website' => array('name' => 'mapping website', 'formtype' => 'text', 'required' => false),


	            'maptools' => array('name' => 'Mapping Tools', 'formtype' => 'lookup','not_field' => true, 'function'=>'maptools'),
	            'lon' => array('name' => 'Mapping - Lon', 'formtype' => 'shorttext', 'required' => false),
	            'lat' => array('name' => 'Mapping - Lat', 'formtype' => 'shorttext', 'required' => false, ),

	            'thumb' => array('name' => 'Thumbnail', 'formtype' => 'image', 'list' => false, 'required' => true, 'size' => 2),
	            'description' => array('name' => 'Description', 'formtype' => 'fckhtml', 'list' => false, 'rows' => 3, 'cols' => 60, 'required' => true),

	            'opening_times' => array('name' => 'Opening Times', 'formtype' => 'textarea', 'list' => false, 'rows' => 3, 'cols' => 60, 'required' => false),
	            'page_name' => array('name' => 'Page Name', 'formtype' => 'hidden', 'keepfirst' => true),

	            //     'sendemail' => array('name' => 'Email Details', 'formtype' => 'checkbox', 'not_field' => true),
                

		);			
		
	}	

        function maptools(){
            $helpers = "<br>After updating the mapping address, we need to check that we can find it on the map.<a style='cursor:pointer; color:#368; text-decoration: underline;' onclick='updategeocode_primer()'>Check the Location Now</a>";
            $helpers = $helpers . "<br> To see your location on the map, or to resolve a problem with that location, try the <a style='cursor:pointer; color:#368; text-decoration: underline; ' onclick='openMapTool()'>Map Location Tool</a>";
            $helpers = $helpers . "<br>";    
            return $helpers;  
        }
        
        function copyString(){
            $cs = 'hi';
            $cs = $this->copyAddressFromBilling(); 
            $cs .= $this->copyAddressFromDelivery();
            $cs .= " | <a onclick='clearMappingAddress();' href='javascript:;' >Clear Mapping Address</a> "; 
         return $cs;
        }
        
        function copyAddressFromBilling(){
            $out = " <a onclick='copyFromBillingAddress();' href='javascript:;' >Copy Billing Address</a> ";            
            return $out;
        }
         function copyAddressFromDelivery(){
            $out = " | <a onclick='copyFromDeliveryAddress();' href='javascript:;' >Copy Delivery Address</a>";            
            return $out;
        }

        function click_counter($id){
       $show_on_map = db_get_single_value("select show_on_map from member_user where id = '$id'",'show_on_map');
       /* if user is added to map- add exta buttons */
       if($show_on_map <> 0){
                      	
                $href="members.php?edit_item=yes&id=" . $id;
                
                /* show partner type bronze, silver, gold */
                if($show_on_map == 1){
                    $this->cms_admin_button($href, 'contentbutton', 'On Map 1_________________', "#");
                }
                if($show_on_map == 2){
                    $this->cms_admin_button($href, 'contentbutton', 'On Map 2________________', "#");
                }
                if($show_on_map == 3){
                    $this->cms_admin_button($href, 'contentbutton', 'On Map Partner', "#");
                }
                
                
                /* check the geo status look up worked */
                $geo_status = db_get_single_value("select geo_status from member_user where id = '$id'",'geo_status');
                /** if the user is going on the map - check the geo status */                
             
			 /*
			 
			    if($geo_status == '1'){
                        // Geo Status is good 
                        $hide_show = 'show';
                }
                
                if($geo_status == '2'){
                        // Geo Look up Failed 
                        $hide_show = 'hide';
                }
                $this->cms_admin_button($href, $hide_show . 'content', 'map geo status',$href );
               
			    */
                
                
                
                
                /* Add Click counter button */
                $href = 'clicks.php?id=' . $id;              
                $this->cms_admin_button($href, 'contentbutton', 'click count', $href);
                
       }      
    }    
    
    function getGeoStatus($id){
       $geo_status = db_get_single_value("select geo_status from member_user where id = '$id'",'geo_status');
       
       if($geo_status = '1'){
           	//$href = 'clicks.php?id=' . $id;// sprintf ("%s?%s%s_item=yes&id=%s", $this->page_self, $parent_part, $hide_show, $content_row["id"]);
		// cms_admin_button($href, $type, $text, $onclick = false) 
            $hide_show = 'show';
          
       }
       if($geo_status = '2'){
             $hide_show = 'hide';
       }
       
       
       $this->cms_admin_button($href, $hide_show . 'content', 'map geo status', "href=members.php?edit_item=yes&id=$id'");
    }     
    
     
    
    
    
    
    
    /* $table, $fieldname, $id, $namefield = '' */  
    function lookupPartners($id){     
        /* see js file for extra bits */
        return $this->lookup('partners', 'partner_id', $id, 'title', true);
    }
            
    function showOnMapLookup($id)
    {
        /* see js file for extra bits */
        return $this->lookup('show_on_map', 'show_on_map', $id, 'name');
    }	
        
    function businessTypeLookup($id)
    {
        return $this->lookup('map_business_type', 'map_business_type', $id, 'title');
    }	

    function mappingCountryLookup($id)
    {
        return $this->lookup('shop_country', 'mapping_country_id', $id, 'name');
    }	
    
    function billingCountryLookup($id)
    {
        return $this->lookup('shop_country', 'billing_country_id', $id, 'name');
    }	

    function deliveryCountryLookup($id)
    {
        return $this->lookup('shop_country', 'delivery_country_id', $id, 'name');
    }	
	
    function hearAboutLookup($id)
    {
        return $this->lookup('hear_about', 'hear_about', $id, 'title');
    }		
	
    function validate_data($id, $data)
    {
            $nonUniqueFields = '';
            $plural = false;
            $sql = "select count(*) as matches from member_user where username = '{$data['username']}'";
            if ($id)
                    $sql .= " and id <> $id";
            if (db_get_single_value($sql, 'matches') > 0)
                    $nonUniqueFields = 'User Name';

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

		$subject = SITE_NAME .": Your member account details";
		$from_email_address = SITE_CONTACT_EMAIL;

		$message ="Your member account details are:\n\n";
		$message .="Your username is: " . $user_row["username"] . "\n\n";
		$message .="Your password is: " . $user_row["password"] . "\n\n";
		$message .= "Click here to access the " . SITE_NAME . " web site - " . SITE_ADDRESS . "\n";

		mail($to_email_address,$subject,$message,'FROM: '.$from_email_address);
	}

	/* function process_submit($id, $parent_id = false)
	{
		$result = parent::process_submit($id, $parent_id);

                // if we have updated the partner link - make us the only one
                if($this->data['show_on_map'] == '3'){
                    if($this->data['partner_id'] != '0'){
                      // system has set this user to be theselected profile link already
                      // need to now tidy up partnership - can only have one member to one partner
                      
                      $sql1 = "SELECT mu.id, mu.firstname, mu.`surname`, p.title AS partner_title FROM member_user mu
INNER JOIN partners p ON p.id = mu.`partner_id`  
WHERE partner_id = 11 AND mu.id !=". $id;  
                      
                      $r1 = mysql_query($sql1);
                       // if you find a record ....
                      
                   //     $result = mysql_query($sql);				
			
			if (mysql_num_rows($r1) > 0) 
			{
                            // previous member users assigned to this partner 
                            $previous_user = '';
                            $partner_name = '';
                           
                            while ($myrow = mysql_fetch_array($r1)) {	
                                $previous_user .=     $myrow['firstname'] . ' ' . $myrow['surname'] . ' [id=' . $myrow['id'] . '],' ;
                                $partner_name = $myrow['partner_title'];
                            }
                            $previous_user = substr_replace($previous_user ,"",-1);
                            
                                    $sql = "UPDATE member_user SET partner_id = 0 WHERE partner_id = " . $this->data['partner_id']  . " AND id !=" . $id;
                                    $r = mysql_query($sql);                              
                                    
                                    if($r == '1'){
                                        echo '<p style=\'color:red;\'>The Partner relationship has been updated,' . $partner_name . ' was previously connected to user <b> ' . 
                                                $previous_user . '</b></p>' ;
                                    }
                                   // var_dump($_POST);
                      
                        }
                      
                      
                      
                     }
                    
                }
                
                
                
                
                

        return $result;
	} */



}

$template = new members();

$main_page = 'index.php';
$main_title = 'Return to main page';
			
$admin_tab = "map";

include 'second_level_navigavtion.php';

include ("../../../admin/template.php");
