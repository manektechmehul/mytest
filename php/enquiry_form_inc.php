<?php

// post, get or file variable declarations
$Submit = "";
$first_name = "";
$surname = "";
$email = "";
$code = "";
$address_line1 = "";
$address_line2 = "";
$address_line3 = "";
$address_line4 = "";
$postcode = "";
$telephone = "";
$enquiry = "";

// Get get and post variables 
if (isset($_REQUEST['Submit'])) $Submit = $_REQUEST['Submit'];
if (isset($_REQUEST['first_name'])) $first_name  = $_REQUEST['first_name'];
if (isset($_REQUEST['surname'])) $surname  = $_REQUEST['surname'];
if (isset($_REQUEST['email'])) $email  = $_REQUEST['email'];
if (isset($_REQUEST['address_line1'])) $address_line1  = $_REQUEST['address_line1'];
if (isset($_REQUEST['address_line2'])) $address_line2  = $_REQUEST['address_line2'];
if (isset($_REQUEST['address_line3'])) $address_line3  = $_REQUEST['address_line3'];
if (isset($_REQUEST['address_line4'])) $address_line4  = $_REQUEST['address_line4'];
if (isset($_REQUEST['postcode'])) $postcode  = $_REQUEST['postcode'];
if (isset($_REQUEST['telephone'])) $telephone  = $_REQUEST['telephone'];
if (isset($_REQUEST['enquiry'])) $enquiry  = $_REQUEST['enquiry'];
if (isset($_REQUEST['code'])) $code  = $_REQUEST['code'];

include("./php/read_config.php");

