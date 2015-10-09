<?php
include '../../../admin/classes/template.php'; 


error_reporting(E_ERROR);
ini_set('display_errors', '1');

class notice_category extends template
{
	function __construct()
	{
		$this->template();
		$this->table = 'notice_category';
		$this->group_name = 'Notice categories';
		$this->single_name = 'Notice category';
		$this->ordered = true;
		$this->singular = 'a';
		$this->hideable = true;
        $this->has_page_name = true;
        $this->invalid_page_names = array('notice', 'notifications');
		//$this->list_top_text = "The case study at the top of the list will be featured on the home page";

		$this->order_clause = 'order_num';
	
		//$this->javascript_file = 'js/case_study_admin.js';
		
		$this->ToolbarSet = 'Default';

		//$this->subfunction = 'subfunction';

        $this->buttons = array(
                            'edit' => array( 'text' => 'add', 'type' => 'standard_edit'),
                            'hide' => array( 'text' => 'hide', 'type' => 'standard_hide'),
                            //'featured' => array( 'text' => 'hide', 'type' => 'function', 'function' => 'set_featured'),
							'post' => array( 'text' => 'show post', 'type' => 'button', 'pattern' => 'notice_categories.php?list_posts=%s'),
                            'delete' => array( 'text' => 'delete', 'type' => 'standard_delete'),
                            'move' => array( 'text' => 'move', 'type' => 'standard_move'),
                            );		
		//$this->custom_list_sql = 'select id,  title, summary, description, published, unix_timestamp(date) as date from documents order by `date` desc';
		
		$this->fields = array( 
			'title' => array('name' => 'Title', 'formtype' => 'text', 'list' => true, 'required' => true, 'primary' => true),
			'body' => array('name' => 'Introduction', 'formtype' => 'fckhtml',  'required' => true),
            'page_name' => array('name' => 'Page Name', 'formtype' => 'hidden', 'keepfirst' => true),
		);			


        $this->actions = array(
            'show_posts' => array('title' => 'List Applicants', 'pagequerystring' => 'list_posts', 'pagemethod' => 'list_posts', 'actionquerystring' => 'delete_post', 'actionmethod' => 'process_delete_post'),
        );

	}	

    function get_crumbs($page)
    {
        if ($page == '')
            return "<b>{$this->single_name} Admin</b>";
        else
            return "<a href='notice_categories.php'>{$this->single_name} Admin</a> > <b>$page</b>";
    }

	function row_fixed($id)
	{
		return ($id == 1);
	}

	function row_show_button($button, $id)
	{
		return ($button != 'delete') || ($id != 1);
	}

	function list_posts()
	{
		$id = $_GET['list_posts'];
		if (!empty($_GET['delete_post']))
		{
			$postid = $_GET['delete_post'];

			$sql = "delete from notice_post where id = $postid";
			mysql_query($sql);
		}

		echo "<div id='main-content'>";
		$sql = "SELECT np.id, np.title, np.description,CONCAT(mu.firstname,' ',mu.surname) AS 'memberName', mu.screenname as 'memberScreenName'
			nc.title AS category, np.link, np.linktitle, unix_timestamp(np.`date`) as `date`
			FROM notice_post np
			JOIN member_user mu ON np.member = mu.id
			JOIN notice_category nc ON np.category = nc.id 
			where nc.id = $id
			order by np.date desc";
		$result = mysql_query($sql);

		while ($row = mysql_fetch_array($result))
		{
			$description = str_replace("\n", "<br />",$row['title']);
			$date = date('jS F Y H:i', $row['date']);

			echo "<h4>{$row['title']}</h4>";
			echo "<p>Date: {$date}</p>";
			echo "<p>Member: {$row['memberName']}</p>";
			echo "<p>Description: {$description}</p>";
			if ($row['linktitle'])
				echo "<p>Link: <a href=\"{$row['link']}\">{$row['linktitle']}</a></p>";
			echo "<a onclick=\"return confirm('Are you sure?');\" href=\"notice_categories.php?list_posts=$id&delete_post={$row['id']}\">delete</a>";
			echo "<hr />";
		}
		echo "</div>";
	}
    
    function process_delete_post() {
		$postid = $_GET['delete_post'];

		$sql = "delete from notice_post where id = $postid";
		mysql_query($sql);        
    }
}

$template = new notice_category();

$main_page = 'index.php';
$main_title = 'Return to main page';
			

$admin_tab = "noticeboard_admin";
$second_admin_tab = "categories";
include 'second_level_navigation.php';

include ("../../../admin/template.php");



