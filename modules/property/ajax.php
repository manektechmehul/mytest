<?php

ini_set('display_errors', '1');
include '../../php/databaseconnection.php';
include '../../php/functions_inc.php';


$bedroom = strtolower(mysql_real_escape_string(strip_tags($_REQUEST['bedroom'])));
$location = strtolower(mysql_real_escape_string(strip_tags($_REQUEST['location'])));
$year = strtolower(mysql_real_escape_string(strip_tags($_REQUEST['year'])));

$_opl = new filtered_porperty();
$result = $_opl->getPropertyData($bedroom, $location, $year);
echo $result;


class filtered_porperty
{
    function getPropertyData($bedroom,$location,$year){        
        $property_str="";
        $sql = "SELECT t.*, cl.title as bedroom FROM property t left join property_bedroom cl on cl.id=t.property_bedroom_id WHERE published = 1 and featured = 1 ";
        if($location != 0){
            $sql .= " AND t.property_location_id = " . $location;
        }  
        if($bedroom != 0){
            $sql .= " AND t.property_bedroom_id = " . $bedroom;
        } 
        if($year != 0){
            $sql .= " AND t.property_year_id = " . $year;
        }
        
        $sql .= " order by order_num desc ";
        $pr_sidebox_result = mysql_query($sql);
        while ($row = mysql_fetch_array($pr_sidebox_result)) {
            $data_arr[] = $row;
            
            /*
            $property_str .= '<div class="propertieslistitem" >';
            $property_str .= '<div class="propertiesstatus status'.$row['property_status_id'].'"></div>';
            $property_str .= '<div class="propertiesleft">';      
            
            $property_str .= '<a href="/{$page_url_parts[0]}/'.$row['page_name'].'"><img src="/php/thumbimage.php?img=/UserFiles/Image/Test_Images/gallery/nature10.jpg&amp;height=180&amp;width=600"></a>';
            $property_str .= '</div>';
            $property_str .= '<div class="propertiesright">';                  
                  $property_str .= '<a href="/{$page_url_parts[0]}/'.$row['page_name'].'">'.$row[address].'</a>';                  
                  $property_str .= '<p>&pound;'.$row['price'].' per week</p>';
                  $property_str .= '<p>'.$row['bedroom'].' bedrooms</p>';
                  $property_str .= '<p class="csbutton"><a href="/{$page_url_parts[0]}/'.$row['page_name'].'" style="border-top-left-radius:0;">View property details</a></p>';
                  $property_str .= '</div>';
                $property_str .= '<div class="clearfix"></div>';
              $property_str .= '</div>';
             
             */
        }
        if (isset($data_arr)) {
            return json_encode($data_arr);
        } else {
                return '[{"id": "-1", "link_type" : "-1", "title": "No result found for your search"}]';
            
        }
    }    
    
//  end clas
//}
}
?>