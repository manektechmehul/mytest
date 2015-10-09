<?php
//session_cache_limiter('must-revalidate');
//session_start();
ini_set('display_errors', '1');
include '../../php/databaseconnection.php';
include '../../php/functions_inc.php';
// include 'classes/categoryChooser.php';

//list($session_member_id) = GetLoginSession();

//if ($session_member_id > 1) {


   // echo $ch->testConnection();
   // Do check for cat id
   // 'pagename=' + pagename + '&start=' + rec_start + '&rec_no=' +  rec_no;

   $pagename = strtolower(mysql_real_escape_string(strip_tags($_REQUEST['pagename'])));
   $start = strtolower(mysql_real_escape_string(strip_tags($_REQUEST['start'])));
   $rec_no = strtolower(mysql_real_escape_string(strip_tags($_REQUEST['rec_no'])));
   $search = strtolower(mysql_real_escape_string(strip_tags($_REQUEST['search'])));



   $_opl = new opl();
   $json = $_opl->getOPLData($pagename, $start, $rec_no, $search);

   // echo '[{"id" : "anatomy_of_mandibular_vital_structures._part_i", "label" : "Anatomy of Mandibular Vital Structures. Part I"},{"id" : "anatomy_of_mandibular_vital_structures._part_ii", "label" : "Anatomy of Mandibular Vital Structures. Part II"},{"id" : "identification_of_the_mandibular_vital_structures", "label" : "Identification of the Mandibular Vital Structures"}]';
   echo $json;
   // getOPLData($pagename, $start, $rec_no);

   class opl {

   function getOPLData($pagename, $start, $rec_no, $search){

   $search_sql = '';

       if(trim($search) != ''){
            $search_sql = " and ( opl.title like '%" . $search .  "%'  or  opl.summary like '%" . $search .  "%' OR  cs.title like '%" . $search .  "%'  or cs.description like '%" . $search .  "%'  ) ";
       }

       
		$opl_sidebox_sql = "SELECT opl.* , cs.title as cs_title, cs.description as cs_desc, cs.id AS cs_id, cs.page_name AS cs_page_name, cs.thumb AS cs_thumb FROM onpagelink opl
        INNER JOIN content ct ON ct.id = opl.`content_id`
        left join case_study cs on opl.`module_id` = cs.`id`
        WHERE ct.page_name ='{$pagename}' AND  opl.published = '1' " . $search_sql .  " order by opl.order_num " .
        " limit " . $start . "," . $rec_no . " " ;
        //// need a order by clause and a hidden condition

        // echo $opl_sidebox_sql;

        $opl_sidebox_result = mysql_query($opl_sidebox_sql);

        while ($row = mysql_fetch_array($opl_sidebox_result)) {

                   /*
                    'id' => $opl_sidebox_row['id'],
                    'link_type' => $opl_sidebox_row['link_type'],
                    'module_id' => $opl_sidebox_row['module_id'],
                    'title' => $opl_sidebox_row['title'],
                    'summary' => $opl_sidebox_row['summary'],
                    'file' => $opl_sidebox_row['file'],
                    'thumb' => $opl_sidebox_row['thumb'],
                    'link' => $opl_sidebox_row['link'],
                    'external_link' => $opl_sidebox_row['external_link'],
                    'video_type' => $opl_sidebox_row['video_type'],
                    'video_id' => $opl_sidebox_row['video_id'],
                    'freetext' => $opl_sidebox_row['freetext'],
                    'content_id' => $opl_sidebox_row['content_id'],
                    'cs_title' => $opl_sidebox_row['cs_title'],
                    'cs_desc' => $opl_sidebox_row['cs_desc'],
                    'cs_id' => $opl_sidebox_row['cs_id'],
                    */
                    // obj.link_type,obj.title, obj.summary, obj.link, obj.thumb

/*
                    $json[] = '{"id" : "' . $row['id'] . '", "title" : "' . $row['title'] . '",' .
                        '"link_type" : "' . $row['link_type'] . '",' .
                        '"link" : "' . $row['link'] . '",' .
                        '"thumb" : "' . $row['thumb'] . '",' .
                        '"cs_id" : "' . $row['cs_id'] . '",' .
                        '"cs_title" : "' . $row['cs_title'] . '"' .
                    //    '"cs_title" : "' . $row['cs_title'] . ',"' .
                        //'"summary" : "' . $row['summary'] . '"' .
                        '},';
*/
                    $data_arr[] = $row;
					
                   //  echo '<br>' . $row->title;
                    // echo $row['title'];

                  //  $json[] = '{"title" : "' . 'id' . '", "label" : "' . 'link' . '"},';

               // );
        }
       // $json_str = implode($json);
     //   return '[' . substr_replace($json_str,"", -1)  . ']';
        if(isset($data_arr)){
             return json_encode($data_arr);
        }else{

            if($start > 0){
             return '[{"id": "-2", "link_type" : "-1", "title": "No more links"}]';
            }else{
             return '[{"id": "-1", "link_type" : "-1", "title": "No links found for your search"}]';
            }

        }

   }

   } //  end clas

   // Ratings::SetItemRating($session_member_id,'doc','document', 'document_rating');
   // echo Ratings::GetItemRating('doc','document', 'document_rating');




//}

?>