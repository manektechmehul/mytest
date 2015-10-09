<?php
/* 
    *  Example Usage
    
    include_once "$base_path/modules/xml/processxml.php"; 
     
    $query="select * from news";
    $url="http://www.local-sttoms.com/modules/xml/xmlfeed.php?customquery=";                                       
    $data = getCrossSiteData($query,$url);
      
    $smarty->assign('news', $data );
    $content_template_file = "../news/templates/list.tpl";
    //echo $content_template_file;
    $smarty->display("file:$content_template_file");
      
*/
$test = false;
if($test){
    $query="select * from news";
    $url="http://www.local-sttoms.com/modules/xml/xmlfeed.php?customquery=";                                       
    $data = getCrossSiteData($query,$url);
    // this data is encode like this utf8_encode(urlencode($value))
    var_dump($data);    
}
      
function getCrossSiteData($query,$url){
                $query=urlencode($query);
                $url = $url . $query;
                $ch = curl_init();
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_URL, $url);    // get the url contents
		$data = curl_exec($ch); // execute curl request
		curl_close($ch);
		$xml = simplexml_load_string($data);
		foreach( $xml->{'item'} as $item){
                        $formatted_item = array();
                        foreach($item as $key => $value) {           
                            $formatted_item[$key] = utf8_decode(urldecode($value));
                        }    
                        $formatted_items[] = $formatted_item;
                } 
                return $formatted_items;	   
}
?>