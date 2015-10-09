<?
           
            include('modules/lit_review/be/lit_review.php');
            $lr = new lit_review();            
            $category_name = $lr->getCategoryData($_GET['cat_id']);
            
           
          


            $c = new colours();
            $coloursArr = $c->getProductColoursAsJSArray($product_id); //$c->getAllColoursAsJSArray();
            $product_id = getProductIdfromURL(); //$name_part[2];
            $basket = new basket(true);          
            $product_item = $products->get_product($product_id); 
        
                       
            $title = $product_item['name'];
            // might need to assign title to smarty var here 
            
            if (isset($_POST['submit_basket_add'])){
            	$showmessage = "showFadeInmessage(' Items Added to Bag ');";
            }            
                     
            
            $smarty->assign('hide_pantone_grid', $hide_pantone_grid);
            $smarty->assign('nonColoursArr', $nonColoursArr);
            $smarty->assign('no_colours', $no_colours);
            $smarty->assign('showmessage', $showmessage);
            $smarty->assign('coloursArr', $coloursArr);
            $smarty->assign('page_name', $page_name);
            $smarty->assign('in_basket',  $basket->product_qty($product_id));
            $smarty->assign('stock_available', $product_item['level']  - $basket->product_qty($product_id));
            $content_template_file = "$base_path/modules/$module_path/templates/shop_single_product.tpl";
           
            break;
?>