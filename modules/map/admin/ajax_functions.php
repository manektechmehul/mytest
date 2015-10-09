<?
session_cache_limiter('must-revalidate');
session_start();

include '../../../php/databaseconnection.php';

$session_user_id =  (isset($_SESSION['session_user_id'])) ? $_SESSION['session_user_id'] : "";
$session_access_to_cms =  (isset($_SESSION['session_access_to_cms'])) ? $_SESSION['session_access_to_cms'] : "";

if (($session_user_id) && ($session_access_to_cms))
{
	$message = '';
	$action = (isset($_GET['action'])) ? $_GET['action'] : '';
	$value = (isset($_GET['value'])) ? $_GET['value'] : '';
	$id = (isset($_GET['id'])) ? $_GET['id'] : '';

	if ($action == 'setfeatured')
	{
		$featured_sql = "update map set featured = $value where id = '$id'";
		echo $featured_sql;
		$featured_result = mysql_query($featured_sql);
	}
}