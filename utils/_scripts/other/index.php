<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>The Genesis Device</title>
</head>

<body>


<h2 style="color: red;"> The Genesis Device will not only create a new site but will also totally reset an exsisting site, so please go carefully  - also database copy is unavailable</h2>


<form action="" method="post" enctype="multipart/form-data" name="form1" id="form1">
  <p>
    <label for="sitenameurl">Site URL</label>
    <input type="text" name="sitenameurl" id="sitenameurl" value="highflyersbusinesscoaching.com" />
    <br />
    <label for="owner">Owner</label>
    <input type="text" name="owner" id="owner" value="highflyers"  />
    <label for="dd_password"><br />
    Database name(this is case sensitive)</label>
    <input type="text" name="dbname" id="dbname" value="HighFlyers" />
    <br />
     <label for="dbpassword">dbpassword</label>
     <input type="text" name="dbpassword" id="dbpassword" value="xx" />    
  </p>
  <p>
  <b> submit button currently deactivated for safety </b>
    <input type="submit" name="button" id="button" value="Submit"  isabled="disabled" />
  </p>
</form>
<?


if($_POST['sitenameurl'] != '' &&
$_POST['owner'] != '' &&
$_POST['dbname'] != '' &&
$_POST['dbpassword'] != '' ){

	//$sitenameurl = "highflyersbusinesscoaching.com";
	//$ownername = "highflyers";
	$sitenameurl = $_POST['sitenameurl'];
	$ownername = $_POST['owner'];
	$dbname = $_POST['dbname'];
	$dbpassword = $_POST['dbpassword'];
	
	$script = "/home/httpd/vhosts/codebase.co.uk/httpdocs/_scripts/a.sh" . ' ' . $sitenameurl . ' ' . $ownername . ' ' . $dbname . ' ' . $dbpassword;	
	echo '<br>$ ' . $script;	
	echo '<textarea cols="200" style="height: 500px; border: 2px solid #ccc;">';		
 	echo shell_exec($script);
	echo '</textarea>';

}else{
	echo 'Please complete the form carefully';	
}
?>

</body>
</html>