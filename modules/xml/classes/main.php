<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of main
 *
 * @author glen
 */
class cs_xml_helper {   

    function execute_query($query) {
       // echo $query;        
        $result = mysql_query($query);
        $out ='<?xml version="1.0"?><data>';         
        while ($data = mysql_fetch_assoc($result)) {           
            $out .= "<item>";
            foreach ($data as $key => $value) {
                $out .= "<$key>" . utf8_encode(urlencode($value)) . "</$key>";
            }
            $out .= "</item>";
        }        
        return $out . "</data>";        
    }
        
}

?>
