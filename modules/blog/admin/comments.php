<?php

include '../../../admin/classes/template.php';

class comments extends template {

    function comments() {
        $this->template();
        $this->table = 'blog_comment';
        $this->group_name = 'Comments';
        $this->single_name = 'Comment';
        $this->singular = 'a';
        $this->hideable = true;
        $post_id = $_GET['post'];
        $this->fail_auth_location = '/modules/blog/admin/blogs.php';
        //$this->where_clause = " where blog_id = $blog_id ";
        $this->parent_id = $post_id;
        $this->parent_field = 'blog_post_id';
        $this->parent_id_name = 'post';
        //$this->list_top_text = sprintf ("<a href=\"categories.php\" onMouseOut=\"MM_swapImgRestore()\" onMouseOver=\"MM_swapImage('defaultcategories','','/admin/images/buttons/cmsbutton-Categories-over.gif',0)\"><img style='border:none' src='./images/buttons/cmsbutton-Categories-off.gif' name='defaultcategories'></a>", $PHP_SELF);
        $this->ToolbarSet = 'Default';

        $this->custom_buttons = array(
            'one' => array('text' => 'comments', 'pattern' => '/modules/blog/admin/comments.php?post=%s'),
        );

        $this->fields = array(
            'name' => array('name' => 'Name', 'formtype' => 'text', 'list' => true, 'required' => true, 'primary' => true),
            'datestamp' => array('name' => 'Post Date', 'formtype' => 'staticdate', 'list' => false, 'required' => true, 'size' => 2),
            //'thumb' => array('name' => 'Thumbnail', 'formtype' => 'image', 'list' => false, 'required' => true, 'size' => 2),
            //'category' => array('name' => 'Categories', 'formtype' => 'checklink', 'list' => false, 'required' => true, 'not_field' => true, 'link' => 'category'),
            'comment' => array('name' => 'Comment', 'formtype' => 'textarea', 'list' => false, 'rows' => 3, 'cols' => 60, 'required' => true),
            'comment_status' => array('name' => 'Main Content', 'formtype' => 'hidden', 'list' => false, 'function' => 'get_comment_status')
        );

        //$this->links = array( 'category' => array('link_table' => 'case_study_category', 'table' => 'category', 'name' => 'title') );
    }

    function get_comment_status() {
        return 1;
    }

    function get_crumbs() {
        $blog_id = db_get_single_value("select blog_id from blog_posts where id = '{$this->parent_id}'", 'blog_id');
        return "<a href='blogs.php'>Blog Admin</a> > <a href='posts.php?blog=$blog_id'>Post Admin</a> > <b>{$this->single_name} Admin</b>";
    }

    function list_items($parent_id = false) {
        $result = true;

        $approve = $_REQUEST['approve'];
        $id = $_REQUEST['id'];

        if ($approve) {
            $status = ($approve == 'yes') ? 1 : 0;
            $approve_sql = "update blog_comment set comment_status = '$status' where id = '$id'";
            $approve_result = mysql_query($approve_sql);
        }

        $content_sql = "SELECT * FROM blog_comment where blog_post_id = $parent_id";

        $content_result = mysql_query($content_sql);

        if ($list_top_text)
            echo $list_top_text;

        $row_count = mysql_num_rows($content_result);

        echo "<div id='admin-page-content'>";
        if ($row_count) {
            $row_num = 1;

            $approved = '';
            $unapproved = '';

            while ($content_row = mysql_fetch_array($content_result)) {
                $out = '<div>Left by: ' . $content_row['name'] . '</div>';
                $out .= '<div>Date: ' . $content_row['datestamp'] . '</div>';
                $out .= '<div>Comment: ' . $content_row['comment'] . '</div>';

                $out .= '<div>';
                if (BLOG_COMMENTS_NEED_APPROVAL) {
                    if ($content_row['comment_status'] == 0)
                        $out .= sprintf("<a href='%s?post=%s&approve=yes&id=%s'>Approve</a> | ", $this->page_self, $parent_id, $content_row["id"]);
                    else
                        $out .= sprintf("<a href='%s?post=%s&approve=no&id=%s'>Remove</a> | ", $this->page_self, $parent_id, $content_row["id"]);
                }
                $out .= sprintf("<a href='%s?post=%s&edit_item=yes&id=%s'>edit</a> | ", $this->page_self, $parent_id, $content_row["id"]);
                $out .= sprintf("<a href='%s?post=%s&delete_item=yes&id=%s' onclick='return confirm(\"Are you sure you want to delete this comment\")'>delete</a>", $this->page_self, $parent_id, $content_row["id"]);
                $out .= "</div><br/>";

                if (BLOG_COMMENTS_NEED_APPROVAL) {
                    if ($content_row['comment_status'] == 0)
                        $unapproved .= $out;
                    else
                        $approved .= $out;
                }
                else
                    $approved .= $out;

                $row_num = $row_num + 1;
            }

            if (BLOG_COMMENTS_NEED_APPROVAL) {
                if ($unapproved == '')
                    $unapproved = '<p>No comments awaiting approval</p>';
                if ($approved == '')
                    $approved = '<p>No approved comments</p>';
                echo "<div class='item_rows'>\n<h4>Require Approval</h4>\n$unapproved\n</div>";
                echo "<div class='item_rows'>\n<h4>Approved</h4>\n$approved\n</div>";
            }
            else {
                if ($approved == '')
                    $approved = '<p>No comments</p>';
                echo "<div class='item_rows'>\n$approved\n</div>";
            }
        }
        else
            $result = false;

        //echo "<div style='height:31px;width:100%;background:url(/admin/images/lightbox-bk.gif)'>";
        if (($parent_id) && ($this->parent_child)) {
            //echo "<div class='subsectionbutton-row'>";
            $add_level = 'sub';
        } else {
            //echo "<div class='sectionbutton-row'>";
            $add_level = '';
        }
        $parentpart = ($parent_id) ? "&{$this->parent_id_name}=$parent_id" : '';
        if (($this->max_items == 0) || ($row_count < $this->max_items)) {
            echo "<a href=\"{$this->page_self}?edit_item=yes$parentpart\" onmouseout='button_off(this)' onmouseover='button_over(this)'>";
            printf("<img src='/admin/images/buttons/cms%sbutton-Add_%s_%s-off.gif' alt='Add %s %s' name='cms%buttonadd%s%s'/></a>", $add_level, $this->singular, $this->single_name, $this->singular, $this->single_name, $add_level, $this->singular, $parent_id);
        }

        //echo "</div>";
        echo "</div>";
        return $result;
    }

    function is_authorised() {

        global $session_user_id, $session_user_type_id;

        if ($session_user_type_id == 1)
            return true;
        else {
            $blog_id = db_get_single_value("select blog_id from blog_posts where id = '{$this->parent_id}'", 'blog_id');
            $status = db_get_single_value("select status from blog_editors where blog_id = '$blog_id' and user_id = '$session_user_id'", 'status');
            return ($status >= 1);
        }
    }

}

$template = new comments();

$main_page = 'index.php';
$main_title = 'Return to main page';

$admin_tab = "blog_admin";

if ($template->parent_id)
    include ("../../../admin/template.php");
else
    header("Location: /modules/blog/admin/blogs.php");
?>


