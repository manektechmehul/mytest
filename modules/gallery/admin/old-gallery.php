<?php
// declarations
$admin_tab = 'gallery_admin';
$table = 'gallery_image';
$group_name = 'Gallery Images';
$single_name = 'Gallery Image';

$singular = 'a';

$fields = array(
			'title' => array('name' => 'Title', 'formtype' => 'text', 'required' => true, 'list' => true, 'primary' => true),
//			'name' => array('name' => 'Name', 'formtype' => 'text', 'required' => true, 'list' => true, 'primary' => true),
			'imagename' => array('name' => 'Image', 'formtype' => 'image', 'required' => true),
			'description' => array('name' => 'Description', 'formtype' => 'textarea', 'rows' => 8, 'cols' => 60, 'required' => false, 'list' => false),
			);

$parent_id_name = 'gallery_id';			
			

session_cache_limiter('must-revalidate');
session_start();

//include '../php/thumbnails_inc.php';

// Changes to remove need for register globals and avoid warnings
//  -- start

// general variable declarations
$acc = 0;
$bgcolor = "";
$PHP_SELF = $_SERVER['PHP_SELF'];

// Get Session variables
$session_user_id =  (isset($_SESSION['session_user_id'])) ? $_SESSION['session_user_id'] : "";
$session_user_type_id = (isset($_SESSION['session_user_type_id'])) ? $_SESSION['session_user_type_id'] : "";
$session_access_to_cms = (isset($_SESSION['session_access_to_cms'])) ? $_SESSION['session_access_to_cms'] : "";

// post, get or file variable declarations


$gallery_id = "";
$id = "";
$confirm_delete = "";
$submit_edit = "";
$status = "";
$delete_item = "";
$edit_item = "";


// Get get and post variables 
if (isset($_REQUEST["$parent_id_name"])) $parent_id_value = $_REQUEST["$parent_id_name"];
if (isset($_REQUEST['id'])) $id = $_REQUEST['id'];
if (isset($_REQUEST['confirm_delete'])) $confirm_delete = $_REQUEST['confirm_delete'];
if (isset($_REQUEST['delete_item'])) $delete_item = $_REQUEST['delete_item'];
if (isset($_REQUEST['edit_item'])) $edit_item = $_REQUEST['edit_item'];
if (isset($_REQUEST['submit_edit'])) $submit_edit = $_REQUEST['submit_edit'];

$id = (isset($_REQUEST['id'])) ? $_REQUEST['id'] : "";

// shorten body of text if wish to only display first $ln chars
function shorten_text($text, $ln )
{
	$body_len=strlen($text);
	$text=substr($text,0,$ln);
	if ($body_len > $ln) {
		$text=$text . "...";
	}
	return $text;
}

foreach ($fields as $fieldname => $field) 
	$data[$fieldname] = (isset($_REQUEST[$fieldname])) ? $_REQUEST[$fieldname] : "";

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Administration</title>

<link href="../../../admin/css/adminstylesheet.css" rel="stylesheet" type="text/css" />

<script>
function call_image_move(id, direction)
{
    // Create new JS element
    var jsel = document.createElement('SCRIPT');
    jsel.type = 'text/javascript';
	type = 'gallery_image';
    jsel.src = '/admin/ajax_functions.php?type='+type+'&id='+id+'&direction='+direction;

    // Append JS element (thereby executing the 'AJAX' call)
    document.body.appendChild (jsel);
    //document.body.removeChild(jsel);
}

function call_image_show_hide(id, action)
{
    // Create new JS element
    
    var jsel = document.createElement('SCRIPT');
    jsel.type = 'text/javascript';
	type = 'gallery_image';
    jsel.src = '/admin/ajax_functions.php?type='+type+'&id='+id+'&action='+action;

    // Append JS element (thereby executing the 'AJAX' call)
    document.body.appendChild (jsel);
    //document.body.removeChild(jsel);
}

function swaprows (first, second)
{
	var parent = second.parentNode;
	var newrow = second.cloneNode(true);
	parent.removeChild(second);
	parent.insertBefore(newrow, first);	
}

function move_image_up(elem, id)
{
	var row = elem.parentNode.parentNode.parentNode;
	var prevRow = row.previousSibling;
	if (prevRow.id != 'gallery_top_row')
	{
		elem.src = elem.src.replace('over.', 'off.');
		elem.oSrc = elem.src;
		call_image_move(id, 'up');
        swaprows(prevRow,row)
		
		//var inner = row.innerHTML;
		//alert(inner);
		//var prevInner = prevRow.innerHTML;
		//row.innerHTML = prevInner;
		//prevRow.innerHTML = inner;
	}
}

