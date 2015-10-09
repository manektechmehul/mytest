<?php
$module_id = db_get_single_value("SELECT id FROM module WHERE constant = 'SITE_HAS_BLOG'");
$blog_url =  '/' . $name_parts[0] . '/' . $name_parts[1];

include_once 'classes/blogs.php';

$blog_page_name = '';
$post_page_name = '';
$show_blog_list = false;
$blog_archive = false;
// get a list of blogs
$smarty->assign('pageName', $currentPageUrl);
$blog_sql = 'select * from blogs where blog_status = 1 and published = 1';
$blog_result = mysql_query($blog_sql);
// if the user lands on the root location - forward to the home of the first blog if there is only one
if (!is_array($name_parts) || $name_parts[1] == '') {
    if (mysql_num_rows($blog_result) == 1) {
        $blog_row = mysql_fetch_array($blog_result);
        $blog_page = $blog_row['page_name'];
        header("Location: /$page_name/$blog_page");
        exit();
    }
} else {
    $part_count = count($name_parts);
    $blog_page_name = '';
    $post_page_name = '';

    if ($part_count > 1) {
        $blog_page_name = $name_parts[1];
        $virtual_page = "/$page_name/$blog_page_name";
    }

    if ($part_count > 2) {
        if ($name_parts[2] == 'archive') {
            $blog_archive = true;
            if ($part_count > 3) {
                $post_page_name = $name_parts[3];
            }
        } else if ($name_parts[2] == 'results') {
            //  $email_post = true;
            //   $post_page_name = $name_parts[3];

            echo ' Search Results for :`' . $_GET['keywords'] . '`';
            $search_results = true;
        } else if (($name_parts[2] == 'email') && ($part_count > 3)) {
            $email_post = true;
            $post_page_name = $name_parts[3];
        }
        else
            $post_page_name = $name_parts[2];
    }
}

$smarty->assign('search_results', $search_results);
$smarty->assign('blog_archive_link', '/' . $name_parts[0] . '/' . $name_parts[1] . '/archive');

// Blog selected
if ($blog_page_name) {
    $blog_sql = "select * from blogs where page_name = '$blog_page_name' and published = 1";
    $blog_result = mysql_query($blog_sql);

    if (mysql_num_rows($blog_result) > 0) {
        $blog_row = mysql_fetch_array($blog_result);
        $blog_id = $blog_row['id'];
        $title = $blog_row['title'];
        if ($post_page_name)
            $post_title = db_get_single_value("select title from blog_posts bp where blog_id = '$blog_id' and page_name = '$post_page_name'", 'title');

        if ($email_post) {
            $sess_email_id = (isset($_SESSION['email_id'])) ? $_SESSION['email_id'] : 1;
            $submit = $_REQUEST['submit_email'];
            $email_id = $_REQUEST['email_id'];
            if ($submit && ($email_id == $sess_email_id)) {
                $message = process_email($blog_id, $title, $post_title, 'http://' . $_SERVER['SERVER_NAME'] . "/$page_name/$blog_page_name/$post_page_name", $post_page_name);
                $smarty->assign('message', $message);
            } else {
                $email_id = $sess_email_id + 1;
                $smarty->assign('email_id', $email_id);
                $smarty->assign('post_title', $post_title);
            }
            $sess_email_id = (isset($_SESSION['email_id'])) ? $_SESSION['email_id'] : 1;
            $_SESSION['email_id'] = $sess_email_id + 1;
        }
        // if its a specific post ... 
        else if ($post_page_name && !$search_results) {
            $post = get_single_post($blog_id, $post_page_name);
            $post_id = $post['id'];
            $sess_form_id = (isset($_SESSION['form_id'])) ? $_SESSION['form_id'] : 1;
            $submit = $_REQUEST['submit_comment'];
            $form_id = $_REQUEST['form_id'];
            if ($submit && ($form_id == $sess_form_id))
                $message = process_comment($post_id, $title, $post_title, "/$page_name/$blog_page_name/$post_page_name");
            else
                $form_id = $sess_form_id + 1;

            $sess_form_id = (isset($_SESSION['form_id'])) ? $_SESSION['form_id'] : 1;
            $_SESSION['form_id'] = $sess_form_id + 1;

            if ($message)
                $smarty->assign('message', $message);
            else {
                $comments = get_comments($post_id);
                $smarty->assign('post', $post);
                $smarty->assign('email_post_link', "/$page_name/$blog_page_name/email/$post_page_name");
                $smarty->assign('comments', $comments);
                $smarty->assign('form_id', $form_id);
            }
            $blogThumb = '/images/sharelogo.jpg';

            $og = array(
                'title' => $post['title'],
                'thumb' => $blogThumb,
                'description' => strip_tags($post['post'])
            );
            $smarty->assign('og', $og);
        } else if ($search_results) {
            // 
            $posts = get_posts($blog_id, $page_name, $blog_page_name, $blog_archive, 0, true, $_GET['keywords']);
            $smarty->assign('posts', $posts);
            // need to highlight post items
        } else {
            // Show list of posts for this blog
            $smarty->assign('blog_archive', $blog_archive);
            $smarty->assign('blog_page_name', $blog_page_name);
            $smarty->assign('blog_title', $blog_row['title']);

            if ($blog_archive) {
                $blog_archive_year = 0;
                if (isset($_GET['archive_year'])) {
                    $blog_archive_year = $_GET['archive_year'];
                }
            }
            $posts = get_posts($blog_id, $page_name, $blog_page_name, $blog_archive, $blog_archive_year);
            if ($blog_archive) {
                $years = get_blog_archive_years($blog_id);
                $archive_pagination = createArchivePagination(count($years), $blog_archive_year, '/' . $page_name . '/' . $blog_page_name . '/archive?archive_year=');
                $smarty->assign('archive_pagination', $archive_pagination);
            }
            $smarty->assign('posts', $posts);
            if (!$blog_archive)
                $smarty->assign('blog_archive_link', '/' . $name_parts[0] . '/' . $name_parts[1] . '/archive');

            $smarty->assign('no_posts', '<p>There are currently no posts in this Blog</p><a href="' . "/$page_name/" . '">All Blogs</a>');
            $show_blog_list = false;
        }
    }
    else
        $show_blog_list = true;
}
else

if ($search_results) {
    $show_blog_list = false;
} else {
    $show_blog_list = true;
}

if ($show_blog_list) {

    $blog_sql = 'select * from blogs where blog_status = 1 and published = 1 order by order_num';
    $blog_result = mysql_query($blog_sql);
    if (mysql_num_rows($blog_result) > 0) {
        while ($blog_row = mysql_fetch_array($blog_result)) {
            $blogs[] = array(
                'title' => $blog_row['title'],
                'link' => '/' . $page_name . '/' . $blog_row['page_name'],
                'description' => $blog_row['description'],
                'page_name' => $blog_row['page_name'],
            );
        }
        $smarty->assign('blogs', $blogs);
    } else {
        
    }
}



        
$smarty->assign('blog_url', $blog_url);        
        
$content_template_file = "$base_path/modules/$module_path/templates/blog.tpl";
$smarty->display("file:$content_template_file");
