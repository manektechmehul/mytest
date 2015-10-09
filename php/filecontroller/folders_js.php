<?php
$field = (!empty($_GET['field'])) ? $_GET['field'] : '';
?>

<script language="JavaScript" type="text/javascript">

var newNodeCount = 0
                                               
var de_nodeContextMenu =
[
	['Knoten anf&uuml;gen&nbsp;', ['javascript:var sd = "{@strData}";var newName = prompt("Name", "new node " + (++newNodeCount));if(newName){var newLink = prompt("Link", "#");if(newLink){addNode(getPath(sd.substr(0, sd.length - 3)), [newName, [newLink,,"node"]], true)}}',,'add']],
	['Knoten l&ouml;schen&nbsp;', ['javascript:var sd = "{@strData}";deleteNode(getPath(sd.substr(0, sd.length - 3)))',,'delete']],
	['Link &auml;ndern&nbsp;', ['javascript:var sd = "{@strData}";var newLink = prompt("Link", eval(sd.substr(0, sd.length-6))[1][0]);if(newLink){eval(sd.substr(0, sd.length-6))[1][0] = newLink;rebuildNode(getPath(sd.substr(0, sd.length-3)), true)}',,'href']],
	['Zielfenster &auml;ndern&nbsp;', ['javascript:var sd = "{@strData}";var newLink = prompt("Zielfenster", eval(sd.substr(0, sd.length-6))[1][1]);if(newLink){eval(sd.substr(0, sd.length-6))[1][1] = newLink;rebuildNode(getPath(sd.substr(0, sd.length-3)), true)}',,'target']],
	['Bild &auml;ndern&nbsp;', ['javascript:var sd = "{@strData}";var newIcon = prompt("Bild", eval(sd.substr(0, sd.length-6))[1][2] ? eval(sd.substr(0, sd.length-6))[1][2] : "node");if(newIcon){eval(sd.substr(0, sd.length-6))[1][2] = newIcon;rebuildNode(getPath(sd.substr(0, sd.length-3)), true)}',,'icon']],
	['Hinweis &auml;ndern&nbsp;', ['javascript:var sd = "{@strData}";var newTooltip = prompt("Hinweis", eval(sd.substr(0, sd.length-6))[1][3] ? eval(sd.substr(0, sd.length-6))[1][3] : "");if(newTooltip){eval(sd.substr(0, sd.length-6))[1][3] = newTooltip;rebuildNode(getPath(sd.substr(0, sd.length-3)), true)}',,'tooltip']]
]

var en_nodeContextMenu =
[
	['Add node&nbsp;', ['javascript:var sd = "{@strData}";var newName = prompt("Name", "new node " + (++newNodeCount));if(newName){var newLink = prompt("Link", "#");if(newLink){addNode(getPath(sd.substr(0, sd.length - 3)), [newName, [newLink]], true)}}',,'add']],
	['Delete node&nbsp;', ['javascript:var sd = "{@strData}";deleteNode(getPath(sd.substr(0, sd.length - 3)))',,'delete']],
	['Change link&nbsp;', ['javascript:var sd = "{@strData}";var newLink = prompt("Link", eval(sd.substr(0, sd.length-6))[1][0]);if(newLink){eval(sd.substr(0, sd.length-6))[1][0] = newLink;rebuildNode(getPath(sd.substr(0, sd.length-3)), true)}',,'href']],
	['Change target frame&nbsp;', ['javascript:var sd = "{@strData}";var newLink = prompt("Target frame", eval(sd.substr(0, sd.length-6))[1][1]);if(newLink){eval(sd.substr(0, sd.length-6))[1][1] = newLink;rebuildNode(getPath(sd.substr(0, sd.length-3)), true)}',,'target']],
	['Change icon&nbsp;', ['javascript:var sd = "{@strData}";var newIcon = prompt("Icon", eval(sd.substr(0, sd.length-6))[1][2] ? eval(sd.substr(0, sd.length-6))[1][2] : "node");if(newIcon){eval(sd.substr(0, sd.length-6))[1][2] = newIcon;rebuildNode(getPath(sd.substr(0, sd.length-3)), true)}',,'icon']],
	['Change tooltip&nbsp;', ['javascript:var sd = "{@strData}";var newTooltip = prompt("Tooltip", eval(sd.substr(0, sd.length-6))[1][3] ? eval(sd.substr(0, sd.length-6))[1][3] : "");if(newTooltip){eval(sd.substr(0, sd.length-6))[1][3] = newTooltip;rebuildNode(getPath(sd.substr(0, sd.length-3)), true)}',,'tooltip']]
]

