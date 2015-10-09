<?php
	session_cache_limiter( 'must-revalidate' );
	session_start();
	$testCKEditor = true;
// Changes to remove need for register globals and avoid warnings
//  -- start
// general variable declarations
	$acc                      = 0;
	$rowBlocks                = array( 0 => 0, 1 => 0, 2 => 0, 3 => 0 );
	$admin_tab                = "";
	$bgcolor                  = "";
	$PHP_SELF                 = $_SERVER['PHP_SELF'];
	$thumbnail_image_filename = "";
// Get Session variables
	$session_user_id       = ( isset( $_SESSION['session_user_id'] ) ) ? $_SESSION['session_user_id'] : "";
	$session_user_type_id  = ( isset( $_SESSION['session_user_type_id'] ) ) ? $_SESSION['session_user_type_id'] : "";
	$session_access_to_cms = ( isset( $_SESSION['session_access_to_cms'] ) ) ? $_SESSION['session_access_to_cms'] : "";
// post, get or file variable declarations
	$content_type_id           = "";
	$delete_image              = "";
	$path                      = "";
	$add_new_main_section      = "";
	$add_new_sub_section       = "";
	$preview                   = "";
	$submit_users_to_edit_page = "";
	$main_section_name           = "";
	$main_section_order_num      = "";
	$section_id                  = "";
	$parent_id                   = "";
	$sub_section_name            = "";
	$sub_section_order_num       = "";
	$id                          = "";
	$title                       = "";
	$nav_title                   = "";
	$thumbnail_imagebinFile      = "";
	$auto_resize_thumbnail       = "";
	$auto_resize_fullsize        = "";
	$template_type               = "";
	$order_num                   = "";
	$live                        = "";
	$template_type               = "";
	$blog_id                     = "";
	$body                        = "";
	$imagebinFile_name           = "";
	$thumbnail_imagebinFile_name = "";
	$image_filename              = "";
	$permitted_users             = "";
	$num_images                  = "";
	$page_name                   = "";
	$page_summary                = "";
	$move                        = "";
// Get get and post variables
	if ( isset( $_REQUEST['poll_id'] ) ) {
		$poll_id = $_REQUEST['poll_id'];
	}
	if ( isset( $_REQUEST['gallery_id'] ) ) {
		$gallery_id = $_REQUEST['gallery_id'];
	}
	if ( isset( $_REQUEST['donate_id'] ) ) {
		$donate_id = $_REQUEST['donate_id'];
	}
	if ( isset( $_REQUEST['add_new_main_section'] ) ) {
		$add_new_main_section = $_REQUEST['add_new_main_section'];
	}
	if ( isset( $_REQUEST['add_new_sub_section'] ) ) {
		$add_new_sub_section = $_REQUEST['add_new_sub_section'];
	}
	if ( isset( $_REQUEST['auto_resize_thumbnail'] ) ) {
		$auto_resize_thumbnail = $_REQUEST['auto_resize_thumbnail'];
	}
	if ( isset( $_REQUEST['auto_resize_fullsize'] ) ) {
		$auto_resize_fullsize = $_REQUEST['auto_resize_fullsize'];
	}
	if ( isset( $_REQUEST['content_type_id'] ) ) {
		$content_type_id = $_REQUEST['content_type_id'];
	}
	if ( isset( $_REQUEST['confirm_delete'] ) ) {
		$confirm_delete = $_REQUEST['confirm_delete'];
	}
	if ( isset( $_REQUEST['blog_id'] ) ) {
		$blog_id = $_REQUEST['blog_id'];
	}
	if ( isset( $_REQUEST['body'] ) ) {
		$body = $_REQUEST['body'];
	}
	if ( isset( $_REQUEST['path'] ) ) {
		$path = $_REQUEST['path'];
	}
	if ( isset( $_REQUEST['form_id'] ) ) {
		$form_id = $_REQUEST['form_id'];
	}
	if ( isset( $_REQUEST['id'] ) ) {
		$id = $_REQUEST['id'];
	}
	if ( isset( $_REQUEST['main_section_name'] ) ) {
		$main_section_name = $_REQUEST['main_section_name'];
	}
	if ( isset( $_REQUEST['main_section_order_num'] ) ) {
		$main_section_order_num = $_REQUEST['main_section_order_num'];
	}
	if ( isset( $_REQUEST['num_images'] ) ) {
		$num_images = $_REQUEST['num_images'];
	}
	if ( isset( $_REQUEST['order_num'] ) ) {
		$order_num = $_REQUEST['order_num'];
	}
	if ( isset( $_REQUEST['parent_id'] ) ) {
		$parent_id = $_REQUEST['parent_id'];
	}
	if ( isset( $_REQUEST['permitted_users'] ) ) {
		$permitted_users = $_REQUEST['permitted_users'];
	}
	if ( isset( $_REQUEST['preview'] ) ) {
		$preview = $_REQUEST['preview'];
	}
	if ( isset( $_REQUEST['section_id'] ) ) {
		$section_id = $_REQUEST['section_id'];
	}
	if ( isset( $_REQUEST['sub_section_name'] ) ) {
		$sub_section_name = $_REQUEST['sub_section_name'];
	}
	if ( isset( $_REQUEST['sub_section_order_num'] ) ) {
		$sub_section_order_num = $_REQUEST['sub_section_order_num'];
	}
	if ( isset( $_REQUEST['submit_users_to_edit_page'] ) ) {
		$submit_users_to_edit_page = $_REQUEST['submit_users_to_edit_page'];
	}
	if ( isset( $_REQUEST['template_type'] ) ) {
		$template_type = $_REQUEST['template_type'];
	}
	if ( isset( $_REQUEST['title'] ) ) {
		$title = $_REQUEST['title'];
	}
	if ( isset( $_REQUEST['nav_title'] ) ) {
		$nav_title = $_REQUEST['nav_title'];
	}
	if ( isset( $_REQUEST['template_type'] ) ) {
		$template_type = $_REQUEST['template_type'];
	}
	if ( isset( $_REQUEST['page_name'] ) ) {
		$page_name = $_REQUEST['page_name'];
	}
	if ( isset( $_REQUEST['page_summary'] ) ) {
		$page_summary = $_REQUEST['page_summary'];
	}
	if ( isset( $_REQUEST['move'] ) ) {
		$move = $_REQUEST['move'];
	}
	if ( isset( $_REQUEST['page_image'] ) ) {
		$page_image = $_REQUEST['page_image'];
	}
	if ( isset( $_REQUEST['thumbnail_image_loc'] ) ) {
		$thumbnail_image_loc = $_REQUEST['thumbnail_image_loc'];
	}
	if ( isset( $_REQUEST['hidepageimage'] ) ) {
		$hidepageimage = $_REQUEST['hidepageimage'];
	}
	/*
	  Actions
	 */
	if ( isset( $_REQUEST['submit_new_main_section'] ) ) {
		$action = 'submit_new_main_section';
	}
	if ( isset( $_REQUEST['submit_new_sub_section'] ) ) {
		$action = 'submit_new_sub_section';
	}
	if ( isset( $_REQUEST['delete_sub_section'] ) ) {
		$action = 'delete_sub_section';
	}
	if ( isset( $_REQUEST['delete_section'] ) ) {
		$action = 'delete_section';
	}
	if ( isset( $_REQUEST['hide_page'] ) ) {
		$action = 'hide_page';
	}
	if ( isset( $_REQUEST['show_page'] ) ) {
		$action = 'show_page';
	}
	if ( isset( $_REQUEST['show_article'] ) || isset( $_REQUEST['hide_article'] ) ) {
		$action = 'show_hide_item';
	}
	if ( isset( $_REQUEST['delete_item'] ) ) {
		$action = 'delete_item';
	}
	if ( isset( $_REQUEST['submit_tags'] ) ) {
		$action = 'submit_tags';
	}
	if ( isset( $_REQUEST['submit_users_to_edit_page'] ) ) {
		$action = 'submit_editors';
	}
	if ( isset( $_REQUEST['set_default_image'] ) ) {
		$action = 'set_default_image';
	}
	if ( isset( $_REQUEST['set_default_logo'] ) ) {
		$action = 'set_default_logo';
	}
	if ( isset( $_REQUEST['submit_edit'] ) || isset( $_REQUEST['submit_edit_x'] ) ) {
		$action = 'submit_edit';
		$live   = 1;
	}
	if ( isset( $_REQUEST['submit_hidden'] ) || isset( $_REQUEST['submit_hidden_x'] ) ) {
		$action = 'submit_edit';
		$live   = 0;
	}
