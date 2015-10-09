<?php

include '../../../admin/classes/template.php';

class posts extends template {

    function posts() {
        $this->template();
        $this->table = 'blog_posts';
        $this->group_name = 'Posts';
        $this->single_name = 'Post';
        $this->singular = 'a';
        $this->hideable = true;
        $this->order_clause = 'datestamp desc';
        $this->has_page_name = true;
        $this->invalid_page_names = array('archive');
        $this->fail_auth_location = '/modules/blog/admin/blogs.php';
        //$this->ordered = true; 
        $blog_id = $_GET['blog'];
        //$this->where_clause = " where blog_id = $blog_id ";
        $this->parent_id = $blog_id;
        $this->parent_field = 'blog_id';
        $this->parent_id_name = 'blog';
        //$this->list_top_text = sprintf ("<a href=\"categories.php\" onMouseOut=\"MM_swapImgRestore()\" onMouseOver=\"MM_swapImage('defaultcategories','','/admin/images/buttons/cmsbutton-Categories-over.gif',0)\"><img style='border:none' src='./images/buttons/cmsbutton-Categories-off.gif' name='defaultcategories'></a>", $PHP_SELF);
        $this->ToolbarSet = 'Default';

        $this->buttons = array(
            'edit' => array('text' => 'add', 'type' => 'standard_edit'),
            'comments' => array('type' => 'button', 'text' => 'manage comments', 'pattern' => '/modules/blog/admin/comments.php?post=%s'),
            'hide' => array('text' => 'hide', 'type' => 'standard_hide'),
            'delete' => array('text' => 'delete', 'type' => 'standard_delete'),
            'comment count' => array('type' => 'function', 'function' => 'show_comment_count'),
        );

        $this->fields = array(
            'title' => array('name' => 'Title&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;', 'formtype' => 'text', 'list' => true, 'required' => true, 'primary' => true),
            'description_seo' => array('name' => 'Description<br>(for SEO)', 'formtype' => 'lookup', 'not_field' => true, 'function' => 'getSEODescription'),
            'clear_custom_seo' => array('name' => 'Clear Custom SEO<br> (Check this box then submit)', 'formtype' => 'checkbox', 'not_field' => true),
            'datestamp' => array('name' => 'Post Date', 'formtype' => 'date', 'list' => false, 'required' => true, 'size' => 2),
            'page_name' => array('name' => 'Page Name', 'formtype' => 'page_name', 'list' => false, 'required' => false),
            'posting' => array('name' => 'Main Content', 'formtype' => 'fckhtml', 'list' => false, 'required' => true),
            'author_id' => array('name' => 'Author', 'formtype' => 'hidden', 'function' => 'get_author_id', 'mode' => 'keepfirst', 'list' => false, 'required' => true)
        );

        //$this->links = array( 'category' => array('link_table' => 'case_study_category', 'table' => 'category', 'name' => 'title') );
    }

    function get_form_data() {
        $this->module_id = db_get_single_value("select id from module where constant = 'SITE_HAS_BLOG'", "id");
        parent::get_form_data();
    }

    function getSEODescription($id) {
        $module_id = $this->module_id;
        if (isset($_GET['id'])) {
            $id = $_GET['id'];
        }
        $sql = "SELECT description FROM metatag WHERE ext_id ='{$id}' AND module_id = '{$module_id}'";
        $desc = db_get_single_value($sql);
        return "<textarea rows=4 cols=50 name=description_seo>{$desc}</textarea>";
    }

    function process_submit($id, $parent_id = false) {
        $module_id = $this->module_id;
        $result = parent::process_submit($id, $parent_id);
        if ($id == '') {
            // insert into tags
            $id = $this->id;
            $sql = "insert into `metatag` (`ext_id`, `title`, `description`, `keywords`, `module_id`)
values ( '{$id}', '{$_REQUEST['title']}', '{$_REQUEST['description_seo']}', 'keywords', '{$module_id}'); ";
        } else {

            // check if this item already has a tage entry
            $count = db_get_single_value("SELECT count(*) FROM metatag WHERE ext_id = '{$id}' AND module_id = '{$module_id}'");
            if ($count > 0) {
                // update tags           
                $sql = " UPDATE `metatag` SET `title` = '{$_REQUEST['title']}', `description` = '{$_REQUEST['description_seo']}', `keywords` = 'keywords'  where module_id ='{$module_id}'  and ext_id = '{$id}'";
            } else {
                $sql = "insert into `metatag` (`ext_id`, `title`, `description`, `keywords`, `module_id`)
values ( '{$id}', '{$_REQUEST['title']}', '{$_REQUEST['description_seo']}', 'keywords', '{$module_id}'); ";
            }
        }
        mysql_query($sql);
        if ($_REQUEST['clear_custom_seo'] == '1') {
            echo $_REQUEST['clear_custom_seo'];
            mysql_query("delete from metatag where ext_id = '{$id}' AND module_id = '{$module_id}'");
        }
        return $result;
    }

    function get_author_id() {
        global $session_user_id;
        return $session_user_id;
    }

    function get_crumbs() {
        return "<a href='blogs.php'>Blog Admin</a> > <b>{$this->single_name} Admin</b>";
    }

    function show_comment_count($id) {
        $count_sql = "select count(*) as comm_count from blog_comment where blog_post_id = '$id' and comment_status = 0";
        $count_result = mysql_query($count_sql);
        $count_row = mysql_fetch_array($count_result);
        $count = $count_row['comm_count'];
        echo "<div style='float:left; background: none; padding: 9px 10px 0; color: #fff'>";
        echo "Comments needing approval: $count";
        echo "</div>";
    }

    function is_authorised() {

        global $session_user_id, $session_user_type_id;

        if ($session_user_type_id == 1)
            return true;
        else {
            $status = db_get_single_value("select status from blog_editors where blog_id = '{$this->parent_id}' and user_id = '$session_user_id'", 'status');
            return ($status >= 1);
        }
    }

}

$template = new posts();

$main_page = 'index.php';
$main_title = 'Return to main page';


$admin_tab = "blog_admin";

include ("../../../admin/template.php");
?>


