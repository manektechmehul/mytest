<?php
error_reporting(0);
// check if this has been included before (like include_once without the code changes)
if (!isset($link)) {
if ($_SERVER['SERVER_ADDR'] == '192.168.100.152' || $_SERVER['SERVER_ADDR'] == '172.24.16.104') {
        $host = "localhost";
        $user = "TrinityCourt";
        $pass = "Ndi45Hwq5hVf";
    } else {
        $host = "localhost";
        $user = "root";
        $pass = "";
    }
    
    $db = "TrinityCourt";
    
    

    $link = mysql_connect($host, $user, $pass);
    if (!$link) {
        $dbError = "Couldn't connect to MySQL server!";
        echo "<h3>Couldn't connect to MySQL server</h3><hr>";
        echo "<br>Database Name is `" . $db . "`";
        echo "<br>Please check your settings in the databaseconnection file.</hr>";
        die();
        return false;
    }
    if (!mysql_select_db($db, $link)) {
        $dbError = mysql_error();
        return false;
    }
}

/**
 * Run a query and return a single value
 * @param string $query - the query string
 * @param string $field - field to be returned
 * @return string
 */
function db_get_single_value($query, $field = '') {
    $result = mysql_query($query);
    if (!$result)
        return '';
    else {
        $row = mysql_fetch_array($result);
        if ($field == '')
            return $row[0];
        else
            return $row[$field];
    }
}

/**
 * Run a query and return a single row
 * @param string $query - the query string
 * @return array
 */
function db_get_single_row($query) {
    $result = mysql_query($query);
    if (!$result)
        return '';
    else {
        $row = mysql_fetch_array($result);
        return $row;
    }
}

function db_get_rows($query) {
    $rows = array();
    $result = mysql_query($query);
    if (!$result)
        return '';
    else {
        while ($row = mysql_fetch_assoc($result))
            $rows[] = $row;
        return $rows;
    }
}

function db_get_mapped_rows($query) {
    $result = mysql_query($query);
    $data = array();
    if (mysql_num_rows($result) > 0) {
        while ($row = mysql_fetch_row($result)) {
            $data[$row[0]] = $row[1];
        }
    }
    return $data;
}

function db_update($sql){
      $result = mysql_query($sql);
      return $result;
}

?>