function move_image_down(elem, id)
{
	var row = elem.parentNode.parentNode.parentNode;
	var nextRow = row.nextSibling;
	if (nextRow.id != 'gallery_bottom_row')
	{
		elem.src = elem.src.replace('over.', 'off.');
		elem.oSrc = elem.src;
		call_image_move(id, 'down');
        swaprows(row,nextRow)

//		var inner = row.innerHTML;
//		row.innerHTML = nextRow.innerHTML;
//		nextRow.innerHTML = inner;
	}
}

function show_image(elem, id)
{
	var row = elem.parentNode.parentNode;   
	elem.oSrc = '/admin/images/gallery-hideimage-off.gif';
	elem.src = '/admin/images/gallery-hideimage-over.gif';
	elem.onclick = function () { hide_image(this, id); };
	var namefield = row.firstChild.firstChild
	namefield.style.backgroundImage =  'url(/admin/images/gallery-field-active.gif)';
	call_image_show_hide(id, 'show');
}

function hide_image(elem, id)
{
	var row = elem.parentNode.parentNode;
	elem.oSrc = '/admin/images/gallery-showimage-off.gif';
	elem.src = '/admin/images/gallery-showimage-over.gif';
	elem.onclick = function () { show_image(this, id); };
	var namefield = row.firstChild.firstChild
	namefield.style.backgroundImage =  'url(/admin/images/gallery-field-hidden.gif)';
	call_image_show_hide(id, 'hide');
}

function button_off (elem)
{
	MM_swapImgRestore();
}

function button_over(elem)
{
	
	var newscr = elem.src.replace('off.', 'over.');
	MM_swapImage(elem.name,'',newscr,0)
}

</script>


<style>
.gallery_cell {
	height:73px;
	background:url(/admin/images/gallery-tallbg.gif);	
	vertical-align: middle;
}
.image_cell {
	width:100%;
	text-align:right;
	padding-right:2px
}
</style>

<script>

function selectimage(elem)
{
	var myFile = '/php/filecontroller/imagecontroller.php?field='+elem;
	var myName = 'Image Manager'
	var image = window.open(myFile, '',"left=0,top=0,width=900,height=600,status=no,toolbar=no,directories=no,scrollbars=yes,location=no,resizable=no,menubar=no");


	return false;
}

</script>

</head>

<body>
<?php
unset($_SESSION["session_section_id"]);
unset ($session_section_id);

$path_prefix = "..";

include ("../../../php/databaseconnection.php");
include ("../../../php/read_config.php");

include ("../../../admin/cms_functions_inc.php");

if (!isset($admin_tab ))
	$admin_tab = "content_admin";

$use_admin_header = "1";
include ("../../../admin/process_login_inc.php");
include ("../../../admin/admin_header_inc.php");

$uploaddir = "../../../UserFiles";

include("../../../html_editor/fckeditor.php");	

include ("../../../php/thumbnails_inc.php");

?>

<?php

