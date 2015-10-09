<?php
session_start();
//Had to add this timer check as the file is getting hit twice ... don't know why
$now = time();
$diff = $now - $_SESSION['captcha'];
// only show captcha if it hasn't been used in the last 2 seconds
if(($now - $_SESSION['captcha']) > 2){
  include("securimage.php");
  $img = new securimage();
  $img->show();  
  $_SESSION['captcha'] = time();
}
?>