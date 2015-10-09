<?php
include_once '../databaseconnection.php';
include_once '../read_config.php';

session_cache_limiter('must-revalidate');
session_start();

$session_user_id =  (isset($_SESSION['session_user_id'])) ? $_SESSION['session_user_id'] : "";

if (!$session_user_id)
    exit();

error_reporting(E_ALL | E_STRICT);
require('UploadHandler.php');

$folder = ($_POST['foldername'] == '/') ? '' : $_POST['foldername'].'/';

$options = array(
    'upload_url' => '/UserFiles/Image/'.$folder,
    'accept_file_types' => '/\.(gif|jpe?g|png|bmp)$/i',
    'upload_dir' => dirname(dirname(dirname(__FILE__))).'/UserFiles/Image/'.$folder.'/',
    );

$upload_handler = new UploadHandler($options);
