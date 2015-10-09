<?php

	include '../../../admin/classes/template.php';

	class posts extends template {

		function posts() {
			$this->template();
			$this->table = 'memorybook_memory';
			$this->group_name = 'Memories';
			$this->single_name = 'Memory';
			$this->singular = 'a';
			$this->hideable = true;
			$this->order_clause = 'id desc';
			$this->has_page_name = true;
		//	$this->invalid_page_names = array('archive');
		//	$this->fail_auth_location = '/modules/blog/admin/blogs.php';
			//$this->ordered = true;
			$memory_id = $_GET['memorybook'];
			//$this->where_clause = " where blog_id = $blog_id ";
			$this->parent_id = $memory_id;
			$this->parent_field = 'memorybook_id';
			$this->parent_id_name = 'memorybook';
			$this->list_top_text = "<b> To indicate you have seen a new item, go into the details view, check the content and click submit </b>";
			$this->ToolbarSet = 'Default';
			$this->max_items = -1;
			$this->buttons = array(
				'edit' => array('text' => 'add', 'type' => 'standard_edit'),
			//	'comments' => array('type' => 'button', 'text' => 'manage comments', 'pattern' => '/modules/blog/admin/comments.php?post=%s'),
				'hide' => array('text' => 'hide', 'type' => 'standard_hide'),
				'delete' => array('text' => 'delete', 'type' => 'standard_delete'),
			 	'comment count' => array('type' => 'function', 'function' => 'isnew'),
			);

			$this->fields = array(
				'name' => array('name' => 'Name', 'formtype' => 'text', 'list' => true, 'required' => true, 'primary' => true),
				'email' => array('name' => 'Email', 'formtype' => 'text'),
				'memory' => array('name' => 'Memory', 'formtype' => 'fckhtml', 'list' => false, 'required' => true),
				'new' => array('name' => 'new', 'formtype' => 'hidden', 'value'=>'0' ),


			);

			//$this->links = array( 'category' => array('link_table' => 'case_study_category', 'table' => 'category', 'name' => 'title') );
		}




		function isnew($id){
			$new = db_get_single_value("select new from memorybook_memory where id={$id}");
			if($new){
				echo '<div class="contentfieldactive" >New Memory Requiring Review</div>';
			}else{

			}

		}



		function get_crumbs() {
			return "<a href='main.php'>Memorybook Admin</a> > <b>{$this->single_name} Admin</b>";
		}





	}

	$template = new posts();

	$main_page = 'index.php';
	$main_title = 'Return to main page';
	$admin_tab = "memorybook";

	include ("../../../admin/template.php");
?>


