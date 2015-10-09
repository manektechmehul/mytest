<?php

function sanitize($html) {
    return strip_tags($html);
}

function highlightWords($text, $words) {
    /*     * * loop of the array of words ** */
    foreach ($words as $word) {
        // highlighting a word - OUTSIDE of html tags - so as not to break images ect
        $text = preg_replace('/(?![^<>]*>)' . preg_quote($word, "/") . '/i', '<span class="highlight_word">$0</span>', $text);
    }
    /*     * * return the text ** */
    return $text;
}

function process_comment($post_id, $blog_title, $post_title, $post_link) {
    $message = '';
    $comment_name = sanitize($_REQUEST['comment_name']);
    $comment = sanitize($_REQUEST['comment']);
    $captcha = sanitize($_REQUEST['captcha']);

    if (($comment_name == '') || ($comment == '') || ($captcha == '')) {
        $message .= '<p>The following fields are required and were left blank</p>';
        $message .= '<ul>';
        if ($comment_name == '')
            $message .= '<li>Name</li>';
        if ($comment == '')
            $message .= '<li>Comment</li>';
        if ($captcha == '')
            $message .= '<li>Captcha</li>';
        $message .= '</ul><a href="javascript:history.go(-1)">back</a>';
        $_SESSION['form_id'] = $_SESSION['form_id'] - 1;
    }
    else {
        require 'php/securimage/securimage.php';
        $img = new securimage();

        if ($img->check($captcha)) {
            $timestamp = date('Y-m-d H:i:s.000');
            $comment_status = (BLOG_COMMENTS_NEED_APPROVAL == 1) ? 0 : 1;
            $new_comment_sql = 'insert into blog_comment (name, comment, datestamp, blog_post_id, comment_status) values ' .
                    "('$comment_name', '$comment', '$timestamp', '$post_id', '$comment_status')";
            $new_comment_result = mysql_query($new_comment_sql);
            if (!$new_comment_result)
                $message = '<p>Sorry, there has been a problem submitting your comment</p>';
            else {
                $notifications_sql = 'select email from blog_posts bp join blog_editors be on  be.blog_id = bp.blog_id ' .
                        'join user u on user_id = u.id where bp.id = ' . $post_id . ' and be.status = 2';
                $notifications_result = mysql_query($notifications_sql);
                while ($notifications_row = mysql_fetch_array($notifications_result)) {
                    $email_message = sprintf("A comment has been made on a blog posting\n \nBlog Title: %s\n \nPost Title: %s\n \n", $blog_title, $post_title);
                    $to_email = $notifications_row['email'];

                    $headers = 'From: ' . SITE_NAME . '<' . SITE_CONTACT_EMAIL . '>' . "\r\n";
                    mail($to_email, $blog_title, $email_message, $headers);
                }
                if (BLOG_COMMENTS_NEED_APPROVAL == 1)
                    $message = '<p>Thank you for your comment. We will process your comment shortly</p>';
                else
                    $message = '<p>Thank you for your comment</p>';
                $message .= '<a href="' . "$post_link" . '">back</a>';
            }
        }
        else {
            $message = '<p>Sorry the security code you entered did not match</p>';
            $message .= '<a href="' . "$post_link" . '">back</a>';
        }
    }
    return $message;
}

function process_email($blog_id, $blog_title, $post_title, $post_link) {
    $message = '';
    $from_name = sanitize($_REQUEST['from_name']);
    $from_email = sanitize($_REQUEST['from_email']);
    $to_email = sanitize($_REQUEST['to_email']);
    $email_message = sanitize($_REQUEST['message']);

    if (($from_name == '') || ($from_email == '') || ($to_email == '')) {
        $message .= '<p>The following fields are required and were left blank</p>';
        $message .= '<ul>';
        if ($from_name == '')
            $message .= '<li>Your Name</li>';
        if ($from_email == '')
            $message .= '<li>Your Email</li>';
        if ($to_email == '')
            $message .= '<li>Friend\'s Email</li>';
        $message .= '</ul>';
        $message .= '<a href="javascript:history.go(-1)">back</a>';
        $_SESSION['email_id'] = $_SESSION['email_id'] - 1;
    }
    else {
        $email_message = sprintf("%s has sent you a link to a blog posting\n \nBlog Title: %s\n \nPost Title: %s\n \n" .
                "Post Link: %s\n \nMessage: %s\n \n", $from_name, $blog_title, $post_title, $post_link, $email_message);

        $headers = "From: $from_name <$from_email>\r\n";


        if (!mail($to_email, $blog_title, $email_message, $headers))
            $message = '<p>Sorry, there has been a problem submitting your email</p>';
        else {
            $message = '<p>Your message has been sent</p><a href="' . $post_link . '">Back to post</a>';
        }
    }
    return $message;
}

function get_single_post($blog_id, $post_page_name) {
    $post_sql = "select bp.id, bp.title, bp.page_name, bp.posting, UNIX_TIMESTAMP(bp.datestamp) datestamp, firstname, surname " .
            " from blog_posts bp join blogs b on b.id = blog_id join user u on author_id = u.id where blog_id = '$blog_id' " .
            " and bp.page_name = '$post_page_name' and b.published = 1 and bp.published = 1";
    $post_result = mysql_query($post_sql);
   
    $post_row = mysql_fetch_array($post_result);
    $post = array(
        'id' => $post_row['id'],
        'title' => $post_row['title'],
        'firstname' => $post_row['firstname'],
        'surname' => $post_row['surname'],
        'post' => $post_row['posting'],
        'date' => $post_row['datestamp'],
    );
    return $post;
}