if (($session_user_id) && ($session_access_to_cms))
{
  
//	printf ("<h1>Content Administration</h1><p>");
	printf ("<h1>$group_name</h1><p>");

	// CONTENT STARTS HERE    
	
	if (($session_access_to_cms) || ($session_user_type_id == "1")) {

		if ($edit_item) {

			// ADD OR EDIT ITEM

			$content_sql = "SELECT * FROM $table WHERE id = '$id'";
			$content_result = mysql_query($content_sql);
			$content_row = mysql_fetch_array($content_result);

			$parent_part = ($parent_id_name) ? "?$parent_id_name=$parent_id_value" : '';
			printf ("<p><form action=%s%s method=POST ENCTYPE=\"multipart/form-data\">", $PHP_SELF, $parent_part);

			printf ("<input type=\"hidden\" name=\"id\" value=\"%s\">", $id);

			echo "<table>";

			$textfield_template = '<tr valign=top><td>%s</td>
				<td><input type=text name=%s size=65 value="%s"></td></tr></td></tr>';
			$textfield_short_template = '<tr valign=top><td>%s</td>
				<td><input type=text name=%s size=30 value="%s"></td></tr></td></tr>';
			$textarea_template = '<tr valign=top><td>%s</td>
				<td><textarea name=%s rows="%s" cols="%s">%s</textarea></td></tr></td></tr>';
			$address_outer_template = '<tr valign=top><td>%s</td>
				<td>%s</td></tr></td></tr>';
			$address_inner_template = '<input type=text name=%s size=65 value="%s">';
			$checkbox_template = '<tr valign=top><td>%s</td>
					<td><input type=checkbox name=%s %s value=%s></td></tr></td></tr>';
			$image_template = '<tr valign=top><td>%s</td>
					<td><input type=hidden name=%s value=%s><span id="%s_img">%s</span><button onclick="return selectimage(\'%s\');">Choose Image</button></td></tr></td></tr>';

			
			
			foreach ($fields as $fieldname => $field) 
			{	
				if ($field['formtype'] == 'text')
					printf($textfield_template, $field['name'], $fieldname, $content_row[$fieldname]);
				if ($field['formtype'] == 'textarea')
					printf($textarea_template, $field['name'], $fieldname, $field['rows'], $field['cols'], $content_row[$fieldname]);
				if ($field['formtype'] == 'shorttext')
					printf($textfield_short_template, $field['name'], $fieldname, $content_row[$fieldname]);
				if ($field['formtype'] == 'checkbox') {
					$checked = ($content_row[$fieldname]) ? "checked" : "";
					printf($checkbox_template, $field['name'], $fieldname, $checked, 1);
				}
				if ($field['formtype'] == 'image')
				{
					if ($content_row[$fieldname])
						$image_html = show_thumb($content_row[$fieldname],11);
					else 
						$image_html = '';
					printf($image_template, $field['name'], $fieldname, $content_row[$fieldname], $fieldname, $image_html, $fieldname);
				}
			}


			// Show the submit button
			printf ("<tr valign=top><td   ></td>
				 <td  > <INPUT TYPE=\"button\" VALUE=\"Cancel\" onClick=\"history.go(-1)\"> &nbsp;&nbsp;&nbsp;<input type=submit name=submit_edit value=\"Submit\">
				 </td></tr>");				

			echo "</table>";
			echo "</form>";						
			mysql_close ( $link );
			include ("../../../admin/admin_footer_inc.php");
			exit;

		}
		else if ($submit_edit) {
			
			// SUBMIT ITEM EDITS
			foreach ($fields as $fieldname => $field) 
			{
				$missing = array();
				if ((isset($field['required'])) && ($field['required']))
				{
					if ((!isset($data[$fieldname])) || (!$data[$fieldname]))
						$missing[] = $field['name'];
				}
			}
			
			if (count($missing) > 0)
			{
				printf ("<b>ERROR</b>: <b>Information missing</b>");
		        printf ("<p>You have not entered the item's:<ul>");
				foreach($missing as $missingitem)
					printf ("<li>$missingitem</li>");	
				printf ("</ul>");

		        printf ("<p>Please return to the form to enter the missing information.</p>");
				printf ("<p><INPUT TYPE=\"button\" VALUE=\"Back\" onClick=\"history.go(-1)\">");
                include ("../../../admin/admin_footer_inc.php");
		        exit();
			}
				
/*
			  if (strpos($title,"\"") > 0) {
				
				printf ("<b>ERROR</b>: <b>Invalid character:</b>");
				printf ("<p>You have used double quotes within the Title.<p>Please click on the Back button, and replace them with single quotes.");
				printf ("<p><INPUT TYPE=\"button\" VALUE=\"Back\" onClick=\"history.go(-1)\">");

				include ("./admin_footer_inc.php"); 
			        exit();
				}
*/				
				
			if ($id) 
			{				
				$sql = "update $table set ";
				$i = 1;
				$c = count($fields);
				foreach ($fields as $fieldname => $field) 
				{	
					$sql .= $fieldname." = '{$data[$fieldname]}'";
					if ($i++ < $c)
						$sql .= ', ';
				}
				$sql .= " where id = $id";

				$result = mysql_query($sql);
			}
			else
			{

				$i = 1;
				$c = count($fields);
				$sql_fields = "";
				$sql_data = "";
				if ($parent_id_name)
				{
					$sql_fields = "$parent_id_name, ";
					$sql_data = "'$parent_id_value', ";
				}
				foreach ($fields as $fieldname => $field) 
				{
					$sql_fields .= $fieldname . ', ';
					$sql_data .= "'{$data[$fieldname]}'" . ', ';
				}

				$sql_fields .= 'order_num';
				$order_sql = "select max(order_num) + 10 as order_num from {$table}";
				$order_result = mysql_query($order_sql);
				$order_row = mysql_fetch_array($order_result);
				$sql_data .= "'{$order_row['order_num']}'";

				$sql = "insert into $table ({$sql_fields}) values ({$sql_data})";
				$result = mysql_query($sql);
				$id = mysql_insert_id();

			}

				// check results
				if ($result) {

/*					echo "<p>The details have been successfully submitted.";
					echo "<ul>";

					printf ("<li><a href=\"%s\">List $group_name</a>", $PHP_SELF);
					printf ("<li><a href=\"content_admin.php\">Return to list of all pages</a><br><br></li>");
					*/
				} else {
					echo $sql;
					echo "<p>.Sorry, an error has occurred.  The content could not be added. Please contact the web administrator";
					mysql_close ( $link );
                    include ("../../../admin/admin_footer_inc.php");
					exit;
					//echo "$update_sql $insert_sql";

				}
				echo "</ul>";

		//	mysql_close ($link);			

			}
			else if ($delete_item) {

				// DELETE ITEM

				if ($confirm_delete == "yes") {					

				//	include ("../php/databaseconnection.php");


					$delete_content_sql = "DELETE FROM $table WHERE id=$id";	

					if ($delete_content_result = mysql_query($delete_content_sql)) {											

						//  XXXX delete the file
/*						echo "<p>This item has been successfully deleted.";
						echo "<ul>";
						printf ("<li><a href=\"%s\">List $group_name</a><br><br></li>", $PHP_SELF);
						printf ("<li><a href=\"%s?edit_item=yes\">Add $singular $single_name</a><br><br></li>", $PHP_SELF);
						printf ("<li><a href=\"content_admin.php\">Return to list of pages</a><br><br></li>");
						echo "</ul>";	
*/
						}
						else {
							printf ("<p>This item was unable to be deleted");
							mysql_close ( $link );
                            include ("../../../admin/admin_footer_inc.php");
							exit;
					    }
					     	     

			//		mysql_close ($link);
					}
					else {
						printf ("Please confirm that you wish to delete this item:");	
						$parent_part = ($parent_id_name) ? "$parent_id_name=$parent_id_value&" : '';
						printf ("<ul><li><a href=\"%s?%sdelete_item=yes&confirm_delete=yes&id=%s\">Yes</a> - I confirm that wish to delete this item<br><br></li>", $PHP_SELF, $parent_part, $id);
						printf ("<li><a href=\"%s\">No</a> - I do not wish to delete this item</li></ul>", $PHP_SELF);

						mysql_close ( $link );
                        include ("../../../admin/admin_footer_inc.php");
						exit;
					}
                }
//					printf ("<p>[ <a href=\"%s?edit_item=yes\">Add $singular $single_name</a> ]", $PHP_SELF);
				$buttons = "onmouseout='button_off(this)' onmouseover='button_over(this)'";	
				echo "<table cellpadding=0 cellspacing=0 width=100%>";
				echo "<tr id='gallery_top_row'><td colspan=6 style='width:100%;background:url(/admin/images/lightbox-bk.gif)'>";
				$parent_part = ($parent_id_name) ? "$parent_id_name=$parent_id_value&" : '';
				echo "<a href=\"$PHP_SELF?{$parent_part}edit_item=yes\"><img $buttons name='addimagebtn' style='border:0' src='/admin/images/gallery-addnewimage-off.gif'/></a>";
				echo "</td></tr>";

				// LATEST ITEMS
				if ($parent_id_name)
					$content_sql = "SELECT * FROM $table where $parent_id_name = $parent_id_value order by order_num";
				else
					$content_sql = "SELECT * FROM $table order by order_num";
				$content_result = mysql_query($content_sql);
			
				if (mysql_num_rows($content_result) > 0 ) {								
										
/*						foreach ($fields as $fieldname => $field) 
					{
						if (isset($field['list']) && ($field['list'] == true))
						{
							echo "<td><center><b>{$field['name']}</b></center></td>";
						}
						echo "<td></td></tr>";
					}
*/							
					$row_num = 1;
					while ($content_row = mysql_fetch_array($content_result)) {				

						//$text = shorten_text($text, 140);
								
						echo "<tr>";
						$rowsecond = "";
						foreach ($fields as $fieldname => $field) 
						{
							if (isset($field['list']) && ($field['list'] == true))
							{
								$value = $content_row[$fieldname];
								if (($field['formtype'] == 'textarea') && isset($field['shorten']) && ($field['shorten'] == true))
									$value = shorten_text($value, 140);
							
								if ($content_row['published'] == true)
									$background = 'gallery-field-active.gif';
								else
									$background = 'gallery-field-hidden.gif';
								if (isset($field['primary']) && ($field['primary'] == true)) 
									$rowfirst =  sprintf ("<td class='gallery_cell'><div style='height:24px;padding:8px 7px 0px 40px;width:250px;display:block;float:left;background:url(/admin/images/$background);'><strong>%s</strong></td>", 
										$value);
								else
									$rowsecond .= sprintf ("<td class='gallery_cell'>%s</td>", $value);
									
								echo $rowfirst . $rowsecond;
							}
						}

						echo "<td width=40 class='gallery_cell'>";
						printf ("<a href=\"%s?%sedit_item=yes&id=%s\"><img $buttons name='imgeditbtn%s' style='border:0' src='/admin/images/gallery-edit-off.gif' /></a>", $PHP_SELF, $parent_part, $content_row["id"], $content_row["id"]);
						echo "</td>";
						if ($content_row['published'] == true)
							printf ("<td class='gallery_cell'><img name='imgpubbtn%s' $buttons onclick='hide_image(this, \"%s\")' style='border:0' src='/admin/images/gallery-hideimage-off.gif' /></td>", $content_row["id"], $content_row["id"], $PHP_SELF);
						else
							printf ("<td class='gallery_cell'><img name='imgpubbtn%s' $buttons onclick='show_image(this, \"%s\")' style='border:0' src='/admin/images/gallery-showimage-off.gif' /></td>", $content_row["id"], $content_row["id"], $PHP_SELF);
						printf ("<td class='gallery_cell'><a href=\"%s?%sdelete_item=yes&id=%s\"><img $buttons name='imgedeletebtn%s' style='border:0' src='/admin/images/gallery-delete-off.gif' /></a></td>", $PHP_SELF, $parent_part, $content_row["id"],$content_row["id"]);
						//printf ("<a href=\"%s?preview=yes&id=%s\">Preview</a> | ", $PHP_SELF, $content_row["id"]);
						echo "<td class='gallery_cell'>";
						echo "<div style='padding-top:13px;height:46px;width:10px;'>";
						//printf ("<a href=\"%s?delete_item=yes&id=%s\"><img style='border:0' src='/admin/images/subbutton-moveup-off.gif' /></a>", $PHP_SELF, $content_row["id"]);
						//printf ("<a href=\"%s?delete_item=yes&id=%s\"><img style='border:0' src='/admin/images/subbutton-movedown-off.gif' /></a></div>", $PHP_SELF, $content_row["id"]);
						printf ("<img onclick='move_image_up(this, \"%s\")' $buttons name='imgmoveupbtn%s' style='border:0' src='/admin/images/button-moveup-off.gif' />", $content_row["id"], $content_row["id"]);
						printf ("<img onclick='move_image_down(this, \"%s\")' $buttons name='imgmovedownbtn%s' style='border:0' src='/admin/images/button-movedown-off.gif' />", $content_row["id"], $content_row["id"]);
						echo "</div></td>";
						
						
						echo "<td class='gallery_cell image_cell'>";
						echo show_thumb($content_row['imagename'], 12);
						echo "</td>";
						echo "</tr>";
							 

						 $row_num = $row_num + 1;
					}
					echo "<tr id='gallery_bottom_row'><td colspan=6 style='height:35px;width:100%;background:url(/admin/images/lightbox-bk.gif)'>  </td></tr>";
					printf ("</table>");

					
					$btn = '<div class="sectionbutton-row"><a onmouseover="MM_swapImage(\'defaultbacktogalleries\',\'\',\'/admin/images/buttons/cmsbutton-Back_to_Gallery_Admin-over.gif\',0)" onmouseout="MM_swapImgRestore()" href="galleries.php"><img name="defaultbacktogalleries" src="/admin/images/buttons/cmsbutton-Back_to_Gallery_Admin-off.gif" style="border: medium none ;"/></a></div>';
					echo $btn;
				}
				else {
					printf ("<p>There are currently no $group_name<p>");
				}	
	}
	else {
		printf ("You do not have the appropriate type of login account to view this page.
				 <P>Please <a href=\"../logout.php\">logout</a> then login again using an Admin account.");
	}
}
else {
	if ($login)
		echo $login_error;
	else
		include ("../../../admin/login_inc.php"); 
}
?>  
<!-- CONTENT ENDS HERE -->
<?php

mysql_close ( $link );
include ("../../../admin/admin_footer_inc.php");
?>
				
