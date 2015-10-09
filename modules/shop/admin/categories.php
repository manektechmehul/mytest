<?php

include '../../../admin/classes/template.php';

class abstractCategory extends template {

    function __construct() {
        $this->template();
        $this->parent_child = true;
        $this->parent_id_name = 'parent_id';
        $this->table = 'shop_category';
        $this->group_name = 'Categories';
        $this->single_name = 'Category';
        $this->singular = 'a';
        $this->hidable = false;
        $this->ordered = true;
        $this->has_page_name = true;
        $this->javascript_file = '/modules/shop/admin/js/admin.js';
        $this->list_top_text = '<a onmouseover="button_over(this)" onmouseout="button_off(this)" href="/modules/shop/admin/categories.php?edit_item=yes"><img name="cmsButtonaddacategory" alt="Add a Category" src="/admin/images/buttons/cmsbutton-Add_a_Category-off.gif"/></a>';

        $this->buttons = array(
            'edit' => array('text' => 'add', 'type' => 'standard_edit'),
            // 'hide' => array('text' => 'hide', 'type' => 'standard_hide'),
            'online' => array('text' => 'hide', 'type' => 'function', 'function' => 'set_category_online'),
            'delete' => array('text' => 'delete', 'type' => 'standard_delete'),
            'move' => array('text' => 'move', 'type' => 'standard_move'),
        );
        $this->fields['name'] = array('name' => 'Name', 'formtype' => 'text', 'list' => true, 'required' => true, 'primary' => true);
        $this->fields['body'] = array('name' => 'Main Content', 'formtype' => 'fckhtml', 'list' => false, 'required' => true);
        $this->fields['page_name'] = array('name' => 'Page Name', 'formtype' => 'pagename', 'list' => false,);

        //'order_num' => array('name' => 'Order', 'formtype' => 'order', 'list' => false, 'required' => false)
        if (isset($_GET['parent_id'])) {
            $this->fields['parent_id'] = array('name' => '_', 'formtype' => 'lookup', 'function' => 'setCategoryParentId', 'list' => false, 'required' => false);
        }
    }

    function setCategoryParentId($id) {
        $out = '<input type="hidden" id="__category_parent_id"  name="__category_parent_id" value="' . $_GET['parent_id'] . '" />';
        return $out;
    }

    function set_category_online($id) {
        $online = db_get_single_value('select online from ' . $this->table . ' where id = ' . $id, 'online');
        $button_type_hide_show = ($online) ? 'hide' : 'show'; // - for button type - red/green
        $next_function = 1; // opposite of current position
        if ($online == 1) {
            $next_function = 0; // opposite of current position
        }else{
            $next_function = 1;
        }
        $button_text = ($online) ? 'hide' : 'show'; // - message to pass to js function

        $href = sprintf("%s?%s%s_item=yes&id=%s", $this->page_self, $parent_part, $button_type_hide_show, $id);
        $this->cms_admin_button($href, $button_type_hide_show . 'content', $button_text, "onclick='return set_category_online(this, \"$online\",\"$id\");'");


    }

    function get_crumbs($page) {
        return "<b>Categories</b>";
    }

    // hopefully overriding the template function of the same name
    function write_data($id, $parent_id) {
        if ($id) {

            $sql = $this->make_update_sql_statement($id);
            $result = mysql_query($sql);
        } else {
            $sql = $this->make_insert_sql_statement($parent_id);
            $result = mysql_query($sql);
            $id = mysql_insert_id();
            $this->id = $id;
            $tidyUpInsert = "update shop_category set parent_id = id where isroot=1";
            mysql_query($tidyUpInsert);
        }
        return array($result, $id);
    }

