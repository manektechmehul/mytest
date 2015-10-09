<?php

session_cache_limiter('must-revalidate');
session_start();

include '../../php/databaseconnection.php';

$session_user_id =  (isset($_SESSION['session_user_id'])) ? $_SESSION['session_user_id'] : "";
$session_user_type_id =  (isset($_SESSION['session_user_type_id'])) ? $_SESSION['session_user_type_id'] : "";
$session_access_to_cms =  (isset($_SESSION['session_access_to_cms'])) ? $_SESSION['session_access_to_cms'] : "";

if (($session_user_id) && ($session_access_to_cms))
{
    $action = $_POST['action'];

    if ($action == 'move') {
        $level = $_POST['level'];
        $curr = $_POST['curr'];
        $other = $_POST['other'];
        
        if ($level == 0)
        {
            $currOrderNum = db_get_single_value('select order_num from section where id = '.$curr);
            $otherOrderNum = db_get_single_value('select order_num from section where id = '.$other);
            mysql_query("update section set order_num = $currOrderNum where id = $other");
            mysql_query("update section set order_num = $otherOrderNum where id = $curr");
        }
        else
        {
            $currOrderNum = db_get_single_value('select order_num from content_type where id = '.$curr);
            $otherOrderNum = db_get_single_value('select order_num from content_type where id = '.$other);
            mysql_query("update content_type set order_num = $currOrderNum where id = $other");
            mysql_query("update content_type set order_num = $otherOrderNum where id = $curr");
        }
    }
    
    if (($action == 'hide') || ($action == 'show')) {
        $id = $_POST['id'];
        $status = ($action == 'hide') ? 0 : 1;
        mysql_query("update content_type set status = $status where id = '$id'");
        $row = db_get_single_row('select section_id, parent_id from content_type where id = '."'$id'");
        if ($row['parent_id'] == 0)
            mysql_query("update section set status = $status where id = '{$row['section_id']}'");
    }

    if ($action == 'delete') {
        $id = $_POST['id'];
        $level = $_POST['level'];
        if ($level == 0)
            mysql_query("update section set editable_content_area = 0, status=0 WHERE id = '$id'");
        else {
            mysql_query("DELETE FROM content_type WHERE id = '$id'");
            mysql_query("update content set page_name = '' WHERE content_type_id = '$id'");
        }
    }


	if ($action == 'secure') {
		$id = $_POST['id'];
		// toggle current value
		// see if this item is in the table
		$count = db_get_single_value("select count(*) from content_public where section_id =" . $id);
		// if not add it
		if($count == 0){
			mysql_query("insert into content_public (section_id, public) values ('{$id}', 1)");
		}else{
			// toggle binary value
			mysql_query("UPDATE content_public SET public = 1 - public  WHERE section_id = " . $id ." ;");
		}
	}
    
}
