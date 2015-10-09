<?php

include_once '../../../php/databaseconnection.php';
include '../../../admin/classes/template.php';

class formFields extends template {

    function __construct() {
        $form_id = $_GET['form'];
        $this->formName = db_get_single_value("select title from forms where id = $form_id", 'title');
        $this->template();
        $this->table = 'form_fields';
        $this->group_name = 'Form Fields';
        $this->single_name = 'Form Field';
        $this->singular = 'a';
        $this->hideable = true;
        $this->ToolbarSet = 'Default';
        $this->ordered = true;
        $this->parent_id = $form_id;
        $this->parent_field = 'form';
        $this->parent_id_name = 'form';
         

        $this->fields = array(
            'title' => array('name' => 'Title', 'formtype' => 'textarea', 'list' => true, 'rows' => 3, 'cols' => 60, 'required' => true, 'primary' => true),
            'fieldtype' => array('name' => 'Type', 'formtype' => 'lookup', 'function' => 'fieldTypesDropDown', 'list' => false, 'required' => true),
            'fieldname' => array('name' => 'Fieldname', 'formtype' => 'text', 'required' => false, 'primary' => true),
            'values' => array('name' => 'Values', 'formtype' => 'textarea', 'list' => false, 'rows' => 3, 'cols' => 60,),
            'section_id' => array('name' => 'Form Section<br>(for larger forms with progress bar)', 'formtype' => 'lookup', 'function' => 'sectionLookUp', 'list' => false),
            'required' => array('name' => 'Required', 'formtype' => 'checkbox'),
            'keyfield' => array('name' => 'Key Field <br><i>[Check to process <br>on uploaded images]</i>', 'formtype' => 'checkbox'),
            'filterfield' => array('name' => 'Filter Field', 'formtype' => 'checkbox'),
            'csmailer_injection' => array('name' => 'csmailer_injection', 'formtype' => 'checkbox'),
        );
    }

  
    
    function sectionLookUp($id, $fieldname, $field) {
         $form_id = $this->parent_id;
         // $id is select field id for form section
         
      
         
        /* #module specific */
        $sql = "SELECT * from form_sections where form = '$form_id' ORDER BY `order_num`";
        
         
        $result = mysql_query($sql);
        $out = '<select name="section_id" id="section_id">';
        $first = true;
        while ($row = mysql_fetch_array($result)) {
                $selected = ($id == $row['id']) ? ' selected="selected"' : '';
                
                if($first){
                    $out .= '<option value="0"' . $selected . ' >Not in a Section</option>';
                }
                 
                    $out .= '<option value="' . $row['id'] . '"' . $selected . ' >' . $row['title'] . '</option>';
                 
           $first = false;
        }
        $out .= '</select>';
        return $out;
    }
    
    function fieldTypesDropDown($value) {
        // make sure these ids match those used in the auto_form.php fieldFactory::registerFieldType 
        $fieldtypes = array(
            array('id' => 1, 'type' => 'text'),
            array('id' => 2, 'type' => 'textarea'),
            array('id' => 3, 'type' => 'checkbox'),
            array('id' => 4, 'type' => 'radio group'),
            array('id' => 5, 'type' => 'checkbox group'),
            array('id' => 6, 'type' => 'dropdown'),
            array('id' => 7, 'type' => 'title'),
            array('id' => 8, 'type' => 'databaselookup'),
            array('id' => 9, 'type' => 'captcha'),         
            array('id' => 10, 'type' => 'fileupload'),
             //array('id' => 11, 'type' => 'email'), ??           
            array('id' => 12, 'type' => 'databaselookup_checkbox'),
            array('id' => 13, 'type' => 'imageupload'),   
            array('id' => 14, 'type' => 'WYSIWYG'),   
            array('id' => 15, 'type' => 'Password'),   
            array('id' => 17, 'type' => 'Date with Calendar'),
            array('id' => 18, 'type' => 'Blank Security'),
	        array('id' => 19, 'type' => 'Databaselookup field with typeahead'),


        );

        $hidden = '';
        $out = '<select id="fieldtype" name="fieldtype" onchange="show_field_for_type()">';
        foreach ($fieldtypes as $fieldtype) {
            $selected = ($value == $fieldtype['id']) ? ' selected="selected"' : '';
            $out .= '<option value="' . $fieldtype['id'] . '"' . $selected . ' >' . $fieldtype['type'] . '</option>';
        }
        $out .= '</select>' . $hidden;

        return $out;
    }

    function get_crumbs($page) {
        return "<a href='forms.php'>Form Admin</a> > <b>{$this->formName} Fields Admin</b>";
    }

}

$template = new formFields();

$main_page = 'index.php';
$main_title = 'Return to main page';


$admin_tab = "forms_admin";

include ("../../../admin/template.php");