function get_posts($blog_id, $page_name, $blog_page_name, $blog_archive, $archive_year = 0, $search = false, $search_term = '') {

    $post_sql = "select bp.id, bp.title, bp.page_name, bp.posting, UNIX_TIMESTAMP(bp.datestamp) datestamp, firstname, surname, " .
            "(select count(*) from blog_comment bc where blog_post_id = bp.id and comment_status > 0) comment_count " .
            " from blog_posts bp join blogs b on b.id = blog_id join user u on author_id = u.id where blog_id = '$blog_id' and b.published = 1 and bp.published = 1 ";
    if ($blog_archive) {
     //   $post_sql .= " AND datestamp < DATE_SUB(NOW(), INTERVAL " . $archive_year . " YEAR) AND datestamp > DATE_SUB(NOW(), INTERVAL " . ($archive_year + 1) . " YEAR)  ";
            $post_sql .= " AND datestamp BETWEEN CONCAT(YEAR(NOW()) -" . $archive_year . " , '-01-01') AND CONCAT(YEAR(NOW()) -" . $archive_year  . ", '-12-31')  ";
 
    }

    if ($search) {
        $post_sql .= " AND (posting LIKE '%" . $search_term . "%' OR bp.title LIKE '%" . $search_term . "%') ";
    }
    $post_sql .= " order by datestamp desc, title ";

    if (!$blog_archive && !$search) {
        // limit by number in homec config
        $post_sql .= " limit 0, " . BLOGS_NUMBER_OF_LATEST_POSTS;
    }
    $post_result = mysql_query($post_sql);

    $posts = array();
    if (mysql_num_rows($post_result) > 0) {
        $date_group = '';
        $new_group = false;
        while ($post_row = mysql_fetch_array($post_result)) {
            if ($blog_archive) {
                $current_date_group = date("F Y", $post_row['datestamp']);
                $new_group = ($date_group !== $current_date_group);
                if ($new_group)
                    $date_group = $current_date_group;

                $post_link = '/' . $page_name . '/' . $blog_page_name . '/archive/' . $post_row['page_name'];
            }
            else
                $post_link = '/' . $page_name . '/' . $blog_page_name . '/' . $post_row['page_name'];

            $post = $post_row['posting'];

            // if in a search or archive - hide  
            // highlight search terms
            if ($search) {
                // in search we show the whole post - and highlight the search terms
                $title = highlightWords($post_row['title'], array($search_term));
                $posting = $post;
                $posting = highlightWords($posting, array($search_term));
            } else if ($blog_archive) {
                // in archive - we strip tags and show the first 200 chars
                $title = $post_row['title'];
                $posting = strip_tags($post);
                $posting = $posting;
            } else {
                // other wise , just show the whole thing
                $title = $post_row['title'];
                $posting = $post_row['posting'];
            }



            $posts[] = array(
                'title' => $title,
                'firstname' => $post_row['firstname'],
                'surname' => $post_row['surname'],
                'post' => $posting,
                'date' => $post_row['datestamp'],
                'comments' => $post_row['comment_count'],
                'link' => $post_link,
                'newgroup' => $new_group,
                'group' => $date_group,
                'email_post_link' => "/$page_name/$blog_page_name/email/" . $post_row['page_name'],
            );
        }
    }
    return $posts;
}

function get_blog_archive_years($blog_id) {
    // get the number of year the blog goes back from. assumes entries in every year !
    $sql = "SELECT YEAR(bp.datestamp) AS YEAR FROM blog_posts bp JOIN blogs b ON b.id = blog_id 
        WHERE blog_id = '" . $blog_id . "' AND b.published = 1 AND bp.published = 1 GROUP BY YEAR";
    $years = db_get_rows($sql);
    return $years;
}

function createArchivePagination($total_pages, $current_page, $base_url) {
    $out .= "<ul class='archive_pagination'>";
    if ($current_page == 0) {
        $back_arrow_url = "#";
    } else {
        $back_one = $current_page - 1;
        $back_arrow_url = $base_url . (int) $back_one;
    }
    $out .= "<li><a href='" . $back_arrow_url . "'><</a></li>";
    $i = 1;
    while ($i <= $total_pages) {

        if ($i == $current_page + 1) {
            $class = "archive_on";
        } else {
            $class = "";
        }
        $out .= "<li><a href='" . $base_url . ($i - 1) . "'  class={$class}  >" . $i . "</a></li>";
        $i++;
    }
    if (($current_page + 1) == $total_pages) {
        $back_arrow_url = "#";
    } else {
        $temp = $current_page + 1;
        $back_arrow_url = $base_url . $temp;
    }
    $out .= "<li><a href='" . $back_arrow_url . "'>></a></li>";
    $out .= "</ul>";
    return $out;
}

function get_comments($post_id) {
    $comment_sql = "select name, comment, UNIX_TIMESTAMP(datestamp) datestamp from blog_comment " .
            "where blog_post_id = '$post_id' and comment_status > 0";
    $comment_result = mysql_query($comment_sql);

    $comments = array();
    while ($comment_row = mysql_fetch_array($comment_result)) {
        $comments[] = array(
            'name' => $comment_row['name'],
            'date' => $comment_row['datestamp'],
            'comment' => $comment_row['comment'],
        );
    };


    return $comments;
}

?>