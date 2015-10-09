<?php

include '../../../admin/classes/template.php';
include_once $base_path . '/modules/shop/classes/colours.php';
include_once $base_path . '/modules/shop/classes/bulk_discount.php';
/*
  ini_set('display_errors', '1');
  ini_set('html_errors', 'on');
  ini_set('error_reporting', '-1');
 */

// this var doesn't seem to working


class products extends template {

    function products() {
        // hidious way to dump in a css file link 
        echo '<link href="../colour_tools/just_grid.css" rel="stylesheet" type="text/css"/>';
        $this->template();

        $this->javascript_file = '/modules/shop/js/productadmin.js';
        //$this->onload = 'apply_fields_logic()';
        $this->table = 'shop_product';
        $this->group_name = 'Products';
        $this->single_name = 'Product';
        $this->singular = 'a';
        $this->hideable = true;
        $this->order_clause = 'order_num';
        $this->ordered = true;
        $this->has_page_name = true;
        
        
        $this->debug_log = false;
        $this->php_debug = false;

        $this->fields['page_name'] = array('name' => 'Page Name', 'formtype' => 'pagename', 'list' => false);
        $this->fields['stock_code'] = array('name' => 'Stock&nbsp;Code&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;', 'formtype' => 'shorttext', 'required' => true, 'list' => true, 'primary' => true, 'custom_get_function' => 'get_stock_code_input');
        $this->fields['name'] = array('name' => 'Name', 'formtype' => 'text', 'list' => true, 'required' => true);
        
        /***  When using the image preserve formtype, you must add the supporting fields as shown here with databse fields to match
        $this->fields['thumb'] = array('name' => 'Image 1', 'formtype' => 'image_preserve', 'list' => false, 'required' => false, 'size' => 2);
        $this->fields['thumb_preserve_large'] = array('name' => 'thumb_preserve_large', 'formtype' => 'image_preserve_subimage');
        $this->fields['thumb_preserve_small'] = array('name' => 'thumb_preserve_small', 'formtype' => 'image_preserve_subimage');
        $this->fields['thumb_preserve_toggle'] = array('name' => 'thumb_preserve_toggle', 'formtype' => 'image_preserve_toggle');
                               
        $this->fields['thumb2'] = array('name' => 'Image 2', 'formtype' => 'image_preserve', 'list' => false, 'required' => false, 'size' => 2);
        $this->fields['thumb2_preserve_large'] = array('name' => 'thumb2_preserve_large', 'formtype' => 'image_preserve_subimage');
        $this->fields['thumb2_preserve_small'] = array('name' => 'thumb2_preserve_small', 'formtype' => 'image_preserve_subimage');
        $this->fields['thumb2_preserve_toggle'] = array('name' => 'thumb2_preserve_toggle', 'formtype' => 'image_preserve_toggle');
                
        $this->fields['thumb3'] = array('name' => 'Image 3', 'formtype' => 'image_preserve', 'list' => false, 'required' => false, 'size' => 2);
        $this->fields['thumb3_preserve_large'] = array('name' => 'thumb3_preserve_large', 'formtype' => 'image_preserve_subimage');
        $this->fields['thumb3_preserve_small'] = array('name' => 'thumb3_preserve_small', 'formtype' => 'image_preserve_subimage');
        $this->fields['thumb3_preserve_toggle'] = array('name' => 'thumb3_preserve_toggle', 'formtype' => 'image_preserve_toggle');
        
            $this->fields['thumb3'] = array('name' => 'Image 3', 'formtype' => 'image_preserve', 'list' => false, 'required' => false, 'size' => 2);
        $this->fields['thumb3_preserve_large'] = array('name' => 'thumb3_preserve_large', 'formtype' => 'image_preserve_subimage');
        $this->fields['thumb3_preserve_small'] = array('name' => 'thumb3_preserve_small', 'formtype' => 'image_preserve_subimage');
        $this->fields['thumb3_preserve_toggle'] = array('name' => 'thumb3_preserve_toggle', 'formtype' => 'image_preserve_toggle');****/


        $this->fields['thumb'] = array('name' => 'Image 1', 'formtype' => 'image', 'list' => false, 'required' => false, 'size' => 2);
        $this->fields['thumb2'] = array('name' => 'Image 2', 'formtype' => 'image', 'list' => false, 'required' => false, 'size' => 2);
        $this->fields['thumb3'] = array('name' => 'Image 3', 'formtype' => 'image', 'list' => false, 'required' => false, 'size' => 2);
        $this->fields['thumb4'] = array('name' => 'Image 4', 'formtype' => 'image', 'list' => false, 'required' => false, 'size' => 2);
        
        $this->fields['thumb4'] = array('name' => 'Image 4', 'formtype' => 'image', 'list' => false, 'required' => false, 'size' => 2);
        // TODO: figure how to get this out of the update statement
        $this->fields['primary_category_id'] = array('name' => 'Main Category', 'formtype' => 'lookup', 'function' => 'categorylookup', 'required' => false); //
        $this->fields['category'] = array('name' => 'Categories', 'formtype' => 'checklink', 'list' => false, 'required' => false,
            'not_field' => true, 'link' => 'category', 'customfunction' => 'categoryChecklist');


        $this->fields['price'] = array('name' => 'Public Price (inc Vat)', 'formtype' => 'shorttext', 'required' => true, 'onchange' => 'show_price()');


        //$this->fields['featured'] = array('name' => 'Shop Featured', 'formtype' => 'checkbox');
        //$this->fields['featured_homepage'] = array('name' => 'Homepage Feature Large', 'formtype' => 'checkbox');
        //$this->fields['featured_homepage2'] = array('name' => 'Homepage Featured small (left)', 'formtype' => 'checkbox');
        //$this->fields['featured_homepage3'] = array('name' => 'Homepage Featured small (right)', 'formtype' => 'checkbox');
        // $this->fields['stock'] = array('name' => 'Stock', 'formtype' => 'shorttext', 'required' => true, );



     //  $this->fields['free_delivery'] = array('name' => 'Free Delivery', 'formtype' => 'checkbox');
        // $this->fields['summary'] = array('name' => 'Summary', 'formtype' => 'textarea', 'rows' => 4, 'cols' => 60, 'required' => true);
        $this->fields['description'] = array('name' => 'Main Content', 'formtype' => 'fckhtml', 'list' => false, 'required' => false);
      //  $this->fields['clear_custom_seo'] = array('name' => 'Clear Custom SEO<br> (Check this box then submit)', 'formtype' => 'checkbox', 'not_field' => true);
      //  $this->fields['description_seo'] = array('name' => 'Description<br>(for SEO)', 'formtype' => 'lookup', 'not_field' => true, 'function' => 'getSEODescription');

        $this->links = array('category' => array('link_table' => 'shop_product_category', 'table' => 'shop_category', 'name' => 'name'));

        $this->buttons = array(
            'edit' => array('text' => 'edit', 'type' => 'standard_edit'),
            'hide' => array('text' => 'hide', 'type' => 'standard_hide'),
            //'featured' => array('text' => 'hide', 'type' => 'function', 'function' => 'set_featured'),
            'shop_featured' => array('text' => 'hide', 'type' => 'function', 'function' => 'set_shop_featured'),
            'delete' => array('text' => 'delete', 'type' => 'standard_delete'),
            'move' => array('text' => 'move', 'type' => 'standard_move')
        );
    }

