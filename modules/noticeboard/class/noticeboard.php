<?php
require $base_path.'/modules/noticeboard/class/model/notice_model.php';
require $base_path.'/modules/noticeboard/class/model/notice_categories_model.php';
require $base_path.'/modules/noticeboard/class/model/notice_comments_model.php';
require $base_path.'/php/classes/page_listmodule.php';

//require_once $base_path.'/php/form_functions.php';


function sanitize ($str)
{
    return strip_tags($str);
}

class NoticeBoard extends PageListModule
{
	public $errors;
	public $errorMessages;
    private $boardListTemplate;

	function  __construct($module_path) {
        global $base_path;
        $this->model = new NoticeModel();
        $this->categoriesModel = new NoticeCategoriesModel();
        $this->commentsModel = new NoticeCommentsModel();
        
        //$this->listTemplateFile = $base_path.'/modules/'.$module_path.'/templates/notices.tpl';
        //$this->singleTemplateFile = $base_path.'/modules/'.$module_path.'/templates/item.tpl';
        $this->listName = 'notices';
        $this->itemName = 'notice';
        $this->boardListTemplate = "$base_path/modules/$module_path/templates/boards.tpl";
        parent::__construct($module_path);
	}

	function DisplayListByCategory($categoryPage)
	{
		global $smarty;
        $category = $this->categoriesModel->GetID($categoryPage);
        if (empty($category))
            $category = 1;
        $categoryItem = $this->categoriesModel->GetItemByPageName($categoryPage);
		$smarty->assign('categoryItem', $categoryItem);
		$items = $this->model->ReadItemsByCategory($category);
		$smarty->assign($this->listName, $items);
		$smarty->assign('no_'.$this->listName, $this->noItemsMessage);
		$smarty->display("file:".$this->listTemplateFile);
        return $this->categoriesModel->GetTitle($categoryPage);
	}

    function ShowCategories() {
		global $smarty;
        $categories = $this->categoriesModel->ReadItems();
        $smarty->assign('boards', $categories);
		$smarty->display("file:".$this->boardListTemplate);
    }
    
	function GetItemByPageName($pageName) {
		$item = $this->model->GetItemByPageName($pageName);
        if (!empty($item)) 
            $item['comments'] = $this->commentsModel->GetCommentsForNotice($item['id']);

        return $item;
	}
    
    function DisplayItemByPageName($pageName) {
		global $smarty;
		$item = $this->GetItemByPageName($pageName);
		$smarty->assign($this->itemName, $item);
		$this->title = $item['title'];
		$templateFile = $this->moduleFolder . 'templates/single.tpl';
		$smarty->display("file:".$templateFile);
        return $this->title;
    }
    
    private function GetFormId() {
        $form_id = isset($_REQUEST['form_id']) ? $_REQUEST['form_id'] : 0;
        if (!$form_id)
            $form_id = isset($_REQUEST['reply_form_id']) ? $_REQUEST['reply_form_id'] : 0;
        if (!$form_id)
            $form_id = (isset($_SESSION['form_id'])) ? $_SESSION['form_id'] + 1 : 1;
        else
            $form_id ++;     
        return $form_id;
    }
    
    private function Resubmitted($form_id) {
        $result = true;
        
        $sess_form_id =  (isset($_SESSION['form_id'])) ? $_SESSION['form_id'] : 1;
        
        if ($form_id == $sess_form_id)
            $result = false;
        return $result;
    }

    private function UpdateSessionFormId() {
        $sess_form_id =  (isset($_SESSION['form_id'])) ? $_SESSION['form_id'] : 1;
        $_SESSION['form_id'] = $sess_form_id + 1;   
        return $sess_form_id;
    }
    
    
    /**
     * Process a comment submission
     * @param string $notice - the current notice
     * @return string
    */
    function ProcessCommentSubmission ($notice, $member)
    {
        global $smarty, $memberName, $session_member_id;
        $member = $session_member_id;
        // TODO jemeno build the notice page URL
        $noticeLink = '';
        
        $action = isset($_REQUEST['action']) ? $_REQUEST['action'] : '';
        $form_id = $this->GetFormId();
        $resubmitted = $this->Resubmitted($form_id);
        $sess_form_id = $this->UpdateSessionFormId();
        if ($resubmitted)
            $form_id = $sess_form_id;
        $smarty->assign('form_id', $form_id);
                
        if (FIREBUG == 1) FB::info("\$form_id = $form_id  ---  \$sess_form_id = $sess_form_id");

        if ($action == 'comment')  {
            $message = '';
            $comment_name = $memberName;
            
            $comment = sanitize($_REQUEST['comment']);

            if ($comment == '')
            {
                $message .= '<p>The following fields are required and were left blank</p>';
                $message .= '<ul>';
                if ($comment == '') $message .= '<li>Comment</li>';
                $message .= '</ul><a href="javascript:history.go(-1)">back</a>';   
                $_SESSION['form_id'] = $_SESSION['form_id'] - 1;
            }
            else
            {
                $comment_status = (NOTICE_COMMENTS_NEED_APPROVAL == 1) ? 0 : 1;
                $parent = (isset($_REQUEST['comment_id'])) ? sanitize($_REQUEST['comment_id']) : 0;
                $noticeId = $this->model->GetID($notice);
                $newCommentResult = $this->commentsModel->AddComment($comment_name, $comment, $noticeId, $parent, $member, $comment_status);
                
                if (!$newCommentResult) {
                    $message = '<p>Sorry, there has been a problem submitting your comment</p>';
                }
                else
                {
                    if (NOTICE_COMMENTS_NEED_APPROVAL == 1)
                        $message = '<p>Thank you for your comment. We will process your comment shortly.</p>';
                    else
                        $message = '<p>Thank you for your comment.</p>';
                    $message .= '<a href="'."$noticeLink".'">Back to post</a>';
                    global $page;
                    header('Location: '.$page);
                    exit(); 
                    $_SESSION['form_id'] = 0;
                    $form_id = 0;
                }
            }
            return $message;
        }
    }
    
