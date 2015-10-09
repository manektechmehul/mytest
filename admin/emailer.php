<?php
include ("../php/databaseconnection.php");
include ("../php/read_config.php");

$admin_tab = "maillist_admin";

session_cache_limiter('must-revalidate');
session_start();

// Changes to remove need for register globals and avoid warnings
//  -- start

// general variable declarations
$use_admin_header = 0;
$attachement_dir = '../UserFiles/attachments/';

// Get Session variables
$session_user_id =  (isset($_SESSION['session_user_id'])) ? $_SESSION['session_user_id'] : "";
$session_user_type_id =  (isset($_SESSION['session_user_id'])) ? $_SESSION['session_user_id'] : "";
$session_section_id = (isset($_SESSION['session_section_id'])) ? $_SESSION['session_section_id'] : "";

?>
<html>
<head>
<title>Administration</title>

<link href="./css/adminstylesheet.css" rel="stylesheet" type="text/css" />
<link href="http://fonts.googleapis.com/css?family=Gafata" rel="stylesheet" type="text/css">
</head>
<body >

<?php

// end --

$path_prefix = ".";


include ("../php/html_format.php");
include ("../admin/cms_functions_inc.php");
include ("./admin_header_inc.php");
include ("../php/phpmailer/class.phpmailer.php");


function send_mail($emailaddress, $fromaddress, $emailsubject, $body, $body_text, $attachment = "", $bcc_list = "", $fromname = "")
{
    $mail = new PHPMailer();
	$mail->PluginDir = '../php/classes/';

    $mail->IsMail();
    //$mail->IsSMTP();

    $mail->From = $fromaddress;
    $mail->FromName = ($fromname) ? $fromname : EMAIL_INVITE_FROM_NAME;
    $mail->AddAddress($emailaddress);           
    $mail->AddReplyTo($fromaddress, ($fromname) ? $fromname : EMAIL_INVITE_FROM_NAME);
    $mail->ConfirmReadingTo = $fromaddress;
    
    if ($attachment) 
    	$mail->AddAttachment($attachment);
    	
    if (is_array($bcc_list))
    	foreach($bcc_list as $bcc)
    		$mail->AddBCC($bcc);

    $mail->WordWrap = 50;                                 // set word wrap to 50 characters
    $mail->IsHTML(true);                                  // set email format to HTML

    $mail->Subject = $emailsubject;
    $mail->Body    = $body;
    $mail->AltBody = $body_text;

    return $mail->Send();
}

?>

