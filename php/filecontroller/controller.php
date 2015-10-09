<?php
	session_cache_limiter('must-revalidate');
	session_start();

	$session_user_id =  (isset($_SESSION['session_user_id'])) ? $_SESSION['session_user_id'] : "";

	if (!$session_user_id)
		exit();

	$newFile = isset($_FILES['newfile']) ? $_FILES['newfile'] : '';
	$newfolder = isset($_POST['newfoldername']) ? $_POST['newfoldername'] : '';
	$foldername = isset($_REQUEST['foldername']) ? $_REQUEST['foldername'] : '/';
	$deletefilename = isset($_REQUEST['deletefilename']) ? $_REQUEST['deletefilename'] : '';
    $deleteFolderName = isset($_REQUEST['deletefoldername']) ? $_REQUEST['deletefoldername'] : '';

	$ext_list = '';
	$first = true;
	foreach ($valid_extensions as $ext)
	{
		if ($first) 
			$first = false;
		else
			$ext_list .= ", ";
		$ext_list .= "'$ext'";
	}




	$message	= '';
    if ($newFile)
    {
        umask(0);
        $newFilename = preg_replace('/[^A-Za-z0-9.]+/', '_', $newFile['name']);
        $dest = '../..'.$upload_dir.$foldername.'/'.$newFilename;
        $fileinfo = pathinfo($dest);
        $ext = strtolower($fileinfo['extension']);
        if (!in_array($ext, $valid_extensions))
            $message = "Invalid extension: $ext<br />Valid extensions are ".implode(', ', $valid_extensions) ;
        else
        {
            move_uploaded_file($newFile['tmp_name'], $dest);
            chmod($dest, 0777);
        }
        //copy();
    }
	if ($newfolder) 
	{
		$newfolder = preg_replace('/[^A-Za-z0-9.]+/', '_', $newfolder);
		$newpath = '../..'.$upload_dir.$foldername.'/'.$newfolder;
		umask(0);
		if (!file_exists($newpath))
			mkdir($newpath, 0777, 0);
		$foldername = $newpath;
	}

    if ($deleteFolderName)
    {
        // don't need to check if empty because if the folder is not empty it can't be deleted.
        $deletePath = '../..'.$upload_dir.'/'.$deleteFolderName;
        if (!rmdir($deletePath))
            $message = 'Could not delete folder '.$deleteFolderName.', please make sure it is empty';
    }

	if ($deletefilename <> '')
	{
		$foldername = trim($foldername,'/');
		$deletefilename = trim($deletefilename,'/');	

		$sql = "select count(*) cnt from ( ";
		$first = true;
		foreach ($usage_queries as $usage_query)
		{
			if (!$first)
				$sql .= ' union ';
			else 
				$first = false;
		
			$sql .= sprintf($usage_query['sql'], $deletefilename);
		}
		$sql .= " ) as x";
        // jtodo: check this sql is clean
		
		$rowCount = db_get_single_value($sql);
		if ($rowCount > 0)
			$message = "$itemname $deletefilename is currently used. Please delete from $usage_areas first.";
		else {
            $fullPathDeleteFile = realpath('../..').$upload_dir.$deletefilename;
            if (file_exists($fullPathDeleteFile))
				unlink($fullPathDeleteFile);

        }
	}
?>
<!DOCTYPE HTML>
<html lang="en">
<head>
    <meta charset="utf-8">

