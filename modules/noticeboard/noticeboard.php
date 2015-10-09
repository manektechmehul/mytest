<?php
require $base_path.'/modules/noticeboard/class/noticeboard.php';

/* @var $noticeBoard NoticeBoard */
$noticeBoard = new NoticeBoard('noticeboard');
$moduleObject = $noticeBoard;



$smarty->assign('noticeBoardBasePage', '/discussions');

if (FIREBUG == 1) FB::info("\$name_parts = ".  var_export($name_parts, true));

$showGeneral = false;

if (isset($_POST['action']) && ($_POST['action'] == 'add notice')) {
    $message = $noticeBoard->ProcessNoticeSubmission();
    if ($message)
        echo $message;
}

if (!empty($name_parts[1]))
{
    if ($name_parts[1] == 'notifications') {        
        include 'notifications.php';
    }
    else if ($name_parts[1] == 'notice')  {
        if (!empty($name_parts[2]))
        {
            $noticePage = $name_parts[2];
            if (isset($_POST['action']))
                $message = $noticeBoard->ProcessCommentSubmission($noticePage, $member);
            if (!empty($message))
                echo $message;
            else
                $title = $noticeBoard->DisplayItemByPageName($noticePage);
        }
        else
            $showGeneral = true;
    }
    else {
        $title = $noticeBoard->DisplayListByCategory($name_parts[1]);
        $noticeBoard->ShowForm($name_parts[1]);
   }
}
else {
    echo $content_row['body'];
    $showGeneral = true;
}
    
if ($showGeneral) {
    $title = 'Discussions';
    //$noticeBoard->DisplayListByCategory('general');
    //$noticeBoard->ShowForm();
    
    $noticeBoard->ShowCategories();
}

/*
$noticeBoardFormData = $_POST;
$showForm = true;
$noticeBoardPagePart = $name_parts[1];
$showNotice = false;

if ($noticeBoardPagePart == 'notice')
{
    $notice = is_numeric($name_parts[2]) ? $name_parts[2] : 0;
    $showNotice = ($notice > 0);
}

if ($showNotice) {
    $notices = $noticeBoard->getNotice($notice);
    $smarty->assign('notice', $notice);
    $smarty->display($base_path.'/modules/noticeboard/template/notices.tpl');
}
else {
    $category = is_numeric($noticeBoardPagePart) ? $noticeBoardPagePart : 1;
    $noticeBoardCategories = $noticeBoard->getNoticecategories();

    $title = 'Notice Board';
    if ($category > 1)
        //$title .= ': '.$noticeBoard->getCategoryName($category);
        $title = $noticeBoard->getCategoryName($category);

    if (!empty($_POST['action'])) {
        // validate form and if valid process
        if ($noticeBoard->validateForm($noticeBoardFormData))
        {
            $postID = $noticeBoard->processData($noticeBoardFormData, $session_member_id);
            $noticeBoard->emailData($postID);
            $noticeBoardFormData = array();
        }
    }

    

    if ($showForm) {
        //$smarty = new Smarty();
        //$smarty->debugging = true;
        $smarty->assign('noticeBoardCategories', $noticeBoardCategories);
        $smarty->assign('noticeBoardFormData', $noticeBoardFormData);
        $smarty->assign('dataError', $noticeBoard->errors);
        $smarty->assign('errorMessage', $noticeBoard->errorMessages);
        $smarty->display($base_path.'/modules/noticeboard/template/form.tpl');
    }
}
 * 
 */