jst_context_menu = en_nodeContextMenu

function _foo(directory){
	
	//alert(directory);
	ajax_do(directory);
}

var arrNodes =
[
	['<?php echo $root_name; ?>', ['javascript:_foo("")'],

<?
include '../../php/thumbnails_inc.php';

//$base = realpath('../../images');
$base = realpath('../..'.$upload_dir). '/';

function dirscan ($scandir, $tab, $start, $end)
{
	global $base;
	$files = scandir($base.$scandir);

	$first = true;
	
	foreach ($files as $dir)
	{
		if (is_dir($base.$scandir.$dir) && ($dir != '.') && ($dir != '..'))
		{
			if ($first) {
				$first = false;
				echo "$start{$tab}[\n";
			}
			else 
				echo ",\n";
			echo "{$tab}	[ '$dir',  ['javascript:_foo(\"{$scandir}{$dir}\")',,'folder']";
			dirscan($scandir.$dir .'/', $tab.'		', ", \n", ',');
			echo "]";
		}
	}
	if (!$first) {
		echo "\n$tab]";
	}
}

dirscan ('', '	', '', '');
?>
	]
]
  /*
var arrNodes =
[
	['The Simpsons', ['javascript:_foo()'],
		[
			['Epsisodes', ['javascript:_foo()',,'folder'],
				[
					['Season One (1989-1990)', ['javascript:_foo()',,'folder'], 
					]
				]
			],
			['Characters', ['javascript:_foo()',,'folder'],
				[
					['Homer Simpson', ['javascript:alert("No!")',,'homer']],
					['Marge Simpson', ['javascript:alert("A little pinch of LSD, that is all i need!")',,'marge']],
					['Bart Simpson', ['javascript:alert("Ey Caramba!")',,'bart']],
					['Lisa Simpson', ['javascript:alert("E = M * C?")',,'lisa']],
					['Maggie Simpson', ['javascript:alert("snhk snhk!")',,'maggie']]
				]
			]
		]
	]
]
    */

var jst_container = "document.getElementById('treeContainerInner')"


var jst_delimiter = ['|', '<|>']
var jst_id = "jsTree"
var jst_data = "arrNodes"
var jst_expandAll_warning = "Expanding all nodes can take a while depending on your hardware! Continue?"
var jst_highlight = true
var jst_highlight_color = "white"
var jst_highlight_bg = "maroon"
var jst_highlight_padding = "1px"
var jst_image_folder = "./images"
var jst_reloading = false
var jst_reload_frame = "reLoader"
var jst_reload_script = ""
var jst_reloading_status = "loading tree nodes ..."
var jst_root_image = "/folder"


// Get base url
url = document.location.href;
xend = url.lastIndexOf("/") + 1;
var base_url = url.substring(0, xend);

