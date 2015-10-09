<?php
// This page will eventually be processed in the process_login_inc.php file
// Changes to remove need for register globals and avoid warnings
// -- start --
$PHP_SELF = $page;
    $form_link = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
// -- end   --
// printf ("<form method=\"post\" action=\"%s\">", $PHP_SELF);
?>

<div class="col-xs-12 col-sm-4 col-md-4 col-md-offset-4 col-sm-offset-4">

    <div class="row"> <span class="formtitle">Login</span>
        <form class="loginform-area" id="login-form" action="<?php echo $form_link ?>" method="post">
            <div class="form-group">
                <input type="text" class="form-control username" id="email" placeholder="email" name="email">
            </div>
            <div class="form-group">
                <input type="password" class="form-control password" id="password" placeholder="password" name="password">
            </div>
            <div class="checkbox">
                <label>
                    <input type="checkbox" name="stayLoggedIn" value="1" checked="checked">
                    <span>Keep me logged in</span> </label>
            </div>
            <button type="submit" class="btn btn-default" value="login" title="Log in to main site" name="login" id="login">Log in to main site</button>
            <div class="successmsg">Thank you for login</div>
            <p><a href="/forgot_password">Reset your password</a><br>
            <a href="/membership-applications" title="New membership applications">New membership applications</a></p>
        </form>
    </div>
</div>

<?
    /*** old version
    // printf ("<form method=\"post\" action=\"%s\">", $PHP_SELF);
     * s>

    <style>
    .sidenav { display:none; }
    @media screen and (min-width:768px) { .sidenav { display:block; } }
    </style>

    <h3>Account Login</h3>
    <table id="form-table">
    <tr>
    <td width="12%" align="right">Email</td>
    <td><input type="Text" name="email"></td>
    </tr>
    <tr>
    <td align="right">Password</td>
    <td><input type="password" name="password"></td>
    </tr>
    <tr>
    <td></td>
    <td>Remember Me<input class="membercheck" type="checkbox" name="stayLoggedIn" value="1" checked="checked"></td>
    </tr>


    <tr>
    <td>&nbsp;</td>
    <td><input type="Submit" name="login" value="login"></td>
    </tr>


    <tr>
    <td>&nbsp;</td>
    <td><p class="textgoright"><a href="/forgot_password">Reset your password</a></p></td>
    </tr>
    </table>
    </form>


     */?>