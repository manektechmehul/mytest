<?php

include '../../../admin/classes/template.php';

class blogs extends template {
    
    function blogs() {
        $this->template();            
        $this->table = 'blogs';
        $this->group_name = 'Blogs';
        $this->single_name = 'Blog';
        $this->singular = 'a';
        $this->hideable = true;
        $this->has_page_name = true;
        $this->ordered = true;
        $this->where_clause = ' where blog_status = 1 ';
        $this->delete_field = 'blog_status';
        //$this->max_items = 2;
		$this->max_items = 1;
        $this->ToolbarSet = 'Default';

        $this->buttons = array(
            'edit' => array('text' => 'edit', 'type' => 'standard_edit'),
            'posts' => array('text' => 'manage posts', 'type' => 'button', 'pattern' => '/modules/blog/admin/posts.php?blog=%s'),
            //'hide' => array('text' => 'hide', 'type' => 'standard_hide'),
            'editors' => array('text' => 'select editors', 'type' => 'button', 'pattern' => '/modules/blog/admin/blogs.php?select_editors=1&id=%s'),
            //'tags' => array('text' => 'edit tags', 'type' => 'button', 'pattern' => '/modules/blog/admin/blogs.php?edit_tags=1&id=%s'),
           // 'delete' => array('text' => 'delete', 'type' => 'standard_delete'),
            'comment count' => array('type' => 'function', 'function' => 'show_comment_count'),
          //  'move' => array('text' => 'move', 'type' => 'standard_move'),
        );

        $this->fields = array(
            'title' => array('name' => 'Title', 'formtype' => 'text', 'list' => true, 'required' => true, 'primary' => true),
            'page_name' => array('name' => 'Page Name', 'formtype' => 'page_name', 'list' => false, 'required' => false),
            'description' => array('name' => 'Description', 'formtype' => 'textarea', 'list' => false, 'rows' => 3, 'cols' => 60, 'required' => true),
        );
        
        $this->actions = array(
            'select editors' => array('title' => 'Select Editors', 'pagequerystring' => 'select_editors', 'pagemethod' => 'select_editors', 'actionquerystring' => 'submit_select_editors', 'actionmethod' => 'process_select_editors'),
            'edit tags' => array('title' => 'Edit Tags', 'pagequerystring' => 'edit_tags', 'pagemethod' => 'edit_tags', 'actionquerystring' => 'submit_edit_tags', 'actionmethod' => 'process_edit_tags'),
        );


        $basepath = $_SERVER['DOCUMENT_ROOT'];
        include "$basepath/php/classes/metatags.php";
        
        $this->tags = new tags();
        $this->tags->tags_sql = "select 'this' as level, 1 as ordernum, m1.* from metatag m1 where ext_id = '%s' and module_id = " . $this->module_id . 
            " union select 'top', 3, m3.* from metatag m3 where ext_id = 0 
            order by 2";
        $this->tags->module_id = $this->module_id;
    }

    function get_form_data() {
        $this->module_id = db_get_single_value("select id from module where constant = 'SITE_HAS_BLOG'", "id");
        parent::get_form_data();
    }
    
    function show_list() {
        if ($_REQUEST['submit_post_count']) {
            $post_count = $_REQUEST['post_count'];
            $sql = "update defaults set value = '$post_count' where name = 'blogs_post_count'";
            $result = mysql_query($sql);
        } else {
            $sql = "select value from defaults where name = 'blogs_post_count'";
            $result = mysql_query($sql);
            $row = mysql_fetch_array($result);
            $post_count = $row['value'];
        }
        $message = '<form method="post" action="">Number of posts to show: <select name="post_count">';
        for ($i = 3; $i < 13; $i++) {
            $select = '';
            if ($i == $post_count)
                $select = 'selected="selected"';
            $message .= "<option $select>$i</option>";
        }
        $message .= '</select><input type="submit" id="submit_post_count" name="submit_post_count" value="set"/></form>';
        $this->list_bottom_text = $message;
        template::show_list();
    }

