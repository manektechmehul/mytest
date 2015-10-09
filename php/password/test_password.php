<?php

$x = '$2a$10$/6fX1ZzUa2mYYGYnTiSnqu.lUrT/nYVuZTjTbIzA9FMaW1RjEOeR2';

include 'PasswordHelper.php';
var_dump($_POST);
$submit =  (!empty($_POST['submit'])) ? $_POST['submit'] : '';
if ($submit) {
    $pass = new PasswordHelper();
    $hash = $_POST['hash'];
    $result =  $pass->compareToHash($_POST['password'], $hash);
    echo ($result) ? 'Success' : 'Fail';
}
else {
?>
<form method="POST" action="">
    <input type="text" name="password" /><br>
    <input type="text" name="hash" value="<?=$x?>"/><br>
    <input type="submit" name="submit" value="go" />
</form>
<?php
}

