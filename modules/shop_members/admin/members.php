<?

include '../../../admin/classes/template.php';
/* TODO: need to make sure there is only one member per partner page - 
 * also - when the show_on_map is not on profile - set partner_id = 0 */

class shop_members extends template {

    function shop_members() {
        $this->template();
        $this->table = 'shop_member_user';
        $this->group_name = 'Members';
        $this->single_name = 'Member';
        $this->singular = 'a';
        $this->hidable = true;
        $this->order_clause = ' username';
        $this->ToolbarSet = 'Default';
        $this->buttons = array(
            'edit' => array('text' => 'edit', 'type' => 'standard_edit'),
            'delete' => array('text' => 'delete', 'type' => 'standard_delete'),           
        );
        //$this->javascript_file = array('/modules/members/js/members_admin2.js', '/modules/maps/js/maphelper.js');
        $this->fields = array(
            'email' => array('name' => 'Email', 'list' => true, 'formtype' => 'text', 'required' => true),
            'firstname' => array('name' => 'First name', 'formtype' => 'shorttext', 'required' => true),
            'surname' => array('name' => 'Surname', 'formtype' => 'shorttext', 'required' => true),
            'password' => array('name' => 'password', 'formtype' => 'password', 'required' => true),
            'status' => array('name' => 'Active', 'formtype' => 'checkbox', 'list' => false, 'required' => false, 'link' => 'category'),       
            'billing_address1' => array('name' => 'Billing Address Line 1', 'formtype' => 'text', 'required' => true),
            'billing_address2' => array('name' => 'Billing Address Line 2', 'formtype' => 'text', 'required' => false),
            'billing_address3' => array('name' => 'Billing Address Line 3', 'formtype' => 'text', 'required' => false),
            'billing_postalcode' => array('name' => 'Billing Postcode', 'formtype' => 'text', 'required' => true),
            'billing_country_id' => array('name' => 'Billing Country', 'formtype' => 'lookup', 'function' => 'billingCountryLookup', 'required' => true),
            'phone' => array('name' => 'Telephone', 'formtype' => 'text', 'required' => true),
            'delivery_address1' => array('name' => 'Delivery Address Line 1', 'formtype' => 'text', 'required' => true),
            'delivery_address2' => array('name' => 'Delivery Address Line 2', 'formtype' => 'text', 'required' => false),
            'delivery_address3' => array('name' => 'Delivery Address Line 3', 'formtype' => 'text', 'required' => false),
            'delivery_postalcode' => array('name' => 'Delivery Postcode', 'formtype' => 'text', 'required' => true),
            'delivery_country_id' => array('name' => 'Delivery Country', 'formtype' => 'lookup', 'function' => 'deliveryCountryLookup', 'required' => true),
            'vat_number' => array('name' => 'VAT Number', 'formtype' => 'text', 'required' => false),   
            'sendemail' => array('name' => 'Email Details', 'formtype' => 'checkbox', 'not_field' => true),
        );
    }


    function copyString() {
        $cs = 'hi';
        $cs = $this->copyAddressFromBilling();
        $cs .= $this->copyAddressFromDelivery();
        $cs .= " | <a onclick='clearMappingAddress();' href='javascript:;' >Clear Mapping Address</a> ";
        return $cs;
    }

    function copyAddressFromBilling() {
        $out = " <a onclick='copyFromBillingAddress();' href='javascript:;' >Copy Billing Address</a> ";
        return $out;
    }

    function copyAddressFromDelivery() {
        $out = " | <a onclick='copyFromDeliveryAddress();' href='javascript:;' >Copy Delivery Address</a>";
        return $out;
    }

   
    function billingCountryLookup($id) {
        return $this->lookup('shop_country', 'billing_country_id', $id, 'name');
    }

    function deliveryCountryLookup($id) {
        return $this->lookup('shop_country', 'delivery_country_id', $id, 'name');
    }
   

    function get_crumbs() {
        return "<b>{$this->single_name} Admin</b>";
    }

    function arealookup($id) {
        return $this->lookup('area', 'area_id', $id);
    }

    function typelookup($id) {
        return $this->lookup('member_type', 'type_id', $id);
    }

    function get_area_name($area_id) {
        return db_get_single_value("select name from area where id = '$area_id'", 'name');
    }

    function get_trade_name($trade_id) {
        return db_get_single_value("select name from trade where id = '$trade_id'", 'name');
    }

    function emailDetails($id) {
        $user_row = db_get_single_row("select * from member_user where id = $id");
        $to_email_address = $user_row["email"];
        $subject = SITE_NAME . ": Your member account details";
        $from_email_address = SITE_CONTACT_EMAIL;
        $message = "Your member account details are:\n\n";
        $message .="Your username is: " . $user_row["username"] . "\n\n";
        $message .="Your password is: " . $user_row["password"] . "\n\n";
        $message .= "Click here to access the " . SITE_NAME . " web site - " . SITE_ADDRESS . "\n";
        mail($to_email_address, $subject, $message, 'FROM: ' . $from_email_address);
    }
    
}

$template = new shop_members();
$main_page = 'index.php';
$main_title = 'Return to main page';
$admin_tab = "shop";
$second_admin_tab = "members";
include 'second_level_navigavtion.php';
include ("../../../admin/template.php");