<script src="jsTree.js" type="text/javascript" language="JavaScript"></script>
    <script src="//ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.min.js"></script>
    <script src="/js/jquery/file_upload/js/vendor/jquery.ui.widget.js"></script>
    <script src="/js/jquery/file_upload/js/jquery.iframe-transport.js"></script>
    <script src="/js/jquery/file_upload/js/jquery.fileupload.js"></script>

    <script>
        if (!$.browser.msie || parseInt($.browser.version, 10) > 8)
        {
            function AddFileUpload(elem) {
                var input = document.getElementById("uploadInput");
                var uploadList = document.getElementById("fileUploads");
                while (uploadList.hasChildNodes()) {
                    uploadList.removeChild(uploadList.firstChild);
                }
                for (var i = 0; i < input.files.length; i++) {
                    var item = document.createElement("li");
                    item.innerHTML = input.files[i].name;
                    uploadList.appendChild(item);
                }
                if(!uploadList.hasChildNodes()) {
                    var item = document.createElement("li");
                    item.innerHTML = 'No Files Selected';
                    uploadList.appendChild(item);
                }
            }

            $(function () {
                'use strict';

                // Initialize the jQuery File Upload widget:
                $('#fileupload').fileupload({
                    // Uncomment the following to send cross-domain cookies:
                    //xhrFields: {withCredentials: true},
                    dataType: 'json',
                    url: '<?php echo $fileUploadUrl; ?>',
                    add: function (e, data) {
                        //data.context = $('<p/>').text('Uploading...').appendTo(document.body);
                        data.submit();
                        $('#progressOuter').show();
                    },
                    progressall: function (e, data) {
                        var progress = parseInt(data.loaded / data.total * 100, 10);
                        $('#progress .bar').css(
                                'width',
                                progress + '%'
                        );
                    },
                    done: function (e, data) {

                        var tmpl = '<?php echo $itemHtmlTemplate; ?>';
                        var exts = ["mp3", "pdf", "mov"];

                        var fileCount = 0;
    /*
                        $.each(data.result.files, function (index, file) {
                            if (file.error) {
                                alert(file.error)
                            }
                        })
     */
                        $.each(data.result.files, function (index, file) {
                            if (file.error == undefined) {
                                var ext = file.name.split('.').pop();
                                var iconfile = 'generic.gif';
                                if (exts.indexOf(ext) > -1)
                                    iconfile = ext + '.gif';
                                var newItemBlock = tmpl
                                        .replace(/{file}/g, file.name)
                                        .replace(/{folder}/g, $('#addfilefoldername').val())
                                        .replace(/{icon}/g, iconfile)
                                $(newItemBlock).prependTo('#Grid')
                                fileCount++;
                            } else {
                                alert(file.error)
                            }
                        });

                        $('#progressOuter').hide();
                        if (fileCount > 0) {
                            var msg = fileCount + ' ' + (fileCount > 1 ? 'files' : 'file') + ' added';
                            $('<p style="background-color: #004b00; color: #ffffff; padding: 2px 6px">'+msg+'</p>').prependTo('#GridAdd').fadeOut(2000);
                        }
                    }
                });

                // Enable iframe cross-domain access via redirect option:
              
			 
			  
			  // I have removed this as in ie9, after the image is uploaded it fails to show the image and looks like it has hung
			  //    window.location.href.replace(
			  /*
			    $('#fileupload').fileupload(
                        'option',
                        'redirect',
                        window.location.href.replace(
                                /\/[^\/]*$/,
                                '/cors/result.html?%s'
                        )
                );
				
				*/


            });
        }
    </script>

<link rel="stylesheet" type="text/css" href="./jsTree.css"/>
<link rel="stylesheet" type="text/css" href="/js/jquery/file_upload/css/jquery.fileupload-ui.css"/>
<?php
include 'folders_js.php';
?>
<link rel="stylesheet" type="text/css" href="<?php echo $css_file;?>"/>

</head>

<body style="width: 900px" onload="renderTree(); ajax_do('<?php echo $foldername;?>')">
<?php if (!empty($message)) { ?><p id="errorMessage" style="position: absolute; width: 100%; top: 0; margin: 0; background-color: #8b0000; color: white; padding: 5px; text-align: center"><?=$message?></p><?php } ?>

<div id="varDefContainer"></div>
<div id="treeContainer">
<div id="treeContainerInner"></div>
<form method='post' action="">
<div id='addfolder'> 
<p>To add a new folder, select the location by clicking on a folder above and type the name below. </p>
<input type='text' name='newfoldername' />
<input type='hidden' id='addfoldername' name='foldername' value='<?php echo $foldername;?>'/>
<input type='submit' name='newfoldersubmit' value='Add Folder' />
</div>
</form>

    <form method='post' action="">
        <div id='deletefolder'>
           
            <input type='hidden' id='deletefoldername' name='deletefoldername' value='<?php echo $foldername;?>'/>
            <input type='submit' name='deletefoldersubmit' value='Delete Selected  Folder' onclick="return confirm('are you sure?')"/>
        </div>
    </form>

</div>
<h3><?php $foldername ?></h3>
<div id="GridOuter">
<div id="Grid">
</div>
<div style="clear:both;padding-left:10px;"><?php echo $message; ?></div>
<div id="GridAdd">
    <div id="progressOuter" style="overflow: hidden; display: none">
        <div style="float: left; width: 90px">Upload progress</div>
        <div id="progress" style="width: 200px; border: 1px solid #aaa; height: 10px; float: left">
            <div class="bar" style="width: 0%; height: 10px; background-color: #009900"></div>
        </div>
    </div>
    Add <?php echo $singular; ?> to this folder<form id="fileupload" method='post'  action='' enctype="multipart/form-data"">
<input type='hidden' id='addfilefoldername' name='foldername' value='<?php echo $foldername;?>'/>
    <div>
        <!--[if gt IE 8]>
          <input type="file" name="files[]" multiple>
        <![endif]-->
        <!--[if !IE]><!-->
          <input type="file" name="files[]" multiple>
        <!--<![endif]-->
        <!--[if lt IE 9]>
          <input type="file" name="newfile" >
        <![endif]-->
        </div>

        <div><ul id="fileUploads">
        </ul>
        </div>
        <!--[if lt IE 9]>
          <div><input type='submit' value='Add <?php echo $itemname; ?>'/></form></div>
        <![endif]-->
    </form></div>
<script>
    $('#errorMessage').delay(3000).fadeOut(3000);
</script>

</body>
</html>