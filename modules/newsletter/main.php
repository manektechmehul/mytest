<?php
	/**
	 * Created by PhpStorm.
	 * User: glen
	 * Date: 09/06/2015
	 * Time: 12:24
	 */
	// Set to true to view the mails without sending
	$debug = true;
	$live = true;
	// set page in cron mode - when running for a cron job there is no $_SERVER ... so set this manually
	$cron_mode = false;
	// show php debug
	//$php_debug = true;
	// show php debug
	if ( $php_debug ) {
		ini_set( 'display_errors', '1' );
		ini_set( 'html_errors', 'on' );
		ini_set( 'error_reporting', '-1' );
	}
	if ( $cron_mode ) {
		// $base_path = '/var/www/vhosts/nationalclubxxxxxxxxxxxxxxxxxxxxx.org.uk/httpdocs';
		$base_path = "E:/htdocs/RHospice/";
	} else {
		$base_path = $_SERVER['DOCUMENT_ROOT'];
	}
	include( $base_path . '/php/databaseconnection.php' );
	include( $base_path . '/php/functions_inc.php' );
	include( $base_path . "/modules/newsletter/classes/newsletter.php" );
	include( $base_path . "/php/smarty/Smarty.class.php" );
	$smarty              = new Smarty;
	$smarty->compile_dir = $base_path . '/templates/templates_c';
	//if ( $debug ) {
	//	echo '<h1> Currently in debug mode - so not sending mails </h1>';
	//	echo 'filename is ' . dirname( __FILE__ );
	//}
	$from    = "The National Club <secretariat@thenationalclub.org.uk>";
	$replyto = "secretariat@thenationalclub.org.uk";
	//$subject = "Glens Amazing Mailer";
	$n = new newsletter();
	$n::create( $live, $from, $replyto, $subject);