    // featured home
    function set_featured($id) {
        $featured = db_get_single_value('select featured from ' . $this->table . ' where id = ' . $id, 'featured');
        $hide_show = ($featured) ? 'hide' : 'show';
        $featured = ($featured) ? 'featured' : 'not featured';
        $href = sprintf("%s?%s%s_item=yes&id=%s", $this->page_self, $parent_part, $hide_show, $content_row["id"]);
        $this->cms_admin_button($href, $hide_show . 'content', $featured, "onclick='return set_featured(this, \"$featured\",\"$id\");'");
    }

    // -- need to fix this --
    function set_shop_featured($id) {
        $featured = db_get_single_value('select shop_featured from ' . $this->table . ' where id = ' . $id, 'shop_featured');
        $hide_show = ($featured) ? 'hide' : 'show';
        $featured = ($featured) ? 'shop featured' : 'shop not featured';
        $href = sprintf("%s?%s%s_item=yes&id=%s", $this->page_self, $parent_part, $hide_show, $content_row["id"]);
        $this->cms_admin_button($href, $hide_show . 'content', $featured, "onclick='return set_shop_featured(this, \"$featured\",\"$id\");'");
    }

    function onload() {
        // echo 'constants sre ' .  USE_TRADE_PRODUCTS . USE_COLOURS . USE_GENDER . USE_SIZE ;

        if (SHOP_USE_TRADE_PRODUCTS) {
            // trade price
            $this->fields['trade_price'] = array('name' => 'Trade Price', 'formtype' => 'shorttext', 'required' => false, 'onchange' => 'show_price()');
            // is trade only product
            $this->fields['trade_only'] = array('name' => 'Trade Only', 'formtype' => 'checkbox'); // , 'onchange' => 'change_online()'
        }
        if (SHOP_USE_COLOURS) {
            // need to create a chekbox group ??
            $this->fields['colours'] = array('name' => 'Colours', 'formtype' => 'lookup', 'required' => false, 'function' => 'getColoursForProduct');
          //  $this->fields['display_as_non_colour'] = array('name' => 'Show product colours but buy as a non colour item', 'formtype' => 'checkbox');
        }
        if (SHOP_USE_BULK_BUY) {
            $this->fields['bulk_discounts'] = array('name' => 'Bulk Discounts', 'formtype' => 'lookup', 'required' => false, 'function' => 'getBulkDiscountsForProduct');
        }
        if (SHOP_USE_GENDER) {            
            $this->fields['gender'] = array('name' => 'Gender Options', 'formtype' => 'checklink', 'list' => false, 'required' => false,
            'not_field' => true, 'link' => 'gender', 'customfunction' => 'genderChecklist');              
            $this->links['gender'] = array('link_table' => 'shop_product_gender', 'table' => 'shop_gender', 'name' => 'title');        
         }

        if (SHOP_USE_SIZE) {             
             $this->fields['size'] = array('name' => 'size Options', 'formtype' => 'checklink', 'list' => false, 'required' => false,
            'not_field' => true, 'link' => 'size', 'customfunction' => 'sizeChecklist');            
            $this->links['size'] = array('link_table' => 'shop_product_size', 'table' => 'shop_size', 'name' => 'title');
        }
    }

// this is the first function to fire on the edit veiw of the data
    function show_edit($id, $parent_id = false) {
        // need to add these items now when the USE_TRADE_PRODCUCTS const is available

 

        parent::show_edit($id, $parent_id = false);
    }