    function make_insert_sql_statement($parent_id) {
        $sql_fields = '';
        $sql_data = '';
        $sql_lang_fields = '';
        $sql_lang_data = '';
        if ($parent_id) {
            $sql_fields .= $this->parent_field . ', ';
            $sql_data .= "'$parent_id', ";
        }
        if ($this->ordered) {
            $parent_part = ($parent_id) ? "where {$this->parent_field} = $parent_id" : '';
            $order_sql = "select coalesce(max(order_num),0) + 10 as order_num from {$this->table} $parent_part";
            $order_result = mysql_query($order_sql);
            $order_row = mysql_fetch_array($order_result);
            $sql_fields .= 'order_num, ';
            $sql_data .= "'{$order_row['order_num']}', ";
        }
        $first = true;
        $lang_first = true;
        foreach ($this->fields as $fieldname => $field) {
            //skip multi lang fields altogether in this part
            if ($field['multi_lang']) {
                /*
                  if ($lang_first)
                  $lang_first = false;
                  else
                  {
                  $sql_fields .= ', ';
                  $sql_data .= ', ';
                  }
                  $sql_lang_fields  .= '`'.$fieldname.'`';
                  $sql_lang_data = "'{$this->data[$fieldname]}'";
                 */
            } else {
                if (isset($field['not_field']) && $field['not_field'])
                    continue;
                if ($first)
                    $first = false;
                else {
                    $sql_fields .= ', ';
                    $sql_data .= ', ';
                }
                if ($field['formtype'] == 'order') {
                    
                }
                if ($field['formtype'] == 'address') {
                    $lines = isset($field['lines']) ? $field['lines'] : 3;
                    for ($i = 0; $i < $lines; $i++) {
                        $cm = ($i > 0) ? ', ' : '';
                        $sql_fields .= "$cm$fieldname" . ($i + 1);
                        $sql_data .= "$cm'{$this->data[$fieldname][$i]}'";
                    }
                } else {
                    $sql_fields .= '`' . $fieldname . '`';
                    $sql_data .= "'{$this->data[$fieldname]}'";
                }
            }// end multi lang condition
        } //  end loop
        $sql = "insert into {$this->table} ({$sql_fields}) values ({$sql_data})";
        if (isset($_POST['__category_parent_id'])) {
            // fixed to level 2 for now
            $sql = "insert into {$this->table} ({$sql_fields}, isRoot,parent_id,level) values ({$sql_data},0," . $_POST['__category_parent_id'] . ",2 )";
        }
        //echo $sql;
        //die();
        return $sql;
    }

    function make_update_sql_statement($id) {
        $sql = "update {$this->table} set ";
        $i = 0;
        $first = true;
        foreach ($this->fields as $fieldname => $field) {
            //skip multi lang fields altogether in this part
            if (!$field['multi_lang']) {
                if (isset($field['not_field']) && $field['not_field'])
                    continue;
                if (($field['formtype'] == 'password') && (trim($this->data[$fieldname], '   *') == ''))
                    continue;
                if ($first)
                    $first = false;
                else
                    $sql .= ', ';
                if ($field['formtype'] == 'address') {
                    $lines = isset($field['lines']) ? $field['lines'] : 3;
                    for ($i = 0; $i < $lines; $i++) {
                        $sql .= ($i > 0) ? ', ' : '';
                        $sql .= "$fieldname" . ($i + 1) . " = '{$this->data[$fieldname][$i]}'";
                    }
                }
                else
                    $sql .= '`' . $fieldname . "` = '{$this->data[$fieldname]}'";
            }
        } // multi lang condition
        $sql .= " where id = $id";
        return $sql;
    }

}

class category extends abstractCategory {

    function __construct() {
        parent::__construct();
        $this->group_name = 'Categories';
        $this->single_name = 'Category';
        $this->where_clause = ' where isRoot = 1 ';
        $this->child = new sub_category();
        $this->min_items = 1;
    }

}

class sub_category extends abstractCategory {

    function __construct() {
        parent::__construct();
        $this->group_name = 'Sub Categories';
        $this->single_name = 'Sub Category';
        $parent_page_id = $_REQUEST['parent_id'];
        $this->parent_id = $parent_page_id;
        $this->parent_field = 'parent_id';
        $this->where_clause = ' where isRoot = 0 ';
    }
    
    function set_category_online($id) {
        
       
        $online = db_get_single_value('select online from ' . $this->table . ' where id = ' . $id, 'online');
        $button_type_hide_show = ($online) ? 'hide' : 'show'; // - for button type - red/green
        $next_function = 1; // opposite of current position
        if ($online == 1) {
            $next_function = 0; // opposite of current position
        }else{
            $next_function = 1;
        }
        $button_text = ($online) ? 'hide' : 'show'; // - message to pass to js function

        $href = sprintf("%s?%s%s_item=yes&id=%s", $this->page_self, $parent_part, $button_type_hide_show, $content_row["id"]);
        // NOTICE -- HERE I SET THE BUTTON TYPE TO CONTENTSUB !!!	
        $this->cms_admin_button($href, $button_type_hide_show . 'contentsub', $button_text, "onclick='return set_category_online(this, \"$online\",\"$id\");'");
        
    }

}

$template = new category();
$admin_tab = "shop";
$second_admin_tab = "categories";
include 'second_level_navigation.php';
include ("../../../admin/template.php");
