<?php

include_once '../databaseconnection.php';
include_once '../read_config.php';

$valid_extensions = array('jpg', 'gif', 'bmp', 'png');
$fileUploadUrl = '/php/fileupload/image.php';
$upload_dir = '/UserFiles/Image/';
$itemHtmlTemplate = "<div class=\"imageSquare\"><div class=\"imageSquareInner\"><img src=\"/php/thumbimage.php?img=/UserFiles/Image/{folder}/{file}&amp;size=13\" onclick=\"pick_image(\\'{folder}/{file}\\', this.src)\"></div><div style=\"float:right;padding:0 1px 2px 1px;\"><form method=\"post\" action=\"\" onsubmit=\"return delete_check()\"><input type=\"hidden\" name=\"foldername\" value=\"{folder}/\"><input type=\"hidden\" name=\"deletefilename\" value=\"{file}\"><input type=\"image\" src=\"images/delete.png\" name=\"delete\" value=\"{file}\"></form></div>{file}</div>";
$itemname = 'Image';
$singular = 'an image';
$usage_areas = 'content or gallery';
$root_name = 'Images';
$usage_queries = array(
    array('sql' => 'select 1 from content where body like \'%%/UserFiles/Image/%s%%\''),
    array('sql' => 'select 1 from gallery_image where imagename = \'%s\'')
);
$title = 'Image Manager';
$css_file = './image_controller.css';
$file_grid_handler = 'folder_images.php?t=' . time();

include 'controller.php';