    function add_button_show() {
        global $session_user_type_id;
        return ($session_user_type_id == 1);
    }

    function row_show($id) {
        global $session_user_id, $session_user_type_id;

        if ($session_user_type_id == 1)
            return true;
        else {
            $status = db_get_single_value("select status from blog_editors where blog_id = '$id' and user_id = '$session_user_id'", 'status');
            return ($status >= 1);
        }
    }

    function row_show_button($button, $id) {
        global $session_user_type_id;

        if ($session_user_type_id == 1)
            return true;
        else if ($button == 'posts')
            return true;
        else
            false;
    }

    function edit_tags($id) {


        echo "<div id='admin-page-content'>";
        echo '<form method="post" action="blogs.php">';
        $this->tags->get_database_values($link, $id);
        echo '<table>';
        $this->tags->output_admin_table_rows($id > 0);
        echo '</table>';
        echo '<input type="hidden" name="id" value="' . $id . '">';
        echo '<input type="submit" name="submit_edit_tags" value="submit">';
        echo '</form>';
        echo '</div>';

        /*
          $sql = "select * from blog_metatags where blog_id = '$id'";
          $request = mysql_query($sql);
          $row =  mysql_fetch_array($request);

          $title_value = $row['title'];
          $description_value = $row['description'];
          $keywords_value = $row['value'];

          $sql = "select * from blog_metatags where blog_id = 0";
          $request = mysql_query($sql);
          $row =  mysql_fetch_array($request);

          $title_default_value = $row['title'];
          $description_default_value = $row['description'];
          $keywords_default_value = $row['value'];

          echo '<form method="post" action="blogs.php">';
          echo '<table>';
          echo "<tr valign=top><td align=right >Title:</td> ";
          echo "<td  colspan=2>";
          if ($is_top) echo "default: $title_default_value<br/>";
          echo "<input type='text' size=50 id='tags_title' name='tags_title' value='$title_value'/>\n";
          echo "<input type='hidden' size=50 id='tags_title_default' name='tags_title_default' value='$title_default_value'/>";
          echo "</td></tr>\n";

          echo "<tr valign=top><td align=right >Description:</td> ";
          echo "<td  colspan=2>";
          if ($is_top) echo "default: $description_default_value<br/>";
          echo "<input type='text' size=50 id='tags_description' name='tags_description' value='$description_value'/>\n";
          echo "<input type='hidden' size=50 id='tags_description_default' name='tags_description_default' value='$description_default_value'/>";
          echo "</td></tr>\n";

          echo "<tr valign=top><td align=right >Keywords:</td> ";
          echo "<td  colspan=2>";
          if ($is_top) echo "default: $keywords_default_value<br/>";
          echo "<input type='text' size=50 id='tags_keywords' name='tags_keywords' value='$keywords_value'/>\n";
          echo "<input type='hidden' size=50 id='tags_keywords_default' name='tags_keywords_default' value='$keywords_default_value'/>";
          echo "</td></tr>\n";
          echo '</table>';
          echo '<input type="submit" name="submit_edit_tags" value="submit">';
          echo '</form>';
         */
    }

