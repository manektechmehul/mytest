<?php

include '../../../admin/classes/template.php';

class main extends template {

    function main() {
        $this->template();
        $this->debug_log = false;
        $this->php_debug = false;
        /* #module specific */
        $this->table = 'lottery';
        /* #module specific */
        $this->group_name = 'Lottery';
        /* #module specific */
        $this->single_name = 'Lottery';
        $this->ordered = true;
        $this->order_clause = ' order_num desc ';
        $this->singular = 'a';
        $this->hideable = true;
        //$this->list_top_text = "The case study at the top of the list will be featured on the home page";
        $this->javascript_file = 'js/admin.js';
        $this->ToolbarSet = 'Default';
        $this->has_page_name = true;

        $this->buttons = array(
            'edit' => array('text' => 'add', 'type' => 'standard_edit'),
            'hide' => array('text' => 'hide', 'type' => 'standard_hide'),
            // need to dynamically initiate this button
            'featured' => array('text' => 'hide', 'type' => 'function', 'function' => 'set_featured'),
            'delete' => array('text' => 'delete', 'type' => 'standard_delete'),
            'move' => array('text' => 'move', 'type' => 'standard_move'),
        );

        $this->fields = array(
            'title' => array('name' => 'Title', 'formtype' => 'text', 'list' => true, 'required' => true, 'primary' => true),
            'category' => array('name' => 'Categories', 'formtype' => 'checklink', 'required' => true, 'not_field' => true, 'link' => 'category',
            'customfunction' => 'category_checklist'),
            'page_image' => array('name' => 'Page Image', 'formtype' => 'image'),
            'thumb' => array('name' => 'Thumbnail', 'formtype' => 'image', 'list' => false, 'required' => true, 'size' => 2),
            'description' => array('name' => 'Description', 'formtype' => 'textarea', 'list' => false, 'rows' => 3, 'cols' => 60, 'required' => true),
            'body' => array('name' => 'Main Content', 'formtype' => 'fckhtml', 'list' => false, 'required' => true),
            'page_name' => array('name' => 'Page Name', 'formtype' => 'hidden', 'keepfirst' => true),
            'description_seo' => array('name' => 'Description<br>(for SEO)', 'formtype' => 'lookup', 'not_field' => true, 'function' => 'getSEODescription'),
            'clear_custom_seo' => array('name' => 'Clear Custom SEO<br> (Check this box then submit)', 'formtype' => 'checkbox', 'not_field' => true),
            
            
            
            
        );
        // this will populate the link table - categories
         /* #module specific */
        $this->links = array('category' => array('link_table' => 'lottery_category_lookup', 'table' => 'lottery_category', 'name' => 'title', 'primary'=>'item_id', 'foreign'=>'category_id'));
    }

	// add gallery bits here

	function onload() {
		// add the gallery chooser dropdown if appropriate
		if ( LOTTERY_HAS_INLINE_GALLERIES ) {
			$this->fields['gallery'] = array('name' => 'Gallery', 'formtype' => 'lookup', 'required' => false, 'function' => 'gallerylookup');
		}



	}

	function gallerylookup($id) {
		$sql = 'select * from gallery order by 1 asc';
		$result = mysql_query($sql);
		//	echo $sql;
		$out = '<select id="gallery" name="gallery">';
		$selected = ( $id == 0 ) ? ' selected="selected"' : '';
		$out .= '<option value="0"' . $selected . ' >No Gallery</option>';
		while ($row = mysql_fetch_array($result)) {
			$selected = ($id == $row[id]) ? ' selected="selected"' : '';
			$out .= '<option value="' . $row['id'] . '"' . $selected . ' >' . $row['title'] . '</option>';
		}
		$out .= '</select>';

		return $out;
	}



    function get_form_data() {
        $this->module_id = db_get_single_value("select id from module where constant = 'SITE_HAS_LOTTERY'", "id");
        parent::get_form_data();
    }
  
     function getSEODescription($id) {
        $module_id = $this->module_id;
        if (isset($_GET['id'])) {
            $id = $_GET['id'];
        }
        $sql = "SELECT description FROM metatag WHERE ext_id ='{$id}' AND module_id = '{$module_id}'";
        $desc = db_get_single_value($sql);
        return "<textarea rows=4 cols=50 name=description_seo>{$desc}</textarea>";
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
        $linksql = "SELECT t.id, t.title, l.item_id FROM lottery_category t 
LEFT OUTER JOIN lottery_category_lookup l ON t.id = l.category_id AND l.item_id = '$id' order by title";      
        
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
    
    
    
function process_submit($id, $parent_id = false) {
        $module_id = $this->module_id;
        $result = parent::process_submit($id, $parent_id);
        if ($id == '') {
            // insert into tags
            $id = $this->id;
            $sql = "insert into `metatag` (`ext_id`, `title`, `description`, `keywords`, `module_id`)
values ( '{$id}', '{$_REQUEST['title']}', '{$_REQUEST['description_seo']}', 'keywords', '{$module_id}'); ";
        } else {

            // check if this item already has a tage entry
            $count = db_get_single_value("SELECT count(*) FROM metatag WHERE ext_id = '{$id}' AND module_id = '{$module_id}'");
            if ($count > 0) {
                // update tags           
                $sql = " UPDATE `metatag` SET `title` = '{$_REQUEST['title']}', `description` = '{$_REQUEST['description_seo']}', `keywords` = 'keywords'  where module_id ='{$module_id}'  and ext_id = '{$id}'";
            } else {
                $sql = "insert into `metatag` (`ext_id`, `title`, `description`, `keywords`, `module_id`)
values ( '{$id}', '{$_REQUEST['title']}', '{$_REQUEST['description_seo']}', 'keywords', '{$module_id}'); ";
            }
        }
        mysql_query($sql);
        if ($_REQUEST['clear_custom_seo'] == '1') {
            echo $_REQUEST['clear_custom_seo'];
            mysql_query("delete from metatag where ext_id = '{$id}' AND module_id = '{$module_id}'");
        }
        return $result;
    }
    
}

$template = new main();
$main_page = 'index.php';
$main_title = 'Return to main page';
/* #module specific */
$admin_tab = "lottery";
/* #module specific */
$second_admin_tab = "lottery";

include 'second_level_navigation.php';
include ("../../../admin/template.php");

?>