if ($Submit) {
  include("./php/securimage.php");

  if (defined('SITE_HAS_CAPTCHA') && (SITE_HAS_CAPTCHA == 1)) {
    $img = new securimage();
    $valid = $img->check($code);
    $captcha = true;
  }
  else {
    $captcha = false;
  }
  


  if (($first_name == "") || ($surname == "") || ($email == "") || ($captcha && ($code == ""))) {	
	
  	echo "<b>Error: Missing Information</b>";
	    echo "<p>The following fields were left blank in your enquiry form:";
	      echo "<ul>";
	       if (!$first_name) {echo "<li>First Name</li>";}
	       if (!$surname) {echo "<li>Surname</li>";}
	       if (!$email) {echo "<li>Email Address</li>";}
	       if ($captcha && !$code) {echo "<li>Security Code</li>";}
	       
	      echo "</ul>";
	      echo "<p>Your enquiry has not yet been submitted because the above information is missing.<p>";
	      printf ("<p>Please click on the <b>Back</b> button to enter the details.");
	      printf ("<p><INPUT TYPE=\"button\" VALUE=\"<< Back\" onClick=\"history.go(-1)\">");
	      } 

	else if (!ereg('^[-!#$%&\'*+\\./0-9=?A-Z^_`a-z{|}~]+'.
	    '@'.
	    '[-!#$%&\'*+\\/0-9=?A-Z^_`a-z{|}~]+\.'.
	    '[-!#$%&\'*+\\./0-9=?A-Z^_`a-z{|}~]+$', $email)) {

	      echo "<b>Error: Invalid Email Address</b>";
	
	      echo "<p>Your enquiry has not yet been submitted because you have entered an invalid email address.";
	      printf ("<p>Please click on the <b>Back</b> button to re-enter your email address.");
	      printf ("<p><INPUT TYPE=\"button\" VALUE=\"<< Back\" onClick=\"history.go(-1)\">");
	      } 

  else if ($captcha && ($valid == FALSE)) {		
	      echo "<b>Error: Invalid Code</b>";
	
	      echo "<p>Your enquiry has not yet been submitted because you have entered an invalid security code.";
	      printf ("<p>Please click on the <b>Back</b> button to re-enter the security code.");
	      printf ("<p><INPUT TYPE=\"button\" VALUE=\"<< Back\" onClick=\"history.go(-1)\">");
  }
	else {		
		// send email to site contact address - informing them of new registration
  		$to_email_address = SITE_CONTACT_EMAIL;
		$subject = SITE_NAME . " enquiry from " . SITE_ADDRESS;
	  	$from_email_address = SITE_CONTACT_EMAIL;

	  	$message ="The following enquiry has been submitted from " . SITE_ADDRESS . ".\n\n";
	  	$message .="First Name: " . $first_name ."\n";
	  	$message .="Surname: " . $surname ."\n\n";
	  	$message .="Email Address: " . $email ."\n\n";	

	  	$message .="Address line1: " . $address_line1 ."\n";	
	  	$message .="Address line2: " . $address_line2 ."\n";	
	  	$message .="Address line3: " . $address_line3 ."\n";	
	  	$message .="Address line4: " . $address_line4 ."\n";	

	  	$message .="Postcode: " . $postcode ."\n\n";	
	  	$message .="Telephone: " . $telephone ."\n\n";	

		$enquiry = str_replace("\'", "'", $enquiry);
	  	$message .="Enquiry: " . $enquiry ."\n\n";	

	  

		mail($to_email_address,$subject,$message,'FROM: '.$from_email_address); 

		echo "<b>Enquiry Successfully Submitted</b>";

		echo "<p>Thank you for your enquiry.";	  	

	  	}

	}

	  else 
	  {
		printf ("<h1>Enquiry</h1>");
		?>

		<form method="post" action="<?php echo $PHP_SELF?>">
		    <input type=hidden name=section_id value="<?php echo $section_id ?>">
                    
                    <table width=100% border="0" cellspacing="0" cellpadding="3" >
                      <tr valign="middle"> 
                        <td width="22%"> 
                          <div align="right">First Name</div>
                        </td>
                        <td> 
                          <input type="text" name="first_name" maxlength="25">
                          * </td>
                      </tr>


                      <tr valign="middle"> 
                        <td width="22%"> 
                          <div align="right">Surname</div>
                        </td>
                        <td> 
                          <input type="text" name="surname" maxlength="40">
                          * </td>
                      </tr>


                      <tr valign="middle"> 
                        <td width="22%"> 
                          <div align="right">Email Address</div>
                        </td>
                        <td> 
                          <input type="text" name="email" maxlength="255">
                          * </td>
                      </tr>
                     

                      <tr valign="top"> 
                        <td width="22%"> 
                          <div align="right">Address</div>
                        </td>
                        <td> 
                          <input type="text" name="address_line1" maxlength="255">
			  <br>
                          <input type="text" name="address_line2" maxlength="255">
			  <br>
                          <input type="text" name="address_line3" maxlength="255">
			  <br>
                          <input type="text" name="address_line4" maxlength="255">
                          </td>
                      </tr>

		   

                      <tr valign="middle"> 
                        <td width="22%"> 
                          <div align="right">Postcode</div>
                        </td>
                        <td> 
                          <input type="text" name="postcode" maxlength="255">
                          </td>
                      </tr>



                      <tr valign="middle"> 
                        <td width="22%"> 
                          <div align="right">Telephone</div>
                        </td>
                        <td> 
                          <input type="text" name="telephone" maxlength="255">
                          </td>
                      </tr>

                      <tr valign="middle"> 
                        <td width="22%"> 
                          <div align="right">Enquiry</div>
                        </td>
                        <td> 
			  <textarea name="enquiry" cols="30" rows="5" id="enquiry"></textarea>
                          </td>
                      </tr>

                      <?php 
                        if (defined('SITE_HAS_CAPTCHA') && (SITE_HAS_CAPTCHA == 1)) {
                      ?>
                      <tr valign="middle"> 
                        <td width="22%"> 
                          <div align="right">Security Code</div>
                        </td>
                        <td> 
                        <img src="/php/securimage_show.php"><br />
                        <input type="text" name="code" />* 
                      </tr>
                      <?php 
                        }
                      ?>

                      <tr valign="middle"> 
                        <td width="22%">&nbsp;</td>
                        <td><i>Please note that an * indicates a mandatory field</i> 
                        </td>
                      </tr>
                      <tr valign="middle"> 
                        <td width="22%">&nbsp;</td>
                        <td> 
                          <input type="submit" name="Submit" value="Submit">
                        </td>
                      </tr>
                    </table>
                  </form>

		 <p>Information provided to us will be held in compliance with the Data Protection Act, 
			and will only be used to contact you with information you have requested. We do not 
			share information with any third parties.


		<?php
		
		}



?>