//var_dump($_REQUEST);
	if ( $move ) {
		$action = 'move';
	}
	/*
	  Pages
	 */
	$admin_page = '';
	if ( isset( $_REQUEST['edit_tags_sub_section'] ) ) {
		$admin_page = 'edit_tags';
	}
	if ( isset( $_REQUEST['list_articles'] ) ) {
		$admin_page = 'list_articles';
	}
	if ( isset( $_REQUEST['select_editors'] ) ) {
		$admin_page = 'select_editors';
	}
	if ( isset( $_REQUEST['edit_item'] ) ) {
		$admin_page = 'edit_item';
	}
	if ( isset( $_REQUEST['edit_default_page_image'] ) ) {
		$admin_page = 'default_image';
	}
	if ( isset( $_REQUEST['edit_default_page_logo'] ) ) {
		$admin_page = 'default_logo';
	}
	$tags_title       = ( isset( $_REQUEST['tags_title'] ) ) ? $_REQUEST['tags_title'] : "";
	$tags_description = ( isset( $_REQUEST['tags_description'] ) ) ? $_REQUEST['tags_description'] : "";
	$tags_keywords    = ( isset( $_REQUEST['tags_keywords'] ) ) ? $_REQUEST['tags_keywords'] : "";
	$show_hide_item = ( isset( $_REQUEST['show_article'] ) ) ? 'show' : "";
	if ( ! $show_hide_item ) {
		$show_hide_item = ( isset( $_REQUEST['hide_article'] ) ) ? 'hide' : "";
	}
	include( "../php/databaseconnection.php" );
	include( "../php/read_config.php" );
	function cms_admin_button( $href, $type, $text, $extra = '' ) {
		global $acc;
		$button_name = $type . str_replace( ' ', '', $text );
		$image       = '/admin/images/' . $type . '-' . str_replace( ' ', '', $text );
		if ( ! file_exists( '../' . $image . '-off.gif' ) ) {
			$image = '/admin/images/buttons/' . $type . '-' . str_replace( ' ', '_', $text );
		}
		echo "<td width=0 rowspan=2>";
		$acc ++;
		printf( "<a href='%s' onmouseout=\"MM_swapImgRestore()\" onmouseover=\"MM_swapImage('%s','','%s-over.gif',0)\" $extra><img src=\"%s-off.gif\" alt=\"%s\" name=\"%s\"  /></a>", $href, $button_name . $acc, $image, $image, $text, $button_name . $acc );
		echo "</td>";
	}

	function cms_admin_submit_button( $type, $text, $name, $value, $extra = '' ) {
//	global $acc;
		$button_name = $type . str_replace( ' ', '', $text );
		$img         = '/admin/images/' . $type . '-' . str_replace( ' ', '', $text );
		if ( ! file_exists( '../' . $img . '-off.gif' ) ) {
			$img = '/admin/images/buttons/' . $type . '-' . str_replace( ' ', '_', $text );
		}
//	$acc ++;
		echo "<input type=\"image\" src=\"$img-off.gif\" id=\"$name\" name=\"$name\" value=\"$value\" $extra onmouseout=\"MM_swapImgRestore()\" onmouseover=\"MM_swapImage('$name','','$img-over.gif',0)\" >";
	}

	function make_sub_section_row( $sub_section_row, $level, $rowBlock ) {
		global $acc;
		global $session_user_type_id;
		global $session_user_id;
		global $PHP_SELF;
		$acc                             = $acc + 1;
		$list_of_users_who_can_edit_page = $sub_section_row["edited_by_user_id"];
		$user_id_to_search_for           = "," . $session_user_id . ",";
		if ( ( strstr( $list_of_users_who_can_edit_page, $user_id_to_search_for ) ) ||
		     ( $session_user_type_id == "1" )
		) {
			$content_type_id = $sub_section_row["id"];
			$rowControlSql = "SELECT has_body, has_subsections, has_articles, can_edit, can_delete
			 FROM content_type ct
			 join page_type pt on ct.page_type  = pt.id
			 WHERE ct.id = '$content_type_id'";
			$rowControl = db_get_single_row( $rowControlSql );
			$content_sql    = "SELECT * FROM content WHERE content_type_id = '$content_type_id'  and template_type = 'main_body'";
			$content_result = mysql_query( $content_sql );
			$content_row    = mysql_fetch_array( $content_result );
			echo "<div class=\"buttonsRow\" level=\"$level\" block=\"$rowBlock\" recId=\"$content_type_id\">";
			echo "<div class=\"submain\">";
			echo '<table width="100%" border="0" cellspacing="0" cellpadding="0">';
			echo '<tr>';
			$sub_section_name = $sub_section_row["name"];
			if ( strlen( $sub_section_name ) > 30 ) {
				$sub_section_name = substr( $sub_section_name, 0, 27 );
				$sub_section_name = $sub_section_name . "...";
			}
			// SUB-SECTION NAME
			if ( $level == 1 ) {
				if ( $sub_section_row["status"] == 0 ) {
					$sub_section_class_status = "contentsubfieldinactive";
				} else {
					$sub_section_class_status = "contentsubfieldactive";
				}
			} else {
				if ( $sub_section_row["status"] == 0 ) {
					$sub_section_class_status = "contentpagefieldinactive";
				} else {
					$sub_section_class_status = "contentpagefieldactive";
				}
			}
			printf( "<td width=296 rowspan=2><div class=\"%s\">%s</div></td>", $sub_section_class_status, $sub_section_name );
			$buttonType = ( $level == 1 ) ? 'contentsubbutton' : 'contentpagebutton';
			// EDIT PAGE /////////////////
			if ( $rowControl["can_edit"] == 1 ) {
				$href = sprintf( "%s?edit_item=yes&id=%s&content_type_id=%s", $PHP_SELF, $content_row["id"], $sub_section_row["id"] );
				cms_admin_button( $href, "$buttonType", "edit page" );
			}
			// ADD/EDIT ARTICLES ///////////////
			// todo: this is wrong events_content_type_id doesn't exist - setting it for now
			//$events_content_type_id = '';
			//if ($sub_section_row["id"] != $events_content_type_id) {
			//	$href =  sprintf ("%s?list_articles=yes&content_type_id=%s", $PHP_SELF, $sub_section_row["id"]);
			//	cms_admin_button($href, "$buttonType", "add edit articles");
			//}
			//else {
			// BLANK BUTTON
			//	echo "<td width=0 rowspan=2>";
			//	echo "<img src=\"/admin/images/main-bk.gif\" width=98 height=32 border=0>";
			//	echo "</td>";
			//}
			if ( SITE_HAS_ONPAGELINKS ) {
				$href = sprintf( "/modules/onpagelinks/admin/main.php?parent_id=%s&id=%s&parent_name=%s", $content_row["id"], $sub_section_row["id"], urlencode( $sub_section_name ) );
				cms_admin_button( $href, $buttonType, "promo links" );
			}
			// HIDE OR SHOW SUB-SECTION /////////////////
			if ( $session_user_type_id == "1" || ( $rowControl["can_edit"] == 1 ) ) {
				if ( $sub_section_row["status"] == 0 ) {
					// SHOW SUB-SECTION
					$href = sprintf( "%s?show_page=yes&content_type_id=%s", $PHP_SELF, $sub_section_row["id"] );
					cms_admin_button( $href, $buttonType, "show page", 'class="showButton showHideButton"' );
				} else {
					// HIDE SUB-SECTION
					$href = sprintf( "%s?hide_page=yes&content_type_id=%s", $PHP_SELF, $sub_section_row["id"] );
					cms_admin_button( $href, $buttonType, "hide page", 'class="hideButton showHideButton"' );
				}
			}
			// DELETE SUB-SECTION //////////
			if ( ( $session_user_type_id == "1" ) && ( $rowControl['can_delete'] == 1 ) ) {
				$href = sprintf( "%s?delete_sub_section=1&content_type_id=%s", $PHP_SELF, $sub_section_row["id"] );
				cms_admin_button( $href, $buttonType, "delete page", 'class="deleteButton"' );
			}
			// SELECT EDITORS ////////////////////
			if ( $session_user_type_id == "1" ) {
				$href = sprintf( "%s?select_editors=yes&content_type_id=%s", $PHP_SELF, $sub_section_row["id"] );
				cms_admin_button( $href, $buttonType, "select editors" );
			}
			//if ( $session_user_type_id == "1" ) {
			//	$href = sprintf( "%s?edit_tags_sub_section=1&content_type_id=%s", $PHP_SELF, $sub_section_row["id"] );
			//	cms_admin_button( $href, $buttonType, "edit tags" );
			//}
			// MOVE SUB-SECTION UP
			$moveButtonType = ( $level == 1 ) ? 'subbutton' : 'pagebutton';
			if ( $session_user_type_id == "1" ) {
				printf( "<td width='0'><a href='%s?content_type_id=%s&move=up'", $PHP_SELF, $sub_section_row["id"] );
				echo " class=\"upButton moveButton\"";
				echo " onMouseOut='MM_swapImgRestore()' onMouseOver=\"MM_swapImage('subup$acc','','/admin/images/$moveButtonType-moveup-over.gif',1)\">";
				echo "<img src='/admin/images/$moveButtonType-moveup-off.gif' alt='move page up' name='subup1' width='16' height='16' border='0' id='subup$acc' /></a></td>";
				$acc ++;
			}
			echo '<td width="100%" rowspan="2" style="background:url(/admin/images/submain-bk.gif);">';
			echo '<div align="right"><a href="/admin/help.php#sub_section"></div></td>';
			echo '</tr><tr>';
			if ( $session_user_type_id == "1" ) {
				printf( "<td width='0'><a href='%s?content_type_id=%s&move=down'", $PHP_SELF, $sub_section_row["id"] );
				echo " class=\"downButton moveButton\"";
				echo " onMouseOut='MM_swapImgRestore()' onMouseOver=\"MM_swapImage('subdown$acc','','/admin/images/$moveButtonType-movedown-over.gif',1)\">";
				echo "<img src='/admin/images/$moveButtonType-movedown-off.gif' alt='move page down' name='subup1' width='16' height='16' border='0' id='subdown$acc' /></a></td>";
				$acc ++;
			};
			echo '</tr></table></div>';
			if ( $rowControl["has_subsections"] ) {
				show_sub_sections( $content_type_id, $level + 1 );
			}
			echo '</div>';
		}
	}

	function show_sub_sections( $parent_id, $level ) {
		global $PHP_SELF;
		global $session_user_type_id;
		global $session_user_id;
		global $section_id;
		global $rowBlocks;
		$sub_section_sql    = "SELECT * FROM content_type WHERE parent_id = '$parent_id' AND level = $level ORDER BY parent_id, order_num";
		$sub_section_result = mysql_query( $sub_section_sql );
		$first              = true;
		$rowBlock           = $rowBlocks[$level] ++;
		while ( $sub_section_row = mysql_fetch_array( $sub_section_result ) ) {
			make_sub_section_row( $sub_section_row, $level, $rowBlock );
			if ( $first ) {
				$first = false;
			}
		}
		if ( ( $session_user_type_id == "1" ) && ( $section_id != "1" ) && ( $level < PAGE_LEVELS ) ) {
			echo '<div class="lightbox">' . "\n";
			echo '<table width="100%" border="0" cellspacing="0" cellpadding="0">' . "\n";
			echo '<tr>' . "\n";
			if ( $level == 1 ) {
				echo '<td width="0"><img src="/admin/images/lightbox-left-bk.gif" alt="spacer" width="32" height="12" /></td>' . "\n";
			} else {
				echo '<td width="0"><img src="/admin/images/lightbox-left-bk.gif" alt="spacer" width="64" height="12" /></td>' . "\n";
			}
			$href = sprintf( "%s?edit_item=yes&parent_id=%s&level=%s", $PHP_SELF, $parent_id, $level );
			if ( $level == 1 ) {
				cms_admin_button( $href, "content", "create new sub section page" );
			} else if ( $level == 2 ) {
				cms_admin_button( $href, "content", "create new page" );
			}
			echo '<td width="100%" style="background:url(/admin/images/lightbox-left-bk.gif);"><img src="/admin/images/lightbox-left-bk.gif" alt="spacer" width="100%" height="12" /></td>' . "\n";
			echo '</tr>' . "\n";
			echo '</table>' . "\n";
			echo '</div>' . "\n";
		}
	}

//next line is a special case
	if ( isset( $_FILES['thumbnail_imagebinFile'] ) ) {
		$thumbnail_imagebinFile      = $_FILES['thumbnail_imagebinFile']['tmp_name'];
		$thumbnail_imagebinFile_name = $_FILES['thumbnail_imagebinFile']['name'];
	}
	if ( isset( $_FILES['imagebinFile'] ) ) {
		$imagebinFile      = $_FILES['imagebinFile']['tmp_name'];
		$imagebinFile_name = $_FILES['imagebinFile']['name'];
	}