    function GetAddNoticeData() {
        $data = array();
        if (!empty($_POST['action']))
        {
            $data = array(
                'title' => $_POST['title'],
                'description' => $_POST['description'],
                'linktitle' => $_POST['linktitle'],
                'link' => $_POST['link'],
                'category' => $_POST['category'],            
            );
        }
        
        return $data;
    }
     
    function ProcessNoticeSubmission()
    {
        global $session_member_id;
        if (!empty($_POST['action']) && ($_POST['action'] == 'add notice')) {
            // validate form and if valid process
            $noticeBoardFormData = $this->GetAddNoticeData();
            
            if ($this->validateForm($noticeBoardFormData))
            {
                if (FIREBUG == 1) FB::info("Process Data");
                $postID = $this->processData($noticeBoardFormData, $session_member_id);
                $this->emailData($postID);
                $noticeBoardFormData = array();
            }
        }    
    }
    
	function addError($title, $message) {
		$this->errors[$title] = true;
		$this->errorMessages[$title] = $message;
	}    
    
	function validateForm($data)
	{
		if ($data['title'] == '') $this->addError('title', 'Please complete this field');
		if ($data['description'] == '') $this->addError('description', 'Please complete this field');
		if (($data['linktitle'] == '') && ($data['link'] != ''))
				$this->addError('linktitle', 'Please give a title for the link');
		if (($data['linktitle'] != '') && ($data['link'] == ''))
				$this->addError('link', 'Please give the link');

		return (count($this->errors) == 0);
	}    
    
	function processData($data, $member)
	{
        return $this->model->ProcessData($data, $member);
	}      
    
	function getNoticecategories() {
		$sql = "SELECT id, title from notice_category where published =	1 order by order_num";
		return db_get_mapped_rows($sql);
	}    
        
    function ShowForm($categoryPage = '') {
        global $smarty, $base_path;
        
        $category = $this->categoriesModel->GetID($categoryPage);
        if (empty($category))
            $category = 1;
        
        $noticeBoardCategories = $this->getNoticecategories();
        if ($this->errors)
            $noticeBoardFormData = $this->GetAddNoticeData();
        $smarty->assign('noticeBoardCategories', $noticeBoardCategories);
        $smarty->assign('noticeBoardFormData', $noticeBoardFormData);
        $smarty->assign('noticeBoardCurrentCategory', $category);
        $smarty->assign('dataError', $this->errors);
        $smarty->assign('errorMessage', $this->errorMessages);
        $smarty->display($base_path.'/modules/noticeboard/templates/form.tpl');
    }
    
	function GetSubmenu($menulink)
	{
        global $name_parts;        
        $submenu = $this->model->ReadMenuItems($menulink);
        
        $submenu[] = array (
				'name' => 'Notifications',
				'on' => ('notifications' == $name_parts[1]),
				'link' => $menulink.'/notifications'
        );
        
        return $submenu;
	}    

    function getNoticeMessage($postId)
	{
		global $smarty;
		global $base_path;

        $row = $this->model->ReadItem($postId);
        
		$smarty->assign('emailNotice', $row);
		$message['body'] = $smarty->fetch($base_path.'/modules/noticeboard/templates/notice_email.tpl');

		$message['subject'] = SITE_NAME.' new notice:'.date('jS F Y',$row['date']).', '.$row['title'];
		$message['category'] = $row['category_id'];
		return $message;
	}

	function getAddressees($category)
	{
        return $this->categoriesModel->getAddressees($category);
	}
    
