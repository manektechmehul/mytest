<?php
session_cache_limiter('must-revalidate');
session_start();
// ini_set('display_errors', '1');
include '../../php/databaseconnection.php';
include '../../php/functions_inc.php';
$search_term = strtolower(mysql_real_escape_string(strip_tags($_REQUEST['term'])));
$module_table = "my_health";
$sql = "select title, page_name from $module_table where title like '%" . $search_term . "%' ";
$debug = $sql;
$results = mysql_query($sql);        
$json = array();        
while (is_resource($results) && $row = mysql_fetch_object($results)) {
    $debug .= 'adding';
    $json[] = '{"id" : "' . $row->page_name . '", "label" : "' . $row->title . '"}';
}
// return data in json format
echo '[' . implode(',', $json) . ']';
?>