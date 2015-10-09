<?php
	/***
	 * All front end pages start here
	 *
	 *
	 */
        
	$show_debug_footer = false;
	$show_php_debug    = false;
	if ( isset( $_GET['php_debug'] ) || $show_php_debug ) {
		ini_set( 'display_errors', '1' );
		// big html error blocks
		ini_set( 'html_errors', 'off' );
		ini_set( 'error_reporting', E_ERROR | E_WARNING | E_PARSE );

	}
        
	$root       = $_SERVER['DOCUMENT_ROOT'];        
	$base_path  = rtrim( str_replace( '\\', '/', realpath( '.' ) ), '/' );
	$pageData   = array();
	$javascript = array();
	require './php/smarty/Smarty.class.php';
	$smarty              = new Smarty;
	$smarty->compile_dir = $base_path . '/templates/templates_c';
	require_once $smarty->_get_plugin_filepath( 'modifier', 'format_date' );
	if ( isset( $_GET['flush'] ) ) {
		$smarty->clear_compiled_tpl();
		$smarty->clear_all_cache();
	}
	// Capture all output to buffer
        
	ob_start();
	include( "./php/databaseconnection.php" );
	include( "./php/read_config.php" );
	session_cache_limiter( 'must-revalidate' );
	session_start();
	if ( OFFLINE == 'down' ) {
		$ip             = $_SERVER['REMOTE_ADDR'];
		$client_preview = isset( $_SESSION['preview'] ) ? $_SESSION['preview'] : '';
		if ( ( $client_preview == '' ) && isset( $_GET['preview'] ) ) {
			$client_preview      = $_GET['preview'];
			$_SESSION['preview'] = $client_preview;
		}
		if ( ( ! in_array( $ip, explode( ',', OFFLINE_IP ) ) ) && ( $ip !== '127.0.0.1' ) && ( $client_preview !== OFFLINE_KEY ) ) {
			$smarty->assign( 'site_name', SITE_NAME );
			$smarty->assign( 'message', OFFLINE_MESSAGE );
			$smarty->assign( 'background_colour', OFFLINE_BACKGROUND_COLOUR );
			$smarty->assign( 'offline_typekit', OFFLINE_TYPEKIT_CODE );
			$smarty->display( 'offline/offline.htm', 0 );
			exit;
		}
	}
	$accessibility_flag = ( isset( $_GET['clear'] ) ) ? 'clear' : '';
	if ( FIREBUG == 1 ) {
		include $base_path . '/php/firebug/fb.php';
	}
        
	include( "./php/nice_urls.php" );        
	include( "./php/thumbnails_inc.php" );
        
	if ( $preview_id ) {
		$preview_sql     = "select * from preview where id = '$preview_id'";
		$preview_result  = mysql_query( $preview_sql );               
		$preview_row     = mysql_fetch_array( $preview_result );
                
		$content_type_id = $preview_row['content_type_id'];
              
		if ( $content_type_id != 1 ) {
			$tpl_type = 'content';
		}
		$preview_title = $preview_row['title'];
		$preview_body  = $preview_row['body'];
	}
// Changes to remove need for register globals and avoid warnings
//  -- start
// general variable declarations
	$use_admin_header = 0;
	$PHP_SELF         = $_SERVER['PHP_SELF'];