     function sizeChecklist($id, $fieldname, $field) {
        $checklink = $this->links[$field['link']];
        /* #module specific */
        $linksql = "SELECT t.id, t.title, l.`shop_size_id` FROM shop_size t 
LEFT OUTER JOIN shop_product_size l ON t.id = l.`shop_size_id` AND l.`shop_product_id` = '$id'  
ORDER BY t.`order_num`  ";
        $linkresult = mysql_query($linksql);
        $template = '<span class="form-checkbox"><input type=checkbox name=%s[] %s value=%s>%s</span>';
      
        $inner = '';
        while ($linkrow = mysql_fetch_array($linkresult)) {
            
            $checked = ($linkrow['shop_size_id']) ? "checked" : "";
            $inner .= sprintf($template, $fieldname, $checked, $linkrow['id'], $linkrow['title']);
        }
        printf('<tr valign=top><td>%s</td><td><div class="form-checkbox-group">%s</div></td></tr>', $field['name'], $inner);
    }
    
    function genderChecklist($id, $fieldname, $field) {
        $checklink = $this->links[$field['link']];
        /* #module specific */
        $linksql = "SELECT t.id, t.title, l.`shop_gender_id` FROM shop_gender t 
LEFT OUTER JOIN shop_product_gender l ON t.id = l.`shop_gender_id` AND l.`shop_product_id` = '$id' 
ORDER BY t.`order_num`";
        $linkresult = mysql_query($linksql);
        $template = '<span class="form-checkbox"><input type=checkbox name=%s[] %s value=%s>%s</span>';
        $specialflag = 1;
        $inner = '';
        while ($linkrow = mysql_fetch_array($linkresult)) {
            if ($specialflag) {
                if ($linkrow['special'] == 0) {
                    $specialflag = 0;
                }
            }
            $checked = ($linkrow['shop_gender_id']) ? "checked" : "";
            $inner .= sprintf($template, $fieldname, $checked, $linkrow['id'], $linkrow['title']);
        }
        printf('<tr valign=top><td>%s</td><td><div class="form-checkbox-group">%s</div></td></tr>', $field['name'], $inner);
    }
    