    function emailData($postId)
	{
		global $base_path;
		include_once $base_path."/php/phpmailer/class.phpmailer.php";

        if (FIREBUG == 1) FB::info("Start Email");

		$message = $this->getNoticeMessage($postId);
		$category = $message['category'];
		$addressees = $this->getAddressees($category);

		foreach ($addressees as $addressee)
		{
        if (FIREBUG == 1) FB::info("Emailing $addressee");
			$mail = new PHPMailer();

			$mail->IsMail();

			$mail->From = SITE_CONTACT_EMAIL;
			$mail->FromName = $fromname;

			$mail->AddAddress($addressee['email']);
			$mail->AddReplyTo(SITE_CONTACT_EMAIL, SITE_NAME);

			$mail->WordWrap = 50;                                 // set word wrap to 50 characters
			$mail->IsHTML(true);                                  // set email format to HTML

			$mail->Subject = $message['subject'];
			$mail->Body    = $message['body'];
			$mail->AltBody = strip_tags($message['body']);
			$mail->Send();
		}
	}    
    
    
/*    
    
	function getNotices($category) {
		$sql = "SELECT np.title, np.description,CONCAT(mu.firstname,' ',mu.surname) AS 'memberName',
			nc.title AS category, np.link, np.linktitle, unix_timestamp(np.`date`) as `date`
			FROM notice_post np
			JOIN member_user mu ON np.member = mu.id
			JOIN notice_category nc ON np.category = nc.id and nc.id = $category order by np.date desc";
		return db_get_rows($sql);
	}
    
	function getNotice($notice) {
		$sql = "SELECT np.title, np.description,CONCAT(mu.firstname,' ',mu.surname) AS 'memberName',
			nc.title AS category, np.link, np.linktitle, unix_timestamp(np.`date`) as `date`
			FROM notice_post np
			JOIN member_user mu ON np.member = mu.id
			JOIN notice_category nc ON np.category = nc.id and n.id = $notice order by np.date desc";
		return db_get_rows($sql);
	}    

    function getNoticeComments($notice) {
		$sql = "SELECT  notice_id = $notice order by np.date desc";
		return db_get_rows($sql);
	}  
    
	function getNoticecategories() {
		$sql = "SELECT id, title from notice_category where published =	1 order by order_num";
		return db_get_mapped_rows($sql);
	}

	function getCategoryName($category) {
		$sql = "SELECT title from notice_category where id = $category";
		return db_get_single_value($sql, 'title');
	}

	function addNotice($data)
	{

	}

	function addError($title, $message) {
		$this->errors[$title] = true;
		$this->errorMessages[$title] = $message;
	}

	function validateForm($data)
	{
		if ($data['title'] == '') $this->addError('title', 'Please complete this field');
		if ($data['description'] == '') $this->addError('description', 'Please complete this field');
		if (($data['linktitle'] == '') && ($data['link'] != ''))
				$this->addError('linktitle', 'Please give a title for the link');
		if (($data['linktitle'] != '') && ($data['link'] == ''))
				$this->addError('link', 'Please give the link');

		return (count($this->errors) == 0);
	}

	function processData($data, $member)
	{
		$sqldata = array(
			'member' => $member,
			'date' => date('Y-m-d H:i:s'),
			'title' => $data['title'],
			'description' => $data['description'],
			'linktitle' => $data['linktitle'],
			'link' => $data['link'],
			'category' => $data['category'],
		);

		foreach ($sqldata as $title => $value)
		{
			$sqlfields[] = $title;
			$sqlvalues[] = $value;
		}
		$sql = 'insert into notice_post (`'.implode('`,`', $sqlfields).'`)'.
				"values ('".implode("','", $sqlvalues)."')";
		mysql_query($sql);
		return mysql_insert_id();
	}

	function getMessage($postId)
	{
		global $smarty;
		global $base_path;

		$sql = "SELECT np.title, np.description,CONCAT(mu.firstname,' ',mu.surname) AS 'memberName',
			nc.title AS category, nc.id as category_id, np.link, np.linktitle, unix_timestamp(np.`date`) as `date`
			FROM notice_post np
			JOIN member_user mu ON np.member = mu.id
			JOIN notice_category nc ON np.category = nc.id
			where np.id = $postId";
		$row = db_get_single_row($sql);

		$smarty->assign('emailNotice', $row);
		$message['body'] = $smarty->fetch($base_path.'/modules/members/template/email.tpl');

		$message['subject'] = SITE_NAME.' new notice:'.date('jS F Y',$row['date']).', '.$row['title'];
		$message['category'] = $row['category_id'];
		return $message;
	}

	function getAddressees($category)
	{
		$sql = "SELECT mu.* FROM  member_user mu
				JOIN member_user_notification_categories mn ON mu.id = mn.user_id
				WHERE category_id = $category";

		return db_get_rows($sql);
	}

	function emailData($postId)
	{
		global $base_path;
		include_once $base_path."/php/phpmailer/class.phpmailer.php";

		$message = $this->getMessage($postId);
		$category = $message['category'];
		$addressees = $this->getAddressees($category);

		foreach ($addressees as $addressee)
		{
			$mail = new PHPMailer();

			$mail->IsMail();

			$mail->From = SITE_CONTACT_EMAIL;
			$mail->FromName = $fromname;

			$mail->AddAddress($addressee['email']);
			$mail->AddReplyTo(SITE_CONTACT_EMAIL, SITE_NAME);

			$mail->WordWrap = 50;                                 // set word wrap to 50 characters
			$mail->IsHTML(true);                                  // set email format to HTML

			$mail->Subject = $message['subject'];
			$mail->Body    = $message['body'];
			$mail->AltBody = strip_tags($message['body']);
			$mail->Send();
		}
	}
    
   
 * 
 */
}
