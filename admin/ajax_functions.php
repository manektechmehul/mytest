<?php

session_cache_limiter('must-revalidate');
session_start();

include '../php/databaseconnection.php';

$session_user_id =  (isset($_SESSION['session_user_id'])) ? $_SESSION['session_user_id'] : "";
$session_user_type_id =  (isset($_SESSION['session_user_type_id'])) ? $_SESSION['session_user_type_id'] : "";
$session_access_to_cms =  (isset($_SESSION['session_access_to_cms'])) ? $_SESSION['session_access_to_cms'] : "";

if (($session_user_id) && ($session_access_to_cms))
{
    $message = '';
	$table = (isset($_GET['type'])) ? $_GET['type'] : '';
	$item_id = (isset($_GET['id'])) ? $_GET['id'] : '';
	$other_id = (isset($_GET['otherid'])) ? $_GET['otherid'] : '';
	$direction = (isset($_GET['direction'])) ? $_GET['direction'] : '';
	$action = (isset($_GET['action'])) ? $_GET['action'] : '';
	$value = (isset($_GET['value'])) ? $_GET['value'] : '';
	$name = (isset($_GET['name'])) ? $_GET['name'] : '';
	$callback = (isset($_GET['callback'])) ? $_GET['callback'] : '';

	if ($direction)
	{
		if 	($direction == 'up') 
			$sql = "select g1.id, g1.order_num, g2.id as otherid, g2.order_num as othernum from $table g1 join $table g2 where g2.order_num = (select max(order_num) from $table where order_num < (g1.order_num)) and g1.id = '$item_id'";
		else
			$sql = "select g1.id, g1.order_num, g2.id as otherid, g2.order_num as othernum from $table g1 join $table g2 where g2.order_num = (select min(order_num) from $table where order_num > (g1.order_num)) and g1.id = '$item_id'";
		
		$result = mysql_query($sql);
		$row = mysql_fetch_array($result);
		$order_num = $row['order_num'];
		$other_id = $row['otherid'];
	//	echo "alert '$other_id';\n";
		$other_order_num = $row['othernum'];
		
		$swap_sql = "update $table set order_num = '$other_order_num' where id = '$item_id'";
		$swap_result = mysql_query($swap_sql);
		$swap_sql = "update $table set order_num = '$order_num' where id = '$other_id'";
		$swap_result = mysql_query($swap_sql);
	}

	if ($action == 'swap')
	{
		$sql = "select sum(order_num) as ordersum from $table where id in ($item_id,$other_id)";
		$result = mysql_query($sql);
		$row = mysql_fetch_array($result);
		$order_sum = $row['ordersum'];
		$swap_sql = "update $table set order_num = $order_sum - order_num  where id in ($item_id,$other_id)";
		$swap_result = mysql_query($swap_sql);
	}
	
	if ($action == 'hide')
	{
		$publish_sql = "update $table set published = 0 where id = '$item_id'";
		$publish_result = mysql_query($publish_sql);
	}

	if ($action == 'show')
	{
		$publish_sql = "update $table set published = 1 where id = '$item_id'";
		$publish_result = mysql_query($publish_sql);
	}
	
	if (($action == 'config_change') && ($session_user_type_id == "1"))
	{
        $not_sm_admin_clause = ($session_user_id != 1) ? 'and sm_admin_only = 0' : '';                
		$config_sql = "update configuration set value = '$value' where name = '$name' $not_sm_admin_clause";
		$config_result = mysql_query($config_sql);
		
        if ($session_user_id == 1)
        {
		    $config_sql = "update section s
			    join content_type ct on s.id = ct.section_id 
			    join page_type pt on pt.id = page_type
			    join  configuration c on config_flag = c.name 
			    set editable_content_area = '$value', s.status = '$value'
			    where c.name = '$name' and parent_id = 0 and c.group = 1 and c.type = 1";
		    $config_result = mysql_query($config_sql);
        }			
		
//		$msg = "alert('" . addslashes($config_sql) . "')";
		//echo "$msg";
	}	
	
	if ($callback)
		echo "$callback()";
	
}

?>