   function getBulkDiscountsForProduct($id) {
        $bd = new bulk_discount();
        $id = 0;
        if (isset($_GET['id'])) {
            $id = $_GET['id'];
        }
        if ($id > 0) {
            // get a list of products that are grouped with this product for discount (as js array)
            list($discountedProducts, $discountedProduct_ids, $group_id) = $bd->getGroupProducts($id);
            // $discount_details comma separate list of item - discount
            $discount_details = $bd->getDiscountGroupDetails($group_id);
        }
        //echo $discount_details;
        //die();
        /*
          $out = '<button type="button" onclick="window.open(\'product_bulk_discount.php?id=' . $id .  '\',\'Bulk Discounts\',
          \'height=550,width=1100,toolbar=no,location=no,directories=no,status=no,menubar=no,scrollbars=no,resizable=no\'); ">Manage Bulk Discounts</button>';
         */
        $out = '<button type="button" onclick="window.open(\'product_bulk_discount.php?id=' . $id . '\',\'Discount\',
    	\'height=550,width=1100,toolbar=no,location=no,directories=no,status=no,menubar=no,scrollbars=no,resizable=no\'); ">Manage Bulk Discounts</button>';
        // will need names and ids, if reloading the editor
        $out .= '<script>discount_products = ' . $discountedProducts . ' </script>';
        $out .= '<input type=hidden name="discount_product_ids" id="discount_product_ids" value="' . $discountedProduct_ids . '" />';
        $out .= '<input type=hidden name="discounts" id="discounts" value="' . $discount_details . '" />';
        return $out;
    }

    function getColoursForProduct($id) {
        // include_once $base_path.'/modules/shop/classes/colours.php';
        $c = new colours();
        //$coloursArr = $c->getAllColoursAsJSArray();
        $id = 0;
        if (isset($_GET['id'])) {
            $id = $_GET['id'];
        }
        if ($id > 0) {
            
            
            
            // no season or tone, to remove duplicates
            $productColours = $c->getProductColoursAsJSArrayNoSeasonTone($id);
            $productColourjustids = $c->getProductColours($id);
            
          //  var_dump($productColours);            
         //   var_dump($productColourjustids);
            
            
        }
        $out = '<button type="button" onclick="window.open(\'product_colours.php?id=' . $id . '\',\'Colours\',
\'height=450,width=1100,toolbar=no,location=no,directories=no,status=no,menubar=no,scrollbars=no,resizable=no\'); ">Edit Product Colours</button>';
        $out .= '<input type=hidden name="colour_ids" id="colour_ids" value="' . $productColourjustids . '" />';
        $out .= "<ul id='swatch_container'></ul>";
        if ($id > 0) {
            $out .= "<script> _colourDetails =  " . $productColours . " ;</script>";
        }


        return $out;
    }

    function categorylookup($id) {
        $sql = 'SELECT sc1.name, sc1.id, sc1.isRoot, sc2.name AS parentname
				FROM shop_category sc1
				left JOIN shop_category sc2 ON sc2.id = sc1.parent_id
				WHERE sc1.online = 1 and coalesce(sc2.online,1) = 1
				ORDER BY case when sc1.isroot = 1 then sc1.order_num else sc2.order_num end, sc1.isRoot desc, sc1.order_num';
        //  echo $sql;
        $result = mysql_query($sql);
        $out = '<select name="primary_category_id">';
        while ($row = mysql_fetch_array($result)) {
            $selected = ($id == $row[id]) ? ' selected="selected"' : '';
            if ($row['isRoot']) {
                $out .= '<option value="' . $row['id'] . '"' . $selected . ' >' . $row['name'] . '</option>';
            } else {
                $out .= '<option value="' . $row['id'] . '"' . $selected . ' >' . $row['parentname'] . ' > ' . $row['name'] . '</option>';
            }
        }
        $out .= '</select>';
        return $out;
    }

