<?php
include_once "$base_path/modules/$module_path/conf.php";
// bounce off the main class to get the required sideboxes
if(call_user_func(array($moduleObject, 'doSideBoxes'))){
    $moduleObject->doSideBoxes();
}
?>
