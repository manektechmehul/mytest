<?php

 ini_set('display_errors', '1');
    ini_set('html_errors', 'on');
    ini_set('error_reporting', '-1');

$password = 'default1234';
$zip = dirname(__FILE__) . '/zippy4.zip';
$source = dirname(__FILE__) . '/source.txt';

 

echo  "7za a -p$password -mem=AES256 -tzip $zip $source";


exec("7za a -p$password -mem=AES256 -tzip $zip $source");

// 7za a -tzip -pMY_SECRET -mem=AES256 secure.zip doc.pdf

echo "<br> created zip ? <hr>";
system('ls '. escapeshellarg(dirname(__FILE__)));