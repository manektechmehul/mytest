<?php
include "../../php/databaseconnection.php";
$reg_sql = 'select firstname, surname, emailaddress, added from registrants where receiveemail = 1';
$reg_result = mysql_query($reg_sql);
$output = "";

while ($reg_row = mysql_fetch_row($reg_result)) 
	$output .=  implode(',',$reg_row)."\r\n";
header('Content-disposition: attachment; filename=registrants.csv');
header("Content-Type: text/x-csv"); 
echo $output;

?>

