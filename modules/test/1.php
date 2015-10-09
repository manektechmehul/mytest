<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

//echo 'hello encrypt';
$message = 'glen daniel lockhart';
$encrypted =  encrypt($message, 'default1234');

function encrypt($decrypted, $password, $salt='be410fea41df7162a679875ec131cf2c') {
// Build a 256-bit $key which is a SHA256 hash of $salt and $password.
$key = hash('SHA256', $salt . $password, true);
// Build $iv and $iv_base64.  We use a block size of 128 bits (AES compliant) and CBC mode.  (Note: ECB mode is inadequate as IV is not used.)
srand(); $iv = mcrypt_create_iv(mcrypt_get_iv_size(MCRYPT_RIJNDAEL_128, MCRYPT_MODE_CBC), MCRYPT_RAND);
if (strlen($iv_base64 = rtrim(base64_encode($iv), '=')) != 22) return false;
// Encrypt $decrypted and an MD5 of $decrypted using $key.  MD5 is fine to use here because it's just to verify successful decryption.
$encrypted = base64_encode(mcrypt_encrypt(MCRYPT_RIJNDAEL_128, $key, $decrypted . md5($decrypted), MCRYPT_MODE_CBC, $iv));
// We're done!
return $iv_base64 . $encrypted;
} 



// http://stackoverflow.com/questions/13490112/how-to-save-base64-encoded-binary-data-to-zip-using-php
$zipStr = $encrypted; //'UEsDBBQACAAIABprdEEAAAAAAAAAAAAAAAAWAAAAb2JqZWN0cy9Db250YWN0Lm9iamVjdI2SX0vDMBTF3/cpSt9NOhERyTJCl+Gga6VNBZ9GlmWuI21mk0799ka7P50TXR4C9+R3L5ecg4bvpfK2sjaFrgZ+HwS+JyuhF0X1MvBzNr6684e4h8LGWF0m87UU1nMtlRn4K2s39xAazTfALHUtJBC6hNdBcAuDG1hKyxfcch/3PHeQKox9KuSbaetvbdkoFfNSYqEry4WdpXxdzBE86EdUaNWUlcHjPIpmMZlSBPfSOUTCMMljBv7jwiRmJGSATVh0Efj4kMS0fwlJp2QS/Q2mFOQZTTNAognJfmOXhbKyzoTeSEydSx925Yxx/9PRf9Kd/p0q1eKwVphSwuhoNnL315yvt1Pezay5dXHAShqT1PS14QrBo3yKb7lqJGbJiDwj2BbHjeDZSkjxuVR7v72d363Y5gR2goJgN3i49wlQSwcI3kTEMT8BAACvAgAAUEsDBBQACAAIABprdEEAAAAAAAAAAAAAAAALAAAAcGFja2FnZS54bWxNj00LwjAMhu/7FaN3lzpkiHTdQfDkQUS9StbFWbXtWIsf/96xDzSX5CF53ySieJtH/KTWa2dzNk84i8kqV2lb5+x42MyWrJCR2KG6Y01xN219zq4hNCsA77BJ/MW1ihLlDKScZ8AXYChghQGZjOIuRPg05Ie6Z0Om7FbKtbMBVei0fT7v8aZLAVP7J7BoSG61DydNLwE9Dtbw5y3GP2SaJVzARJGA8XwZfQFQSwcIaf0pNKsAAADwAAAAUEsBAhQAFAAIAAgAGmt0Qd5ExDE/AQAArwIAABYAAAAAAAAAAAAAAAAAAAAAAG9iamVjdHMvQ29udGFjdC5vYmplY3RQSwECFAAUAAgACAAaa3RBaf0pNKsAAADwAAAACwAAAAAAAAAAAAAAAACDAQAAcGFja2FnZS54bWxQSwUGAAAAAAIAAgB9AAAAZwIAAAAA';

// Prepare Tmp File for Zip archive
$file = tempnam("tmp", "zip");
$zip = new ZipArchive();
$zip->open($file, ZipArchive::OVERWRITE);

// Add contents
// $zip->addFromString('your_file_name', base64_decode($zipStr));
$zip->addFromString('my_encrypted_zip', base64_decode($zipStr));
// Close and send to users
$zip->close();
header('Content-Type: application/zip');
header('Content-Length: ' . filesize($file));
header('Content-Disposition: attachment; filename="file.zip"');
readfile($file);
unlink($file);

