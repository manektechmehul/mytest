<?php

include '../../../admin/classes/template.php';

class case_study extends template {

    function case_study() {
        $this->template();
        $this->debug_log = false;
        $this->php_debug = false;
        $this->table = 'case_study';
        $this->group_name = 'Case Studies';
        $this->single_name = 'Case Study';
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
            'category' => array('name' => 'Categories', 'formtype' => 'checklink', 'required' => false, 'not_field' => true, 'link' => 'category',
                'customfunction' => 'category_checklist'), 
            //'page_image' => array('name' => 'Page Image', 'formtype' => 'image'),
            'thumb' => array('name' => 'Thumbnail', 'formtype' => 'image', 'list' => false, 'required' => true, 'size' => 2),
            'description' => array('name' => 'Description', 'formtype' => 'textarea', 'list' => false, 'rows' => 3, 'cols' => 60, 'required' => true),
            'body' => array('name' => 'Main Content', 'formtype' => 'fckhtml', 'list' => false, 'required' => true),
            'page_name' => array('name' => 'Page Name', 'formtype' => 'hidden', 'keepfirst' => true),
        );
        // this will populate the link table - categories
        $this->links = array('category' => array('link_table' => 'case_study_category', 'table' => 'category', 'name' => 'title'));
    }

	function onload() {
		// add the gallery chooser dropdown if appropriate
		if ( CASESTUDIES_HAS_INLINE_GALLERIES ) {
			$this->fields['gallery'] = array('name' => 'Gallery', 'formtype' => 'lookup', 'required' => false, 'function' => 'gallerylookup');
		}


		// add the donate chooser dropdown if appropriate
		if ( SITE_HAS_DONATE ) {
			$this->fields['donate'] = array('name' => 'Donation', 'formtype' => 'lookup', 'required' => false, 'function' => 'donatelookup');
		}



	}

		function donatelookup($id) {
			$sql = 'select * from donate where published=1 order by title';
			$result = mysql_query($sql);
			//	echo $sql;
			$out = '<select id="donate" name="donate">';
			$selected = ( $id == 0 ) ? ' selected="selected"' : '';
			$out .= '<option value="0"' . $selected . ' >No Donate</option>';
			while ($row = mysql_fetch_array($result)) {
				$selected = ($id == $row[id]) ? ' selected="selected"' : '';
				$out .= '<option value="' . $row['id'] . '"' . $selected . ' >' . $row['title'] . '</option>';
			}
			$out .= '</select>';

			return $out;
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



    function get_crumbs($page) {
        if ($page == '')
            return "<b>{$this->single_name} Admin</b>";
        else
            return "<a href='blogs.php'>{$this->single_name} Admin</a> > <b>$page</b>";
    }

    function category_checklist($id, $fieldname, $field) {
        $checklink = $this->links[$field['link']];
        $linksql = "select t.id, t.title, l.case_study_id from category t left outer join case_study_category l" .
                " on t.id = l.category_id and l.case_study_id = '$id' order by title";
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
            $checked = ($linkrow['case_study_id']) ? "checked" : "";
            $inner .= sprintf($template, $fieldname, $checked, $linkrow['id'], $linkrow['title']);
        }
        printf('<tr valign=top><td>%s</td><td><div class="form-checkbox-group">%s</div></td></tr>', $field['name'], $inner);
    }

    
}

$template = new case_study();
$main_page = 'index.php';
$main_title = 'Return to main page';
$admin_tab = "case_studies";
$second_admin_tab = "casestudies";
include 'second_level_navigation.php';
include ("../../../admin/template.php");

?>