    function process_edit_tags($id) {
        $this->tags->get_form_values();

        if (!$this->tags->update_tags($link, $id)) {
            return "<p>Update failed</p><p><a href=\"blogs.php\">Return to list of pages</a></p>";
        }
        /*
          if (isset($_POST['tags_title']))
          $title = $_POST['tags_title'];
          else
          $title = $_POST['tags_title_default'];

          if (isset($_POST['tags_title']))
          $description = $_POST['tags_description'];
          else
          $description = $_POST['tags_description_default'];

          if (isset($_POST['tags_title']))
          $keywords = $_POST['tags_keywords'];
          else
          $keywords = $_POST['tags_keywords_default'];

          $sql = "select * from blog_metatags where blog_id = '$content_type_id'";
          $result = mysql_query($sql);
          $this->row_exists = (mysql_num_rows($result) > 0);

          if ($this->row_exists)
          $sql = sprintf("update blog_metatags set title='%s', description='%s', keywords='%s' where blog_id = '%s'",
          $title, $description, $keywords, $blog_id);
          else
          $sql = sprintf("insert into blog_metatags (title, description, keywords, blog_id) values ('%s', '%s', '%s' , '%s')",
          $title, $description, $keywords, $blog_id);

          $result = mysql_query($sql);
          return ($result);
         */
    }

    function select_editors($id) {
        $blog_id = $id;
        $action = $_POST['submit'];
        $sql = 'select u.id, concat(firstname," ", surname) as name, user_type_id, coalesce(be.status,0) status ' .
                "from user u left outer join blog_editors be on u.id = user_id and blog_id = '$id'" .
                'where u.id > 1 and account_status = 1';
        $result = mysql_query($sql);

        if (mysql_num_rows($result) > 0) {
            echo "<div id='admin-page-content'>";
            echo '<form method="post" action="blogs.php">';
            echo '<table>';
            echo '<tr><th>Name</th><th>Can Edit</th><th>Receive Notification</th></tr>';
            while ($row = mysql_fetch_array($result)) {
                $status = $row['status'];
                $can_edit = ($status > 0) ? ' checked="checked" ' : '';
                $notified = ($status > 1) ? ' checked="checked" ' : '';
                printf('<tr><td>%s</td><td><input type="checkbox" name="edit[%s]" %s /></td><td><input type="checkbox" name="notify[%s]" %s/></td></tr>', $row['name'], $row['id'], $can_edit, $row['id'], $notified);
            }
            echo '</table>';
            echo '<input type="hidden" name="blog_id" value="' . $blog_id . '">';
            echo '<input type="submit" name="submit_select_editors" value="submit">';
            echo '</form>';
            echo '</div>';
        }
    }

    function process_select_editors($id) {
        $blog_id = $_POST['blog_id'];
        $action = $_POST['submit'];
        $editor_list = '0';
        $nofified_list = '0';
        if (is_array($_POST['edit']))
            $editor_list = implode(',', array_keys($_POST['edit']));
        if (is_array($_POST['notify']))
            $nofified_list = implode(',', array_keys($_POST['notify']));

        $update_sql = "update blog_editors set status = ( 0 + (user_id in ($editor_list)) + (user_id in ($nofified_list))) where blog_id = $blog_id";
        $update_result = mysql_query($update_sql);

        $insert_sql = "insert into blog_editors (user_id, blog_id, status) select id, $blog_id,    (0 + (id in ($editor_list)) + (id in ($nofified_list))) " .
                "from user where id > 1 and id not in (select user_id from blog_editors where blog_id = $blog_id)";
        $insert_result = mysql_query($insert_sql);
    }

    function get_crumbs($page) {
        if ($page == '')
            return "<b>{$this->single_name} Admin</b>";
        else
            return "<a href='blogs.php'>{$this->single_name} Admin</a> > <b>$page</b>";
    }

    function show_comment_count($id) {
        $count_sql = "select count(*) as comm_count from blog_comment bc join blog_posts bp on bp.id = blog_post_id where blog_id = '$id' and comment_status = 0";
        $count_result = mysql_query($count_sql);
        $count_row = mysql_fetch_array($count_result);
        $count = $count_row['comm_count'];
        echo "<div style='float:left; background: none; padding: 9px 10px 0; color: #fff'>";
        echo "Comments needing approval: $count";
        echo "</div>";
    }

}

$template = new blogs();

$main_page = 'index.php';
$main_title = 'Return to main page';


$admin_tab = "blog_admin";

include ("../../../admin/template.php");
?>


