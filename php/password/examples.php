<?php
require('PasswordHelper.php');

$pass = new PasswordHelper();

// Password hashing examples
$hash = $pass->generateHash('myP@ssword');
echo "Hash 'myP@ssword' with default cost factor of 10: " . $hash . "<br>";

$hash = $pass->generateHash('myP@ssword', 12);
echo "Hash 'myP@ssword' with cost factor of 12: " . $hash . "<br>";

$result = $pass->compareToHash('myWrongPassword', $hash);
echo "Compare incorrect password 'myWrongPassword' with hashed password: " . booleanToString($result) . "<br>";

$result = $pass->compareToHash('myP@ssword', $hash);
echo "Compare correct password 'myP@ssword' with hashed password: " . booleanToString($result) . "<br><br>";


// Password generation examples
echo "Random passwords of different lengths:<br>";
echo "8 (Default): " . $pass->generateRandomPassword() . '<br>';
echo "8 (Default): " . $pass->generateRandomPassword() . '<br>';
echo "10: " . $pass->generateRandomPassword(10) . '<br>';
echo "12: " . $pass->generateRandomPassword(12) . '<br>';
echo "6: " . $pass->generateRandomPassword(6) . '<br><br>';

// Password complexity validation
echo 'Validate password complexity for various passwords:<br>';
$passwords = array('test','Test12#','Test123$','myPassword!');
foreach($passwords as $password) {
	echo "'{$password}': " . booleanToString($pass->checkPasswordComplexity($password)) . "<br>";
}

function booleanToString($bool) {
	return ($bool) ? 'true' : 'false';
}