<?php

include '../../../admin/classes/template.php';
include_once 'conf.php';

class myAdmin extends template {

    function __construct() {
        $this->template();
        $this->debug_sql = false;
        $this->php_debug = false;
        $this->table = '';
        $this->group_name = '';
        $this->single_name = '';
        //$this->ordered = true;
        $this->singular = 'a';
        $this->hideable = true;
        //$this->list_top_text = "The case study at the top of the list will be featured on the home page";

        $this->ordered = true;
        $this->order_clause = ' order_num ';

        $this->javascript_file = 'js/admin.js';
        $this->ToolbarSet = 'Default';


        // This will allow us to recieve a aretn id into the this admin area and pass it arround
        $parent_id = $_GET['parent_id']; //get the parent id
        $this->parent_id = $parent_id;
        $this->parent_field = 'content_id'; //this is the table column name
        $this->parent_id_name = 'parent_id'; // url parameter name

       // $this->parent_child = true;

        //$this->subfunction = 'subfunction';

        $this->buttons = array(
            'edit' => array('text' => 'add', 'type' => 'standard_edit'),
            'hide' => array('text' => 'hide', 'type' => 'standard_hide'),
            //'featured' => array( 'text' => 'hide', 'type' => 'function', 'function' => 'set_featured'),
            'delete' => array('text' => 'delete', 'type' => 'standard_delete'),
            'move' => array('text' => 'move', 'type' => 'standard_move'),
        );

        $this->custom_list_sql = 'SELECT opl.* , cs.title AS cs_title, cs.description AS cs_desc, cs.id AS cs_id, cs.page_name AS page_name,
CASE
WHEN opl.link_type <> 1  THEN opl.title
ELSE cs.title
END  AS title
FROM onpagelink opl
INNER JOIN content ct ON ct.id = opl.`content_id`
LEFT JOIN case_study cs ON opl.`module_id` = cs.`id` where content_id=' . $_GET['parent_id'] . ' order by order_num ' ;



        $this->fields = array(

            'link_type' => array('name' => 'Link Type', 'formtype' => 'lookup', 'required' => false, 'function' => 'linktypelookup'),
            'module_id' => array('name' => 'Case Study', 'formtype' => 'lookup', 'required' => false, 'function' => 'casestudylookup'),
            'title' => array('name' => 'Title', 'formtype' => 'text', 'required' => false, 'list' => true,),
            'summary' => array('name' => 'Summary', 'formtype' => 'textarea', 'required' => false),
            'file' => array('name' => 'File', 'formtype' => 'file', 'required' => false),
            'thumb' => array('name' => 'Thumbnail Image', 'formtype' => 'image', 'required' => false),
            'link' => array('name' => 'Link', 'formtype' => 'text', 'required' => false),
			'audio' => array('name' => 'Audio', 'formtype' => 'checkbox', 'list' => false, 'required' => false),
            'external_link' => array('name' => 'External_link', 'formtype' => 'checkbox', 'required' => false),
            'video_type' => array('name' => 'Video Source', 'formtype' => 'lookup', 'required' => false, 'function' => 'videotypelookup'),
            'video_id' => array('name' => 'Video id', 'formtype' => 'shorttext', 'required' => false),
            'freetext' => array('name' => 'Free text', 'formtype' => 'textarea', 'required' => false),
        );

        //	$cat_sql = 'select id, title from bulletins_category order by special desc,   case special when 1 then id when 0 then title end';;
        //	$this->links = array( 'category' => array('link_table' => 'bulletins_category_link', 'table' => 'bulletins_category', 'name' => 'title') );




    }

    /* So here I have managed to add a complex title to the template by pre processing the get_form_data function */
      function get_form_data() {


           $title =  db_get_single_value("SELECT title  FROM content WHERE id =" . $_GET['parent_id'] , 'title');

           // $this->list_top_text = $title;
           $this->list_top_text = '<h3>Associated with `' . $title . '` &nbsp; '
           .  "<a href='../../../admin/content_admin.php' >Back to Content List</a></h3>";

           parent::get_form_data();

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

    function category_checklist($id, $fieldname, $field) {
        $checklink = $this->links[$field['link']];
        $linksql = "select t.id, t.title, t.special, l.documents_id from bulletins_category t left outer join bulletins_category_link l" .
                " on t.id = l.bulletins_category_id and l.documents_id = '$id' order by title";
        $linkresult = mysql_query($linksql);
        ;
        $template = '<span class="form-checkbox"><input type=checkbox name=%s[] %s value=%s>%s</span>';
        $specialflag = 1;
        while ($linkrow = mysql_fetch_array($linkresult)) {
            if ($specialflag) {
                if ($linkrow['special'] == 0) {
                    $specialflag = 0;
                }
            }
            $checked = ($linkrow['documents_id']) ? "checked" : "";
            $inner .= sprintf($template, $fieldname, $checked, $linkrow['id'], $linkrow['title']);
        }
        printf('<tr valign=top><td>%s</td><td><div class="form-checkbox-group">%s</div></td></tr>', $field['name'], $inner);
    }

    function subfunction($id) {
        return db_get_single_value("select `date` <= now() as notexpired from documents where id = '$id'", 'notexpired');
    }

    function linktypelookup($id) {
        // see the onpagelinks / admin / js file for ;ayout swithing
        // keep the id on this dropdown for the js
		
		
		$types = '';
		
		
		
		
		if(ONPAGE_LINKS_CAT_CASESTUDIES){
			$types .= '1,';
		}
		if(ONPAGE_LINKS_CAT_DOWNLOADS){
			$types .= '2,';
		}
		if(ONPAGE_LINKS_CAT_LINK){
			$types .= '3,';
		}
		if(ONPAGE_LINKS_CAT_VIDEO_LINK){
			$types .= '4,';
		}
		if(ONPAGE_LINKS_CAT_STATIC_LINK){
			$types .= '5,';
		}
		
		$types = rtrim($types, ',');
		
        $sql = 'select * from onpagelink_type where id in (' . $types . ') order by title';
	
		
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

    function videotypelookup($id) {
        $sql = 'select * from onpagelink_video_type order by title';
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

    function categoryname($id) {
        return (db_get_single_value("select title from bulletins_category where id = '$id'", 'title'));
    }



    function get_crumbs($page) {
        if ($page == '')
            return "<b>{$this->single_name} Admin</b>";
        else
            return "<a href='" . $this->page_self . "'>{$this->single_name} Admin</a> > <b>$page</b>";
    }

}

$template = new myAdmin();

$template->table = $main_table;
$template->group_name = $main_group_name;
$template->single_name = $main_single_name;
$admin_tab = $main_admin_tab_name;

$main_page = 'index.php';
$main_title = 'Return to main page';
$second_admin_tab = "main";
// include 'second_level_navigavtion.php';
include ("../../../admin/template.php");
?>