<?php

include '../../../admin/classes/template.php';

class resources extends template {

    function resources() {
        $this->template();

     //   $this->php_debug = true;
     //   $this->debug_log = true;
        
        $this->table = 'factor_resource';
        $this->group_name = 'Factor Resources';
        $this->single_name = 'Resource';
        $this->singular = 'a ';
    //    $this->max_items = -1;
        $this->javascript_file = 'js/admin.js';

        $factor_id = $_GET['factor_id'];
        $this->parent_id = $factor_id;
        $this->parent_field = 'factor_id'; // need this in this table
        $this->parent_id_name = 'factor_id';
        
        
     //   $order_id = $_GET['order'];
   //     $this->parent_id = $order_id;
    //    $this->parent_field = 'order_id';
    //    $this->parent_id_name = 'order';
        
        //    $this->list_top_text = sprintf ("<a href=\"categories.php\" onMouseOut=\"MM_swapImgRestore()\" onMouseOver=\"MM_swapImage('defaultcategories','','/admin/images/buttons/cmsbutton-Categories-over.gif',0)\"><img style='border:none' src='./images/buttons/cmsbutton-Categories-off.gif' name='defaultcategories'></a>", $PHP_SELF);
        $this->ToolbarSet = 'Default';
        $this->order_clause = ' order_num ';

        $this->custom_list_sql = "SELECT * FROM factor_resource where factor_id = {$factor_id} ";

        $this->buttons = array(
            'edit' => array('text' => 'add', 'type' => 'standard_edit'), 
            'hide' => array('text' => 'hide', 'type' => 'standard_hide'),
            //'featured' => array( 'text' => 'hide', 'type' => 'function', 'function' => 'set_featured'),
            'delete' => array('text' => 'delete', 'type' => 'standard_delete'),
            'move' => array('text' => 'move', 'type' => 'standard_move'),
        );
        $this->fields = array(
            'link_type' => array('name' => 'Link Type', 'formtype' => 'lookup', 'required' => false, 'function' => 'linktypelookup'),
            'module_id' => array('name' => 'Case Study', 'formtype' => 'lookup', 'required' => false, 'function' => 'casestudylookup'),
            'title' => array('name' => 'Title', 'formtype' => 'text', 'required' => false, 'list' => true,),
            'summary' => array('name' => 'Summary', 'formtype' => 'fckhtml', 'required' => false),
            'file' => array('name' => 'File', 'formtype' => 'file', 'required' => false),            
            'audio' => array('name' => 'Audio', 'formtype' => 'checkbox', 'list' => false, 'required' => false),            
            'thumb' => array('name' => 'Thumbnail Image', 'formtype' => 'image', 'required' => false),
            'link' => array('name' => 'Link', 'formtype' => 'text', 'required' => false),
            'external_link' => array('name' => 'External_link', 'formtype' => 'checkbox', 'required' => false),
            'video_type' => array('name' => 'Video Source', 'formtype' => 'lookup', 'required' => false, 'function' => 'videotypelookup'),
            'video_id' => array('name' => 'Video id', 'formtype' => 'shorttext', 'required' => false),
            'freetext' => array('name' => 'Free text', 'formtype' => 'fckhtml', 'required' => false),
        );
        //$this->links = array( 'category' => array('link_table' => 'case_study_category', 'table' => 'category', 'name' => 'title') );
    }

    function onload() {
        // echo 'constants sre ' .  USE_TRADE_PRODUCTS . USE_COLOURS . USE_GENDER . USE_SIZE ;
        //    if(SHOP_USE_COLOURS){
        //   $this->fields['colours'] = array('name' => 'Colour', 'formtype' => 'lookup', 'list' => true, 'list_prefix' => ' ', 'required' => false, 'function' => 'get_colour_desc', 'titlefunction' => 'get_colour_desc');
        //     }
    }

    function casestudylookup($id) {
        // see the onpagelinks / admin / js file for ;ayout swithing
        // keep the id on this dropdown for the js
        $sql = 'select * from case_study order by title';
        $result = mysql_query($sql);
        //	echo $sql;
        $out = '<select  name="module_id">';
        while ($row = mysql_fetch_array($result)) {
            $selected = ($id == $row[id]) ? ' selected="selected"' : '';
            $out .= '<option value="' . $row['id'] . '"' . $selected . ' >' . $row['title'] . '</option>';
        }
        $out .= '</select>';

        return $out;
    }

    function get_crumbs() {
        return "<a href='main.php'>Factor Admin</a> > <b>{$this->single_name} Admin</b>";
    }

    function linktypelookup($id) {
        // see the factor_resources / admin / js file for ;ayout swithing
        // keep the id on this dropdown for the js
 
        $types = '';
 
        //	if(ONPAGE_LINKS_CAT_CASESTUDIES){
        //		$types .= '1,';
        //	}
        if (FACTOR_DOWNLOADS) {
            $types .= '2,';
        }
        if (FACTOR_LINK) {
            $types .= '3,';
        }
        if (FACTOR_VIDEO_LINK) {
            $types .= '4,';
        }
        if (FACTOR_STATIC_LINK) {
            $types .= '5,';
        }

        $types = rtrim($types, ',');

        $sql = 'select * from factor_resource_type where id in (' . $types . ') order by title';


        $result = mysql_query($sql);
        //	echo $sql;
        $out = '<select id="link_type" name="link_type">';
        while ($row = mysql_fetch_array($result)) {
            $selected = ($id == $row[id]) ? ' selected="selected"' : '';
            $out .= '<option value="' . $row['id'] . '"' . $selected . ' >' . $row['title'] . '</option>';
        }
        $out .= '</select>';

        return $out;
    }

    
    function process_submit($id, $parent_id = false) {
        $result = parent::process_submit($id, $parent_id);    
        
        if(($_REQUEST['link_type']=='4') && ($_REQUEST['video_type']=='2')){
        $video_id = $_REQUEST['video_id'];        
	$property = 'thumbnail_large';
	if (function_exists('curl_init')){ 	
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, "http://vimeo.com/api/v2/video/$video_id.php");
		curl_setopt($ch, CURLOPT_HEADER, 0);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_TIMEOUT, 10);
		$output = unserialize(curl_exec($ch));
	}else{
		die('CURL is not installed!');
	}
	$thumbnail_url =  $output[0][$property];	
     //  echo '<img src="' . $thumbnail_url . '"> ';
    //    die();
        
       if(!$id){
            $id = $this->id;
       }
                
        
        // using this->id is best as it should be available after inital insert as well as update
        $sql = "UPDATE factor_resource SET vimeo_thumb_url = '{$thumbnail_url}' WHERE id = {$id}";
        db_update($sql);
        
       // echo $sql;
       // die();
     
      
        
        
        }
        
    }
    
    
    function videotypelookup($id) {
        $sql = 'select * from factor_resource_video_type order by title';
        $result = mysql_query($sql);
        // echo $sql;
        $out = '<select name="video_type">';
        while ($row = mysql_fetch_array($result)) {
            $selected = ($id == $row[id]) ? ' selected="selected"' : '';
            $out .= '<option value="' . $row['id'] . '"' . $selected . ' >' . $row['title'] . '</option>';
        }
        $out .= '</select>';

        return $out;
    }

}

$template = new resources();
$main_page = 'index.php';
$main_title = 'Return to main page';
$admin_tab = "shop";
$second_admin_tab = "orders";
include 'second_level_navigation.php';
include ("../../../admin/template.php");
?>
