<?php
/**
 * Created by PhpStorm.
 * User: glen
 * Date: 23/06/2015
 * Time: 10:06
 */

class mailgun {


	var $api_key;
	var $api_url;
	var $from;

	function __construct(){
		$this->api_key = "key-0dbf90034de4fcd3dc987971600af6f8";
		$this->api_url = "https://api.mailgun.net/v3/sandbox5e8310e4886a4542b99bdc97410721ff.mailgun.org/messages";
		$this->from    = "<mailgun@sandbox5e8310e4886a4542b99bdc97410721ff.mailgun.org>";
	}


	function send($to, $from_text, $replyto, $subject, $content){

		/* testing setting */
		if(false) {
			$to      = "glen@codelab.software";
			$from    = "Excited User <mailgun@sandbox5e8310e4886a4542b99bdc97410721ff.mailgun.org>";
			$replyto = "&lt;info@codelab.software.com&gt;";
			$subject = "My first mailgun adventure";
			$content = "This is the email content";
		}

		$message = array();
		$message['from'] = $from_text . $this->from;
		$message['to'] = $to;
		$message['h:Reply-To'] = $replyto;
		$message['subject'] = $subject;
		$message['html'] = $content;
		//var_dump($message);

		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $this->api_url);
		curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
		curl_setopt($ch, CURLOPT_USERPWD, "api:{$this->api_key}");
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