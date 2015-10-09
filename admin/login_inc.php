<?php

// Changes to remove need for register globals and avoid warnings
//  -- start


// end -- 
		
	printf ("<form method=\"post\" action=\"\">");

	?>

<table id="login" align="center" width="100%" height="300">
    <tr><td align="center">
        <table cellpadding="6" border="0" style="border:3px solid #72B6E6; border-radius:10px; background:#fff; padding:10px 0; box-shadow:5px 8px 5px #5F6062;">
          <tr>
            <td style="background:#fff; text-align:right;" width="86">Username</td>
            <td style="background:#fff;" width="160"> <input type="Text" name="username"> </td>
         </tr>
          <tr>
            <td style="background:#fff; text-align:right;">Password</td>
            <td style="background:#fff;"> <input type="password" name="password"></td>
          </tr>
          <tr>
            <td style="background:#fff;">&nbsp;</td>
            <td style="background:#fff;"><input type="Submit" name="login" value="Login">
      <p style="background:#fff; margin:8px 0 0;"><a href="./forgotten_password.php">Forgotten my password</a></p></td>
          </tr>
        </table>
    </td></tr>
</table>
            
            
	</form>

	<?php
?>
