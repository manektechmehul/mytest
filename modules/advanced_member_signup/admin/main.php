<?php

include '../../../admin/classes/template.php';

class palette extends template {

    function __construct() {
        $this->template();
        $this->table = 'colour_palettes';
        $this->group_name = 'Palettes';
        $this->single_name = 'My Colours Tool';
        //$this->ordered = true;
        $this->singular = 'a';
        $this->hideable = true;
        //$this->list_top_text = "The case study at the top of the list will be featured on the home page";
        $this->has_page_name = true;
        // $this->order_clause = '`order_num`';
              $this->javascript_file = '/modules/shop/js/productadmin.js';
        $this->ToolbarSet = 'Default';
        $this->max_items = 12;



        $this->buttons = array(
            'edit' => array('text' => 'add', 'type' => 'standard_edit'),
                //  'hide' => array('text' => 'hide', 'type' => 'standard_hide'),
                //'featured' => array( 'text' => 'hide', 'type' => 'function', 'function' => 'set_featured'),
                // 'delete' => array('text' => 'delete', 'type' => 'standard_delete'),
                //   'move' => array('text' => 'move', 'type' => 'standard_move'),
        );

        $this->fields = array(
            'name' => array('name' => 'Name', 'formtype' => 'text', 'primary' => true, 'list' => true),
            'description' => array('name' => 'Main Content', 'formtype' => 'textarea', 'list' => false, 'required' => true),
           // 'colours_csv' => array('name' => 'colours csv', 'formtype' => 'text', ),
            'colours' => array('name' => 'Colours','not_field'=>true, 'formtype' => 'lookup', 'required' => false, 'function'=>'getColoursForProduct'),
			 'products_csv' => array('name' => 'Feature Products', 'formtype' => 'text' ),
        );
    }

    function process_submit($id, $parent_id) {
        parent::process_submit($id, $parent_id);
        
        
        $sql = "update colour_palettes  set colours_csv = ";
        $c = substr($_POST['colour_ids'], 0, strlen($_POST['colour_ids']) - 1);
        
        $sql .= " '" . $c . "' where id ={$id};";

      //  echo $sql;
       // die();
        mysql_query($sql);
            
    }
    
    function getProductColours($id){
        return db_get_single_value("SELECT `colours_csv` FROM `colour_palettes` WHERE id = " . $id);
    }
        
    function getPaletteColoursAsJSArrayNoSeasonTone($palette_ids){	   
	   	if($palette_ids != ""){	   	
                    
	   		$sql = "SELECT * FROM colour_colour_details d  WHERE id IN ({$palette_ids})  " ;	   	
	   		
                        $s = ' [';  	
                        $result = mysql_query($sql);
	   		while ($row = mysql_fetch_array($result))
	   		{
	   			$s .= "[" .  $row['id'] . "," .  $row['dc_id'] . ",'" .  $row['name'] . "','" .  $row['pantone'] . "','#" .  $row['rgb'] . "'," .  $row['season_id'] . "," .  $row['tone_id']  .  "],";
	   		}
	   		// knock final comma	   	
	   		$s = substr($s,0,strlen($s)-1);	   	
	   		// add closing bracket, but only if we found some items
	   		if($s != ' '){
	   			$s .= ']';
	   		}
	   		return $s;
	   	}else{
	   	
	   		return '';
	   	}
     }
    
     function getColoursForProduct($id) {
        global $base_path;
        include_once $base_path.'/modules/shop/classes/colours.php';
        $c = new colours();        
        //$coloursArr = $c->getAllColoursAsJSArray();
        $id = 0;
        if (isset($_GET['id'])) {
            $id = $_GET['id'];
        }
        if ($id > 0) {
            // no season or tone, to remove duplicates
           
          //  var_dump($this->fields['colours_csv']);
           // var_dump($this->fields);
            $productColourjustids = $this->getProductColours($id);
            $productColours = $this->getPaletteColoursAsJSArrayNoSeasonTone($productColourjustids);
        }
        $out = '<button type="button" onclick="window.open(\'palette_colours.php?id=' . $id . '\',\'Colours\',
\'height=450,width=1100,toolbar=no,location=no,directories=no,status=no,menubar=no,scrollbars=no,resizable=no\'); ">Edit Palette Colours</button>';
        $out .= '<input type=hidden name="colour_ids" id="colour_ids" value="' . $productColourjustids . '" />';

        
        
        $out .= "<ul id='swatch_container'></ul>";
        if ($id > 0) {
            $out .= "<script> _colourDetails =  " . $productColours . " ;</script>";
        }
        return $out;
    }
    
    
    function get_crumbs($page) {
        if ($page == '')
            return "<b>{$this->single_name} Admin</b>";
        else
            return "<a href='palette.php'>{$this->single_name} Admin</a> > <b>$page</b>";
    }

}

$template = new palette();
$main_page = 'index.php';
$main_title = 'Return to main page';
$admin_tab = "shop";
$second_admin_tab = "palette";
include 'second_level_navigavtion.php';
include ("../../../admin/template.php");
?>
