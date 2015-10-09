<?php

include '../../../admin/classes/template.php';

class main extends template {

    function main() {
        $this->template();
        $this->debug_log = false;
        $this->php_debug = false;
        /* #module specific */
        $this->table = 'profiles';
        /* #module specific */
        $this->group_name = 'Profiles';
        /* #module specific */
        $this->single_name = 'Profile';
        $this->ordered = true;
        $this->order_clause = ' order_num desc ';
        $this->singular = 'a';
        $this->hideable = true;
        //$this->list_top_text = "The case study at the top of the list will be featured on the home page";
        $this->javascript_file = 'js/admin.js';
        $this->ToolbarSet = 'Default';
        $this->has_page_name = true;

        $this->buttons = array(
            'edit' => array('text' => 'add', 'type' => 'standard_edit'),
            'hide' => array('text' => 'hide', 'type' => 'standard_hide'),
            // need to dynamically initiate this button
            'featured' => array('text' => 'hide', 'type' => 'function', 'function' => 'set_featured'),
            'delete' => array('text' => 'delete', 'type' => 'standard_delete'),
            'move' => array('text' => 'move', 'type' => 'standard_move'),
        );

        $this->fields = array(
            'title' => array('name' => 'Title', 'formtype' => 'text', 'list' => true, 'required' => true, 'primary' => true),
            'firstname' => array('name' => 'Firstname', 'formtype' => 'text','required' => false, 'list' => true, ),
            'surname' => array('name' => 'Surname', 'formtype' => 'text','required' => false, 'list' => true, ),
            'gender_id' => array('name' => 'Gender', 'formtype' => 'lookup', 'function' => 'gender_lookup'),
            'age_group_id' => array('name' => 'Age group', 'formtype' => 'lookup', 'function' => 'age_group_lookup'),
            'email' => array('name' => 'Email', 'formtype' => 'text','required' => false),
            'website_social_link' => array('name' => 'Website or Social link', 'formtype' => 'text','required' => false), 
            'category' => array('name' => 'Categories', 'formtype' => 'checklink', 'required' => true, 'not_field' => true, 'link' => 'category',
            'customfunction' => 'category_checklist'),
            'thumb' => array('name' => 'Profile Image', 'formtype' => 'image', 'list' => false, 'required' => true, 'size' => 2),
            'description' => array('name' => 'Biography', 'formtype' => 'fckhtml', 'list' => false, 'rows' => 3, 'cols' => 60, 'required' => true),            
            'organisation' => array('name' => 'Organisation', 'formtype' => 'text','required' => false),
            'organisation_position' => array('name' => 'Organisation position', 'formtype' => 'text','required' => false),           
            'specialist_topic' => array('name' => 'Specialist topic', 'formtype' => 'text','required' => false),
            'page_name' => array('name' => 'Page Name', 'formtype' => 'hidden', 'keepfirst' => true),
        );
        // this will populate the link table - categories
         /* #module specific */
        $this->links = array('category' => array('link_table' => 'profiles_category_lookup', 'table' => 'profiles_category', 'name' => 'title', 'primary'=>'item_id', 'foreign'=>'category_id'));
    }

 

       
/* This function is duplicated from the template to override the custom page name functionality */
function process_submit($id, $parent_id = false) {
        global $base_path;

        // SUBMIT ITEM EDITS
        list($hasErrors, $message) = $this->check_data($id);
        if ($hasErrors)
            return $message;
       
        /* This function is duplicated from the template to override the custom page name functionality */
        $custom_page_name = $this->data['title'] . "-" .  $this->data['firstname'] . "-" . $this->data['surname'];
     
        if ($this->has_page_name) {
            $page_name = ((isset($this->data['page_name'])) && ($this->data['page_name'])) ? $this->data['page_name'] : $custom_page_name;
            $page_name = $custom_page_name;            
            $page_name = $this->make_valid_pagename($id, $parent_id, $page_name);
            $this->data['page_name'] = $page_name;
        }
        
        // end custom coding
        list($result, $id) = $this->write_data($id, $parent_id);

        if (is_array($this->links) && (count($this->links) > 0)) {
            $this->update_links($id);
        }

        // check results
        if ($result) {
            
        } else {
            $errorMsg = mysql_error();
            echo "<script>window.console.log(\"$errorMsg\");</script>";
            echo "<p>Sorry, an error has occurred.  The content could not be added. Please contact the web administrator";
            $base_path = $_SERVER['DOCUMENT_ROOT'];
            include ("$base_path/admin/admin_footer_inc.php");
            exit();
            //echo "$update_sql $insert_sql";
        }
        //echo "</ul>";
    }
    
    
    function gender_lookup($id){    
        $sql = 'select * from profiles_gender';
        $result = mysql_query($sql);          
        $out = '<select id="gender_id" name="gender_id" >';
        while ($row = mysql_fetch_array($result))
        {
            $selected = ($id == $row[id]) ? ' selected="selected"' : '';
            $out .= '<option value="'.$row['id'].'"'.$selected.' >'.$row['title'].'</option>';
           
        }     
        $out .= '</select>'; 
        return $out;
    }
    