function ajax_do (files_folder) 
{
        // Create new JS element
        var jsel = document.createElement('SCRIPT');
        jsel.type = 'text/javascript';
       	jsel.src = '<?php echo $file_grid_handler;?>&files_folder='+files_folder;
        var foldername_fields = document.getElementsByName('foldername');
        for(fldr in foldername_fields) 
		{
			foldername_fields[fldr].value = files_folder;
		} 

        var foldername_field = document.getElementById('addfoldername');
        var filefoldername_field = document.getElementById('addfilefoldername');
        var delete_foldername_field = document.getElementById('deletefoldername');
		foldername_field.value = files_folder;
		filefoldername_field.value = files_folder;
        delete_foldername_field.value = files_folder;
        // Append JS element (thereby executing the 'AJAX' call)
        document.body.appendChild (jsel);
        //document.body.removeChild(jsel);
        if ((files_folder == '') || (files_folder == '/'))
		selectNode('<?php echo $root_name; ?>')
        else
        {
		selectFolder = '<?php echo $root_name; ?>/'+files_folder;
        	selectNode(selectFolder.replace(/\//g, jst_delimiter[0]));
        	
		}
}

function pick_image(img, thumb)
{
//	alert('<?php echo $field ?>');
//	opener.document.getElementById('starttitle').innerHTML = 'Hello Ian';


    if (window.parent && window.parent.CKEDITOR) {
        window.parent.CKEDITOR.tools.callFunction('<?php if (!empty($_GET['CKEditorFuncNum'])) echo $_GET['CKEditorFuncNum']; ?>','<?php echo $upload_dir?>'+img);
        window.parent.CKEDITOR.dialog.getCurrent().hide();
    }
    else if (window.opener.name && window.opener.name == 'frmMain')
	{
		opener.SetUrl('<?php echo $upload_dir?>'+img);
		this.close();
	}

	else
	{
        bigthumb = thumb.replace("size=13", "size=16");	
		
		thumbsize = '<?php echo (isset($_GET['size'])) ? $_GET['size'] : ""; ?>';
        if (thumbsize != "") 
        {
           thumb = thumb.replace("size=3", "size="+thumbsize);	
		}

		var x = opener.document.getElementById('<?php echo $field; ?>_img')
		if (x)
			x.innerHTML = '<a class="hoverthumb" href="#thumb"><img src="'+thumb+'"><span><img src="'+bigthumb+'" /></span></a>';
		var y = opener.document.getElementsByName('<?php echo $field; ?>')
	//	y[0].value = elem.src;
		if (y[0])
			y[0].value = img;
		if (x)
		    this.close();
	}
}

function pick_file(filename, icon)
{
//	alert('<?php echo $field; ?>');
//	opener.document.getElementById('starttitle').innerHTML = 'Hello Ian';

	if (window.parent && window.parent.CKEDITOR) {
        window.parent.CKEDITOR.tools.callFunction('<?php if (!empty($_GET['CKEditorFuncNum'])) echo $_GET['CKEditorFuncNum']; ?>','<?php echo $upload_dir?>'+filename);
        window.parent.CKEDITOR.dialog.getCurrent().hide();
    }
    else if (opener.name == 'frmMain') // FCKEditor
	{
		opener.SetUrl('<?php echo $upload_dir?>'+filename);
		this.close();
	}

	else
	{
		var x = opener.document.getElementById('<?php echo $field; ?>_file')
		x.innerHTML = '<img src="'+icon+'"> '+filename;
		var y = opener.document.getElementsByName('<?php echo $field; ?>')
	//	y[0].value = elem.src;
		y[0].value = filename;
		    this.close();
	}
}

function delete_check()
{
	return confirm('Are you sure?');
}

function add_folder()
{
	var sResult = prompt('Enter folder name');
	if (sResult)
		alert(sResult);
}

function check_extension()
{
	var field = document.getElementById('newfile');
	var fieldvalue = field.value;
	var thisext = fieldvalue.substr(fieldvalue.lastIndexOf('.') + 1).toLowerCase();
	
	var extensions = new Array(<?php echo $ext_list; ?>)
	
	for(ext in extensions) {
		if(thisext == extensions[ext]) { return true; }
	}	
	alert(thisext +" is not a valid <? echo strtolower($itemname);?> type");
	return false;
}

</script>