<?php if (!empty($session_member_id)) { ?>
<p>Please tick the box below and then click on "save your configuration" to be notified by email of new posts within our notice board.<br />

<?php
$title = "Notification Settings";
if ($_POST['submit']) {

	$cats = $_POST['cat'];
	
	foreach ($cats as $cat)
	{
		$cat = preg_replace('[^0-9,]', '', $cat);
		if ($cat)
		{
			$cleancats[] = $cat;
			$sql = "replace into member_user_notification_categories (user_id, category_id) values ($session_member_id, $cat)";
			mysql_query($sql);
		}
	}
	if (!empty($cleancats))
	{
		$cleancatsList = implode(',', $cleancats);
		$sql = "delete from member_user_notification_categories where user_id = '$session_member_id' and category_id not in ($cleancatsList)";
	}
	else
		$sql = "delete from member_user_notification_categories where user_id = '$session_member_id'";
	
	mysql_query($sql);
	echo "<span id=\"message\">Notifications Updated $date</span>";
	echo "<script>$('#message').fadeOut(2000);</script>";
}
echo "&nbsp;</p>";

$sql = "select nc.id, title, case when isnull(mn.user_id) then 0 else 1 end as 'on' from notice_category nc
		left outer join member_user_notification_categories mn on nc.id = mn.category_id and user_id = {$session_member_id}
        where nc.published = 1
        order by nc.order_num";

$result = mysql_query($sql);

?>

<form action="" method="post">

<?php
while ($row = mysql_fetch_array($result)) {
	$checked = ($row['on'] == 1) ? 'checked="checked"' : '';
	echo "<p style=\"border-bottom:1px solid #ff8aba;\"><label style=\"width:500px;\" for=\"cat{$row['id']}\">{$row['title']}</label>&nbsp;";
	echo "<input style=\"float:right;\" type=\"checkbox\" name=\"cat[{$row['id']}]\" id=\"cat{$row['id']}\" value=\"{$row['id']}\" $checked /></p>";
}
?>
	<br />
<input type="hidden" name="submit" value="save notifications" />
<input type="submit" value="Save Your Configuration" />
</form>
<?php } ?>