    function categoryChecklist($id, $fieldname, $field) {
        $linksql = "SELECT sc1.name, sc1.id, sc1.isRoot, sc2.name AS parentname, case when shop_category_id is not null then 1 else 0 end as checked
					FROM shop_category sc1
					left JOIN shop_category sc2 ON sc2.id = sc1.parent_id
					left join shop_product_category spc on sc1.id = spc.shop_category_id and spc.shop_product_id = '$id'
					WHERE sc1.online = 1   and coalesce(sc2.online,1) = 1
					ORDER BY case when sc1.isroot = 1 then sc1.order_num else sc2.order_num end, sc1.isRoot desc, sc1.order_num";
        $linkresult = mysql_query($linksql);
        //echo $linksql;
        $parentTemplate = '<p style="margin:0; margin-top: 10px;"><input type=checkbox name=%s[] %s value=%s class="required"><strong>%s</strong></p>';
        $childTemplate = '<span class="form-checkbox"><input type=checkbox name=%s[] %s value=%s class="required">%s</span>';
        $parentName = '';
        $cats = array();
        $first = true;
        $key = 'checked';
        while ($linkrow = mysql_fetch_array($linkresult)) {
            $checked = ($linkrow[$key]) ? "checked" : "";
            if ($linkrow['isRoot'] == 1)
                $inner .= sprintf($parentTemplate, $fieldname, $checked, $linkrow['id'], $linkrow['name']);
            else
                $inner .= sprintf($childTemplate, $fieldname, $checked, $linkrow['id'], $linkrow['name']);
            if ($first) {
                $first = false;
                $parentTemplate = '<br  style="clear:both" />' . $parentTemplate;
            }
        }
        $inner .= '<br  style="clear:both" />';
        printf('<tr valign=top><td>%s</td><td>%s<div id="categoryCheck" class="form-checkbox-group" style="border: 1px solid #aaa; padding: 0 10px 10px; margin-bottom: 10px">%s</div></td></tr>', $field['name'], $button, $inner);
    }

    function commission_vat_calc_space() {
        return "<input type=\"text\" id=\"comm_vat_calc\" disabled=\"disabled\" />";
    }

    function commission_amount_calc_space() {
        return "<input type=\"text\" id=\"comm_amount_calc\" disabled=\"disabled\" />";
    }

    function price_calc_space() {
        return "<input type=\"text\" id=\"price_calc\" onchange=\"change_commission()\"/>";
    }

    function shipping_lookup($id) {
        $sql = 'select * from shop_shipping';
        $result = mysql_query($sql);
        $out = '<select id="shipping_id" name="shipping_id"  onchange="set_shipping_value_name()">';
        while ($row = mysql_fetch_array($result)) {
            $selected = ($id == $row[id]) ? ' selected="selected"' : '';
            $out .= '<option value="' . $row[id] . '"' . $selected . ' >' . $row[name] . '</option>';
        }
        $out .= '</select>';
        return $out;
    }

    function is_shipping_value_required($data) {
        return ($data['shipping_id'] != 3);
    }

    function validate_data($id, $data) {
        $sql = "select count(*) as existing from shop_product where stock_code = '{$data['stock_code']}'";
        $existing = db_get_single_value($sql, 'existing');
        $msg = '';
        if (($id == '') && ($existing > 0))
            $msg = '<p><b>Invalid stock code</b></p><p>The Stock code "' . $data['stock_code'] . '" is already in use</p>';
        return $msg;
    }

