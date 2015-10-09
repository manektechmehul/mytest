<?php

function blogs_sidebox() {
    $sql = 'select * from blogs where blog_status = 1 and published = 1 order by order_num';
    $result = mysql_query($sql);
    $blog_list = array();
    while ($row = mysql_fetch_array($result)) {
        $blog_list[] = array(
            'title' => $row['title'],
            'link' => $base_path . '/blog/' . $row['page_name'],
        );
    }
    return $blog_list;
}

function blogs_latest_posts($postcount, $blog_id) {
    $sql = 'select bp.title, bp.datestamp, concat(b.page_name, \'/\', bp.page_name) as path from blog_posts bp join blogs b ' .
            'on b.id = bp.blog_id where b.published = 1 and bp.published = 1 and b.id = ' . $blog_id . ' order by bp.datestamp desc, title limit ' . $postcount;
    $result = mysql_query($sql);
    $post_list = array();
    while ($row = mysql_fetch_array($result)) {
        $post_list[] = array(
            'title' => $row['title'],
            'date' => $row['datestamp'],
            'link' => $base_path . '/blog/' . $row['path'],
        );
    }
    return $post_list;
}

function buildarchivelistsidebox($postcount, $blog_id, $year) {
    // we use the current number of posts so we can skip over them for the archive..

    $sql = 'select bp.title, bp.`posting`, bp.datestamp, concat(b.page_name, \'/\', bp.page_name) as path , MONTH(datestamp) as month_number
from blog_posts bp join blogs b on b.id = bp.blog_id 
where b.published = 1 and bp.published = 1 and b.id = 5 
order by YEAR(datestamp) desc, MONTH(datestamp) desc';
    // limit '.$postcount;
    $result = mysql_query($sql);
    $post_list = array();
    while ($row = mysql_fetch_array($result)) {

        $post_list[] = array(
            'title' => $row['title'],
            'posting' => $row['posting'], // trunc this
            'date' => $row['datestamp'],
            'link' => $base_path . '/blog/' . $row['path'],
            'month_number' => $row['month_number'],
            'month_name' => date("F", mktime(0, 0, 0, $row['month_number'], 10))
        );
    }

    return $post_list;
}

$blog_list = blogs_sidebox();

if (count($blog_list) > 1)
    $smarty->assign('blog_list', $blog_list);

if (isset($blog_id) && ($blog_id)) {
    // BLOGS_NUMBER_OF_LATEST_POSTS
    $blog_items = db_get_single_value("select value from defaults where `name` = 'blogs_post_count'");
    $post_list = blogs_latest_posts($blog_items, $blog_id);

    // $year = "need to code this in";
    // $archive_items = buildarchivelistsidebox($blog_items,$blog_id,$year);
    // $smarty->assign('archive_items', $archive_items); 
    $smarty->assign('post_list', $post_list);
    $smarty->assign('blog_archive_link', "/$page_name/$blog_page_name/archive");
}




$blogs_sidebox_template_file = "$base_path/modules/blog/templates/sidebox.tpl";
$filters['blog_list'] = array('search_string' => '/<!-- CS blog list start -->(.*)<!-- CS blog list end -->/s',
    'replace_string' => '{include file="file:' . $blogs_sidebox_template_file . '"}');