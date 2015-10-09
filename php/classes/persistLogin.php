<?php

	/**
	 * A couple of issues to be aware of.
	 * 1) shop basket session is not saved. This is a good thing as if a product is removed from the site a member may try to purchase when there basket is re created
	 *
	 */
	class persistLogin {

		/** LOGIN & REGISTRER COOKIE**/
		/** on login, create a database entry for re-establishing session later .
		called from ./php/process_login_inc.php **/
		static function SaveKeepLoginSession( $id ) {
			$sql = "delete from member_session where  date_sub(now(), interval 16 day) > date and member = $id";
			mysql_query( $sql );
			$date          = date( 'Y-m-d' );
			$sessionId     = uniqid( '', true );
			$connectionStr = self::GetConnectionIdStr();
			$sql           = "insert into member_session (sid, connection, member, `date`) values ('$sessionId', '$connectionStr', $id, '$date')";
			mysql_query( $sql );
			$expire = time() + 60 * 60 * 24 * 150;
			setcookie( 'sid', $sessionId, $expire );
		}



		/** REFRESH COOKIE **/
		/** check if the user is already logged in. If not see if they have a db entry for there session, and log them in
		called from ./php/process_login_inc.php ***/
		static function GetLoginSession() {
			$memberDetails = '';
			$memberId      = ( isset( $_SESSION['session_member_id'] ) ) ? $_SESSION['session_member_id'] : "";
			if ( empty( $memberId ) || ( $memberId == 1 ) ) {
				if ( ! empty( $_COOKIE['sid'] ) ) {
					$sid = $_COOKIE['sid'];
					list( $memberId, $memberDetails ) = self::GetKeepLoginSession( $sid );
				}
			} else {
				$memberDetails = ( isset( $_SESSION['session_member_details'] ) ) ? $_SESSION['session_member_details'] : "";
			}
			return array( $memberId, $memberDetails );
		}

		/** helper for GetLoginSession - get me the db entry for the session */
		private static function GetKeepLoginSession( $sid ) {
			$connectionStr = self::GetConnectionIdStr();
			//filter sid
			$sid           = preg_replace( '/[^a-z0-9]\./', '', $sid );
			$sql           = "select member, connection from member_session where sid = '$sid'";
			$memberSession = db_get_single_row( $sql );
			if ( $memberSession ['connection'] == $connectionStr ) {
				$memberId                           = $memberSession['member'];
				$details                            = self::GetMemberDetailsById( $memberId );
				$_SESSION['session_member_id']      = $memberId;
				$_SESSION['session_member_details'] = $details;
				$_SESSION["session_member_name"]    = "{$details['firstname']} {$details['surname']}";
				if ( SITE_HAS_SHOP ) {
					if ( SHOP_USE_TRADE_PRODUCTS ) {
						$_SESSION['isTrade'] = 1;
					}
				}
			} else {
				$sql      = "delete from member_session where sid = '$sid'";
				$memberId = 0;
				$details  = '';
			}
			return array( $memberId, $details );
		}

		/** helper for GetKeepLoginSession - get member info associated with cookie */
		private static function GetMemberDetailsById( $id ) {
			// check to see if username is already in database
			$sql = "SELECT * FROM member_user WHERE id='$id'";
			if ( SITE_HAS_SHOP ) {
				// if we are using shop member system switch table
				$sql = "SELECT * FROM shop_member_user WHERE id='$id'";
			}
			$row = db_get_single_row( $sql );
			return $row;
		}



		/** KILL COOKIE **/
		/** on logout kill the persistent cookie in the database
		called from ./php/page_types/logout/main.php */
		static function DeleteKeptSession( $memberId ) {
			$connectionStr = self::GetConnectionIdStr();
			$sid           = empty( $_COOKIE['sid'] ) ? '' : $_COOKIE['sid'];
			$sql           = "delete from member_session where (sid = '$sid') or (member = $memberId and connection = '$connectionStr')";
			mysql_query( $sql );
		}


		/** UTIL */
		/** util for looking up the machine the cookie is registered with in the db - extra security */
		private static function GetConnectionIdStr() {
			$ip       = $_SERVER['REMOTE_ADDR'];
			$clientIp = $_SERVER['HTTP_CLIENT_IP'];
			$fwdFor   = $_SERVER['HTTP_X_FORWARDED_FOR'];
			return "$ip:$clientIp:$fwdFor";
		}
	}