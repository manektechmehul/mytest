<?php

session_cache_limiter('must-revalidate');
session_start();
include '../../php/databaseconnection.php';
include '../../php/functions_inc.php';

include_once "/classes/main.php";
header("Content-type: text/xml; charset=utf-8");
 
/*
 * 
 This module will extra data from the database
 Parameters passed in via the get will define the query
 * 
 * 
 * Encode the parameters for this service with urlencode();
 */
/*
 ini_set('display_errors', '1');
 ini_set('html_errors', 'on');
 ini_set('error_reporting', '-1');
*/  
//echo urlencode('select * from documents where published=1 order by order_num');
$query = urldecode($_GET['customquery']);
/*
$table = $_GET['table'];
$where = $_GET['where'];
$order_by = $_GET['order_by'];
*/
$safeAddresses = array('127.0.0.1','78.136.29.152');
$remote_ip = $_SERVER['REMOTE_ADDR'];
if(in_array($remote_ip, $safeAddresses)){
    $cs_xml = new cs_xml_helper();
    //echo 'Your ip is on our safelist';  
    echo $cs_xml->execute_query($query);    
}else{
    outputError('You did not request from a valid host - ' . $remote_ip);
}
       
function outputError($message){
    echo '<?xml version="1.0"?><error>{$message}</error>';    
}
 
?>