    function age_group_lookup($id){    
        $sql = 'select * from profiles_age_group';
        $result = mysql_query($sql);          
        $out = '<select id="age_group_id" name="age_group_id"    >';
        while ($row = mysql_fetch_array($result))
        {
            $selected = ($id == $row[id]) ? ' selected="selected"' : '';
            $out .= '<option value="'.$row['id'].'"'.$selected.' >'.$row['title'].'</option>';
           
        }     
        $out .= '</select>'; 
        return $out;
    }

    function set_featured($id) {
        $featured = db_get_single_value('select featured from ' . $this->table . ' where id = ' . $id, 'featured');
        $hide_show = ($featured) ? 'hide' : 'show';
        $featured = ($featured) ? 'featured' : 'not featured';
        $href = sprintf("%s?%s%s_item=yes&id=%s", $this->page_self, $parent_part, $hide_show, $content_row["id"]);
        $this->cms_admin_button($href, $hide_show . 'content', $featured, "onclick='return set_featured_item(this, \"$featured\",\"$id\");'");
    }

    function get_crumbs($page) {
        if ($page == '')
            return "<b>{$this->single_name} Admin</b>";
        else
            return "<a href='main.php'>{$this->single_name} Admin</a> > <b>$page</b>";
    }

   function category_checklist($id, $fieldname, $field) {
        $checklink = $this->links[$field['link']];           
        /* #module specific */
        $linksql = "SELECT t.id, t.title, l.item_id FROM profiles_category t 
LEFT OUTER JOIN profiles_category_lookup l ON t.id = l.category_id AND l.item_id = '$id' order by title";      
        
        $linkresult = mysql_query($linksql);       
        
        $template = '<span class="form-checkbox"><input type=checkbox name=%s[] %s value=%s>%s</span>';
        $specialflag = 1;
        $inner = '';
        while ($linkrow = mysql_fetch_array($linkresult)) {
            if ($specialflag) {
                if ($linkrow['special'] == 0) {
                    $specialflag = 0;
                }
            }
            $checked = ($linkrow['item_id']) ? "checked" : "";
            $inner .= sprintf($template, $fieldname, $checked, $linkrow['id'], $linkrow['title']);
        }
        printf('<tr valign=top><td>%s</td><td><div class="form-checkbox-group">%s</div></td></tr>', $field['name'], $inner);
    }

    
}

$template = new main();
$main_page = 'index.php';
$main_title = 'Return to main page';
/* #module specific */
$admin_tab = "profile";
/* #module specific */
$second_admin_tab = "profiles";

include 'second_level_navigation.php';
include ("../../../admin/template.php");

?>


