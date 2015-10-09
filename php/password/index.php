<!DOCTYPE html>
<html>
<head>
	<title>PasswordHelper</title>
	<style>
		body {
			font-family: Arial, Verdana, sans-serif;
			font-size: 14px;
		}

		div.exampleCode, div.exampleResult {
			max-height: 300px;
			overflow: auto;
			border: 1px solid #000;
			margin-bottom: 30px;
		}

		div.exampleResult {
			background: #FAFFCF;
			padding: 3px;
			font-family: 'Courier New', Courier, monospac;
			font-size: 12px;
		}

		div.section {
			margin-left: 50px;
			width: 800px;
		}

		blockquote {
			border-left: 6px solid #CCC;
			padding: 10px;
			background: #EFEFEF;
		}

		div.download {
			border: 2px solid #4C9F31;
			background: #D8EFD1;
			color: #4C9F31;
			font-weight: bold;
			font-size: 16px;
			margin: 20px auto 20px auto;
			width: 250px;
			padding: 5px;
			text-align: center;
		}
		div.download a {
			color: #4C9F31;
		}
	</style>
</head>
<body>
	<h1>PasswordHelper: PHP password utility library</h1>

	<h3>Overview</h3>
	<div class="section">
	PasswordHelper is a lightweight <b>BSD licensed</b> PHP class that has a number of password related utility functions that make it easy to:
	<ul>
		<li><b>Securely store passwords</b> by hashing them with the adaptive <b>Blowfish/bcrypt</b> algorithm with random salt values</li>
		<li>Compare user submitted passwords with stored password hashes</li>
		<li>Generate <b>random passwords</b></li>
		<li>Validate <b>password complexity</b> for length and matches to a configurable set of regular expressions</li>
	</ul>
	The library does nothing too complex - it just makes it easy to do common things to help create more secure PHP applications utilizing existing PHP functions through a simpler API.  Most of the <a href="http://www.php.net/manual/en/function.crypt.php">existing APIs</a> seem a bit...cryptic (ha ha, right?) and there are so many options and algorithms from which to choose.  Choosing the 'wrong' algorithm for password hashing can lead to big problems, like the ability for hackers to <a href="http://www.duosecurity.com/blog/entry/brief_analysis_of_the_gawker_password_dump">brute force hundreds of thousands of passwords from your database in an hour</a>.<br><br>
	
	In <a href="http://codahale.com/how-to-safely-store-a-password/">How to Safely Store a Password</a>, Coda Hale writes:
	<blockquote>
		How much slower is bcrypt than, say, MD5? Depends on the work factor. Using a work factor of 12, bcrypt hashes the password yaaa in about 0.3 seconds on my laptop. MD5, on the other hand, takes less than a microsecond.<br><br>

		So we're talking about <b>5 or so orders of magnitude</b>. Instead of cracking a password every 40 seconds, I'd be cracking them every <b>12 years or so</b>. Your passwords might not need that kind of security and you might need a faster comparison algorithm, but bcrypt allows you to choose your balance of speed and security. Use it.
	</blockquote>

	PasswordHelper for PHP uses <a href="http://en.wikipedia.org/wiki/Crypt_(Unix)#Blowfish-based_scheme">Blowfish/bcrypt</a> to make it easy for you to more securely store passwords for your users and throws in a couple extra helper functions for generating random passwords and checking the complexity of user passwords.<br><br>
	
	<b>PasswordHelper requires either PHP 5.3 or greater or an underlying operating system that supports the Blowfish/bcrypt algorithm.</b><br><br>  

	I hope you find it helpful!
	
	<div class="download">
		<img src="disk.png">
		<a href="passwordHelper_1.0.zip">Download PasswordHelper</a>
	</div>

	</div>
		  
	<h3>Available methods</h3>
	<div class="section">
	<h4>Password Hashing and Comparison</h4>
	<ul>
		<li><b>generateHash($password,$cost=10)</b>
		This method uses PHP's <a href="http://www.php.net/manual/en/function.crypt.php">crypt function</a> and specifically CRYPT_BLOWFISH to generate bcyrpt hashed passwords with a random salt and a user-configurable cost.  Passwords hashed in this way are significantly more secure than other hashing approaches and mean that even if an evil-doer gains access to your database and can access all of the hashed passwords, brute force attempts to find the underlying passwords will be prohibitively time consuming and expensive.  The greater the value of cost (valid values are 4-31), the more time it will take to validate passwords, and the more time it will take to brute force passwords.  The function creates a string that includes information about the algorithm, cost, salt, and the hashed salt+password, so no additional fields need to be stored in your database except for the password hash itself, a 60 character string.</li>
	
		<li><b>compareToHash($password,$hash)</b>
		This method takes a user supplied password from a login attempt and compares it to the hash created from the generateHash function, returning true if the password is a match and false if it is not.</li>
	</ul>

	<h4>Password Generation</h4>
	<ul>
		<li><b>generateRandomPassword($length=8, $validChars='ABCDEFGHJKMNPQRSTUVWXYZabcdefghjkmnpqrstuvwxyz23456789!@#$%^&amp;*')</b>
		This method can be used to generate a random password that can be used to give users on their initial login to an application or on password reset requests.  By default this generates an 8 character password using most of the characters in the uppercase, lowercase, integer, and special characters ranges...except for ambigous characters like I, L, O, and zero.  You can change the alphabet from which the method pulls characters by passing a list of valid characters as the second parameter.</li>
	</ul>

	<h4>Password Complexity Validation</h4>
	<ul>
		<li><b>checkPasswordComplexity($password, $minLength=8, $maxLength=50, $patterns=array('/[a-z]/', '/[A-Z]/', '/[0-9]/', '/[!@#$%\^\&amp;\(\)\+=]/'), $minPatternMatches=3)</b>
		This method is used for validating the complexity of a password by comparing the password's length against a configurable range and comparing the characters in the password against a configurable list of regular expressions.  You can choose how many of the regular expressions must match for the password to be valid.  By default, the method considers passwords between 8-50 characters that contain 3 of the following 4 character types: lowercase a-z, uppercase A-Z, numbers 0-9, and special characters (!@#$%^&amp;()+=).</li>
	</ul>
	</div>

	<h3>Example Code</h3>
	<div class="section">
	<div class="exampleCode">
	<?php
	highlight_file('examples.php');
	?>
	</div>

	...and an example of the resulting output:<br><br>

	<div class="exampleResult">
		Hash 'myP@ssword' with default cost factor of 10: $2a$10$9.oGr9JUazV2PL9OgfDNQ.xrchVDP1whIzxZbhDV8WFXa3Bm.ixIq<br>Hash 'myP@ssword' with cost factor of 12: $2a$12$CSuP/F38LU.aZ3TptOV/tunz8Dt2q.d3iXsvuwbXQG.PSNyTB1poq<br>Compare incorrect password 'myWrongPassword' with hashed password: false<br>Compare correct password 'myP@ssword' with hashed password: true<br><br>Random passwords of different lengths:<br>8 (Default): YtStdf^d<br>8 (Default): Wyd6JA^b<br>10: $Kw5CGKe@z<br>12: NDTumv5v6cDm<br>6: 3xQH#a<br><br>Validate password complexity for various passwords:<br>'test': false<br>'Test12#': false<br>'Test123$': true<br>'myPassword!': true<br>
	</div>
	</div>
</body>
</html>