// The following do not appear to be used but if they are should be coverted to FILE
//
// if (isset($_REQUEST['imagebinFile_name'])) $imagebinFile_name  = $_REQUEST['imagebinFile_name'];
// if (isset($_REQUEST['image_filename'])) $image_filename  = $_REQUEST['image_filename'];
// end --
?>
	<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
		"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
	<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
		<title>Administration</title>
		<script type="text/javascript" src="//ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.min.js"></script>
		<!-- specific jQuery -->
		<script src="js/template.js"></script>
		<link href="./css/adminstylesheet.css" rel="stylesheet" type="text/css"/>
		<link href="http://fonts.googleapis.com/css?family=Gafata" rel="stylesheet" type="text/css">
		<script type="text/javascript">
			$('.moveButton').live('click', function (e) {
				e.preventDefault();
				$rowDiv = $(this).parents('.buttonsRow').first();
				currLevel = $rowDiv.attr('level');
				if ($(this).hasClass('upButton'))
					$otherRowDiv = $rowDiv.prevAll('.buttonsRow[level="' + currLevel + '"]').first();
				else
					$otherRowDiv = $rowDiv.nextAll('.buttonsRow[level="' + currLevel + '"]').first();
				if ($otherRowDiv.attr('level') == currLevel) {
					currId = $rowDiv.attr('recId')
					otherId = $otherRowDiv.attr('recId')
					currHtml = $rowDiv.html();
					$rowDiv.html($otherRowDiv.html());
					$otherRowDiv.html(currHtml);
					$rowDiv.attr('recId', otherId);
					$otherRowDiv.attr('recId', currId);
					url = '/admin/ajax/content.php';
					$.post(url, {action: 'move', level: currLevel, curr: currId, other: otherId});
				}
				return false;
			})

			$('.showHideButton').live('click', function (e) {
				e.preventDefault();
				$this = $(this);
				MM_swapImgRestore();
				$p = $this.parent();
				html = $p.html();
				action = ($this.hasClass('showButton')) ? 'show' : 'hide';
				html = html.replace(/showHide/g, 'xxxxxxxx');
				if (action == 'show')
					html = html.replace(/show/g, 'hide');
				else
					html = html.replace(/hide/g, 'show');
				html = html.replace(/xxxxxxxx/g, 'showHide');
				$p.html(html);

				id = html.match(/content_type_id=(\d+)/)[1]
				url = '/admin/ajax/content.php';
				$.post(url, {action: action, id: id});
			});




			$('.deleteButton').live('click', function (e) {
				e.preventDefault();
				if (confirm('Are You sure?')) {
					action = 'delete';
					$rowDiv = $(this).parents('.buttonsRow').first();
					level = $rowDiv.attr('level');
					id = $rowDiv.attr('recId');
					url = '/admin/ajax/content.php';
					$.post(url, {action: action, level: level, id: id});
					$rowDiv.remove();
				}
			});



			$('.securityButton').live('click', function (e) {
				e.preventDefault();
					action = 'secure';
					$rowDiv = $(this).parents('.buttonsRow').first();
					level = $rowDiv.attr('level');
					id = $rowDiv.attr('recId')
					url = '/admin/ajax/content.php';
					$.post(url, {action: action, level: level, id: id});
					$this = $(this);
					MM_swapImgRestore();
					$p = $this.parent();
					html = $p.html()
					action = ($this.hasClass('public')) ? 'public' : 'secure';
					if (action == 'public'){
						html = html.replace(/public/g, 'secure');
					}else{
						html = html.replace(/secure/g, 'public');
					}
					$p.html(html);
			});
 
		</script>
	</head>
