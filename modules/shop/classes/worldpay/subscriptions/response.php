<?php

include '../../php/databaseconnection.php';
include '../../php/read_config.php';

function writeLog($data)
{
    $details = mysql_escape_string($data);
    $sql = "insert into mytest (details) values ('$details')";
    $result = mysql_query($sql);
}

$data = var_export($_POST, true);

writeLog($data);

/*
 * Check the password and details
 */

$transStatus = isset($_POST['transStatus']) ? $_POST['transStatus'] : 'N';

$valid = ($transStatus == 'Y');
// this call back password needs to be added to the worldpay gateway installation -> Payment Response password



if (($valid) && ($_POST['callbackPW'] == WORLDPAY_CALLBACK_PASSWORD))
{
    /*
     * Update user account with last payment = today
     */
    $userId = 0;
    $activation = $_POST['cartId'];
    $futurePayId = $_POST['futurePayId'];
    
    $memberRow = db_get_single_row("select * from member_user where activation = '$activation'");

    $userId = $memberRow['id'];
     
    if (($userId == 0) && (!empty($futurePayId))) {
        $activation = $_POST['cartId'];
        $userId = db_get_single_value("select id from member_user where futurepayid = '$futurePayId'");
    }
    
    if ($userId)
    {
        $sql = "update member_user set status = 1, freetrial = 0, student_upgrade = 0, lastpayment = '".date('Y-m-d')."',". 
            " futurepayid = '$futurePayId', premium = 1 where id = '$userId'"; // leave birthday as is
    
        writeLog($sql);    
        $result = mysql_query($sql);

        $newUser = ($_POST['MC_action'] == '1');

        if ($newUser)    
        {

            // set a subscription birthday for future ref
            $sql = "update member_user set subscription_birthday = '".date('Y-m-d')."' where id = '$userId'";
            $result = mysql_query($sql);


            $sendSignupEmail = true;
            include 'new_user_actions.php';
        }
	echo '<meta http-equiv="refresh" content="3;URL=\'http://www.prodentalcpd.com/join_success\'">';
    }
    else
    {
        // Not User - check for group
		// first check if it has an activation code - a first time payment
        $memberRow = db_get_single_row("select * from group_member_subscription where activation = '$activation'");
        $groupId = $memberRow['group'];
		// if the group is not found on activation, we assume it is a renewal payment and thus must use the furture pay id to get the group id.
		// 
        if (($groupId == 0) && (!empty($futurePayId))) {
            $activation = $_POST['cartId'];
            $groupId = db_get_single_value("select `group` from group_member_subscription where futurepayid = '$futurePayId'");
        }


        if ($groupId)
        {
          
		  /* Remove old record
            $sql = "update group_member_subscription set status = -2 where `group` = '$groupId' and status = 2";
            writeLog(' NOT EXECUTED >> ' . $sql);            			
			//  $result = mysql_query($sql);

            // Update new record
			// hopefully will fix the failed subscription issues - could not update if a group wasn't status 0 - which I think is awaiting activation
			
            $sql = "update group_member_subscription set status = 2, lastpayment = '".date('Y-m-d')."',".
                "  where `group` = '$groupId' and status = 0";
							
			writeLog($sql);    
            $result = mysql_query($sql);
			*/
			 writeLog('>>  HERE COMES A GROUP JOIN <<');
			
			
				// is this the first payment for the group or a renewal
                // if there is a record than is not a status 0 - then its a renewal
                $count = db_get_single_value("SELECT COUNT(*) AS count FROM group_member_subscription WHERE status!=0 and `group` = $groupId","count");

                if($count > 0){
					
						writeLog('>>  HERE COMES A GROUP JOIN - RENEWAL ! <<');
                        // This is a renewal tidy up the old ones
                        $sql = "update group_member_subscription set status = -2 where `group` = $groupId and status = 2";
                        writeLog($sql);
                        $result = mysql_query($sql);

                        // insert a new record as we haven't already created one - this session has been initaited from worldpay
                        // get the date from the last record in the db for this group
                        $row = db_get_single_row("SELECT * FROM group_member_subscription WHERE `group` = $groupId ORDER BY id DESC LIMIT 1 ");
                        writeLog($sql);
                        $result = mysql_query($sql);

                        // now insert a new record for this update to the subscription - using most of the row data from the last entry
                        $sql = "insert into group_member_subscription (`group`, members, price, status, start, activation, lastpayment,futurepayid, subscription_birthday) values
                (" . $row['group'] . "," . $row['members'] . ", " . $row['price'] . ", 2, CAST(NOW() AS DATE), '" . $activation . "', CAST(NOW() AS DATE), '" . $row['futurepayid'] . "' , '" . $row['subscription_birthday'] . "' )";
												
                        writeLog($sql);
                        $result = mysql_query($sql);						
						
						// if it was a group adding places its a bit more complex
						// first we need to see if there was a previous record for this group that was cancelled ... this is a marker that we are upgrading 
						// this will give us the latest cancellled item date - this is the date to continue from.
					//	$previous_agreement_start_date = db_get_single_value('SELECT lastpayment FROM group_member_subscription WHERE `group` = ' . $row['group'] . ' AND cancelled = 1 ORDER BY 1 DESC LIMIT 1');
						
						// check to see it the last but one item was a cancellation - if so get the date and reset this item
						
						$cancellation_sql = "SELECT `start`,cancelled FROM group_member_subscription WHERE `group` = " . $row['group'] . " ORDER BY 1 DESC LIMIT 1,1";
						
						$previous_cancellation_agreement = db_get_single_row($cancellation_sql);
						
						
						
						if($previous_cancellation_agreement['cancelled'] == '1'){
							
							   $sql = "update group_member_subscription set lastpayment = '" . $previous_cancellation_agreement['start'] .  "' where futurepayid = '" . $row['futurepayid'] . "'";
							   writeLog('ADDING PLACES TO A GROUP > FIXING PAYMENT DATE FROM PREVIOUS CANCELLED ENTRY');
							   writeLog($sql);
                        	   $result = mysql_query($sql);
						}					
						
						
                }else{
                        // This is for a first time account creation
                        // Clean up any old inactive records
                        $sql = "update group_member_subscription set status = -2 where `group` = '$groupId' and status = 2";
                        writeLog($sql);
                        $result = mysql_query($sql);
                        // Update new record
                        // hopefully will fix the failed subscription issues - could not update if a group wasn't status 0 - which I think is awaiting activation
                        $sql = "update group_member_subscription set status = 2, lastpayment = '".date('Y-m-d')."' , futurepayid = '$futurePayId', subscription_birthday ='".date('Y-m-d')."' ".
                                "  where `group` = '$groupId' and status = 0";
                        writeLog($sql);
                        $result = mysql_query($sql);
                }
			

            $mainUrl = 'www.prodentalcpd.com';
            $url = db_get_single_value("select coalesce(url, '$mainUrl') from `group_membership` gm left join `organisation` o on gm.`organisation` = o.id where gm.id = '$groupId'");
            if (empty($url))
                $url = $mainUrl;

            echo '<meta http-equiv="refresh" content="3;URL=\'http://'.$url.'/group_membership?payment_successful\'">';
        }
        
    }
        
}
else {
    writeLog('not valid - ' . $data);
    echo '<meta http-equiv="refresh" content="3;URL=\'http://www.prodentalcpd.com/join_failure\'">';
}