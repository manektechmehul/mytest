<?php

include '../../../admin/classes/template.php';

class main extends template {

    function main() {
        $this->template();
        $this->debug_log = false;
        $this->php_debug = false;
        /* #module specific */
        $this->table = 'video';
        /* #module specific */
        $this->group_name = 'Videos';
        /* #module specific */
        $this->single_name = 'Video';
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
            'featured' => array('text' => 'featured', 'type' => 'function', 'function' => 'set_featured'),
            'delete' => array('text' => 'delete', 'type' => 'standard_delete'),
            'move' => array('text' => 'move', 'type' => 'standard_move'),
        );

        $this->fields = array(
            'title' => array('name' => 'Title', 'formtype' => 'text', 'list' => true, 'required' => true, 'primary' => true),
            'category' => array('name' => 'Categories', 'formtype' => 'checklink', 'required' => true, 'not_field' => true, 'link' => 'category',
                'customfunction' => 'category_checklist'),
        //    'page_image' => array('name' => 'Page Image', 'formtype' => 'image'),
        //    'thumb' => array('name' => 'Thumbnail', 'formtype' => 'image', 'list' => false, 'required' => true, 'size' => 2),
            'description' => array('name' => 'Description', 'formtype' => 'textarea', 'list' => false, 'rows' => 3, 'cols' => 60, 'required' => true),
            'body' => array('name' => 'Main Content', 'formtype' => 'fckhtml', 'list' => false, 'required' => true),
            'page_name' => array('name' => 'Page Name', 'formtype' => 'hidden', 'keepfirst' => true),
            'video_type' => array('name' => 'Video Source', 'formtype' => 'lookup', 'required' => false, 'function' => 'videotypelookup'),
            'video_id' => array('name' => 'Video id', 'formtype' => 'shorttext', 'required' => false),
        );
        // this will populate the link table - categories
        $this->links = array('category' => array('link_table' => 'video_category_lookup', 'table' => 'video_category', 'name' => 'title', 'primary' => 'item_id', 'foreign' => 'category_id'));
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
        $linksql = "SELECT t.id, t.title, l.item_id FROM video_category t 
LEFT OUTER JOIN video_category_lookup l ON t.id = l.category_id AND l.item_id = '$id' order by title";
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

    /* Custom functions  */

    function videotypelookup($id) {
        $sql = 'select * from video_video_type order by title';
        $result = mysql_query($sql);
        // echo $sql;
        $out = '<select name="video_type">';
        while ($row = mysql_fetch_array($result)) {
            $selected = ($id == $row[id]) ? ' selected="selected"' : '';
            $out .= '<option value="' . $row['id'] . '"' . $selected . ' >' . $row['title'] . '</option>';
        }
        $out .= '</select>';

        return $out;
    }

}

$template = new main();
$main_page = 'index.php';
$main_title = 'Return to main page';
/* #module specific */
$admin_tab = "video";
/* #module specific */
$second_admin_tab = "videos";

include 'second_level_navigation.php';
include ("../../../admin/template.php");
?>


