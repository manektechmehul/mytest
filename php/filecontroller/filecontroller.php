<?php
	include_once '../databaseconnection.php';	
	include_once '../read_config.php';	
	
	$valid_extensions = explode(',', PERMITTED_UPLOAD_FILE_TYPES);
	//$valid_extensions = array('mp3', 'pdf', 'doc', 'xls', 'txt');

	$upload_dir = '/UserFiles/File/';
    $fileUploadUrl = '/php/fileupload/file.php';
	$itemname = 'File';


    $itemHtmlTemplate = "<div class=\"fileSquare\"><div class=\"fileSquareInner\" onclick=\"pick_file(\\'{file}\\', \\'/php/filecontroller/images/{icon}\\')\"><img src=\"./images/{icon}\"></div><div class=\"fileDelete\"><form method=\"post\" action=\"\" onsubmit=\"return delete_check()\"><input type=\"hidden\" name=\"foldername\" value=\"{folder}/\"><input type=\"hidden\" name=\"deletefilename\" value=\"{file}\"><input type=\"image\" src=\"images/delete-off.gif\" name=\"delete\" value=\"{file}\"></form></div><div class=\"fileDescription\" onclick=\"pick_file(\\'{folder}/{file}\\', \\'/php/filecontroller/images/generic.gif\\')\">{file}</div></div>";

	$singular = 'a file';
	$usage_areas = 'content';
	$root_name = 'Files';
	$usage_queries = array(
						array('sql' => 'select 1 from content where body like \'%%/UserFiles/File/%s%%\''),
						);
	$title = 'File Manager';						
	$css_file = './file_controller.css';
	$file_grid_handler = 'folder_files.php?t='.time();

    include 'controller.php';
						
