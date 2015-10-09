<?php

 class lit_review {
               
                function getCategoryData($cat_id)
                {
                      
                    
                    
                    $sql="SELECT * FROM lit_review WHERE category_id = $cat_id ORDER BY order_num";
                        $result = mysql_query($sql);
                        $currentTitle = '';
                        while ($row = mysql_fetch_array($result))
                        {	
                               $data[] = $row;	                               
                        }
                        return $data;
                } // end function 
                
                
           function  getCategoryName(){
                
            $category_name = "";
            // check if item is numeric  ....
            
            
                // get cat name from url
                if(isset($_GET['cat_id'])){            
                    $category_name = db_get_single_value("SELECT `name` FROM lit_review_categories WHERE id = " . $cat_id, 'cat_name');         	          	          	

                    // get all items for this category

                }
           }
                
                
  } // end class
            
?>
