<?php

function email_is_valid($emailaddress)
{
	$m = ereg('^[-!#$%&\'*+\\./0-9=?A-Z^_`a-z{|}~]+'.
		    '@'.
		    '[-!#$%&\'*+\\/0-9=?A-Z^_`a-z{|}~]+\.'.
		    '[-!#$%&\'*+\\./0-9=?A-Z^_`a-z{|}~]+$', $emailaddress);
	return $m;
}


function email_registered($emailaddress)
{
	$sql = "select * from registrants where emailaddress = '{$emailaddress}'" ;
	$result = mysql_query($sql);
	$rows = mysql_num_rows($result);
	return ($rows > 0);
}

function send_email($emailaddress, $action)
{
	$from_email_address = SITE_CONTACT_EMAIL;
	$to_email_address = $from_email_address;
	if ($action == 'add')
	{
		$subject = 'email registration';
	    $message = "The email address $emailaddress has been registered";
	}
	else
	{
		$subject = 'email removed from mailing list';
	    $message = "The email address $emailaddress has been removed from mailing list";
	}
	mail($to_email_address,$subject,$message,'FROM: '.$from_email_address); 
}

function deregister_email($emailaddress)
{
	$sql = sprintf("delete from registrants where emailaddress = '%s'", $emailaddress);
	$result = mysql_query($sql);
}

function register_email($emailaddress)
{
	$sql = sprintf("insert into registrants (emailaddress)  values ('%s') ", $emailaddress);
	$result = mysql_query($sql);
}

$register_email = isset($_POST['email_address']) ? $_POST['email_address'] : '';
$receive_mailings = isset($_POST['receive_mailings']) ? $_POST['receive_mailings'] : '';


if ($receive_mailings)
{
	$message = '<p class="newsletter-registration-text">';
	if ($receive_mailings == 'yes')
	{
		if (email_is_valid($register_email))
		{
			if (email_registered($register_email) == false)
			{
				send_email($register_email, 'add');
				register_email($register_email);
				$message .= 'Thankyou for registering your email ';
				$show_form = false;
			}
			else
			{
				$message .= 'Thankyou, this email address is already registered on our mailing list';
			}
		}
		else
		{
			$message .= 'Thankyou, but this email address is invalid';
		}
	}
	else
	{
		send_email($register_email, 'remove');
		if (email_registered($register_email) == false)
		{
			$message .= 'This email is not on our mailing list';
		}
		else
		{
			deregister_email($register_email);
			$message .= 'Your email address has been removed from the mailing list';
		}
	}
		
	$message .= '</p><a href="javascript:history.go(-1)" onmouseover="MM_swapImage(\'back\',\'\',\'/images/buttons/button-back-over.png\',1)" onmouseout="MM_swapImgRestore()">'.
		'<img id=\'back\' src="/images/buttons/button-back-off.png" alt="back"/></a>';

	$smarty->assign('newsletter_message', $message);
}

$filters['newsletter'] = array('search_string'  => '/<!-- CS newsletter start -->(.*)<!-- CS newsletter end -->/s',
        'replace_string' => '{if $newsletter_message} {$newsletter_message} {else} $1 {/if}');
?>	