<?php
include 'PasswordHelper.php';
var_dump($_POST);
$submit =  (!empty($_POST['submit'])) ? $_POST['submit'] : '';
if ($submit) {
    $pass = new PasswordHelper();
    $password =  $pass->generateHash($_POST['password']);
    echo $password;
}
else {
?>
<form method="POST" action="">
    <input type="text" name="password" />
    <input type="submit" name="submit" value="go" />
</form>
<?php
}
    