    function getcategoryname($id) {
        $cat = db_get_single_value("select primary_category_id from shop_product where id = '$id'");
        return db_get_single_value("SELECT CASE WHEN sc.isroot = 0 THEN CONCAT(scp.name, ' > ',sc.name) ELSE sc.name END AS name 
				FROM shop_category sc 
				JOIN shop_category scp ON scp.id = sc.parent_id 
				WHERE sc.id = '$cat'", 'name');
    }

    function get_crumbs($page) {
        if ($page == '')
            return "<b>{$this->single_name} Admin</b>";
        else
            return "<a href='product.php'>{$this->single_name} Admin</a> > <b>$page</b>";
    }

    function getSEODescription($id) {
        
        
        $module_id = db_get_single_value('SELECT `id` FROM  `module` WHERE constant = "SITE_HAS_SHOP"');
        if (isset($_GET['id'])) {
            $id = $_GET['id'];
        }
        $sql = "SELECT description FROM metatag WHERE ext_id ='{$id}' AND module_id = '{$module_id}'";
        $desc = db_get_single_value($sql);
        return "<textarea rows=4 cols=50 name=description_seo>{$desc}</textarea>";
    }

    function process_submit($id, $parent_id = false) {
        $str = parent::process_submit($id, $parent_id);
        
             
        
        
        // check that there is no error message and the product is a new one
        if (($str == "") && ($id == "")) {
            $stock_id = $this->id;
            $level = $this->data['stock'];
            $stock_sql = "insert into shop_stock (product_id, level, received_date) values ('$stock_id', '$level',  now())";
            $stock_result = mysql_query($stock_sql);
        }
                
        if ($str == ""){    
        
        $module_id = db_get_single_value('SELECT `id` FROM  `module` WHERE constant = "SITE_HAS_SHOP"');
        //$result = parent::process_submit($id, $parent_id);
        if ($id == '') {
            // insert into tags
            $id = $this->id;
            $sql = "insert into `metatag` (`ext_id`, `title`, `description`, `keywords`, `module_id`) values ( '{$id}', '{$_REQUEST['name']}', '{$_REQUEST['description_seo']}', 'keywords', '{$module_id}'); ";
             
        } else {

            // check if this item already has a tage entry
            $count = db_get_single_value("SELECT count(*) FROM metatag WHERE ext_id = '{$id}' AND module_id = '{$module_id}'");
            if ($count > 0) {
                // update tags           
                $sql = " UPDATE `metatag` SET `title` = '{$_REQUEST['name']}', `description` = '{$_REQUEST['description_seo']}', `keywords` = 'keywords'  where module_id ='{$module_id}'  and ext_id = '{$id}'";
            } else {
                $sql = "insert into `metatag` (`ext_id`, `title`, `description`, `keywords`, `module_id`) values ( '{$id}', '{$_REQUEST['name']}', '{$_REQUEST['description_seo']}', 'keywords', '{$module_id}'); ";
            }
        }
        
        mysql_query($sql);
        if ($_REQUEST['clear_custom_seo'] == '1') {
            echo $_REQUEST['clear_custom_seo'];
            mysql_query("delete from metatag where ext_id = '{$id}' AND module_id = '{$module_id}'");
        }
        
        
         }else{
             echo $str;
             echo '<input type="button" onclick="history.go(-1)" value="Back" class="admin-back-button">';
             
             exit();
         }
        
        
        
        
        
       // return $result;




        // return $str;
    }

    function get_stock_code_input() {
        return strtoupper((isset($_REQUEST['stock_code'])) ? $_REQUEST['stock_code'] : "");
    }

    // hopefully overriding the template function of the same name 
    function write_data($id, $parent_id) {
        if ($id) {


            /*             * * Update colours for product ** */
            $sql = "delete from shop_product_colour where product_id =" . $id;
            // then insert the new colour list
            mysql_query($sql);
            $sql = "insert into shop_product_colour (product_id, colour_id, order_no) value ";
            $c = substr($_POST['colour_ids'], 0, strlen($_POST['colour_ids']) - 1);
            $colourArr = explode(",", $c);
            $order_no = 0;
            foreach ($colourArr as $item) {
                $order_no = $order_no + 10;
                $sql = $sql . " (" . $id . "," . $item . ", " . $order_no . "),";
            }
            $sql = substr($sql, 0, strlen($sql) - 1);
            
          //  echo $sql;
            
            mysql_query($sql);
            /*             * * end colour update * */

            $this->updateBulkDiscounts($id);
            $sql = $this->make_update_sql_statement($id);
            //echo $sql;
            $result = mysql_query($sql);
        } else {
            $sql = $this->make_insert_sql_statement($parent_id);
            //echo $sql;
            $result = mysql_query($sql);
            $id = mysql_insert_id();
            $this->id = $id;

            /*             * * Update colours for product ** */
            $sql = "insert into shop_product_colour (product_id, colour_id, order_no) value ";
            $c = substr($_POST['colour_ids'], 0, strlen($_POST['colour_ids']) - 1);
            $colourArr = explode(",", $c);
             $order_no = 0;
            foreach ($colourArr as $item) {
                  $order_no = $order_no + 10;
                $sql = $sql . " (" . $id . "," . $item . ", " . $order_no . "),";
            }
            $sql = substr($sql, 0, strlen($sql) - 1);
            
           
            
            mysql_query($sql);
            /*             * * end colour update * */
            $this->updateBulkDiscounts($id);
        }
        return array($result, $id);
    }

    function make_insert_sql_statement($parent_id) {
        $sql_fields = '';
        $sql_data = '';
        $sql_lang_fields = '';
        $sql_lang_data = '';
        if ($parent_id) {
            $sql_fields .= $this->parent_field . ', ';
            $sql_data .= "'$parent_id', ";
        }
        if ($this->ordered) {
            $parent_part = ($parent_id) ? "where {$this->parent_field} = $parent_id" : '';
            $order_sql = "select coalesce(max(order_num),0) + 10 as order_num from {$this->table} $parent_part";
            $order_result = mysql_query($order_sql);
            $order_row = mysql_fetch_array($order_result);
            $sql_fields .= 'order_num, ';
            $sql_data .= "'{$order_row['order_num']}', ";
        }
        $first = true;
        $lang_first = true;
        foreach ($this->fields as $fieldname => $field) {
            //skip multi lang fields altogether in this part
            if ($field['multi_lang']) {
                /*
                  if ($lang_first)
                  $lang_first = false;
                  else
                  {
                  $sql_fields .= ', ';
                  $sql_data .= ', ';
                  }
                  $sql_lang_fields  .= '`'.$fieldname.'`';
                  $sql_lang_data = "'{$this->data[$fieldname]}'";
                 */
            } else {
                if (isset($field['not_field']) && $field['not_field'])
                    continue;
                if ($first)
                    $first = false;
                else {
                    $sql_fields .= ', ';
                    $sql_data .= ', ';
                }
                if ($field['formtype'] == 'order') {
                    
                }
                if ($field['formtype'] == 'address') {
                    $lines = isset($field['lines']) ? $field['lines'] : 3;
                    for ($i = 0; $i < $lines; $i++) {
                        $cm = ($i > 0) ? ', ' : '';
                        $sql_fields .= "$cm$fieldname" . ($i + 1);
                        $sql_data .= "$cm'{$this->data[$fieldname][$i]}'";
                    }
                } else {
                    $sql_fields .= '`' . $fieldname . '`';
                    $sql_data .= "'{$this->data[$fieldname]}'";
                }
            }// end multi lang condition
        } //  end loop
        $sql = "insert into {$this->table} ({$sql_fields}) values ({$sql_data})";
        return $sql;
    }

    function make_update_sql_statement($id) {
        $sql = "update {$this->table} set ";
        $i = 0;
        $first = true;
        foreach ($this->fields as $fieldname => $field) {
            //skip multi lang fields altogether in this part			
            if (!$field['multi_lang']) {
                //	$this->fields['colours'] = array('name' => 'Colours', 'formtype' => 'lookup', 'required' => false, 'function'=>'getColoursForProduct');
                if (isset($field['not_field']) && $field['not_field'])
                    continue;
                if (($field['formtype'] == 'password') && (trim($this->data[$fieldname], '   *') == ''))
                    continue;
                if ($first)
                    $first = false;
                else
                    $sql .= ', ';
                if ($field['formtype'] == 'address') {
                    $lines = isset($field['lines']) ? $field['lines'] : 3;
                    for ($i = 0; $i < $lines; $i++) {
                        $sql .= ($i > 0) ? ', ' : '';
                        $sql .= "$fieldname" . ($i + 1) . " = '{$this->data[$fieldname][$i]}'";
                    }
                } else {
                    $sql .= '`' . $fieldname . "` = '{$this->data[$fieldname]}'";
                }
            } // not colour
        } // multi lang condition
        $sql .= " where id = $id";
        return $sql;
    }

    function updateBulkDiscounts($id) {
        // from from product id - get discount group .... 
        $bd = new bulk_discount();
        $bd->updated_discounts($id, $_POST['discount_product_ids'], $_POST['discounts']);
    }

}

// end product class
$template = new products();
$main_page = 'index.php';
$main_title = 'Return to main page';
$admin_tab = "shop";
$second_admin_tab = "product";
include 'second_level_navigation.php';
include ("../../../admin/template.php");
?>
