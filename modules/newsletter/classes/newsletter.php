<?php

	/**
	 * Created by PhpStorm.
	 * User: glen
	 * Date: 09/06/2015
	 * Time: 12:25
	 */
	class newsletter {

		static function create( $send = false, $from, $replyto, $subject) {
			$main = self::generateNewsLetterMain();
			// if main is empty - as it has been switched off, exit process
			if ( $main ) {
				$events    = self::getEvents();
				$news    = self::getNews();
				$members   = self::getMembers();
				// loop for each member
				foreach($members as $m) {
					$firstname = $m['firstname'];
					$to = $m['email'];
					$body      = $main['html'];
					$subject = $main['name'];
					$content      = self::replaceInMailTemplate( $firstname, $body, $events, $news );
					echo "<br>sending mail ";
					if ( $send ) {
						// TODO: remove before going live
					//	$to      = "glen@codelab.software"; // for easy testing
						$response = self::send_mailgun($to, $from, $replyto, $subject, $content);
						var_dump($response);
					} else {
						echo $content;
					}
				}
			}
		}

		private static function generateNewsLetterMain() {
			// lets assume we are just going to get the first available newsletter for now
			// trying not to use usual cms classes as this wll need to work in a cron type mode
			$sql             = "select * from newsletter where published =1 order by 1";
			$newsletter_main = db_get_single_row( $sql );
			return $newsletter_main;
		}


		private static function getNews() {
			$startOfYesterday = mktime( 0, 0, 0, date( "m" ), date( "d" ) - 1, date( "Y" ) );
			$date             = date( 'y-m-d', $startOfYesterday );
			// for bookings table
			$sql = "SELECT * FROM news WHERE `date` > NOW() AND published = 1 AND ARCHIVE = 0 ORDER BY `date` LIMIT 2";
			$news  = db_get_rows( $sql );

			global $base_path;
			global $smarty;
			/*** TODO: change this base Url for LIVE **/
			$baseUrl = "http://www.thenationalclub.isarriving.com/";
			$smarty->assign( 'news', $news );
			$smarty->assign( 'baseUrl', $baseUrl );
			$tmpl   = $base_path . '/modules/newsletter/templates/news.tpl';
			$output = $smarty->fetch( $tmpl );
			//echo $output;
			//echo 'global site url is ' . SITE_ADDRESS;
			return $output;
		}

		private static function getEvents() {
			$startOfYesterday = mktime( 0, 0, 0, date( "m" ), date( "d" ) - 1, date( "Y" ) );
			$date             = date( 'y-m-d', $startOfYesterday );
			// for events table
		//	$sql              = "select id, title, summary, body as description,   unix_timestamp(startdate) as startdate, unix_timestamp(enddate) enddate, thumbnail, page_name from events " .
		//	                    "where enddate > '$date' and published = 1 order by startdate limit 3";

			// for bookings table
			$sql = "SELECT speaker_info, event_time, id, title, description AS summary, body AS description,   UNIX_TIMESTAMP(start_date) AS startdate, UNIX_TIMESTAMP(end_date) end_date, thumb AS thumbnail, page_name FROM booking  " .
				                "where end_date > '$date' and published = 1 order by startdate limit 2";
			$events = db_get_rows( $sql );
			global $base_path;
			global $smarty;
			/*** TODO: change this base Url for LIVE **/
			$baseUrl = "http://www.thenationalclub.isarriving.com/";
			$smarty->assign( 'events', $events );
			$smarty->assign( 'baseUrl', $baseUrl );
			$tmpl   = $base_path . '/modules/newsletter/templates/events.tpl';
			$output = $smarty->fetch( $tmpl );
			return $output;
		}

		/** TODO: need to change this function to look for member who have subscribed  */
		// i am 1441 - ant is 1436
		private static function getMembers() {
			$sql     = "select * from shop_member_user  where status = 1 and id in (1436,1441) ";
			$members = db_get_rows( $sql );
			return $members;
		}


		private static function replaceInMailTemplate( $first_name, $body, $events, $news ) {
			global $base_path;
			$template = $base_path . "/modules/newsletter/templates/email.html";
			$mail     = file_get_contents( $template );
			$filters  = array(
				'first_name' => array( 'search_string' => '/<!-- CS first_name start -->.*<!-- CS first_name end -->/s', 'replace_string' => $first_name ),
				'body' => array( 'search_string' => '/<!-- CS body start -->.*<!-- CS body end -->/s', 'replace_string' => $body ),
				'events' => array( 'search_string' => '/<!-- CS events start -->.*<!-- CS events end -->/s', 'replace_string' => $events ),
				'news' => array( 'search_string' => '/<!-- CS news start -->.*<!-- CS news end -->/s', 'replace_string' => $news ),
			);
			foreach ( $filters as $filter ) {
				$mail = preg_replace( $filter['search_string'], $filter['replace_string'], $mail );
			}
			return $mail;
		}

		static function send_mailgun($to, $from, $replyto, $subject, $content){
			/** @var  $api_key store in database*/
			$api_key = "key-0dbf90034de4fcd3dc987971600af6f8";
			/** @var  $api_url store in database */
			$api_url = "https://api.mailgun.net/v3/sandbox5e8310e4886a4542b99bdc97410721ff.mailgun.org/messages";

			/* testing setting */
			if(false) {
				$to      = "glen@codelab.software";
				$from    = "Excited User <mailgun@sandbox5e8310e4886a4542b99bdc97410721ff.mailgun.org>";
				$replyto = "&lt;info@codelab.software.com&gt;";
				$subject = "My first mailgun adventure";
				$content = "This is the email content";
			}

			$config = array();
			$config['api_key'] = $api_key;
			$config['api_url'] = $api_url;

			$message = array();
			$message['from'] = $from;
			$message['to'] = $to;
			$message['h:Reply-To'] = $replyto;
			$message['subject'] = $subject;
			$message['html'] = $content;

			//var_dump($message);

			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, $config['api_url']);
			curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
			curl_setopt($ch, CURLOPT_USERPWD, "api:{$config['api_key']}");
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
			curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
			curl_setopt($ch, CURLOPT_POST, true);
			curl_setopt($ch, CURLOPT_POSTFIELDS,$message);
			$result = curl_exec($ch);
			curl_close($ch);
			return $result;
		}

	}