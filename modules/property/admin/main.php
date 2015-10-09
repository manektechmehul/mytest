<?php
include '../../../admin/classes/template.php';

class main extends template {

    function main() {
        $this->template();
        $this->debug_log = false;
        $this->php_debug = false;
        /* #module specific */
        $this->table = 'property';
        /* #module specific */
        $this->group_name = 'Properties';
        /* #module specific */
        $this->single_name = 'Property';
        $this->ordered = true;
        $this->order_clause = ' order_num desc ';
        $this->singular = 'a';
        $this->hideable = true;
        //$this->list_top_text = "The case study at the top of the list will be featured on the home page";
        $this->css_file = array('css/imgmap.css','css/colorPicker.css');
        $this->javascript_file = array('js/admin.js','js/jquery.colorPicker.js');        
        $this->ToolbarSet = 'Default';
        $this->has_page_name = true;
        

        $this->buttons = array(         
          'edit' => array('text' => 'add', 'type' => 'standard_edit'),
          'hide' => array('text' => 'hide', 'type' => 'standard_hide'),
          'featured' => array( 'text' => 'featured', 'type' => 'function', 'function' => 'set_featured'),
          'delete' => array('text' => 'delete', 'type' => 'standard_delete'),
          'move' => array('text' => 'move', 'type' => 'standard_move'),
        );

        $this->fields = array(
		 	'page_name' => array('name' => 'page_name', 'formtype' => 'hidden'),
            'title' => array('name' => 'Title', 'formtype' => 'text', 'list' => true, 'required' => true, 'primary' => true),            
            'price' => array('name' => 'Price', 'formtype' => 'text', 'required' => false),
            
            //'property_type_id' => array('name' => 'Property Type', 'formtype' => 'lookup', 'required' => false, 'function' => 'property_type_lookup'),
            //'property_status_id' => array('name' => 'Property Status', 'formtype' => 'lookup', 'required' => false, 'function' => 'property_status_lookup'),
            'property_bedroom_id' => array('name' => 'Bedrooms', 'formtype' => 'lookup', 'required' => false, 'function' => 'property_bedroom_lookup'),
            'property_location_id' => array('name' => 'Location', 'formtype' => 'lookup', 'required' => false, 'function' => 'property_location_lookup'),
            'property_year_id' => array('name' => 'Acadamic Year', 'formtype' => 'lookup', 'required' => false, 'function' => 'property_year_lookup'),
            
            //  'category' => array('name' => 'Categories', 'formtype' => 'checklink', 'required' => true, 'not_field' => true, 'link' => 'category',
            //  'customfunction' => 'category_checklist'),            
            'page_image' => array('name' => 'Page Image', 'formtype' => 'image'),

            'thumb' => array('name' => 'Thumbnail', 'formtype' => 'image', 'list' => false, 'required' => false, 'size' => 2),
            
            'image_1' => array('name' => 'Image 1', 'formtype' => 'image'),
            'image_2' => array('name' => 'Image 2', 'formtype' => 'image'),
            'image_3' => array('name' => 'Image 3', 'formtype' => 'image'), 
            'image_4' => array('name' => 'Image 4', 'formtype' => 'image'), 
            'image_5' => array('name' => 'Image 5', 'formtype' => 'image'),
           // 'image_6' => array('name' => 'Image 6', 'formtype' => 'image'),            
  
            'address' => array('name' => 'Address', 'formtype' => 'textarea', 'list' => false, 'rows' => 3, 'cols' => 60, 'required' => false),
            'postcode' => array('name' => 'Postcode', 'formtype' => 'text', 'required' => false),
           // 'map_url' => array('name' => 'Map URL', 'formtype' => 'textarea', 'list' => false, 'rows' => 3, 'cols' => 60, 'required' => false),
            
            'attachment' => array('name' => 'Attachment(pdf)', 'formtype' => 'file'),
            
            'description' => array('name' => 'Amenities', 'formtype' => 'textarea', 'list' => false, 'rows' => 3, 'cols' => 60, 'required' => true),
            'body' => array('name' => 'Main Content', 'formtype' => 'fckhtml', 'list' => false, 'required' => true),
           // 'page_name' => array('name' => 'Page Name', 'formtype' => 'hidden', 'keepfirst' => true),
            
            //'floorplan_image' => array('name' => 'Floor Plan', 'formtype' => 'lookup', 'required' => false, 'function' => 'property_floor_plan'),            
            'floorplan_image' => array('name' => 'Floor Plan', 'formtype' => 'lookup', 'required' => false, 'function' => 'property_floor_plan'),
            'floorplan_mapping' => array('name' => 'Mapping', 'formtype' => 'hidden', 'not_field' => true, 'function' => 'getMappings'),
        );
        
        $this->actions = array(
                'edit_coords' => array(
                        'title' => 'Edit Coords Details',
                        'pagequerystring' => 'edit_coords',
                        'pagemethod' => 'edit_coords',
                        'actionquerystring' => 'process_coords',
                        'actionmethod' => 'process_coords'
                ),
        );
        // this will populate the link table - categories
         /* #module specific */
        $this->links = array('category' => array('link_table' => 'property_category_lookup', 'table' => 'property_category', 'name' => 'title', 'primary'=>'item_id', 'foreign'=>'category_id'));
        if(isset($_REQUEST['id']) && $this->getTotalCoords($_REQUEST['id'])){ 
            $this->body_javascript_file = array('js/imgmap.js','js/default_interface.js','js/property_custom.js');
        }else{
            $this->body_javascript_file = array('js/imgmap.js','js/default_interface.js');
        }
    }
                
