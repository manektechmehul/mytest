<?php
$member_page_root = '/members/';
$member_pages = array();
if ($logged_in) {
//		$memberName = db_get_single_value("select trim(concat(firstname,' ', surname)) as `name` from member_user where id = $session_member_id", 'name');
    $member_pages_sql = "select id, title, page_name from member_page where published = 1 and parent_id = 0 order by order_num";
    $member_pages_result = mysql_query($member_pages_sql);
    $first = true;
    while ($member_pages_row = mysql_fetch_array($member_pages_result)) {
        $member_page_link = $member_page_root . $member_pages_row['page_name'];
        $member_sub_pages = array();
        $onMemberPage = ($page_name == 'members') && (($article_name == $member_pages_row['page_name']) || (($article_name == '') && $first));
        if ($onMemberPage) {
            $parent_id = $member_pages_row['id'];
            $member_sub_pages_sql = "select title, page_name from member_page where published = 1 and parent_id = $parent_id order by order_num";
            $member_sub_pages_result = mysql_query($member_sub_pages_sql);
            while ($member_sub_pages_row = mysql_fetch_array($member_sub_pages_result)) {
                $member_sub_pages[] = array(
                    'link' => $member_page_link . '/' . $member_sub_pages_row['page_name'],
                    'on' => ($page_name == 'members') && ($name_parts[2] == $member_sub_pages_row['page_name']),
                    'name' => $member_sub_pages_row['title'],
                );
            }
        }
        else
            $member_sub_pages = '';
        $memberlink = ($first) ? '/members' : $member_page_root . $member_pages_row['page_name'];
        $member_pages[] = array(
            'link' => $memberlink,
            'on' => $onMemberPage,
            'name' => $member_pages_row['title'],
            'subpages' => $member_sub_pages
        );
        $first = false;
    }
    $member_sub_pages = ($article_name == 'noticeboard') ? $moduleObject->GetSubmenu($member_page_root . 'noticeboard') : '';
    $member_sub_pages = ($article_name == 'documents') ? $moduleObject->GetSubmenu($member_page_root . 'documents') : '';
    $member_pages[] = array('name' => 'Notice Board', 'link' => $member_page_root . 'noticeboard', 'on' => ($article_name == 'noticeboard'), 'subpages' => $member_sub_pages);
    $member_pages[] = array('name' => 'Documents', 'link' => $member_page_root . 'documents', 'on' => ($article_name == 'documents'), 'subpages' => $member_sub_pages);
    $member_pages[] = array('name' => 'Log out', 'link' => '/logout', 'on' => ($name_parts[1] == 'logout'));
    $smarty->assign('membermenu', $member_pages);
//		$smarty->assign('membername', $memberName);
}
$smarty->assign('logged_in', $logged_in);
$filters['loginbox'] = array('search_string' => '/<!-- CS login start -->(.*)<!-- CS login end -->/s',
    'replace_string' => '{include file="subtemplates/login.tpl"}');
