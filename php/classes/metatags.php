<?php
include_once($base_path .'/php/functions_inc.php');
class tags {

    var $title;
    var $description;
    var $keywords;
    var $row_exists;
    var $tags_sql;

    function tags() {
        $this->title = new tags_field('title', 'Title');
        $this->description = new tags_field('description', 'Description<br>(160 characters <br>max. advised)');
        $this->keywords = new tags_field('keywords', 'Keywords');
        $this->row_exists = false;

        $this->tags_sql = "select 'this' as level, 1 as ordernum, m1.* from metatag m1 join content_type ct1 on ext_id = ct1.id where ct1.id = '%s' and module_id = 0
                     union
                     select 'parent', 2, m2.* from metatag m2 join content_type ct2 on ext_id = ct2.parent_id where ct2.id = '%s' and parent_id > 0 and module_id = 0
                     union 
                     select 'top', 3, m3.* from metatag m3 where ext_id = 0 and module_id = 0
                     order by 2";
        $this->table = 'metatag';
        $this->module_id = 0;
    }

    function get_database_values($dblink, $id) {
        $tags_result = mysql_query(sprintf($this->tags_sql, $id, $id));
        _d(sprintf($this->tags_sql, $id, $id));
        while ($tags_row = mysql_fetch_array($tags_result)) {
            $level = $tags_row['level'];

            if ($id == '0')
                $level = 'this';

            if ($level == 'this')
                $this->row_exists = true;

            $this->title->process_row($tags_row['title'], $level);
            $this->description->process_row($tags_row['description'], $level);
            $this->keywords->process_row($tags_row['keywords'], $level);
        }
    }

    function output_admin_table_rows($is_top) {
        $this->title->output_admin_table_row($is_top, 'input');
        $this->description->output_admin_table_row($is_top);
        //$this->keywords->output_admin_table_row($is_top);
    }

    function get_form_values() {
        $this->title->get_form_values();
        $this->description->get_form_values();
        $this->keywords->get_form_values();
    }

    function update_tags($dblink, $id) {
        $sql = "select * from {$this->table} where ext_id = '$id' and module_id = '{$this->module_id}'";
        $result = mysql_query($sql);
        $this->row_exists = (mysql_num_rows($result) > 0);

        if ($this->row_exists)
            $sql = sprintf("update {$this->table} set title='%s', description='%s', keywords='%s' where ext_id = '%s' and module_id = '%s'", $this->title->value, $this->description->value, $this->keywords->value, $id, $this->module_id);
        else
            $sql = sprintf("insert into {$this->table} (title, description, keywords, ext_id, module_id) values ('%s', '%s', '%s' , '%s', '%s')", $this->title->value, $this->description->value, $this->keywords->value, $id, $this->module_id);

        $result = mysql_query($sql);
        return ($result);
    }

}

class tags_field {

    var $name;
    var $label;
    var $value;
    var $default_value;
    var $level;

    function tags_field($tag_name, $label) {
        $this->name = $tag_name;
        $this->label = $label;
    }

    function value_or_default() {
        return ($this->value) ? $this->value : $this->default_value;
    }

    function process_row($row_value, $row_level) {
        if (!$this->default_value) {
            if ($row_level == 'this') {
                $this->value = $row_value;
                $this->level = $row_level;
            }
            else
                $this->default_value = $row_value;
        }
    }

    function output_admin_table_row($is_top, $type = 'textarea') {
        echo "<tr valign=top><td align=right >{$this->label}:</td> ";
        echo "<td  colspan=2>";
        if ($is_top)
            echo "default: {$this->default_value}<br/>";
        if ($type == 'input')
            echo "<input type='text' style='width:350px;' size=50 id='tags_{$this->name}' name='tags_{$this->name}' value='{$this->value}'/>\n";
        else
            echo "<textarea style='width:580px; height:100px;' id='tags_{$this->name}' name='tags_{$this->name}'>{$this->value}</textarea>\n";
        echo "<input type='hidden' size=50 id='tags_{$this->name}_default' name='tags_{$this->name}_default' value='{$this->default_value}'/>";
        echo "</td></tr>\n";
    }

    function output_header_line() {
        echo '';
    }

    function get_form_values() {
        $this->value = $_POST["tags_{$this->name}"];
        $this->default_value = $_POST["tags_{$this->name}_default"];

        if ($this->value == $this->default_value)
            $this->value = '';
    }

}

?>
