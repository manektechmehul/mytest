<?php

include '../../../php/databaseconnection.php';

$pollId = $_POST['id'];
$answer = $_POST['answer'];

if (is_numeric($pollId) && is_numeric($answer))
{
    $sql = "insert into poll_response (poll, answer) values ($pollId, $answer)";
    mysql_query($sql);
    $base_path = realpath('../../..');
    require '../../../php/smarty/Smarty.class.php';
    $smarty = new Smarty;
    $smarty->compile_dir = $base_path.'/templates/templates_c';
    require_once $smarty->_get_plugin_filepath('modifier', 'format_date');
    include '../main.php';
}