    function set_featured($id) {        
        $featured = db_get_single_value('select featured from ' . $this->table . ' where id = ' . $id, 'featured');
        $hide_show = ($featured) ? 'hide' : 'show';
        $featured = ($featured) ? 'featured' : 'not featured';
        $href = sprintf("%s?%s%s_item=yes&id=%s", $this->page_self, $parent_part, $hide_show, $content_row["id"]);
        $this->cms_admin_button($href, $hide_show . 'content', $featured, "onclick='return set_featured_item(this, \"$featured\",\"$id\");'");
    }    
    
    function get_crumbs($page) {
        if ($page == '')
            return "<b>{$this->single_name} Admin</b>";
        else
            return "<a href='main.php'>{$this->single_name} Admin</a> > <b>$page</b>";
    }

   function category_checklist($id, $fieldname, $field) {
        $checklink = $this->links[$field['link']];           
        /* #module specific */
        $linksql = "SELECT t.id, t.title, l.item_id FROM property_category t 
LEFT OUTER JOIN property_category_lookup l ON t.id = l.category_id AND l.item_id = '$id' order by title";      
        
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
            $checked = ($linkrow['item_id']) ? "checked" : "";
            $inner .= sprintf($template, $fieldname, $checked, $linkrow['id'], $linkrow['title']);
        }
        printf('<tr valign=top><td>%s</td><td><div class="form-checkbox-group">%s</div></td></tr>', $field['name'], $inner);
    }


    function property_type_lookup($id) {
        $sql = 'select * from property_type order by title';
        $result = mysql_query($sql);
        // echo $sql;
        $out = '<select name="property_type_id">';
        while ($row = mysql_fetch_array($result)) {
            $selected = ($id == $row[id]) ? ' selected="selected"' : '';
            $out .= '<option value="' . $row['id'] . '"' . $selected . ' >' . $row['title'] . '</option>';
        }
        $out .= '</select>';

        return $out;
    }
    function property_status_lookup($id) {
        $sql = 'select * from property_status order by title';
        $result = mysql_query($sql);
        // echo $sql;
        $out = '<select name="property_status_id">';
        while ($row = mysql_fetch_array($result)) {
            $selected = ($id == $row[id]) ? ' selected="selected"' : '';
            $out .= '<option value="' . $row['id'] . '"' . $selected . ' >' . $row['title'] . '</option>';
        }
        $out .= '</select>';

        return $out;
    }
    
     function property_location_lookup($id) {
        $sql = 'select * from property_location order by title';
        $result = mysql_query($sql);
        // echo $sql;
        $out = '<select name="property_location_id">';
        while ($row = mysql_fetch_array($result)) {
            $selected = ($id == $row[id]) ? ' selected="selected"' : '';
            $out .= '<option value="' . $row['id'] . '"' . $selected . ' >' . $row['title'] . '</option>';
        }
        $out .= '</select>';

        return $out;
    }
    
    function property_bedroom_lookup($id) {
        $sql = 'select * from property_bedroom order by title';
        $result = mysql_query($sql);
        // echo $sql;
        $out = '<select name="property_bedroom_id">';
        while ($row = mysql_fetch_array($result)) {
            $selected = ($id == $row[id]) ? ' selected="selected"' : '';
            $out .= '<option value="' . $row['id'] . '"' . $selected . ' >' . $row['title'] . '</option>';
        }
        $out .= '</select>';

        return $out;
    }
    
    function property_year_lookup($id) {
        $sql = 'select * from property_year order by title';
        $result = mysql_query($sql);
        // echo $sql;
        $out = '<select name="property_year_id">';
        while ($row = mysql_fetch_array($result)) {
            $selected = ($id == $row[id]) ? ' selected="selected"' : '';
            $out .= '<option value="' . $row['id'] . '"' . $selected . ' >' . $row['title'] . '</option>';
        }
        $out .= '</select>';

        return $out;
    }
    
    function property_floor_plan($id){
        $prop_str = '<fieldset>
			<legend>
				<a onclick="toggleFieldset(this.parentNode.parentNode)">Image map areas</a>
			</legend>
			<div style="border-bottom: solid 1px #efefef">
			<div id="button_container">
				<!-- buttons come here -->
				<img src="images/add.gif" onclick="myimgmap.addNewArea()" alt="Add new area" title="Add new area"/>
				<img src="images/delete.gif" onclick="myimgmap.removeArea(myimgmap.currentid)" alt="Delete selected area" title="Delete selected area"/>				
                                <input type="hidden" id="dd_zoom" value="1" /> 
				<input type="hidden" id="dd_output" value="imagemap" /> 
			</div>			
			</div>
			<div id="form_container" style="clear: both;">
			<!-- form elements come here -->
         	</div>
		</fieldset>
		<fieldset>
			<legend>
				<a onclick="toggleFieldset(this.parentNode.parentNode)">Image</a>
			</legend>
			<div id="pic_container">
			</div>			
		</fieldset>
                <fieldset id="fieldset_html" class="">
			<legend>
				<a onclick="toggleFieldset(this.parentNode.parentNode)">Code</a>
			</legend>
			<div>
			<div id="output_help">
			</div>
			<textarea id="html_container" name="floorplan_mapping1"></textarea></div>
		</fieldset>';
       
        return '<iframe id="iframe_uploader"
                name="uploader"
                src="../floorplan_upload.php"
                scrolling="no"
                noresize="noresize"
                frameborder="no"
                width="auto"
                height="30" style="float:left;"></iframe>
                <a href="javascript:gui_loadImage(document.getElementsByName(\'floorplan_image\')[0].value);" value="'.$id.'" class="source_accept" id="source_accept" style="">accept</a><br/><br />
                '.$prop_str;
    }
    
    function process_coords(){
       $property_id = $_REQUEST['id'];
       $this->main->get_form_data();
       return $this->main->process_submit( $property_id );       
    }
    function process_submit( $id ) {
        template::process_submit( $id );  
        if($id==''){
            $id = $this->id;            
        }
        $sql1 = "delete from property_floorplan where property_id=$id";
        $del  = mysql_query( $sql1 );
        for($i=0;$i<count($_POST['img_coords']);$i++){
            $coords=$_POST['img_coords'][$i];
            $ctitle=$_POST['img_title'][$i];
            if($coords!=''){
                $sql     = "insert into property_floorplan (property_id, coords, room_title) values ($id, '".$coords."', '".$ctitle."')";
                $result  = mysql_query( $sql );
            }
        } 
        
        $sqlupd = "update property set floorplan_mapping='".$_POST['floorplan_mapping1']."' where id=$id";
        $upd  = mysql_query( $sqlupd );
        $this->prop_mapping=$_POST['floorplan_mapping1'];
    }
    function getMappings(){           
        $sql  = "SELECT floorplan_mapping FROM property WHERE id ='{$_REQUEST[id]}'";       
        $propMap = db_get_single_value( $sql , "floorplan_mapping");    
        $propMap=str_replace("\"","'",$propMap);
        return $propMap;
    }
    
    function getTotalCoords($id){
        $sql = 'select * from property_floorplan where floorplan_mapping!="" AND property_id='.$id;
        $result = mysql_query($sql);
        return count($result);
    }    
}

$template = new main();
$main_page = 'index.php';
$main_title = 'Return to main page';
/* #module specific */
$admin_tab = "property";
/* #module specific */
$second_admin_tab = "property";

include 'second_level_navigation.php';
include ("../../../admin/template.php");
?>


