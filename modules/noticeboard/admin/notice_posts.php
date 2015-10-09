<?php
/*
 * Copyright (c): Creative Stream & Jemeno
 */
include '../../../admin/classes/template.php'; 

error_reporting(E_ERROR);
ini_set('display_errors', '1');


class notice_posts extends template
{
	function __construct()
	{
		$this->template();
		$this->table = 'documents';
		$this->group_name = 'Documents';
		$this->single_name = 'Document';
		//$this->ordered = true;
		$this->singular = 'a';
		$this->hideable = true;
		//$this->list_top_text = "The case study at the top of the list will be featured on the home page";

		$this->order_clause = '`date`';
	
		$this->javascript_file = 'js/case_study_admin.js';
		
		$this->ToolbarSet = 'Default';

		//$this->subfunction = 'subfunction';

        $this->buttons = array(
                            'edit' => array( 'text' => 'add', 'type' => 'standard_edit'),
                            'hide' => array( 'text' => 'hide', 'type' => 'standard_hide'),
                            //'featured' => array( 'text' => 'hide', 'type' => 'function', 'function' => 'set_featured'),
                            'delete' => array( 'text' => 'delete', 'type' => 'standard_delete'),
                            'move' => array( 'text' => 'move', 'type' => 'standard_move'),
                            );		
		$this->custom_list_sql = 'select id,  title, summary, description, published, unix_timestamp(date) as date from documents order by `date` desc';
		
        // TODO: jemeno - Add page_name
		$this->fields = array( 
			//'publishdate' => array('name' => 'Publish on', 'formtype' => 'date', 'required' => true ),
			'date' => array('name' => 'Date', 'formtype' => 'date', 'list' => true, 'required' => true ),
			//'time' => array('name' => 'Time', 'formtype' => 'time', 'list' => true, 'required' => true ),
			'title' => array('name' => 'Title', 'formtype' => 'text', 'list' => true, 'required' => true, 'primary' => true),
			'members' => array('name' => 'Accessible by Members ', 'formtype' => 'checklink', 'required' => true, 'not_field' => true, 'link' => 'members', 
				'customfunction' => 'member_checklist'),
			//'location' => array('name' => 'Location', 'formtype' => 'text', 'size' => 70, 'required' => true),
			//'fee' => array('name' => 'Fee', 'formtype' => 'text', 'size' => 10, 'required' => true),
			//'deadline' => array('name' => 'Booking Deadline', 'formtype' => 'date', 'required' => true ),
			//'thumb' => array('name' => 'Thumbnail', 'formtype' => 'image', 'list' => false, 'required' => true, 'size' => 2),
			'summary' => array('name' => 'Summary', 'formtype' => 'textarea', 'list' => false, 'rows' => 3, 'cols' => 60, 'required' => true),
			//'keywords' => array('name' => 'Keywords', 'formtype' => 'textarea', 'list' => false, 'rows' => 3, 'cols' => 60, 'required' => true),
			//'description' => array('name' => 'Main Content', 'formtype' => 'fckhtml', 'list' => false, 'required' => true)
			'file' => array('name' => 'Document', 'formtype' => 'file', 'list' => false, 'required' => true),
		);			

		$cat_sql = 'select id, title from documents_category order by special desc,   case special when 1 then id when 0 then title end';;
		$this->links = array( 'members' => array('link_table' => 'document_member_user_link', 'table' => 'member_user', 'name' => 'firstname') );

	}	

    function get_crumbs($page)
    {
		return "<b>Noticeboard Posts Admin</b>";
    }

	function getMappings($view)
	{
		$result = mysql_query("select * from $view");
		while ($row = mysql_fetch_array($result))
			$mappings[$row['id']] = $row['title'];
		return $mappings;
	}

	function show_list($parent_id = false)
	{
		if (!empty($_GET['delete_post']))
		{
			$postid = $_GET['delete_post'];

			$sql = "delete from notice_post where id = $postid";
			mysql_query($sql);
		}

		echo "<div id='main-content'>";
		$sql = "SELECT np.id, np.title, np.description,CONCAT(mu.firstname,' ',mu.surname) AS 'memberName', mu.screenname as 'memberScreenName',
			nc.title AS category, np.link, np.linktitle, unix_timestamp(np.`date`) as `date`
			FROM notice_post np
			JOIN member_user mu ON np.member = mu.id
			JOIN notice_category nc ON np.category = nc.id order by np.date desc";;
		$result = mysql_query($sql);
		while ($row = mysql_fetch_array($result))
		{
			$description = str_replace("\n", "<br />",$row['title']);
			$date = date('jS F Y H:i', $row['date']);

			echo "<h4>{$row['title']}</h4>";
			echo "<p>Category: {$row['category']}</p>";
			echo "<p>Date: {$date}</p>";
			echo "<p>Member: {$row['memberName']}</p>";
			echo "<p>Description: {$description}</p>";
			if ($row['linktitle'])
				echo "<p>Link: <a href=\"{$row['link']}\">{$row['linktitle']}</a></p>";
			echo "<a onclick=\"return confirm('Are you sure?');\" href=\"notice_posts.php?delete_post={$row['id']}\">delete</a>";
			echo "<hr />";
		}
		echo "</div>";
	}
}

$template = new notice_posts();

$main_page = 'index.php';
$main_title = 'Return to main page';
			

$admin_tab = "noticeboard_admin";
$second_admin_tab = "posts";
include 'second_level_navigation.php';

include ("../../../admin/template.php");



