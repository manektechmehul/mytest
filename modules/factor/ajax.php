<?php

ini_set('display_errors', '1');
include '../../php/databaseconnection.php';
include '../../php/functions_inc.php';

$pagename = strtolower(mysql_real_escape_string(strip_tags($_REQUEST['pagename'])));
$start = strtolower(mysql_real_escape_string(strip_tags($_REQUEST['start'])));
$rec_no = strtolower(mysql_real_escape_string(strip_tags($_REQUEST['rec_no'])));
$search = strtolower(mysql_real_escape_string(strip_tags($_REQUEST['search'])));

$factor_id = db_get_single_value("SELECT id FROM factor WHERE page_name = '{$pagename}'");

$_opl = new opl();
$json = $_opl->getOPLData($factor_id, $start, $rec_no, $search);
echo $json;


class opl
{
    function getOPLData($factor_id, $start, $rec_no, $search)
    {
        $search_sql = '';
        if (trim($search) != '') {
            $search_sql = " and  (  title like '%" . $search . "%'  or   summary like '%" . $search . "%'   ) ";
        }
        $opl_sidebox_sql = "SELECT  * FROM factor_resource opl WHERE factor_id ='{$factor_id}' AND published = '1' " . $search_sql . " order by  order_num   limit " . $start . "," . $rec_no . " ";
        $opl_sidebox_result = mysql_query($opl_sidebox_sql);
        while ($row = mysql_fetch_array($opl_sidebox_result)) {
            $data_arr[] = $row;
        }
        if (isset($data_arr)) {
            return json_encode($data_arr);
        } else {
            if ($start > 0) {
                return '[{"id": "-2", "link_type" : "-1", "title": "No more links"}]';
            } else {
                return '[{"id": "-1", "link_type" : "-1", "title": "No links found for your search"}]';
            }
        }
    }
}

//  end clas
//}
?>