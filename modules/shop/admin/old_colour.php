<?php
session_cache_limiter('must-revalidate');
session_start();
$base_path = $_SERVER['DOCUMENT_ROOT'];
// Get Session variables
// var_dump($_POST);
$session_user_id = (isset($_SESSION['session_user_id'])) ? $_SESSION['session_user_id'] : "";
$session_user_type_id = (isset($_SESSION['session_user_type_id'])) ? $_SESSION['session_user_type_id'] : "";
$session_access_to_cms = (isset($_SESSION['session_access_to_cms'])) ? $_SESSION['session_access_to_cms'] : "";
include_once ("$base_path/php/databaseconnection.php");
// post, get or file variable declarations
$id = 0;
$admin_tab = "shop";
$second_admin_tab = "colours";
// Get get and post variables 
if (isset($_REQUEST['id']))
    $id = $_REQUEST['id'];
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
        <title>Administration</title>
        <link href="/admin/css/adminstylesheet.css" rel="stylesheet" type="text/css" />
        <script type="text/javascript" src="//ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.min.js"></script><!-- specific jQuery -->
        <script src="/admin/js/template.js" ></script>
        <script src="/js/functions.js" ></script>  
        <script>
            function goToByScroll(id) {
                $('html,body').animate({scrollTop: $("#" + id).offset().top}, 'slow');
            }
            function flashRow(row) {
                $(row).stop().animate({borderColor: "#3737A2"}, 250)
                        .animate({borderColor: "#FFFFFF"}, 250)
                        .animate({borderColor: "#3737A2"}, 250)
                        .animate({borderColor: "#FFFFFF"}, 250);
            }
        </script>
    </head>
    <body>
        <?php
	        unset($_SESSION["session_section_id"]);
        unset($session_section_id);
        $path_prefix = "..";
        include_once ("$base_path/php/read_config.php");
        include_once ("$base_path/admin/cms_functions_inc.php");
        if (!isset($admin_tab))
            $admin_tab = "content_admin";
        $use_admin_header = "1";
        include_once ("$base_path/admin/process_login_inc.php");
        include 'second_level_navigation.php';
        include_once ("$base_path/admin/admin_header_inc.php");
        $uploaddir = "../UserFiles";
        include_once ("$base_path/php/thumbnails_inc.php");
        if (($session_user_id) && ($session_access_to_cms)) {
            if (($session_access_to_cms) || ($session_user_type_id == "1")) {
                // var_dump($_POST);
                $id = $_POST['id'];
                if (isset($_POST['add'])) {
                    if (isset($_POST['active'])) {
                        $active = 1;
                    } else {
                        $active = 0;
                    }
                    $sql = "insert colour_colour_details (`name`, rgb, active) values ";
                    $sql .= "('" . $_POST['colour_name'] . "','" . $_POST['web_colour'] . "'," . $active . "); ";
                    //echo $sql;
                    $result = mysql_query($sql);
                    // get last inserted id ... touse on the seasons and tone
                    $id = db_get_single_value("select last_insert_id() as id", 'id');
                }
                if (isset($_POST['update'])) {
                    ?>
                    <script>
                        jQuery(document).ready(function() {
                            //showFadeInmessage(' Colour Updated ');
                            goToByScroll("form_<?php echo $_REQUEST['id'] ?>");
                            // flashRow('#form_<?php echo $_REQUEST['id'] ?>');
                            showFadeInmessage(' Colour Updated ', 'table_id_<?php echo $_REQUEST['id'] ?>');
                        });
                    </script>
                    <?php
                    if (isset($_POST['active'])) {
                        $active = 1;
                    } else {
                        $active = 0;
                    }

                    $sql = "UPDATE colour_colour_details SET `name` = '" . $_POST['colour_name'] . "',";
                    $sql .= " rgb = '" . $_POST['web_colour'] . "', active=" . $active;
                    $sql .= ' where id = ' . $_POST['id'];
                    //echo $sql;
                    $result = mysql_query($sql);
                }


if (isset($_POST['delete'])) {
                    ?>
                    <script>
                        jQuery(document).ready(function() {
                            //showFadeInmessage(' Colour Updated ');
                            goToByScroll("form_<?php echo $_REQUEST['id'] ?>");
                            // flashRow('#form_<?php echo $_REQUEST['id'] ?>');
                            showFadeInmessage(' Colour Deleted ', 'table_id_<?php echo $_REQUEST['id'] ?>');
                        });
                    </script>
                    <?php
                    if (isset($_POST['active'])) {
                        $active = 1;
                    } else {
                        $active = 0;
                    }

                    $sql = "delete from colour_colour_details  ";
                    $sql .= ' where id = ' . $_POST['id'];
                    //echo $sql;
                    $result = mysql_query($sql);
                }




                printf("<a href='#' onclick='showAddPanel();'><img src='/admin/images/buttons/cmsbutton-Add_a_colour-off.gif'></a>");
                // <a href=\"categories.php\" onMouseOut=\"MM_swapImgRestore()\" onMouseOver=\"MM_swapImage('defaultcategories','','/admin/images/buttons/cmsbutton-Categories-over.gif',0)\"><img style='border:none' src='/admin/images/buttons/cmsbutton-add_colour.gif' name='defaultcategories'></a>
                ?>
                <script src="/js/jquery.js" ></script>
                <script>
                    function showAddPanel() {
                        // alert('ok');
                        $('#add_colour_panel').toggle('slow', function() {
                            // Animation complete.
                        });
                    }
                </script>
                <div id="admin-page-content">
                    <br />
                    <div id="add_colour_panel" style="display: none; border: 1px solid #444;margin-bottom: 30px;">
                      
                        <form id="form_0" method="post"><table width="100%" border="0" cellpadding="2" cellspacing="0">
                                <tr>
                                    <td colspan="4"><input name="id" type="hidden" id="id" value="0" /></td>
                                </tr>
                                <tr>
                                    <td width="7%">Name</td>
                                    <td width="37%"><label for="colour_name">
                                            <input name="colour_name" type="text" size="22" maxlength="22"  id="colour_name" value="" />
                                        </label></td>
                                    <td colspan="2" >&nbsp;</td>
                                </tr>

                                <tr>
                                    <td >Web Colour</td>
                                    <td><input name="web_colour" type="text" id="web_colour" value="" size="6" maxlength="6" /></td>
                                    <td width="42%" ><input type="checkbox" name="active" id="active"  />
                                        <label for="active">Active</label></td>
                                    <td width="14%" align="right"><input type="submit" name="add" id="add" value="Add" /></td>
                                </tr>
                            </table></form>
                    </div>
                    <?php
                    echo "<tr><th>All Colours</th></tr>";
                    // $shipping_sql = 'select * from shop_shipping_names order by quantity';
                    $sql = 'SELECT DISTINCT ccd.id, ccd.`name`, ccd.`rgb`, ccd.`active`  FROM colour_colour_details ccd ORDER BY ccd.`name`';
                    // SELECT id, NAME FROM colour_tone ORDER BY NAME ASC
                    $result = mysql_query($sql);
                    $last_id = 0;
                    while ($row = mysql_fetch_array($result)) {
                    
                        if ($row['id'] != '200'){
                        
                        ?>
                        <form id="form_<?php echo $row['id'] ?>" method="post"><table  id="table_id_<?php echo $row['id'] ?>" width="100%" border="0" cellpadding="2" cellspacing="0">
                                <tr>
                                    <td align="right"><table  id="table_id_<?php echo $row['id'] ?>2" width="100%" border="0" cellpadding="2" cellspacing="0">
                                            <tr>
                                                <td ><input name="id" type="hidden" id="id" value="<?php echo $row['id'] ?>" />
                                                    Name</td>
                                                <td><input name="colour_name" type="text" size="22" maxlength="22"  id="colour_name" value="<?php echo $row['name'] ?>" /></td>
                                                <td><input type="checkbox" name="active" id="active"  <?php
            if ($row['active'] == '1') {
                echo 'checked';
            }
                        ?>  />
                                                    <label for="active2">Active</label></td>
                                            </tr>
                                            <tr>
                                                <td>Web Colour</td>
                                                <td ><input name="web_colour" type="text" id="web_colour" value="<?php echo $row['rgb'] ?>" size="6" maxlength="6" /></td>
                                                <td >Preview</td>
                                                <td ></td>
                                                <td  align="right"><?php if ($row['id'] != '200') { ?>
                                                        <input type="submit" name="update" id="update" value="Update" />
                                                          <input type="submit" name="delete" id="delete" value="delete" />
                                                    <?php } else { ?>
                                                        Reserved non colour, Do not edit.
            <?php } ?></td>
                                            </tr>
                                            <tr>
                                                <td colspan="5"><div id="preview_<?php echo $row['id']; ?>" style="padding: 3px; background: #<?php echo $row['rgb'] ?>; color:<?php
                                                    if ($row['display_text'] == '0') {
                                                        echo '#333';
                                                    } else {
                                                        echo '#fff';
                                                    }
                                                    ?> ;"> <?php echo $row['name'] ?></div></td>
                                            </tr>
                                        </table></td>
                                </tr>
                            </table></form>
                        <hr>
                            <?php
                        }
                        echo '</table>';
                        
                    }// end if not 200
                        
                    } else {
                        printf("You do not have the appropriate type of login account to view this page.
                 <P>Please <a href=\"../logout.php\">logout</a> then login again using an Admin account.");
                    }
                } else {
                    include_once ("$base_path/admin/login_inc.php");
                }
                ?>  
                <!-- CONTENT ENDS HERE -->
                <?php
                mysql_close($link);
                include_once ("$base_path/admin/admin_footer_inc.php");
// include ("$base_path/php/debug_footer.php");
                ?>
