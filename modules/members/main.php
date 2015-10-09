<?php

/* please check the cms pages - line up to the expected names in the members mode 
eg. don't expect the reset_passowrd page to work as we are calling it resetpassword here */
	define ('MEMBER_PAGE', 1);
	define ('MEMBER_ARTICLE', 2);
	define ('MEMBER_DOCUMENTS', 3);
    define ('MEMBER_NOTICEBOARD', 4);
    define ('MEMBER_EDIT_DETAILS', 9);

	$memberPageType = MEMBER_PAGE;

	if ($session_member_id)
	{
		//$memberName = db_get_single_value("select trim(concat(firstname,' ', surname)) as `name` from member_user where id = $session_member_id", 'name');
		$memberName = db_get_single_value("select firstname as `name` from member_user where id = $session_member_id", 'name');
		$smarty->assign('membername', $memberName);
        
		if ($page_name == 'edit_details')
		{	
            $memberPageType = MEMBER_EDIT_DETAILS;
            include 'edit.php';
        }
		if ($article_name == 'password')
		{	
            $memberPageType = MEMBER_EDIT_DETAILS;
            include 'password.php';
        }
		elseif ($article_name == 'documents')
		{	
			$module_path = 'documents';
			array_shift($name_parts);
			$levels = count($name_parts);
			include $base_path.'/modules/documents/main.php';

			$memberPageType = MEMBER_DOCUMENTS;
		}
		else if ($article_name == 'noticeboard')
		{	
			array_shift($name_parts);
			$levels = count($name_parts);
			require $base_path.'/modules/noticeboard/noticeboard.php';
            
			
			$memberPageType = MEMBER_NOTICEBOARD;
		}        
		else if (count($name_parts) == 3)
		{
			$main_body_sql="select * from member_page where page_name = '$name_parts[2]'";

			if ($article_name == 'articles')
			{	
				$module_path = 'members';
				$main_body_sql="select * from member_article where page_name = '$name_parts[2]'";
				$memberPageType = MEMBER_ARTICLE;
			}

		}
		else if ($article_name)
		{
			$main_body_sql="select * from member_page where page_name = '$article_name'";
		}
		else
			$main_body_sql="select * from member_page where parent_id = 0 order by order_num limit 1";

		if (FIREBUG == 1) FB::info("\$memberPageType = $memberPageType");

		if (($memberPageType == MEMBER_PAGE) || ($memberPageType == MEMBER_ARTICLE)) {
            if (FIREBUG == 1) FB::error("\$memberPageType = $memberPageType");
		
			$main_body_result = mysql_query($main_body_sql);	
			$main_body_row = mysql_fetch_array($main_body_result);
			
			//if (empty($title))
			$title = $main_body_row['title'];
		
			//$template_file = 'members.htm';
			echo $main_body_row['body']."\n";
			//echo "<br /><a class=\"button-purple\" href=\"/logout\" >logout</a>";
			
			if ($memberPageType == MEMBER_PAGE)
			{
				$sql="select * from member_article where page_id = {$main_body_row['id']} AND published = 1 order by order_num";
				
				$result = mysql_query($sql);				
				if (mysql_num_rows($result) > 0) 
				{
					echo '<hr />';
					while ($myrow = mysql_fetch_array($result)) {	
						echo "<p class='article_header'>{$myrow["title"]}</p>";
						//echo "<p class='article_summary'>{$myrow["summary"]}</p>";
						printf ("<a href=\"/members/articles/%s\">more</a>", $myrow["page_name"]);
					}
				}
			}
			if ($memberPageType == MEMBER_ARTICLE)
			{
				$pageName = db_get_single_value("select page_name from member_page where id = {$main_body_row['page_id']}", 'page_name');
				echo "<br /><a href= \"/members/$pageName\">Back</a>";
			};
		};
	}
	else
	{
        if ($page_name == 'sign_up') {
            include 'signup.php';
        }
        else if ($page_name == 'resetpassword') {
            include 'resetpassword.php';
        }
        else if ($page_name == 'forgot_password') {
            include 'forgotpassword.php';
        }
        else {
            //echo $content_row["body"];
            if (!empty($failstate))
                echo $login_error;
            include './php/login_inc.php';
        }
	}