// Get Session variables
	$session_user_id       = ( isset( $_SESSION['session_user_id'] ) ) ? $_SESSION['session_user_id'] : "";
	$session_user_type_id  = ( isset( $_SESSION['session_user_type_id'] ) ) ? $_SESSION['session_user_type_id'] : "";
	$session_access_to_cms = ( isset( $_SESSION['session_access_to_cms'] ) ) ? $_SESSION['session_access_to_cms'] : "";
	if ( isset( $_REQUEST['id'] ) ) {
		$id = $_REQUEST['id'];
	}
	if ( ! $section_id ) {
		$section_id = 1;
	}
	$session_section_id             = $section_id;
	$_SESSION["session_section_id"] = $session_section_id;
	$session_page                   = $current_page['page_name'];
	if ( ! $session_page ) {
		$session_page = '';
	}
	if ( isset( $current_page['page_params'] ) && ( $current_page['page_params'] != '' ) ) {
		$session_page .= '?' . $current_page['page_params'];
	}
	$_SESSION["session_page"] = $session_page;

	$path_prefix              = ".";
	include( "./php/functions_inc.php" );
	include( "./admin/cms_functions_inc.php" );
	include( "./php/process_login_inc.php" );
	require $root . '/php/social.php';
	if ( ( $session_member_id == '' ) || ( $current_page['page_name'] == 'logout' ) ) {
		$logged_in = false;
	} else {
		$logged_in = true;
	}
       
	$section_sql    = "select * from section where id = '$session_section_id' ";
	$section_result = mysql_query( $section_sql );
	$section_row    = mysql_fetch_array( $section_result );
	// $content_type_id IS THE VARIABLE USED FOR EACH SPECIFIC PAGE WITHIN THE CMS
	if ( $content_type_id ) {
		$content_type_sql = "select * from content_type where id = '$content_type_id' ";
	} else {
		$content_type_sql = "select * from content_type where section_id = '$session_section_id' AND parent_id = '0'";
	}
        
	$content_type_result = mysql_query( $content_type_sql );
	$content_type_row    = mysql_fetch_array( $content_type_result );
	$content_type_id     = $content_type_row["id"];        
	$content_sql         = "select * from content where section_id = '$session_section_id' AND content_type_id = '$content_type_id'  and template_type = 'main_body'";
	$content_result      = mysql_query( $content_sql );
	$content_row         = mysql_fetch_array( $content_result );
	$section_title       = $section_row["name"];
	$page_type           = $content_type_row['page_type'];
	if ( ! $preview_id ) {
		$title = $content_row["title"];
	} else {
		$title = $preview_title;
	}
	$nav_title = $content_type_sql['name'];
	if ( $content_row['image_loc'] ) {
		//$promo_image = sprintf( "<img src='" . USER_IMAGE_DIR . "%s' class='banner-img' alt='%s' />", $content_row['image_loc'], $title );
		$promo_image = sprintf("'/php/thumbimage.php?img=" . USER_IMAGE_DIR . "%s&size=50'", $content_row['image_loc'], $title);
	} else {
		$promo_image_sql    = 'select value from defaults where name = \'default image\'';
		$promo_image_result = mysql_query( $promo_image_sql );
		if ( mysql_num_rows( $promo_image_result ) > 0 ) {
			$promo_image_row = mysql_fetch_array( $promo_image_result );
			//$promo_image     = sprintf( "<img src='" . USER_IMAGE_DIR . "%s' class='banner-img' alt='%s' />", $promo_image_row['value'], $title );;
			$promo_image = sprintf("'/php/thumbimage.php?img=" . USER_IMAGE_DIR . "%s&size=50'", $promo_image_row['value'], $title);
		} else {
			$promo_image = '';
		}
	}
	if ( $content_row["hidepageimage"] == '1' ) {
		$promo_image = '';
	}
	/* Changed the level code here - the previous didn't seem to work */
	// $level = ($content_type_row["parent_id"] == "0") ? 1 : 2;
	$level     = $content_type_row["level"];
	$rhs_image = sprintf( '<img src="' . USER_IMAGE_DIR . '%s" width="240" height="150" />', $content_row['image_loc'] );
	if ( SITE_HAS_SECTION_LINKS ) {
		$section_link = db_get_single_value( 'SELECT c.page_name
FROM content c
INNER JOIN content_type ct ON ct.id = c.`content_type_id`
WHERE parent_id = 0 AND ct.section_id = ' . $section_row['id'], 'page_name' );
	}
	$smarty->assign( 'title', $title );
	$smarty->assign( 'promo_image', $promo_image );
	$smarty->assign( 'nav_title', $nav_title );
	$smarty->assign( 'section_title', $section_title );
	$smarty->assign( 'level', $level );
	$smarty->assign( 'section_link', $section_link );
	$pageData['title'] = $title;
	if ( isset( $content_row['image_loc'] ) ) {
		$smarty->assign( 'rhs_image', $content_row['image_loc'] );
	}
	$content_template = '';
	$page_type_sql    = 'select module_page, coalesce(path, module_or_type) path, template ' .
	                    'from page_type pt left outer join module m on m.name = pt.module_or_type where pt.id = ' . $page_type;
	$page_type_result = mysql_query( $page_type_sql );
	$page_type_row    = mysql_fetch_array( $page_type_result );
	$is_module_page   = $page_type_row['module_page'];
	$module_path      = $page_type_row['path'];
	$template_file    = $page_type_row['template'];
	$show_page        = true;
	if ( $section_row['section_type_id'] == 2 ) {
		include( "./php/process_login_inc.php" );
		if ( ! $session_member_id ) {
			$show_page = false;
			include( "./php/login_inc.php" );
		}
	}
	$virtual_page = $content_row['page_name'];
	if ( ! $virtual_page ) {
		$virtual_page = strip_tags( $_SERVER['REQUEST_URI'] );
	}
	$smarty->assign( 'virtual_page', $virtual_page );
	if ( $preview_id ) {
		echo $preview_body;
		$show_page = false;
	}
	// need to look up shop dir
	$shop_dir = 'shop';
	if ( $name_parts[0] == $shop_dir ) {
		if ( $name_parts[1] == 'success' || $name_parts[1] == 'failed' ) {
			//echo 'kill basket';
			$_SESSION['basket'] = array();
		}
	}
	$virtual_page = $content_row['page_name'];
	$sectiontitle = $title;
	if ( $show_page ) {
		$inc_file = "main.php";
		if ( $is_module_page ) {
			$inc_path = "./modules/$module_path/main.php";
		} else {
			if ( $id ) {
				$inc_file = "article.php";
			}
			$inc_path = "./php/page_types/$module_path/$inc_file";
		}                
		if ( file_exists( $inc_path ) ) {
			include( $inc_path );                
		} else {
			include( "./php/page_types/normal/$inc_file" );                        
		}
	}
        #echo $inc_path; exit;
	include( "./php/menu_inc.php" );      
	include_once './php/output.php';
       
         