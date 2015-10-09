<?php

	if ( ! isset( $base_path ) || ( $base_path == '' ) ) {
		$base_path = str_replace( '\\', '/', realpath( '..' ) );
	}

	// inlude_once ($base_path."/php/phpmailer/class.phpmailer.php");

	function send_mail( $emailaddress, $fromaddress, $emailsubject, $body, $body_text, $attachment = "", $bcc_list = "", $fromname = "" ) {
		global $base_path;
		$mail            = new PHPMailer();
		$mail->PluginDir = $base_path . '/php/classes/';
		$mail->IsMail();
		//$mail->IsSMTP();
		$mail->From     = $fromaddress;
		$mail->FromName = ( $fromname ) ? $fromname : EMAIL_INVITE_FROM_NAME;
		$mail->AddAddress( $emailaddress );
		$mail->AddReplyTo( $fromaddress, ( $fromname ) ? $fromname : EMAIL_INVITE_FROM_NAME );
		$mail->ConfirmReadingTo = $fromaddress;
		if ( $attachment ) {
			$mail->AddAttachment( $attachment );
		}
		if ( is_array( $bcc_list ) ) {
			foreach ( $bcc_list as $bcc ) {
				$mail->AddBCC( $bcc );
			}
		}
		$mail->WordWrap = 50;                                 // set word wrap to 50 characters
		$mail->IsHTML( true );                                  // set email format to HTML
		$mail->Subject = $emailsubject;
		$mail->Body    = $body;
		$mail->AltBody = $body_text;
		return $mail->Send();
	}

	function display_lookup_menu( $table_name, $field_name, $details_row, $name_of_lookup_value_field ) {
		$sql = "SELECT * FROM " . $table_name;
		$result = mysql_query( $sql );
		printf( "<select name=\"%s\">", $field_name );
		printf( "<option value=\"\" " );
		if ( $row[$field_name] == "" ) {
			printf( " selected " );
		}
		printf( " >Please select</option>" );
		while ( $row = mysql_fetch_array( $result ) ) {
			printf( "<option value=\"%s\" ", $row["id"] );
			if ( $details_row[$field_name] == $row["id"] ) {
				printf( " selected " );
			}
			printf( " >%s</option>", $row[$name_of_lookup_value_field] );
		}
		echo "</select>";
		return;
	}

	function check_date_fields( $field_name, $day, $month, $year ) {
		if ( ( $day == "" ) || ( $month == "" ) || ( $year == "" ) ) {
			printf( "<span class=\"title\"><b>Error: Incomplete Date</b></span><p>" );
			printf( "The details have not yet been updated.<p>" );
			printf( "The date you set for <b>%s</b> is missing some information.  You need to set the day, month and year.", $field_name );
			printf( "<p>Please click on the <b>Back</b> button to enter the missing details." );
			printf( "<p><INPUT TYPE=\"button\" VALUE=\"<< Back\" onClick=\"history.go(-1)\">" );
			echo "</body></html>";
			exit;
		}
		$combined_date = $year . "-" . $month . "-" . $day;
		return $combined_date;
	}

	function check_time_fields( $field_name, $hour, $minute ) {
		if ( ( $hour == "" ) || ( $minute == "" ) ) {
			printf( "<span class=\"title\"><b>Error: Incomplete Time</b></span><p>" );
			printf( "The details have not yet been updated.<p>" );
			printf( "The time you set for <b>%s</b> is missing some information.  You need to set the hour and minutes.", $field_name );
			printf( "<p>Please click on the <b>Back</b> button to enter the missing details." );
			printf( "<p><INPUT TYPE=\"button\" VALUE=\"<< Back\" onClick=\"history.go(-1)\">" );
			echo "</body></html>";
			exit;
		}
		$combined_time = $hour . ":" . $minute . ":00";
		return $combined_time;
	}

	function display_date_menus( $row, $base_field_name, $year_start, $year_end ) {
		$current_day   = 0;
		$current_month = 0;
		$current_year  = 0;
		$current_day   = substr( $row[$base_field_name], 8, 2 );
		$current_month = substr( $row[$base_field_name], 5, 2 );
		$current_year  = substr( $row[$base_field_name], 0, 4 );
		// display day drop-down
		$field_name = $base_field_name . "_day";
		printf( " <select name=\"%s\">", $field_name );
		printf( "<option value=\"\">day</option>" );
		$day_num = 1;
		while ( $day_num < 32 ) {
			$selected = "";
			if ( $day_num == $current_day ) {
				$selected = "selected";
			}
			$day_num = str_pad( $day_num, 2, "0", STR_PAD_LEFT );
			printf( "<option value=\"%s\" %s>", $day_num, $selected );
			printf( "%s</option>", $day_num );
			$day_num = $day_num + 1;
		}
		printf( "</select>" );
		// display month drop-down
		$field_name = $base_field_name . "_month";
		printf( " <select name=\"%s\">", $field_name );
		printf( "<option value=\"\">month</option>" );
		$month_num = 1;
		while ( $month_num < 13 ) {
			$selected = "";
			if ( $month_num == $current_month ) {
				$selected = "selected";
			}
			$month_num = str_pad( $month_num, 2, "0", STR_PAD_LEFT );
			printf( "<option value=\"%s\" %s>", $month_num, $selected );
			printf( "%s</option>", get_month_name( $month_num ) );
			$month_num = $month_num + 1;
		}
		printf( "</select>" );
		// display year drop-down
		$field_name = $base_field_name . "_year";
		printf( " <select name=\"%s\">", $field_name );
		printf( "<option value=\"\">year</option>" );
		$year_num = $year_start;
		while ( $year_num <= $year_end ) {
			$selected = "";
			if ( $year_num == $current_year ) {
				$selected = "selected";
			}
			printf( "<option value=\"%s\" %s>", $year_num, $selected );
			printf( "%s</option>", $year_num );
			$year_num = $year_num + 1;
		}
		printf( "</select>" );
		return;
	}

	function display_time_menus( $row, $base_field_name ) {
		$current_hour   = 0;
		$current_minute = 0;
		$current_hour   = substr( $row[$base_field_name], 0, 2 );
		$current_minute = substr( $row[$base_field_name], 3, 2 );
		// display day drop-down
		$field_name = $base_field_name . "_hour";
		printf( "Hour: <select name=\"%s\">", $field_name );
		$hour_num = 1;
		while ( $hour_num < 25 ) {
			$selected = "";
			if ( $hour_num == $current_hour ) {
				$selected = "selected";
			}
			$hour_num = str_pad( $hour_num, 2, "0", STR_PAD_LEFT );
			printf( "<option value=\"%s\" %s>", $hour_num, $selected );
			printf( "%s</option>", $hour_num );
			$hour_num = $hour_num + 1;
		}
		printf( "</select>" );
		// display month drop-down
		$field_name = $base_field_name . "_minute";
		printf( "&nbsp;&nbspMins: <select name=\"%s\">", $field_name );
		$minute_num = 0;
		while ( $minute_num < 61 ) {
			$selected = "";
			if ( $minute_num == $current_minute ) {
				$selected = "selected";
			}
			$minute_num = str_pad( $minute_num, 2, "0", STR_PAD_LEFT );
			printf( "<option value=\"%s\" %s>", $minute_num, $selected );
			printf( "%s</option>", $minute_num );
			$minute_num = $minute_num + 5;
		}
		printf( "</select>" );
		return;
	}

	function display_date( $date_from_dbase ) {
		$day         = substr( $date_from_dbase, 8, 2 );
		$month       = substr( $date_from_dbase, 5, 2 );
		$year        = substr( $date_from_dbase, 0, 4 );
		$date_string = get_month_name( $month ) . " " . $day . " " . $year;
		return $date_string;
	}

	function display_main_body_content( $section_id, $content_type_id ) {
		$main_body_sql = "select * from content where section_id = '$section_id' AND content_type_id = $content_type_id AND template_type = 'main_body' AND live = 1 order by order_num DESC";
		$main_body_result = mysql_query( $main_body_sql );
		while ( $main_body_row = mysql_fetch_array( $main_body_result ) ) {
			// printf ("<h1>%s</h1>", $main_body_row["title"]);
			printf( "%s\n", $main_body_row["body"] );
		}
		return;
	}

	function display_chosen_item( $id, $uploaddir, $path_prefix ) {
		$content_sql    = "select * from content where id = $id";
		$content_result = mysql_query( $content_sql );
		while ( $content_row = mysql_fetch_array( $content_result ) ) {
			echo "<table  border=0 cellspacing=\"0\" cellpadding=\"4\">
		<tr valign=top>
	        <td valign=top class=cms>";
			printf( "<p><b>%s</b>", $content_row["title"] );
			if ( ( $content_row["image_loc"] != "none" ) && ( $content_row["image_loc"] != "" ) ) {
				if ( $content_row["template_type"] == "image_left" ) {
					printf( "<IMG align=left hspace=4 vspace=4 src=\"%s/%s\">", $uploaddir, $content_row["image_loc"] );
				} else if ( $content_row["template_type"] == "image_right" ) {
					printf( "<IMG align=right hspace=4 vspace=4 src=\"%s/%s\">", $uploaddir, $content_row["image_loc"] );
				}
			}
			// display header and body text of item
			printf( "<p>%s", $content_row["body"] );
			// display any downloadable items
			$downloadable_sql    = "select * from downloadable_item where content_id = $id";
			$downloadable_result = mysql_query( $downloadable_sql );
			if ( mysql_num_rows( $downloadable_result ) > 0 ) {
				printf( "<p>Download associated files:" );
				printf( "<table cellpadding=4>" );
				while ( $downloadable_row = mysql_fetch_array( $downloadable_result ) ) {
					if ( $downloadable_row["file_loc"] != "" ) {
						// determine file type from extension
						if ( strpos( $downloadable_row["file_loc"], ".doc" ) ) {
							$file_type_image = $path_prefix . "/admin/images/wordicon-sm.gif";
						} else if ( strpos( $downloadable_row["file_loc"], ".pdf" ) ) {
							$file_type_image = $path_prefix . "/admin/images/pdficon-sm.gif";
						} else if ( strpos( $downloadable_row["file_loc"], ".mp3" ) ) {
							$file_type_image = $path_prefix . "/admin/images/musicicon-sm.gif";
						} else if ( ( strpos( $downloadable_row["file_loc"], ".avi" ) ) || ( strpos( $downloadable_row["file_loc"], ".wmf" ) ) || ( strpos( $downloadable_row["file_loc"], ".mov" ) ) ) {
							$file_type_image = $path_prefix . "/admin/images/videoicon-sm.gif";
						} else {
							$file_type_image = $path_prefix . "/admin/images/othericon-sm.gif";
						}
						printf( "<tr><td>" );
						if ( $file_type_image != "" ) {
							printf( "<a href=\"%s/%s\" target=_blank><img src=\"%s\" border=0></a>", $uploaddir, $downloadable_row["file_loc"], $file_type_image );
						}
						printf( "</td>" );
						printf( "<td ><a href=\"%s/%s\" target=_blank>%s</a></td></tr>", $uploaddir, $downloadable_row["file_loc"], $downloadable_row["title"] );
					}
				}
				printf( "</table>" );
			}
			printf( "</td></tr></table>" );
		}
		return;
	}

	function display_items( $section_id, $content_type_id, $item_type_title, $max_num_chars_to_show, $uploaddir, $strip_html_tags ) {
		global $max_num_items_to_list;
		// DISPLAY ALL live items
		$sql = "select * from content where content_type_id = $content_type_id AND template_type != 'main_body' AND live = 1 order by order_num DESC";
		if ( $max_num_items_to_list > 0 ) {
			$sql .= " LIMIT " . $max_num_items_to_list;
		}
		//now process sql statement and begin loop
		$result = mysql_query( $sql );
		if ( mysql_num_rows( $result ) > 0 ) {
			if ( $item_type_title == "" ) {
				echo "<br><hr>";
			}
			if ( $item_type_title == " " ) {
				// display nowt
			} else {
				printf( "<b>%s</b>", $item_type_title );
			}
//	if ($item_type_title != "") {
//		printf ("%s:", $item_type_title);
//		}
		}
		while ( $myrow = mysql_fetch_array( $result ) ) {
			$id = $myrow["id"];
			// shorten body of text if wish to only display first x chars
			$ln = $max_num_chars_to_show;
//	$text=substr($myrow["body"],0,$ln);
			if ( $strip_html_tags == "yes" ) {
				$text = strip_tags( $myrow["body"] );
			} else {
				$text = $myrow["body"];
			}
			$body_len = strlen( $text );
			$text = substr( $text, 0, $ln );
			if ( $body_len > $ln ) {
				$text = $text . "...";
			}
			echo "<table border=0 cellspacing=\"0\" cellpadding=\"4\">
		<tr valign=top>
                 <td>";
			printf( "<b>%s</b>", $myrow["title"] );
			if ( $strip_html_tags == "yes" ) {
				$text = strip_tags( $text );
			}
			if ( ( $myrow["template_type"] != "no_image" ) && ( $myrow["thumbnail_image_loc"] != "" ) ) {
				$alignment = "left";
				if ( $myrow["template_type"] == "image_left" ) {
					$alignment = "left";
				} else if ( $myrow["template_type"] == "image_right" ) {
					$alignment = "right";
				}
				echo "<table width=100%>
			<tr valign=top>";
				if ( $myrow["template_type"] == "image_left" ) {
					printf( "<td><IMG src=\"%s/%s\"></td>", $uploaddir, $myrow["thumbnail_image_loc"] );
					printf( "<td><p>%s</td>", $text );
				} else {
					printf( "<td><p>%s</td>", $text );
					printf( "<td><IMG src=\"%s/%s\"></td>", $uploaddir, $myrow["thumbnail_image_loc"] );
				}
				echo "</tr></table>";
			} else {
				printf( "<br>%s", $text );
			}
			// check to see if there are any downloadable files associated with this item
			$content_id          = $myrow["id"];
			$downloadable_sql    = "select * from downloadable_item where content_id = $content_id";
			$downloadable_result = mysql_query( $downloadable_sql );
			if ( ( $body_len > $ln ) || ( mysql_num_rows( $downloadable_result ) > 0 ) ||
			     ( ( $myrow["image_loc"] != "none" ) && ( $myrow["image_loc"] != "" ) )
			) {
				printf( " [ <a href=\"%s?id=%s&section_id=%s&content_type_id=%s\"><b>more info</b></a> ]", $_SERVER['PHP_SELF'], $myrow["id"], $section_id, $content_type_id );
			}
			echo "</td>
              </tr>
              </table>";
			echo "<br><img src=\"/images/1x1brown.gif\" height =1 width=100%><br>";
		}
		return;
	}

	function get_month_name( $month_num ) {
		if ( $month_num == "01" ) {
			return "Jan";
		} else if ( $month_num == "02" ) {
			return "Feb";
		} else if ( $month_num == "03" ) {
			return "Mar";
		} else if ( $month_num == "04" ) {
			return "Apr";
		} else if ( $month_num == "05" ) {
			return "May";
		} else if ( $month_num == "06" ) {
			return "Jun";
		} else if ( $month_num == "07" ) {
			return "Jul";
		} else if ( $month_num == "08" ) {
			return "Aug";
		} else if ( $month_num == "09" ) {
			return "Sep";
		} else if ( $month_num == "10" ) {
			return "Oct";
		} else if ( $month_num == "11" ) {
			return "Nov";
		} else if ( $month_num == "12" ) {
			return "Dec";
		}
		return;
	}

	function getFileExtension( $str ) {
		$i = strrpos( $str, "." );
		if ( ! $i ) {
			return "";
		}
		$l   = strlen( $str ) - $i;
		$ext = substr( $str, $i + 1, $l );
		return $ext;
	}

	function delete_associated_document( $id, $table_name, $field_name, $uploaddir ) {
		// delete the previous file that may have been associated with this record
		$sql    = "select * from " . $table_name . " where id = $id";
		$result = mysql_query( $sql );
		while ( $myrow = mysql_fetch_array( $result ) ) {
			if ( $myrow[$field_name] != "" ) {
				$orig_file = $uploaddir . "/" . $myrow[$field_name];
				unlink( $orig_file );
			}
		}
		return;
	}

	function resizeimage( $original, $image_type, $newwidth, $newheight, $crop = 0 ) {
		//CHANGE THE FIGURE BELOW TO ALLOW FOR LARGER DIMENSION IMAGE UPLOADS - default is 32M
		//ini_set("memory_limit", "70M");
		//SWITCHES THE IMAGE CREATE FUNCTION BASED ON FILE EXTENSION
		switch ( $image_type ) {
			case '.jpg':
				$source = imagecreatefromjpeg( $original );
				break;
			case '.png':
				$source = imagecreatefrompng( $original );
				break;
			case '.gif':
				$source = imagecreatefromgif( $original );
				break;
			default:
				echo( "Error Invalid Image Type: $image_type" );
				return 0;
				break;
		}
		//FINDS SIZE OF THE OLD FILE
		list( $width, $height ) = getimagesize( $original );
		if ( $crop == 0 ) {
			$need_resize = ( $width > $newwidth ) || ( $height > $newheight );
		} else {
			$need_resize = ( $width > $newwidth ) && ( $height > $newheight );
		}
		if ( $need_resize ) {
			$img = $tmp_upload_path;
			$w   = $newwidth;
			$h   = $newheight;
			$hx = ( 100 / ( $width / $w ) ) * .01;
			$hx = @round( $height * $hx );
			$wx = ( 100 / ( $height / $h ) ) * .01;
			$wx = @round( $width * $wx );
			if ( ( ( $hx < $h ) && ( $crop == 0 ) ) || ( ( $hx > $h ) && ( $crop ) ) ) {
				$h = $hx;
			} else {
				$w = $wx;
			}
		} else {
			$w = $width;
			$h = $height;
		}
		//CREATES IMAGE WITH NEW SIZES
		if ( $need_resize ) {
			$thumb = imagecreatetruecolor( $w, $h );
			imagecopyresampled( $thumb, $source, 0, 0, 0, 0, $w, $h, $width, $height );
		} else {
			$thumb = $source;
		}
		//RESIZES OLD IMAGE TO NEW SIZES
		if ( $crop ) {
			$offx = 0;
			$offy = 0;
			if ( $w > $newwidth ) {
				$offx = ( $w - $newwidth ) / 2;
			}
			if ( $h > $newheight ) {
				$offy = ( $h - $newheight ) / 2;
			}
			$cropthumb = imagecreatetruecolor( $newwidth, $newheight );
			imagecopyresampled( $cropthumb, $thumb, 0, 0, $offx, $offy, $newwidth, $newheight, $newwidth, $newheight );
			$thumb = $cropthumb;
		}
		return $thumb;
	}

	function makeimage( $original, $newname, $newwidth, $newheight, $background = '', $crop = 0 ) {
		//SEARCHES IMAGE NAME STRING TO SELECT EXTENSION (EVERYTHING AFTER . )
		$image_type = strtolower( substr( $original, strrpos( $original, '.' ) ) );
		$thumb = resizeimage( $original, $image_type, $newwidth, $newheight, $crop );
		//SAVES IMAGE AND SETS QUALITY || NUMERICAL VALUE = QUALITY ON SCALE OF 1-100
		if ( $background != "" ) {
			$back         = imagecreatefrompng( '../cmsimages/' . $background );
			$back_width   = imagesx( $back );
			$back_height  = imagesy( $back );
			$thumb_width  = imagesx( $thumb );
			$thumb_height = imagesy( $thumb );
			$back_thumb = imagecreatetruecolor( $back_width, $back_height );
			imagealphablending( $back_thumb, false );
			imagesavealpha( $back_thumb, true );
			imagecopy( $back_thumb, $back, 0, 0, 0, 0, $back_width, $back_height );
			$pos_x = ( $back_width - $thumb_width ) / 2;
			$pos_y = ( $back_height - $thumb_height ) / 2;
			imagecopy( $back_thumb, $thumb, $pos_x, $pos_y, 0, 0, $thumb_width, $thumb_height );
			imagejpeg( $back_thumb, $newname, 80 );
		} else {
			imagejpeg( $thumb, $newname, 80 );
		}
		//RETURNS FULL FILEPATH OF IMAGE ENDS FUNCTION
		return 1;
	}

	function makeuploadimage( $original, $newname, $newwidth, $newheight ) {
		$image_type = strtolower( substr( $newname, strrpos( $newname, '.' ) ) );
		$thumb      = resizeimage( $original, $image_type, $newwidth, $newheight );
		imagejpeg( $thumb, $newname, 100 );
		return 1;
	}

	function upload_file( $id, $tmp_upload_path, $uploaded_file_name, $file_type, $max_image_width, $max_image_height, $permitted_extensions, $item_type, $uploaddir, $table_name, $field_name, $auto_resize_image ) {
		/* == get file extension == */
		$pext = getFileExtension( $uploaded_file_name );
		$pext = strtolower( $pext );
		/* == if either permitted_exension has been set check to see if file has right extension file, if not do not allow upload == */
		$permitted = preg_split( "/[, .]+/", $permitted_extensions );
		//($pext != "mp3") && ($pext != "doc") && ($pext != "ppt") && ($pext != "pdf") && ($pext != "xls")
		if ( ! in_array( $pext, $permitted ) ) {
			printf( "<p><b>ERROR</b><P>%s Extension Unknown", $file_type );
			//.mp3, .doc, .ppt, pdf, .xls
			printf( "<p>Please only upload files with the extension: " . implode( ", ", $permitted ) );
			print ".<p>The file you uploaded had the following extension: $pext</p>\n";
			echo "</font>";
			printf( "<INPUT TYPE=\"button\" VALUE=\"Back\" onClick=\"history.go(-1)\">" );
			/* == delete uploaded file == */
			unlink( $tmp_upload_path );
			return 0;
		}
		/* == setup final file location and name == */
		/* == change spaces to underscores in filename  == */
		$final_filename = str_replace( " ", "_", $uploaded_file_name );
		$final_filename = str_replace( "\'", "_", $final_filename );
		$final_filename = str_replace( "%", "_", $final_filename );
		$num               = 0;
		$final_upload_path = $uploaddir . "/" . $item_type . "-" . $num . $final_filename;
		while ( file_exists( $final_upload_path ) ) {
			$num               = $num + 1;
			$final_upload_path = $uploaddir . "/" . $item_type . "-" . $num . $final_filename;
		}
		$final_filename = $item_type . "-" . $num . "$final_filename";
		$move_file_to_upload_dir = "yes";
		/* == do extra security check to prevent malicious abuse== */
		if ( is_uploaded_file( $tmp_upload_path ) ) {
			if ( ( $file_type == "Image" ) && ( $auto_resize_image == "yes" ) ) {
				/* == resize image to be uploaded if larger than max_image_width or max_image_width == */
				$imgsize = GetImageSize( $tmp_upload_path );
				$image_width  = $imgsize[0];
				$image_height = $imgsize[1];
				/* == check size  0=width, 1=height == */
				if ( ( $image_width > $max_image_width ) || ( $image_height > $max_image_height ) ) {
					if ( makeuploadimage( $tmp_upload_path, $final_upload_path, $max_image_width, $max_image_height ) == 0 ) {
						return 0;
					}
					$move_file_to_upload_dir = "no";  // no need to move file because the resize function did it
				}
			}
		} else {
			echo "<p><b>Error - Your document failed to be uploaded</b></p>";
			printf( "<P><b>The size of your file may be too large</b>
				 <p>Please check and make sure that it is less than 2MB in size." );
			printf( "<p>Please click on the on the <b>Back</b> button." );
			printf( "<p><INPUT TYPE=\"button\" VALUE=\"Back\" onClick=\"history.go(-1)\">" );
			return 0;
		}
		if ( $move_file_to_upload_dir == "yes" ) {
			umask( 0 );
			if ( ! copy( $tmp_upload_path, $final_upload_path ) ) {
				/* == if an error occurs the file could not
				  be written, read or possibly does not exist == */
				print "<b>Error Uploading File</b>";
				return 0;
			}
		}
		/* == delete the temporary uploaded file == */
		if ( unlink( $tmp_upload_path ) ) {
			if ( $id ) {
				// delete the previous file that may have been associated with this record
				$sql    = "select * from " . $table_name . " where id = $id";
				$result = mysql_query( $sql );
				while ( $myrow = mysql_fetch_array( $result ) ) {
					if ( $myrow[$field_name] != "" ) {
						$orig_file = $uploaddir . "/" . $myrow[$field_name];
						unlink( $orig_file );
					}
				}
			}
		}
		return $final_filename;
	}

	function display_diary_item( $id, $page_name, $section_id, $content_type_id, $type_name ) {
		global $title;
		$diary_item_sql    = "select * from diary_item WHERE id = '$id'";
		$diary_item_result = mysql_query( $diary_item_sql );
		if ( mysql_num_rows( $diary_item_result ) > 0 ) {
			$diary_item_row = mysql_fetch_array( $diary_item_result );
			$event_day   = substr( $diary_item_row["date_of_event"], 8, 2 );
			$event_month = substr( $diary_item_row["date_of_event"], 5, 2 );
			$event_year  = substr( $diary_item_row["date_of_event"], 0, 4 );
			$event_date = mktime( 12, 30, 10, $event_month, $event_day, $event_year );
			printf( "<p><b>%s</b></p>", date( 'jS F Y', $event_date ) );
			$title = $diary_item_row["title"];
			//printf (": %s</b>
			echo $diary_item_row["description"];
			$old_style_url = ( $page_name == '/content.php' );
			if ( $old_style_url ) {
				$page_link = sprintf( "%s?section_id=%s&content_type_id=%s", $page_name, $section_id, $content_type_id );
			} else {
				$page_link = $page_name;
			}
			echo "<p>";
			if ( $type_name == 'events' ) {
				$img = "<img id='news-current-button' name='news-current-button' src='/images/buttons/button-view_all_events-off.png' alt='View All Events' />";
				echo "<a href='$page_link'>$img</a>";
			} else {
				if ( $diary_item_row['archived'] == 0 ) {
					//echo "<a href='$page_link' class=\"blink\">Back to Past Events</a>";
					echo "<a href='$page_link' onmouseover=\"MM_swapImage('news-current-button','','/images/buttons/button-current_news-over.png',1)\" onmouseout='MM_swapImgRestore()'>";
					echo "<img id='news-current-button' name='news-current-button' src='/images/buttons/button-current_news-off.png' alt='News Current' /></a>";
				} else {
					$page_link .= ( $old_style_url ) ? '&achive' : '?achive';
					//echo "<a href='$page_link' class=\"blink\">Back to Archive</a>";
					echo "<a href='$page_link' onmouseover=\"MM_swapImage('news-archive-button','','/images/buttons/button-news_archive-over.png',1)\" onmouseout='MM_swapImgRestore()'>";
					echo "<img id='news-archive-button' name='news-archive-button' src='/images/buttons/button-news_archive-off.png' alt='News Archive' /></a>";
				}
			}
			echo "</p>";
		}
	}

?>