<body>
<?php

	unset( $_SESSION["session_section_id"] );
	unset( $session_section_id );
	$path_prefix = "..";
	include( "./cms_functions_inc.php" );
	$admin_tab = "content_admin";
	$use_admin_header = "1";
	include( "./process_login_inc.php" );
	include( "./admin_header_inc.php" );
	$uploaddir = '..' . USER_IMAGE_DIR;
	if ( $testCKEditor ) {
		include "ckeditor/ckeditor.php";
	} else {
		include( "../html_editor/fckeditor.php" );
	}
	if ( ( $session_user_id ) && ( $session_access_to_cms ) ) {
		// CONTENT STARTS HERE
		if ( ( $session_access_to_cms ) || ( $session_user_type_id == "1" ) ) {
			/*
					  Process actions

					 */
			if ( ! isset( $action ) ) {
				$action = '';
			}
			switch ( $action ) {
				case ( 'delete_sub_section' ): {
					$content_type_sql    = "SELECT * FROM content_type WHERE id = '$content_type_id' ";
					$content_type_result = mysql_query( $content_type_sql );
					$content_type_row    = mysql_fetch_array( $content_type_result );
					$delete_sub_section_sql    = "DELETE FROM content_type WHERE id = '$content_type_id'";
					$delete_sub_section_result = mysql_query( $delete_sub_section_sql );
					$remove_page_name_sql    = "update content set page_name = '' WHERE content_type_id = '$content_type_id'";
					$remove_page_name_result = mysql_query( $remove_page_name_sql );
					if ( $delete_sub_section_result && $remove_page_name_result ) {
						$admin_page = 'show_content_list';
					} else {
						printf( "<p><b>Delete Page</b>" );
						printf( "<p>This page was unable to be deleted" );
					}
				}
					break;
				case ( 'delete_section' ): {
					$content_type_sql    = "SELECT * FROM content_type WHERE id = '$content_type_id' ";
					$content_type_result = mysql_query( $content_type_sql );
					$content_type_row    = mysql_fetch_array( $content_type_result );
					//printf ("<p><b>Delete Section {$content_type_row['section_id']}</b>");
					$delete_section_sql    = "update section set editable_content_area = 0, status=0 WHERE id = '{$content_type_row['section_id']}'";
					$delete_section_result = mysql_query( $delete_section_sql );
					$remove_page_name_sql    = "update content set page_name = '' WHERE section_id = '{$content_type_row['section_id']}'";
					$remove_page_name_result = mysql_query( $remove_page_name_sql );
					if ( $delete_section_result && $remove_page_name_result ) {
						$admin_page = 'show_content_list';
					} else {
						printf( "<p><b>Delete Section</b>" );
						printf( "<p>This page was unable to be deleted" );
					}
				}
					break;
				case ( 'hide_page' ): {
					$content_type_sql    = "select * from content_type where id = '$content_type_id' ";
					$content_type_result = mysql_query( $content_type_sql );
					$content_type_row    = mysql_fetch_array( $content_type_result );
					if ( $content_type_row["parent_id"] == "0" ) {
						// MAIN SECTION TO HIDE
						$section_id = $content_type_row["section_id"];
						$hide_page_sql    = "UPDATE section SET status = '0' WHERE id='$section_id'";
						$hide_page_result = mysql_query( $hide_page_sql );
						$hide_page_sql = "UPDATE content_type SET status = '0' WHERE id='$content_type_id'";
					} else {
						// SUB-SECTION TO HIDE
						$hide_page_sql = "UPDATE content_type SET status = '0' WHERE id='$content_type_id'";
					}
					if ( $hide_page_result = mysql_query( $hide_page_sql ) ) {
						$admin_page = 'show_content_list';
					} else {
						printf( "<p><b>Error</b>
						 <p>The page was unable to be hidden" );
					}
				}
					break;
				case ( 'show_page' ): {
					$content_type_sql    = "select * from content_type where id = '$content_type_id' ";
					$content_type_result = mysql_query( $content_type_sql );
					$content_type_row    = mysql_fetch_array( $content_type_result );
					if ( $content_type_row["parent_id"] == "0" ) {
						// PAGE IS A MAIN SECTION
						$section_id = $content_type_row["section_id"];
						$show_page_sql    = "UPDATE section SET status = '1' WHERE id='$section_id'";
						$show_page_result = mysql_query( $show_page_sql );
						$show_page_sql    = "UPDATE content_type SET status = '1' WHERE id='$content_type_id'";
						$show_page_result = mysql_query( $show_page_sql );
					}
					$show_page_sql    = "UPDATE content_type SET status = '1' WHERE id='$content_type_id'";
					$show_page_result = mysql_query( $show_page_sql );
					$show_page_sql    = "UPDATE content SET live = 1 WHERE content_type_id='$content_type_id' and template_type ='main_body'";
					$show_page_result = mysql_query( $show_page_sql );
					if ( $show_page_result = mysql_query( $show_page_sql ) ) {
						$admin_page = 'show_content_list';
					} else {
						printf( "<p><b>Error</b>
						 <p>The page was unable to be shown" );
					}
				}
					break;
				case ( 'show_hide_item' ): {
					$status = ( $show_hide_item == 'show' ) ? 1 : 0;
					$article_sql = "UPDATE content SET live = '$status' WHERE id='$id'";
					if ( $article_result = mysql_query( $article_sql ) ) {
						$admin_page = 'list_articles';
					} else {
						if ( $status ) {
							printf( "<p><b>Error</b><p>The page was unable to be shown" );
						} else {
							printf( "<p><b>Error</b><p>The page was unable to be hidden" );
						}
					}
				}
					break;
				case ( 'submit_tags' ): {
					include( '../php/classes/metatags.php' );
					$tags = new tags;
					$tags->get_form_values();
					if ( $tags->update_tags( $link, $content_type_id ) ) {
						$admin_page = 'show_content_list';
					} else {
						printf( "<p>Update failed</p>" );
						printf( "<p><a href=\"content_admin.php\">Return to list of pages</a></p>" );
					}
				}
					break;
				case ( 'set_default_image' ): {
					$def_img_update_sql    = "update defaults set value = '$page_image' where name = 'default image'";
					$def_img_update_result = mysql_query( $def_img_update_sql );
					if ( $def_img_update_result ) {
						$admin_page = 'show_content_list';
					} else {
						printf( "<p>Update failed</p>" );
						printf( "<p><a href=\"content_admin.php\">Return to list of pages</a></p>" );
					}
				}
					break;
				case ( 'set_default_logo' ): {
					// thumbnail_image_loc
					$page_logo             = $_POST['page_logo'];
					$def_img_update_sql    = "update defaults set value = '{$page_logo}' where name = 'default_thumbnail_image_loc' ";
					$def_img_update_result = mysql_query( $def_img_update_sql );
					if ( $def_img_update_result ) {
						$admin_page = 'show_content_list';
					} else {
						printf( "<p>Update failed</p>" );
						printf( "<p><a href=\"content_admin.php\">Return to list of pages</a></p>" );
					}
				}
					break;
				case ( 'submit_edit' ): {
					// SUBMIT ITEM EDITS
					if ( ( $title == "" ) || ( ( $body == "" ) ) ) {
						printf( "<b>ERROR</b>: <b>Information missing</b>" );
						printf( "<p>You have not entered the item's:<ul>" );
						if ( $title == "" ) {
							printf( "<li>title</li>" );
						}
						if ( $body == "" ) {
							printf( "<li>content</li>" );
						}
						printf( "</ul>" );
						printf( "<p>Please return to the form to enter the missing information.</p>" );
						printf( "<p><INPUT TYPE=\"button\" VALUE=\"Back\" onClick=\"history.go(-1)\">" );
						include( "./admin_footer_inc.php" );
						exit();
					}
					if ( strpos( $title, "\"" ) > 0 ) {
						printf( "<b>ERROR</b>: <b>Invalid character:</b>" );
						printf( "<p>You have used double quotes within the Title.<p>Please click on the Back button, and replace them with single quotes." );
						printf( "<p><INPUT TYPE=\"button\" VALUE=\"Back\" onClick=\"history.go(-1)\">" );
						include( "./admin_footer_inc.php" );
						exit();
					}
					if ( strpos( $nav_title, "\"" ) > 0 ) {
						printf( "<b>ERROR</b>: <b>Invalid character:</b>" );
						printf( "<p>You have used double quotes within the Menu Title.<p>Please click on the Back button, and replace them with single quotes." );
						printf( "<p><INPUT TYPE=\"button\" VALUE=\"Back\" onClick=\"history.go(-1)\">" );
						include( "./footer_inc.php" );
						exit();
					}
					if ( $imagebinFile_name != "" ) {
						if ( ( $image_filename = upload_file( $id, $imagebinFile, $imagebinFile_name, "Image", "250", "250", "gif,jpg,png", $content_type_id, $uploaddir, "content", "image_loc", $auto_resize_fullsize ) ) == 0 ) {
							include( "./admin_footer_inc.php" );
							exit();
						}
					}
					if ( $thumbnail_imagebinFile_name != "" ) {
						if ( ( $thumbnail_image_filename = upload_file( $id, $thumbnail_imagebinFile, $thumbnail_imagebinFile_name, "Image", THUMBNAIL_WIDTH, THUMBNAIL_HEIGHT, "gif,jpg,png", $content_type_id, $uploaddir, "content", "thumbnail_image_loc", $auto_resize_thumbnail ) ) == 0 ) {
							include( "./admin_footer_inc.php" );
							exit();
						}
						//if ($template_type != "image_right") {
						// if image is uploaded and no template chosen - set template to show image on right
						//	 $template_type = "image_left";
						//	}
					}
					//$title = mysql_escape_string($title);
					//$body =  mysql_escape_string($body);
					if ( $nav_title == "" ) {
						$nav_title = $title;
					}
					if ( $page_name == '' ) {
						$page_name = $nav_title;
					}
					if ( $page_name ) {
						$page_name = strtolower( $page_name );
						if ( $page_name != '/' ) {
							$page_name = preg_replace( '/[^a-z0-9.]+/', '-', $page_name );
							$sql = "select page_name from content where page_name like '$page_name%'";
							if ( $id ) {
								$sql .= "and id <> '$id'";
							}
							$result = mysql_query( $sql );
							if ( ( mysql_num_rows( $result ) > 0 ) || ( file_exists( realpath( '..' ) . '/' . $page_name ) ) ) {
								$name_part = $page_name;
								$rows      = array();
								while ( $row = mysql_fetch_array( $result ) ) {
									$rows[] = $row['page_name'];
								}
								$i = 1;
								while ( ( in_array( $page_name, $rows ) ) || ( file_exists( realpath( '..' ) . '/' . $page_name ) ) ) {
									$i ++;
									$page_name = $name_part . $i;
								}
							}
						}
					}
					if ( ! $id ) {
						if ( $template_type == 'main_body' ) {
							if ( ! $parent_id ) {
								$insert_sql = "INSERT INTO section (name,
											  section_type_id,
											  order_num,
											  editable_content_area,
											  status)
										 select '$nav_title',
											 '1',
											 COALESCE(max(order_num), 0) + 10,
											 '1',
											 '$live'
											FROM section";
								$result     = mysql_query( $insert_sql );
								$section_id = mysql_insert_id();
								$parent_id  = 0;
								$level      = 0;
								$page_type  = 0;
							} else {
								$parent_sql    = "select section_id, page_type, level from content_type where id = '$parent_id'";
								$parent_result = mysql_query( $parent_sql );
								$parent_row    = mysql_fetch_array( $parent_result );
								$section_id    = $parent_row['section_id'];
								$level         = $parent_row['level'] + 1;
								$page_type     = $parent_row['page_type'];
							}
							// here we assume you want to be the same as the parent page type
							// perhaps if its a module you should default to normal ?
							if ( $gallery_id == '' ) {
								$gallery_id = 0;
							}
							if ( empty( $content_type_id ) ) {
								$insert_sql = "INSERT INTO content_type (section_id,
										  	       parent_id,
											       name,
											       order_num,
												   page_type,
											       status,poll_id,
												   level, gallery_id,donate_id)
									 	      select '$section_id',
										 	      '$parent_id',
											      '$nav_title',
											      COALESCE(max(order_num), 0) + 10,
												  $page_type,
											      '$live','$poll_id',
											      '$level','$gallery_id', '$donate_id'

		  									  from content_type where parent_id = $parent_id and level = $level";
								$result          = mysql_query( $insert_sql );

								$content_type_id = mysql_insert_id();
							} else {
								$content_type_sql    = "SELECT * FROM content_type WHERE id = '$content_type_id'";
								$content_type_result = mysql_query( $content_type_sql );
								$content_type_row    = mysql_fetch_array( $content_type_result );
								$section_id = $content_type_row["section_id"];
							}
						}
						$datestamp = date( "Y-m-d H:i:s" );
						if ( $hidepageimage == 'on' ) {
							$hidepageimageinsert = 1;
						} else {
							$hidepageimageinsert = 0;
						}
						$insert_sql = "INSERT INTO content
								(section_id,
								 content_type_id,
								 title,
								 page_name,
								 nav_title,
								 summary,
								 body,
								 form_id,
								 image_loc,
								 thumbnail_image_loc,
								 template_type,
								 order_num,
								 live,
								 author_id,
								 hidepageimage,
								 datestamp)
							 VALUES ('$section_id',
								 '$content_type_id',
								 '$title',
								 '$page_name',
								 '$nav_title',
								 '$page_summary',
								 '$body',
								 '$form_id',
								 '$page_image',
								 '$thumbnail_image_loc',
								 '$template_type',
								 '$order_num',
								 1,
								 '$session_user_id',
								 $hidepageimageinsert,
								 '$datestamp')";
						$result = mysql_query( $insert_sql );
					} else {
						$content_sql    = "SELECT * FROM content WHERE id = '$id'";
						$content_result = mysql_query( $content_sql );
						$content_row    = mysql_fetch_array( $content_result );
						if ( $hidepageimage == 'on' ) {
							$hidepageimage = 1;
						}
						// UPDATE ITEM with ammends
						$update_sql = "UPDATE content SET content_type_id='$content_type_id',
										title='$title',
										nav_title='$nav_title',
										page_name='$page_name',
										summary='$page_summary',
										body='$body',
										form_id = '$form_id',
                                                                                template_type='$template_type',
                                                                                order_num='$order_num',
										image_loc='$page_image',
										hidepageimage='$hidepageimage',
										live='$live',
                                                                                author_id='$session_user_id'
							WHERE id = $id";
						// add a new update for the page_type t be set
						// echo $update_sql;
						// die();
						$result = mysql_query( $update_sql );
						$update_page_type = "UPDATE content_type SET page_type = {$_POST['pt_id']} WHERE id = {$_POST['content_type_id']}";
						$result           = mysql_query( $update_page_type );
						if ( $imagebinFile_name != "" ) {
							$sql    = "UPDATE content SET image_loc = '$image_filename' WHERE id=$id";
							$result = mysql_query( $sql );
						}
						//    if ($thumbnail_imagebinFile_name != "") {
						$sql    = "UPDATE content SET thumbnail_image_loc='$thumbnail_image_loc' WHERE id=$id";
						$result = mysql_query( $sql );
						//   }
						// UPDATE TITLE OF PAGE IF IT'S BEEN CHANGED
						if ( $content_type_id == "" ) {
							$content_type_id = $content_row["content_type_id"];
						}
						if ( $section_id == "" ) {
							$section_id = $content_row["section_id"];
						}
						if ( $content_row["template_type"] == "main_body" ) {
							// THE PAGE IS A SUB-SECTION SO UPDATE [CONTENT_TYPE] TABLE
							$update_content_type_sql    = "UPDATE content_type SET status='$live', name = '$nav_title' , poll_id='$poll_id',  gallery_id='$gallery_id',  donate_id='$donate_id'    WHERE id = '$content_type_id'";
							$update_content_type_result = mysql_query( $update_content_type_sql );
							$content_type_sql    = "SELECT * FROM content_type WHERE id = '$content_type_id'";
							$content_type_result = mysql_query( $content_type_sql );
							$content_type_row    = mysql_fetch_array( $content_type_result );
							if ( $content_type_row["parent_id"] == "0" ) {
								// THE PAGE IS A MAIN SECTION - SO UPDATE THE [SECTION] TABLE
								$update_section_sql    = "UPDATE section SET name = '$nav_title' WHERE id = '$section_id'";
								$update_section_result = mysql_query( $update_section_sql );
							}
						}
					}
					if ( $result ) {
						if ( $template_type == 'main_body' ) {
							$admin_page = 'show_content_list';
						} else {
							$admin_page = 'list_articles';
						}
					} else {
						echo "<p>Sorry, an error has occurred.  The content could not be added. Please contact the web administrator";
					}
					echo "</ul>";
					//	mysql_close ($link);
				}
					break;
				case ( 'delete_item' ): {
					// DELETE ITEM
					delete_associated_document( $id, "content", "image_loc", $uploaddir );
					delete_associated_document( $id, "content", "thumbnail_image_loc", $uploaddir );
					// delete any associated downloadable files
					$item_sql    = "SELECT * FROM downloadable_item WHERE content_id = '$id'";
					$item_result = mysql_query( $item_sql );
					if ( mysql_num_rows( $item_result ) > 0 ) {
						while ( $item_row = mysql_fetch_array( $item_result ) ) {
							$item_id = $item_row["id"];
							delete_associated_document( $item_id, "downloadable_item", "file_loc", $uploaddir );
							$delete_item_sql    = "DELETE FROM downloadable_item WHERE id=$item_id";
							$delete_item_result = mysql_query( $delete_item_sql );
						}
					}
					$delete_content_sql = "DELETE FROM content WHERE id=$id";
					if ( $delete_content_result = mysql_query( $delete_content_sql ) ) {
						$admin_page = 'list_articles';
					} else {
						printf( "<p>This item was unable to be deleted" );
					}
				}
					break;
				case ( 'move' ): {
					if ( $id ) {
						$article_sql    = "SELECT * FROM content WHERE id = '$id' and template_type <> 'main_body'";
						$article_result = mysql_query( $article_sql );
						$article_row    = mysql_fetch_array( $article_result );
						$order_num = $article_row['order_num'];
						if ( $move == 'up' ) {
							$swap_candidate_sql = sprintf( 'select id, order_num from content where template_type <> \'main_body\' and content_type_id = %s and order_num = ' .
							                               ' (select max(order_num) from content where template_type <> \'main_body\' and order_num < %s and content_type_id = %s)', $content_type_id, $order_num, $content_type_id );
						} else {
							$swap_candidate_sql = sprintf( 'select id, order_num from content where template_type <> \'main_body\' and content_type_id = %s and order_num = ' .
							                               ' (select min(order_num) from content where template_type <> \'main_body\' and order_num > %s and content_type_id = %s)', $content_type_id, $order_num, $content_type_id );
						}
						$swap_candidate_result = mysql_query( $swap_candidate_sql );
						if ( mysql_num_rows( $swap_candidate_result ) == 1 ) {
							$swap_candidate_row = mysql_fetch_array( $swap_candidate_result );
							$swap_sql           = "update content set order_num = '$order_num' where id = '{$swap_candidate_row[id]}'";
							$swap_result        = mysql_query( $swap_sql );
							$swap_sql           = "update content set order_num = '{$swap_candidate_row['order_num']}' where id = '{$article_row[id]}'";
							$swap_result        = mysql_query( $swap_sql );
						}
						$admin_page = 'list_articles';
					} else {
						$content_type_sql    = "SELECT * FROM content_type WHERE id = '$content_type_id'";
						$content_type_result = mysql_query( $content_type_sql );
						$content_type_row    = mysql_fetch_array( $content_type_result );
						$parent = $content_type_row['parent_id'];
						if ( $parent == 0 ) {
							$section_sql    = "SELECT * FROM section WHERE id = {$content_type_row['section_id']}";
							$section_result = mysql_query( $section_sql );
							$section_row    = mysql_fetch_array( $section_result );
							$order_num      = $section_row['order_num'];
							if ( $move == 'up' ) {
								$swap_candidate_sql = sprintf( 'select id, order_num, name from section where order_num = ' .
								                               ' (select max(order_num) from section where order_num < %s and editable_content_area = 1)', $order_num );
							} else {
								$swap_candidate_sql = sprintf( 'select id, order_num, name from section where order_num = ' .
								                               ' (select min(order_num) from section where order_num > %s and editable_content_area = 1)', $order_num );
							}
							$swap_candidate_result = mysql_query( $swap_candidate_sql );
							if ( mysql_num_rows( $swap_candidate_result ) == 1 ) {
								$swap_candidate_row = mysql_fetch_array( $swap_candidate_result );
								//echo  $swap_candidate_sql;
								//echo "{$section_row['name']} x {$swap_candidate_row['name']}";
								$swap_sql    = "update section set order_num = '$order_num' where id = '{$swap_candidate_row[id]}'";
								$swap_result = mysql_query( $swap_sql );
								$swap_sql    = "update section set order_num = '{$swap_candidate_row['order_num']}' where id = '{$section_row[id]}'";
								$swap_result = mysql_query( $swap_sql );
							}
						} else {
							$order_num = $content_type_row['order_num'];
							if ( $move == 'up' ) {
								$swap_candidate_sql = sprintf( 'select id, order_num from content_type where parent_id = %s and order_num = ' .
								                               ' (select max(order_num) from content_type where order_num < %s and parent_id = %s)', $parent, $order_num, $parent );
							} else {
								$swap_candidate_sql = sprintf( 'select id, order_num from content_type where parent_id = %s and order_num = ' .
								                               ' (select min(order_num) from content_type where order_num > %s and parent_id = %s)', $parent, $order_num, $parent );
							}
							$swap_candidate_result = mysql_query( $swap_candidate_sql );
							if ( mysql_num_rows( $swap_candidate_result ) == 1 ) {
								$swap_candidate_row = mysql_fetch_array( $swap_candidate_result );
								$swap_sql           = "update content_type set order_num = '$order_num' where id = '{$swap_candidate_row[id]}'";
								$swap_result        = mysql_query( $swap_sql );
								$swap_sql           = "update content_type set order_num = '{$swap_candidate_row['order_num']}' where id = '{$content_type_row[id]}'";
								$swap_result        = mysql_query( $swap_sql );
							}
						}
						$admin_page = 'show_content_list';
					}
				}
					break;
				case ( 'submit_editors' ): {
					$users_permitted_to_edit_this_page = ",";
					if ( $permitted_users ) {
						foreach ( $permitted_users as $user ) {
							$users_permitted_to_edit_this_page .= $user . ",";
						}
					}
					$update_sql = "UPDATE content_type SET edited_by_user_id = '$users_permitted_to_edit_this_page'
								WHERE id = $content_type_id";
					$result     = mysql_query( $update_sql );
					$admin_page = 'show_content_list';
				}
					break;
				default:
					if ( $admin_page == '' ) {
						$admin_page = 'show_content_list';
					}
			}
			/*                 * *************************************************************************

					  Show Pages

					 * ************************************************************************* */
			switch ( $admin_page ) {
				case ( 'edit_tags' ): {
					$page_sql    = "SELECT * FROM content WHERE content_type_id = '$content_type_id' AND template_type = 'main_body'";
					$page_result = mysql_query( $page_sql );
					$page_row    = mysql_fetch_array( $page_result );
					if ( $content_type_id == 0 ) {
						printf( "<h2><a href='content_admin.php'>Content Administration</a> > <b>Default Search Engine Tags</b></h2><br />" );
					} else {
						printf( "<h2><a href='content_admin.php'>Content Administration</a> > <b>Search Engine Tags for page {$page_row['title']}</b></h2><br />" );
					}
					include( '../php/classes/metatags.php' );
					$tags = new tags;
					printf( "<p>Use this page to edit the meta-tags that search engines read in order to accurately index the pages of your site. If you do not make any changes then this page " .
					        "will use the default meta-tags for your site which can be edited from the main content admin page. In the case of sub-section pages, the default meta-tags are taken " .
					        "from the main section page.</p>" );
					echo "<form method='post' action='$PHP_SELF?edit_tags_sub_section=$edit_tags&content_type_id=$content_type_id'>\n";
					echo "<table cellpadding=4>";
					$tags->get_database_values( $link, $content_type_id );
					$tags->output_admin_table_rows( $content_type_id > 0 );
					echo "<tr valign=top><td align=right ></td> ";
					echo "<td  colspan=2><input type='submit' name='submit_tags' value='apply'/></td></tr> ";
					echo "<tr valign=top><td align=right ></td><td>";
					echo "<input type=\"button\" value=\"Cancel\" onClick=\"history.go(-1)\"> &nbsp;&nbsp;&nbsp;";
					echo "</td></tr> ";
					echo "</table>";
					echo "</form>";
					if ( $content_type_id ) {
						echo "<script>set_tag_fields();</script>";
					}
				}
					break;
				case ( 'default_image' ): {
					printf( "<h2><a href='content_admin.php'>Content Administration</a> > <b>Default page main image</b></h2><br />" );
					$def_img_sql    = 'select value from defaults where name = \'default image\'';
					$def_img_result = mysql_query( $def_img_sql );
					$def_img_row    = mysql_fetch_array( $def_img_result );
					$image     = $def_img_row["value"];
					$image_htm = '';
					if ( $image ) {
						$image     = USER_IMAGE_PATH . $image;
						$thumb1    = "/php/thumbimage.php?img=$image&size=13";
						$thumb2    = "/php/thumbimage.php?img=$image&size=16";
						$image_htm = "<a href='#thumb' class='hoverthumb'><img src='$thumb1'/><span><img src='$thumb2'/></span></a>";
					}
					echo "<div class='adminboxbuttons'>";
					echo "<form method='post' action='$PHP_SELF?set_default_image=1'>\n";
					printf( '<div>%s</div><input type=hidden name=%s value=%s><span id="%s_img">%s</span>
						<button onclick="return selectimage(\'%s\', %s);">Choose Image</button>', 'Default Page Image', 'page_image', $def_img_row["value"], 'page_image', $image_htm, 'page_image', 13 );
					echo "<br /><input type='submit' name='set_default_image' value='apply'/><br />";
					echo "<input type=\"button\" value=\"Cancel\" onClick=\"history.go(-1)\"> &nbsp;&nbsp;&nbsp;";
					echo "</form>";
					echo "</div>";
				}
					break;
				case ( 'default_logo' ): {
					printf( "<h2><a href='content_admin.php'>Content Administration</a> > <b>Default Page Logo</b></h2><br />" );
					$def_img_sql    = "select value from defaults where name = 'default_thumbnail_image_loc' ";
					$def_img_result = mysql_query( $def_img_sql );
					$def_img_row    = mysql_fetch_array( $def_img_result );
					$image     = $def_img_row["value"];
					$image_htm = '';
					if ( $image ) {
						$image     = USER_IMAGE_PATH . $image;
						$thumb1    = "/php/thumbimage.php?img=$image&size=13";
						$thumb2    = "/php/thumbimage.php?img=$image&size=16";
						$image_htm = "<a href='#thumb' class='hoverthumb'><img src='$thumb1'/><span><img src='$thumb2'/></span></a>";
					}
					echo "<div class='adminboxbuttons'>";
					echo "<form method='post' action='$PHP_SELF?set_default_logo=1'>\n";
					printf( '<div>%s</div><input type=hidden name=%s value=%s><span id="%s_img">%s</span>
						<button onclick="return selectimage(\'%s\', %s);">Choose Image</button>', 'Default Page Logo', 'page_logo', $def_img_row["value"], 'page_logo', $image_htm, 'page_logo', 13 );
					echo "<br /><input type='submit' name='set_default_logo' value='apply'/><br />";
					echo "<input type=\"button\" value=\"Cancel\" onClick=\"history.go(-1)\"> &nbsp;&nbsp;&nbsp;";
					echo "</form>";
					echo "</div>";
				}
					break;
				case( 'list_articles' ): {
					$parent_page_sql    = "SELECT * FROM content WHERE content_type_id = '$content_type_id' AND template_type = 'main_body'";
					$parent_page_result = mysql_query( $parent_page_sql );
					$parent_page_row    = mysql_fetch_array( $parent_page_result );
					printf( "<h2><a href='content_admin.php'>Content Administration</a> > <b>Articles for page {$parent_page_row['title']}</b></h2><br />" );
					// LATEST ITEMS
					$content_sql    = "SELECT * FROM content WHERE content_type_id = '$content_type_id' AND template_type != 'main_body' ORDER BY order_num DESC";
					$content_result = mysql_query( $content_sql );
					$button_type     = 'contentbutton';
					$table           = 'diary_item';
					$parent_id_name  = 'content_type_id';
					$parent_id_value = $content_type_id;
					$parent_part     = ( $parent_id_name ) ? "$parent_id_name=$parent_id_value&" : '';
					$singular        = '';
					$item_type       = '';
					echo "<table cellpadding=0 cellspacing=0 width=100%>";
					echo "<tr id='top-row'><td colspan=6 style='width:100%;background:url(/admin/images/lightbox-tall-bk.gif)'>";
					echo "<a style='padding: 5px 0 5px 8px; display:block' href=\"$PHP_SELF?{$parent_part}edit_item=yes\" onmouseout='button_off(this)' onmouseover='button_over(this)'>";
					printf( "<img src='/admin/images/addanarticle-off.gif' alt='Add an article' name='cmsbuttonadd%s%s'/></a>", $singular, $item_type, '', $singular, $parent_id_value );
					//echo "<img src='./images/events-field-names.gif'/>";
					if ( mysql_num_rows( $content_result ) > 0 ) {
						$row_num = 1;
						while ( $content_row = mysql_fetch_array( $content_result ) ) {
							echo "<tr>";
							printf( "<td rowspan=2><div class='newseventtitlefieldactive'>%s</strong></div></td>", $content_row["title"] );
							$href = sprintf( "%s?%sedit_item=yes&id=%s", $PHP_SELF, $parent_part, $content_row["id"] );
							cms_admin_button( $href, $button_type, 'edit' );
							// HIDE OR SHOW article
							if ( $content_row["live"] == 0 ) {
								// SHOW article
								$href = sprintf( "%s?%sshow_article=yes&id=%s", $PHP_SELF, $parent_part, $content_row["id"] );
								cms_admin_button( $href, "showcontent", "show" );
							} else {
								// HIDE article
								$href = sprintf( "%s?%shide_article=yes&id=%s", $PHP_SELF, $parent_part, $content_row["id"] );
								cms_admin_button( $href, "hidecontent", "hide" );
							}
							$href = sprintf( "%s?%sdelete_item=yes&id=%s", $PHP_SELF, $parent_part, $content_row["id"] );
							cms_admin_button( $href, $button_type, 'delete' );
							echo "<td>";
							printf( "<a href='%s?list_articles=yes&%sid=%s&move=down'", $PHP_SELF, $parent_part, $content_row["id"] );
							echo " class=\"downButton moveButton\"";
							echo "onmouseout=\"MM_swapImgRestore()\" onmouseover=\"MM_swapImage('mainup$acc','','/admin/images/button-moveup-over.gif',1)\">";
							echo "<img src=\"/admin/images/button-moveup-off.gif\" alt=\"move page up\" name=\"mainup3\" width='16' height='16' border=\"0\" id=\"mainup$acc\" /></a>";
							$acc ++;
							echo "<td rowspan=2 width=100% style='background: url(/admin/images/main-bk.gif)'></td>";
							echo "</tr>";
							echo "<tr>";
							echo "<td>";
							printf( "<a href='%s?list_articles=yes&%sid=%s&move=up'", $PHP_SELF, $parent_part, $content_row["id"] );
							echo " class=\"upButton moveButton\"";
							echo " onMouseOut='MM_swapImgRestore()' onMouseOver=\"MM_swapImage('maindown$acc','','/admin/images/button-movedown-over.gif',1)\">";
							echo "<img src='/admin/images/button-movedown-off.gif' alt='move article down' name='mainup1' width='16' height='16' border='0' id='maindown$acc' /></a>";
							$acc ++;
							echo "</td>";
							echo "</tr>";
						}
					} else {
						printf( "<p>There are currently no articles in this page<p>" );
					}
					echo '</table>';
				}
					break;
				case ( 'select_editors' ): {
					if ( $session_user_type_id == "1" ) {
						$page_sql    = "SELECT * FROM content WHERE content_type_id = '$content_type_id' AND template_type = 'main_body'";
						$page_result = mysql_query( $page_sql );
						$page_row    = mysql_fetch_array( $page_result );
						printf( "<h2><a href='content_admin.php'>Content Administration</a> > <b>Select editors for page {$page_row['title']}</b></h2><br />" );
						// LIST USERS WHO CAN EDIT THIS PAGE
						// get comma seperated list of users who can edit this page
						$list_of_users_who_can_edit_page = "";
						$content_type_sql    = "select * from content_type where id = '$content_type_id' ";
						$content_type_result = mysql_query( $content_type_sql );
						if ( $content_type_row = mysql_fetch_array( $content_type_result ) ) {
							$list_of_users_who_can_edit_page = $content_type_row["edited_by_user_id"];
						}
						printf( "<form method=post action=\"%s\">", $PHP_SELF );
						printf( "<input type=hidden name=content_type_id value=\"%s\">", $content_type_id );
						printf( "<p><b>Users who can edit this page:</b><p>" );
						echo "<table cellpadding=4>";
						$user_sql    = "select * from user where (user_type_id = '1' OR access_to_cms = '1') AND account_status = '1' AND id > 1 order by firstname";
						$user_result = mysql_query( $user_sql );
						while ( $user_row = mysql_fetch_array( $user_result ) ) {
							$checked = "";
							// determine whether user can edit this page
							$user_id_to_search_for = "," . $user_row["id"] . ",";
							$user_type_id = $user_row["user_type_id"];
							if ( ( strstr( $list_of_users_who_can_edit_page, $user_id_to_search_for ) ) ||
							     ( $user_type_id == "1" )
							) {
								// ALL ADMIN USERS CAN EDIT THE PAGE
								$checked = "checked";
							}
							if ( $user_type_id == "1" ) {
								printf( "<tr valign=absmiddle><td align=center><img src=\"./images/black_tick.gif\"></td><td>%s %s (Admin user)</td></tr>", $user_row["firstname"], $user_row["surname"] );
							} else {
								printf( "<tr  valign=absmiddle><td><input type=checkbox value=\"%s\" %s name=\"permitted_users[]\"></td><td>%s %s</td></tr>", $user_row["id"], $checked, $user_row["firstname"], $user_row["surname"] );
							}
						}
						echo "</table>";
						echo "<div class='adminboxbuttons'>";
						echo "<input type=submit name=submit_users_to_edit_page value=\"Update\"><br />";
						echo "<input type=\"button\" value=\"Cancel\" onClick=\"history.go(-1)\"> &nbsp;&nbsp;&nbsp;";
						echo '</div>';
						printf( "</form>" );
					} else {
						printf( "<p>Sorry you are not able to view this page" );
					}
				}
					break;
				case ( 'edit_item' ): {
					// ADD OR EDIT ITEM
					$content_type_sql    = "SELECT * FROM content_type WHERE id = '$content_type_id'";
					$content_type_result = mysql_query( $content_type_sql );
					$content_type_row    = mysql_fetch_array( $content_type_result );
					$content_sql = "select content.*, ct.`page_type` from content inner join content_type ct on ct.id = content.content_type_id where content.id = '$id'";
					$content_result = mysql_query( $content_sql );
					$content_row    = mysql_fetch_array( $content_result );
					$isAnArticle = ( $content_type_id ) && ( $content_row["template_type"] != "main_body" );
					$page_title = $content_row['title'];
					if ( $isAnArticle ) {
						$page_sql    = "SELECT * FROM content WHERE content_type_id = '$content_type_id' and template_type='main_body'";
						$page_result = mysql_query( $page_sql );
						$page_row    = mysql_fetch_array( $page_result );
						$page_title    = $page_row['title'];
						$addedit       = ( $id == "" ) ? 'Add' : 'Edit';
						$article_title = $content_row['title'];
						printf( "<h2><a href='content_admin.php'>Content Administration</a> > <a href='$PHP_SELF?content_type_id=$content_type_id&list_articles=yes'>$page_title - Articles</a> > <b>$addedit Article $article_title</b></h2><br />" );
					} else {
						$level   = ( ( $content_type_row['parent_id'] === '0' ) || ( $section_id == 'new' ) ) ? 'section' : 'sub-section';
						$addedit = ( $content_type_id ) ? "Edit $level Page - $page_title" : "Add $level Page";
						printf( "<h2><a href='content_admin.php'>Content Administration</a> > <b>$addedit</b></h2><br />" );
					}
					if ( $isAnArticle ) {
						printf( "<p><form name=page_form action=%s?list_articles=yes method=POST ENCTYPE=\"multipart/form-data\">", $PHP_SELF );
					} else {
						printf( "<p><form name=page_form action=%s method=POST ENCTYPE=\"multipart/form-data\">", $PHP_SELF );
					}
					if ( $content_type_id ) {
						printf( "<input type=\"hidden\" name=\"content_type_id\" value=\"%s\">", $content_type_id );
						printf( "<input type=\"hidden\" name=\"id\" value=\"%s\">", $id );
					} else {
						printf( "<input type=\"hidden\" name=\"parent_id\" value=\"%s\">", $parent_id );
					}
					echo "<table >";
					if ( ! defined( 'PAGE_HEADING_IN_CONTENT' ) || ( PAGE_HEADING_IN_CONTENT == 0 ) ) {
						printf( "<tr valign=top>
						<td>Page Heading</td>
						<td><input type=text name=title size=65 value=\"%s\"></td></tr>", $content_row["title"] );
					} else {
						printf( "<input type=hidden name=title value=\"%s\">", $content_row["title"] );
					}
					if ( $isAnArticle == false ) {
						printf( "<tr valign=top>
						<td>Menu Title</td>
						<td ><input type=text name=nav_title size=65 value=\"%s\"></td></tr>", $content_type_row["name"] );
					}
					if ( defined( 'SITE_HAS_NICE_URLS' ) && ( SITE_HAS_NICE_URLS == 1 ) ) {
						if ( $content_type_id != 1 ) {
							printf( "<tr valign=top>
							<td >Page Name</td>
							<td ><input type=text name=page_name size=65 value=\"%s\"></td></tr>", $content_row["page_name"] );
						} else {
							printf( "<tr valign=top>
							<td>Page Name</td>
							<td>%s</td></tr>", $content_row["page_name"] );
							printf( "<input type=hidden name=page_name value=\"%s\">", $content_row["page_name"] );
						}
					} else {
						printf( "<input type=hidden name=page_name value=\"%s\">", $content_row["page_name"] );
					}
					$form_sql    = 'select * from forms';
					$form_result = mysql_query( $form_sql );
					$form_id = $content_row["form_id"];
					if ( ( $session_user_id == 1 ) && ( $isAnArticle == false ) ) {
						$form_sql    = 'select * from forms';
						$form_result = mysql_query( $form_sql );
						$selected = ( $form_id == 0 ) ? ' selected="selected"' : '';
						$out      = '<select id="form_id" name="form_id">';
						$out .= '<option value="0"' . $selected . ' >No Form</option>';
						while ( $form_row = mysql_fetch_array( $form_result ) ) {
							$selected = ( $form_id == $form_row['id'] ) ? ' selected="selected"' : '';
							$out .= '<option value="' . $form_row['id'] . '"' . $selected . ' >' . $form_row['title'] . '</option>';
						}
						if ( empty( $hidden ) ) {
							$hidden = '';
						}
						$out .= '</select>' . $hidden;
						printf( "<tr valign=top>
 								<td>Show form</td>
 								<td>%s</td></tr>", $out );
					} else {
						echo "<input type=\"hidden\" name=\"form_id\" value=\"{$form_id}\" />";
					}
					// need to add a switch here if site has polls
					if ( SITE_HAS_POLLS ) {
						if ( $inArticle == false ) {
							$poll_id = $content_type_row['poll_id'];
							$polls   = db_get_rows( "select * from polls" );
							$selected = ( $poll_id == 0 ) ? ' selected="selected"' : '';
							$out      = '<select id="poll_id" name="poll_id">';
							$out .= '<option value="0"' . $selected . ' >No Poll</option>';
							foreach ( $polls as $poll ) {
								$selected = ( $poll_id == $poll['id'] ) ? ' selected="selected"' : '';
								$out .= '<option value="' . $poll['id'] . '"' . $selected . ' >' . $poll['question'] . '</option>';
							}
							$out .= '</select>';
							printf( "<tr valign=top>
 								<td>Show Poll</td>
 								<td>%s</td></tr>", $out );
						}
					} else {
						echo "<input type=\"hidden\" name=\"poll_id\" value=\"{$poll['id']}\" />";
					}
					// need to add a switch here if site has polls
					if ( SITE_HAS_INLINE_GALLERIES ) {
						if ( $inArticle == false ) {
							$gallery_id = $content_type_row['gallery_id'];
							$gallerys   = db_get_rows( "select * from gallery" );
							$selected = ( $gallery_id == 0 ) ? ' selected="selected"' : '';
							$out      = '<select id="gallery_id" name="gallery_id">';
							$out .= '<option value="0"' . $selected . ' >No Gallery</option>';
							foreach ( $gallerys as $gallery ) {
								$selected = ( $gallery_id == $gallery['id'] ) ? ' selected="selected"' : '';
								$out .= '<option value="' . $gallery['id'] . '"' . $selected . ' >' . $gallery['title'] . '</option>';
							}
							$out .= '</select>';
							printf( "<tr valign=top>
									 <td>Show Gallery</td>
									 <td>%s</td></tr>", $out );
						}
					} else {
						echo "<input type=\"hidden\" name=\"gallery_id\" value=\"{$gallery['id']}\" />";
					}
					// need to add a switch here if site has polls
					if ( SITE_HAS_DONATE ) {
						if ( $inArticle == false ) {
							$donate_id = $content_type_row['donate_id'];
							$donations = db_get_rows( "select * from donate where id != 999 order by title " ); // to hide the checkout option
							$selected = ( $donate_id == 0 ) ? ' selected="selected"' : '';
							$out      = '<select id="donate_id" name="donate_id">';
							$out .= '<option value="0"' . $selected . ' >No Donate Option</option>';
							foreach ( $donations as $donate ) {
								$selected = ( $donate_id == $donate['id'] ) ? ' selected="selected"' : '';
								$out .= '<option value="' . $donate['id'] . '"' . $selected . ' >' . $donate['title'] . '</option>';
							}
							$out .= '</select>';
							printf( "<tr valign=top>
									 <td>Show Donate Option</td>
									 <td>%s</td></tr>", $out );
						}
					} else {
						echo "<input type=\"hidden\" name=\"$donate_id\" value=\"{$donate['id']}\" />";
					}
					/* Adding the page type chooser */
					$pt_sql    = 'SELECT id , `name` FROM page_type ORDER BY `name`';
					$pt_result = mysql_query( $pt_sql );
					// need to check we have this
					if ( $content_row["page_type"] != '' ) {
						$pt_id = $content_row["page_type"];
					} else {
						$pt_id = '0';
					}
					// this is a special one just for cs admn - hopefully
					if ( ( $session_user_id == 1 ) && ( $isAnArticle == false ) ) {
						//$pt_sql = 'select * from forms';
						//$pt_result = mysql_query($pt_sql);
						$selected = ( $pt_id == 0 ) ? ' selected="selected"' : '';
						$out      = '<select id="pt_id" name="pt_id">';
						//  $out .= '<option value="0"' . $selected . ' >No Form</option>';
						while ( $pt_row = mysql_fetch_array( $pt_result ) ) {
							$selected = ( $pt_id == $pt_row['id'] ) ? ' selected="selected"' : '';
							$out .= '<option value="' . $pt_row['id'] . '"' . $selected . ' >' . $pt_row['name'] . '</option>';
						}
						if ( empty( $hidden ) ) {
							$hidden = '';
						}
						$out .= '</select>' . $hidden;
						printf( "<tr valign=top>
 								<td>Change Page Type</td>
 								<td>%s</td></tr>", $out );
					} else {
						echo "<input type=\"hidden\" name=\"pt_id\" value=\"{$pt_id}\" />";
					}
					/* end page type chooser */
					if ( PAGES_HAVE_MAIN_ICON ) {
						$image     = USER_IMAGE_PATH . $content_row["thumbnail_image_loc"];
						$image_htm = ( $image ) ? "<a href='#thumb' class='hoverthumb'><img src='/php/thumbimage.php?img=$image&size=13'/>" .
						                          "<span><img src='/php/thumbimage.php?img=$image&size=16'/></span></a>" : '';
						printf( '<tr valign=top><td>%s</td>
						<td><input type=hidden name=%s value=%s><span id="%s_img">%s</span>
						<button onclick="return selectimage(\'%s\', %s);">Choose Image</button>
						<button onclick="return clear_image(\'%s\');" >Use Default Image</button>
						</td></tr>', 'Page Icon', 'thumbnail_image_loc', $content_row["thumbnail_image_loc"], 'thumbnail_image_loc', $image_htm, 'thumbnail_image_loc', 13, 'thumbnail_image_loc' );
						/*

											$icon_thumbnail_name = ( PAGES_HAVE_MAIN_ICON ) ? 'Icon' : 'Thumbnail';

											printf( "<tr valign=top>
													<td >$icon_thumbnail_name image:</td>
													<td ><input type=\"file\" name=\"thumbnail_imagebinFile\">" );

											if ( $content_row["thumbnail_image_loc"] != "" ) {
												printf( "&nbsp;&nbsp;($icon_thumbnail_name image already uploaded)" );
											}

											printf( "<br><input type=\"checkbox\" name=\"auto_resize_thumbnail\" value=\"yes\" checked >" );
											printf( "<i>Auto resize if larger than " . THUMBNAIL_WIDTH . "x" . THUMBNAIL_HEIGHT . "</i>" );

											printf( "</td>
													 </tr>" );
													 */
					}
					if ( ( ! $isAnArticle ) && ( PAGES_HAVE_MAIN_IMAGE ) ) {
						$image     = USER_IMAGE_PATH . $content_row["image_loc"];
						$image_htm = ( $image ) ? "<a href='#thumb' class='hoverthumb'><img src='/php/thumbimage.php?img=$image&size=13'/>" .
						                          "<span><img src='/php/thumbimage.php?img=$image&size=16'/></span></a>" : '';
						printf( '<tr valign=top><td>%s</td>
						<td><input type=hidden name=%s value=%s><span id="%s_img">%s</span>
						<button onclick="return selectimage(\'%s\', %s);">Choose Image</button>
						<button onclick="return clear_image(\'%s\');" >Use Default Image</button>
						</td></tr>', 'Page Image', 'page_image', $content_row["image_loc"], 'page_image', $image_htm, 'page_image', 13, 'page_image' );
						// printf("<tr valign=top>
						//<td >Hide Image:</td>
						//<td >");
						//  if ($content_row["hidepageimage"] == '1') {
						//       $hidepageimage_selected = 'checked';
						//  } else {
						//       $hidepageimage_selected = '';
						//   }
						//   printf("<br><input type=\"checkbox\" name=\"hidepageimage\" $hidepageimage_selected >");
						//   printf("</td></tr>");
					}
					if ( ( PAGES_HAVE_SUMMARY == 1 ) || ( $isAnArticle == true ) ) {
						printf( "<tr valign=top>
						<td >Page Summary</td>
						<td ><textarea name=page_summary cols=50 rows=3>%s</textarea></td></tr>", $content_row["summary"] );
					}
					echo "<tr valign=top>
					<td >Item content</td>
					<td    width=100%>";
					// insert HTML editor
					$sBasePath = "/html_editor/";
					if ( $testCKEditor ) {
						$ckeditor = new CKEditor();
						$ckeditor->editor( 'body', $content_row["body"] );
					} else {
						$oFCKeditor           = new FCKeditor( 'body' );
						$oFCKeditor->BasePath = $sBasePath;
						$oFCKeditor->Value = $content_row["body"];
						$oFCKeditor->Create();
					}
					printf( "</td></tr>" );
					if ( $isAnArticle ) {
						printf( "<input type=\"hidden\" name=\"template_type\" value=\"no_image\" >" );
						$order_num = $content_row["order_num"];
						if ( $order_num == '' ) {
							$order_num_sql    = "select coalesce(max(order_num), 0) + 10 as order_num from content where content_type_id = $content_type_id and template_type <> 'main_body'";
							$order_num_result = mysql_query( $order_num_sql );
							$order_num_row    = mysql_fetch_array( $order_num_result );
							$order_num        = $order_num_row['order_num'];
						}
						printf( "<input type=hidden name=order_num value=\"%s\" >", $order_num );
						printf( "<input type=hidden name=live value=\"%s\" >", $content_row["live"] );
					} else {
						$template_type = $content_row["template_type"];
						if ( $template_type == '' ) {
							$template_type = 'main_body';
						}
						printf( "<input type=hidden name=template_type value=\"%s\" >", $template_type );
						printf( "<input type=hidden name=order_num value=\"%s\" >", $content_row["order_num"] );
						printf( "<input type=hidden name=live value=\"%s\" >", $content_row["live"] );
					}
					echo "<tr valign=top><td></td><td>";
					if ( ! $isAnArticle ) {
						cms_admin_submit_button( 'publish', 'preview', 'preview', 'preview', 'onclick="return doPreview();"' );
					}
					/*
								  $checked = ($content_row["live"] == 1) ? "checked='checked'" : '';
								  echo "<input type='radio' name='live' value='1' $checked />live<br />";
								  $checked = ($content_row["live"] == 0) ? "checked='checked'" : '';
								  echo "<input type='radio' name='live' value='0' $checked />hidden";
								  echo "<input type=submit name=submit_edit value=\"Submit\"></td></tr>";
								 */
					echo "&nbsp;\n";
					cms_admin_submit_button( 'publish', 'savepublish', 'submit_edit', 'Submit' );
					echo "&nbsp;\n";
					cms_admin_submit_button( 'publish', 'savehide', 'submit_hidden', 'Submit' );
					echo "&nbsp;\n";
					cms_admin_submit_button( 'publish', 'cancel', 'cancel', 'cancel', 'onClick="history.go(-1)"' );
					echo "&nbsp;&nbsp;&nbsp;";
					echo "</td></tr>";
					echo "</table>";
					echo "</form>";
				}
					break;
				case ( 'show_content_list' ): {
					printf( "<h2><b>Content Administration<b></h2>" );
					// CHOOSE TYPE OF CONTENT
					// MAIN LIST OF  SECTIONS AND SUBSECTIONS
					echo "<div class='adminboxbuttons'>";
					if ( $session_user_type_id == "1" ) {
						printf( "<p><a href=\"%s?edit_tags_sub_section=1&content_type_id=0\" onMouseOut=\"MM_swapImgRestore()\" onMouseOver=\"MM_swapImage('defaultedittags','','/admin/images/buttons/cmsbutton-Edit_default_search_engine_tags-over.gif',0)\"><img style='border:none' src='./images/buttons/cmsbutton-Edit_default_search_engine_tags-off.gif' name='defaultedittags'></a>&nbsp;", $PHP_SELF );
						if ( PAGES_HAVE_MAIN_IMAGE ) {
							printf( "<a href=\"%s?edit_default_page_image=1\" onMouseOut=\"MM_swapImgRestore()\" onMouseOver=\"MM_swapImage('defaultpageimage','','/admin/images/buttons/cmsbutton-Change_default_page_image-over.gif',0)\"><img style='border:none' src='./images/buttons/cmsbutton-Change_default_page_image-off.gif' name='defaultpageimage'></a>", $PHP_SELF );
						}
						if ( PAGES_HAVE_MAIN_ICON ) {
							printf( " <a href=\"%s?edit_default_page_logo=1\" onMouseOut=\"MM_swapImgRestore()\" onMouseOver=\"MM_swapImage('defaultpagelogo','','/admin/images/buttons/cmsbutton-Change_default_page_logo-over.gif',0)\"><img style='border:none' src='./images/buttons/cmsbutton-Change_default_page_logo-off.gif' name='defaultpagelogo'></a>", $PHP_SELF );
						}
						echo "<br /></p>";
					}
					printf( "<a href=\"/php/filecontroller/imagecontroller.php\" onMouseOut=\"MM_swapImgRestore()\" onMouseOver=\"MM_swapImage('viewuploadedimgs','','/admin/images/buttons/cmsbutton-View_your_uploaded_images-over.gif',0)\" onClick=\"return pop_up(this)\"><img style='border:none' src='./images/buttons/cmsbutton-View_your_uploaded_images-off.gif' name='viewuploadedimgs'></a> ", $PHP_SELF );
					printf( "<a href=\"/php/filecontroller/filecontroller.php\" onMouseOut=\"MM_swapImgRestore()\" onMouseOver=\"MM_swapImage('viewuploadedfiles','','/admin/images/buttons/cmsbutton-View_your_uploaded_files-over.gif',0)\" onClick=\"return pop_up(this)\"><img style='border:none' src='./images/buttons/cmsbutton-View_your_uploaded_files-off.gif' name='viewuploadedfiles'></a> ", $PHP_SELF );
					printf( "<p>Please select the page you wish to manage:" );
					echo "</div>";
					// LIST SECTIONS ///////////////////////
					$section_sql    = "SELECT * FROM section WHERE editable_content_area ='1' ORDER BY order_num";
					$section_result = mysql_query( $section_sql );
					$section_id = 0;
					$num_sections_listed = 0;
					while ( $section_row = mysql_fetch_array( $section_result ) ) {
						$acc = $acc + 1;
						if ( $section_id != $section_row["id"] ) {
							// DISPLAY SECTION INFO
							$section_id = $section_row["id"];
							$section_intro_page_sql    =
								"SELECT ct.*, maint_title, maint_page, has_maintence, has_body, has_subsections,
                            has_articles, can_edit, can_delete, COALESCE(`value`,1) `show`
							 FROM content_type ct
							 join page_type pt on ct.page_type  = pt.id
							 left join configuration c on config_flag = c.name
							 WHERE section_id = '$section_id' AND parent_id = '0' and ct.hide_from_admin = 0 ";
							$section_intro_page_result = mysql_query( $section_intro_page_sql );
							$section_intro_page_row = mysql_fetch_array( $section_intro_page_result );
							if ( $section_intro_page_row['show'] != '1' ) {
								continue;
							}
							$rowBlock = $rowBlocks[0] ++;
							echo "
						<div class=\"buttonsRow\" level=0 block=\"$rowBlock\"  recId=\"$section_id\">
						<div class=\"main\">
						  <table width=\"100%\" border=0 cellspacing=0 cellpadding=0 >
							<tr >
							  <td width=296 rowspan=2>";
							// NAME OF SECTION //
							$section_name = $section_row["name"];
							if ( strlen( $section_name ) > 35 ) {
								$section_name = substr( $section_name, 0, 32 );
								$section_name = $section_name . "...";
							}
							printf( "<div class=contentfieldactive>%s</div>", $section_name );
							echo "</td>";
							$parent_id = $section_intro_page_row["id"];
							$list_of_users_who_can_edit_page = $section_intro_page_row["edited_by_user_id"];
							$user_id_to_search_for           = "," . $session_user_id . ",";
							// ALL ADMIN USERS CAN EDIT ANY PAGE
							if ( ( ( strstr( $list_of_users_who_can_edit_page, $user_id_to_search_for ) ) ||
							       ( $session_user_type_id == "1" ) ) && ( $section_intro_page_row['has_body'] == '1' )
							) {
								// EDIT PAGE //
								if ( $section_intro_page_row['can_edit'] ) {
									$content_id = db_get_single_value( "select id FROM content WHERE content_type_id = '$parent_id' and template_type = 'main_body'", 'id' );
									$href       = sprintf( "%s?edit_item=yes&id=%s&content_type_id=%s", $PHP_SELF, $content_id, $section_intro_page_row["id"] );
									cms_admin_button( $href, "contentbutton", "edit page" );
								}
								if ( SITE_HAS_ONPAGELINKS ) {
									$href = sprintf( "/modules/onpagelinks/admin/main.php?parent_id=%s&id=%s&parent_name=%s", $content_id, $content_id, urlencode( $section_name ) );
									cms_admin_button( $href, "contentbutton", "promo links" );
								}
								if ( $section_intro_page_row['has_articles'] ) {
									$href = sprintf( "%s?list_articles=yes&content_type_id=%s", $PHP_SELF, $section_intro_page_row["id"] );
									cms_admin_button( $href, "contentbutton", "add edit articles" );
								}
							}
							if ( $session_user_type_id == "1" ) {
								if ( $section_intro_page_row['has_maintence'] == '1' ) {
									$maint_link = $section_intro_page_row['maint_page'];
									$maint_link .= ( strpos( $maint_link, '?' ) == 0 ) ? '?' : '&';
									$maint_link .= "content_type_id={$section_intro_page_row['id']}";
									$maint_title = $section_intro_page_row['maint_title'];
									cms_admin_button( $maint_link, "contentbutton", $maint_title );
								}
							} else {
								//			echo "<td width=100%  rowspan=2 background=\"/admin/images/main-bk.gif\">";
								// BLANK BUTTON
								//			echo "<img src=\"/admin/images/main-bk.gif\" width=98 height=32 border=0>";
								//			echo "</td>";
							}
							// ADD/EDIT ARTICLES ///////////////
							if ( ( $session_user_type_id == "1" ) && ( $section_id > 1 ) && ( MAIN_SECTIONS_FIXED == 0 ) ) {
								if ( $section_intro_page_row["status"] == 0 ) {
									// SHOW SECTION
									$href = sprintf( '%s?show_page=yes&content_type_id=%s', $PHP_SELF, $section_intro_page_row["id"] );
									cms_admin_button( $href, "contentbutton", "show section", 'class="showButton showHideButton"' );
								} else {
									// HIDE SECTION
									$href = sprintf( '%s?hide_page=yes&content_type_id=%s', $PHP_SELF, $section_intro_page_row["id"] );
									cms_admin_button( $href, "contentbutton", "hide section", 'class="hideButton showHideButton"' );
								}
							}
							// DELETE SECTION
							if ( ( $section_intro_page_row['can_delete'] ) && ( $section_id > 1 ) && ( $session_user_type_id == "1" ) && ( MAIN_SECTIONS_FIXED == 0 ) ) {
								$href = sprintf( '%s?delete_section=1&content_type_id=%s', $PHP_SELF, $section_intro_page_row["id"] );
								cms_admin_button( $href, "contentbutton", "delete section", 'class="deleteButton"' );
							}
							if ( $session_user_type_id == "1" ) {
								if ( $section_intro_page_row['has_body'] == '1' ) {
									$href = sprintf( "%s?select_editors=yes&content_type_id=%s", $PHP_SELF, $section_intro_page_row["id"] );
									cms_admin_button( $href, "contentbutton", "select editors" );
									//$href = sprintf( "%s?edit_tags_sub_section=1&content_type_id=%s", $PHP_SELF, $section_intro_page_row["id"] );
									//cms_admin_button( $href, "contentbutton", "edit tags" );
								}
							}


							// MAKE THIS SECTION SECURE
						 	if ( SITE_HAS_SECURE_SECTIONS ) {
								// look up section .. show public/private section text
								$href = sprintf( '%s?secure_section=1&content_type_id=%s', $PHP_SELF, $section_intro_page_row["id"] );
								// add ajax call on the secureButton in the top of file near delete ajax call
								$public = db_get_single_value("SELECT public FROM content_public WHERE section_id = " . $section_intro_page_row["section_id"]);
								if($public == 1){
									cms_admin_button( $href, "contentbutton", "public section", 'class="securityButton public"' );
								}else{
									cms_admin_button( $href, "contentbutton", "secure section", 'class="securityButton secure"' );
								}
						 	}





							// MOVE SECTION UP
							if ( ( $session_user_type_id == "1" ) && ( MAIN_SECTIONS_FIXED == 0 ) ) {
								printf( "<td width='0'><a href='%s?content_type_id=%s&move=up'", $PHP_SELF, $section_intro_page_row["id"] );
								echo " class=\"upButton moveButton\"";
								echo "onmouseout=\"MM_swapImgRestore()\" onmouseover=\"MM_swapImage('mainup$acc','','/admin/images/button-moveup-over.gif',1)\">";
								echo "<img src=\"/admin/images/button-moveup-off.gif\" alt=\"move page up\" name=\"mainup3\" width='16' height='16' border=\"0\" id=\"mainup$acc\" /></a></td>";
							}
							// HELP
							echo "<td width=100% rowspan=2 style=\"background:url(/admin/images/main-bk.gif);\">";
							//printf ("<div align=right><a href=\"/admin/help.php#main_section\"><img src=\"/admin/images/help-icon.gif\" alt=\"help\" width=25 height=32 border=0 /></a></div>");
							echo "</td>";
							?>

                                    </tr>
                                    <!-- MOVE SECTION DOWN -->
                                    <tr>
                                    <?php
							if ( ( $session_user_type_id == "1" ) && ( MAIN_SECTIONS_FIXED == 0 ) ) {
								printf( "<td width='0'><a href='%s?content_type_id=%s&move=down'", $PHP_SELF, $section_intro_page_row["id"] );
								echo " class=\"downButton moveButton\"";
								echo "onmouseout=\"MM_swapImgRestore()\" onmouseover=\"MM_swapImage('maindown$acc','','/admin/images/button-movedown-over.gif',1)\">";
								echo "<img src=\"/admin/images/button-movedown-off.gif\" alt=\"move page down\" name=\"maindown3\" width='16' height='16' border='0' id='maindown$acc' /></a></td>";
							}
							?>
                                    </tr>
                                    </table>
                                    </div>

                                    <?php
						}
						// LIST SUB-SECTIONS //////////////
						if ( $section_intro_page_row['has_subsections'] == '1' && ! SITE_IS_STARTER ) {
							show_sub_sections( $parent_id, 1 );
						}
						echo '</div>';
					}
					if ( ( $session_user_type_id == "1" ) && ( MAIN_SECTIONS_FIXED == 0 ) ) {
						?>
						<div class="lightbox">
							<table width="100%" border="0" cellspacing="0" cellpadding="0">
								<tr>
									<?php
										$href = sprintf( "%s?edit_item=yes&section_id=new", $PHP_SELF );
										cms_admin_button( $href, "content", "create new main section" );
									?>
									<td width="100%" style="background:url(/admin/images/lightbox-left-bk.gif);"></td>
								</tr>
							</table>
						</div>
					<?php
					}
				}
					break;
			}
		} else {
			printf( "You do not have the appropriate type of login account to view this page.
			 <P>Please <a href=\"../logout.php\">logout</a> then login again using an Admin account." );
		}
	} else {
		if ( $login ) {
			echo $login_error;
		} else {
			include( "./login_inc.php" );
		}
	}
?>
	<!-- CONTENT ENDS HERE -->
<?php
	include( "./admin_footer_inc.php" );