<!-- CONTENT STARTS HERE -->
<div id="main_content">
<?php
if (($session_user_id) && ($session_access_to_cms))
{
	echo "<h1>Email</h1>";

  if (isset($_POST['process']) && ($_POST['process'] == 'check email')) 
  {
  	echo $_POST['process'];
  	$ids = $_POST['user'];
  	$users = implode(", ", $ids);
  	
  	$sql = "select * from registrants where id in ($users) order by surname";
    $result = mysql_query($sql);
    $template_body = $_POST['body'];
    $subject = $_POST['subject'];

	if (isset($_FILES['attachment'])) {
	  $attachment_file = $_FILES['attachment']['tmp_name'];
	  $attachment_name = $_FILES['attachment']['name']; 
	  
	  if (file_exists($attachment_file) && is_uploaded_file($attachment_file))
	  {
	    $clean_name = preg_replace('/[^A-Za-z0-9.]+/', '_', $attachment_name);
	  	copy($attachment_file, $attachement_dir.$clean_name);
	  }
	  else 
	  	'Problem uploading file<br/>';
	}
    
    $first = true;
    $search = array('%firstname%', '%surname%', '%username%', '%password%');
    while ($row = mysql_fetch_array($result)) 
    {
    	$replace = array($row['firstname'],$row['surname'],$row['username'],$row['password']);
    	$body = str_ireplace($search,$replace,stripcslashes($template_body));
    	if ($first) 
    	{
    		echo "<br />Here is an example using a member from the list:<br /><br />";
	    	echo '<div style="border: 1px solid black; padding: 5px 15px 5px 15px;margin-bottom: 20px">';
	    	echo $body;
    		echo '</div>'; 
			echo "Mail list<br><form method=\"post\" action=\"".$_SERVER['PHP_SELF']."\"><ul>";
		}
    	
    	$first = false;	
    	echo "<li>{$row['firstname']} {$row['surname']} - {$row['emailaddress']}<input type='hidden' name='userid[]' value = {$row['id']} /></li>";
	}
	echo '</ul><input type="hidden" name="subject" value="'.$subject.'">';
	if (isset($clean_name)) {
		echo 'Attachment<br /><ul><li>'.$clean_name.'</li></ul>';
		echo '<input type="hidden" name="attachment" value="'.$clean_name.'">';
	}
	echo '<input type="hidden" name="body" value="'.htmlspecialchars(stripcslashes($template_body)).'">';
	?>
	
	<input type="hidden" name="process" value="send email">
	</ul>
      <input type="image" src="./images/buttons/cmsbutton-send-off.gif" value="Send"></form><br /><br />
<?php  	
  }
  else
  if (isset($_POST['process']) && ($_POST['process'] == 'send email')) 
  {
  	$ids = $_POST['userid'];
  	$users = implode(", ", $ids);
  	
  	$sql = "select * from registrants where id in ($users) order by surname";
    $result = mysql_query($sql);
    $template_body = $_POST['body'];
    $template_body = html_entity_decode($template_body);
    
    // XXXX - need to get site details in next line
  	$template_body = str_ireplace('src="/UserFiles/', 'src="http://new.preview.creativestream.co.uk/UserFiles/', stripcslashes($template_body));
  	$subject = $_POST['subject'];
  	$attachment = isset($_POST['attachment']) ? $_POST['attachment'] : "";  
	echo "Sent Mail to<br><ul>";
    $search = array('%firstname%', '%surname%'); //, '%username%', '%password%');
    
    $from_sql = "select email from user where id = '$session_user_id'";
    $from_result = mysql_query($from_sql);
    $from_row = mysql_fetch_array($from_result);
    $from_email_address = $from_row['email'];
    
    $bcc_email_address = array();
    $bcc_sql = "select email from user join bcc_list on id = user_id";
    $bcc_result = mysql_query($from_sql);
    while ($bcc_row = mysql_fetch_array($from_result))
    {
    	$bcc_email_address[] = $from_row['email'];
	}
    
    while ($row = mysql_fetch_array($result)) 
    {
    	$replace = array($row['firstname'],$row['surname'],$row['username'],$row['password']);
    	$body = str_ireplace($search,$replace,$template_body);
		$body_text = strip_tags($body);
		send_mail($row['emailaddress'], 'noreply@creativestream.co.uk', $subject, $body, $body_text, $attachement_dir.$attachment, $bcc_email_address, "Creative Stream");
		echo "<li>{$row['firstname']} {$row['surname']}</li>";
		
	}  		
	echo '</ul><br /><br />';
  }
  else {

  // Show page
	include("../html_editor/fckeditor.php");	
  
    //$team_list = new team_list($big_group);
    ?>
	<p>You may use the following merge variables</p>
    <dl>
    	<dt>%firstname%</dt>
    	<dd>e.g. Hello %firstname%,
    		For member with first name "John", this will show 
    			Hello John,
    	</dd>
    	<dt>%surname%</dt>
    	<dd>The members surname
    	</dd>
    <dl>
    <br/>
    <br/>
	<form method='post' action='<?php echo $_SERVER['PHP_SELF'];?>' ENCTYPE="multipart/form-data">
    <strong>Email Subject</strong> <br/>
	<input type='text' name='subject' size=100 /><br /><br />
	<strong>Attachment (leave blank for none)</strong> <br/>
	<input type="file" name="attachment" size=80 /><br /><br />
    <strong>Email content</strong> <br/>
    <?php
	// insert HTML editor
	$sBasePath = "/html_editor/";

	$oFCKeditor = new FCKeditor('body') ;
	$oFCKeditor->BasePath	= $sBasePath ;
	$oFCKeditor->BaseHref = 'http://www.ecpn.org/UserFiles/' ;

	$oFCKeditor->Value = "<p>Hello %firstname% %surname%,</p><p>Please have a look at our new exciting range of products</p>".
		"<p>Your sincerely,</p><p>Me</p>";	
	$oFCKeditor->Create();	
	?>
<br/>
<br/>
	<input type="hidden" name="process" value="check email" />
    <table width="50%" cellpadding="2" cellspacing="1"><tbody><tr>
    <th>Member Name</th>
    <th>Send Email</th>
    <?php
    
    $sql = "select * from registrants";
    $result = mysql_query($sql);
    while ($row = mysql_fetch_array($result)) {
    ?>
    
    <tr>
      <td>
      	<?php echo $row['firstname'].' '.$row['surname'];?>
      </td>
      <td>
      <input type="checkbox" name="user[]" value="<?php echo $row['id']?>">
      </td>
      </tr>
      <?php
	};
	  ?>
      </tbody></table>
      <input type="image" src="./images/buttons/cmsbutton-preview-off.gif" value="Preview"><br>
    </form>
<?php
  }
}
?>
</div>
<!-- CONTENT ENDS HERE -->


<?php
include './admin_footer_inc.php';
mysql_close ( $link );
?>
