<?php
include '../../../admin/classes/template.php';

// declarations
$admin_tab = 'gallery_admin';

class gallery extends template 
{
	function gallery()
	{
		$this->template();

		$this->table = 'gallery';
		$this->group_name = 'Galleries';
		$this->single_name = 'Gallery';
		$this->singular = 'a';
		$this->hidable = true;
		$this->ordered = true;
		
		$this->fields = array( 
			'title' => array('name' => 'Title', 'formtype' => 'text', 'list' => true, 'required' => true, 'primary' => true),
			'description' => array('name' => 'Description', 'formtype' => 'textarea', 'list' => false, 'rows' => 3, 'cols' => 60, 'required' => true),
		);			

        $this->buttons = array(
                            'edit' => array( 'text' => 'add', 'type' => 'standard_edit'),
                            'images' => array( 'text' => 'add%252Fedit images', 'type' => 'button', 'pattern' => '/modules/gallery/admin/gallery.php?gallery_id=%s'),
	        'categories'=>'placeholder',

            'hide' => array( 'text' => 'hide', 'type' => 'standard_hide'),
                            'delete' => array( 'text' => 'delete', 'type' => 'standard_delete'),
                            'move' => array( 'text' => 'move', 'type' => 'standard_move'),
                            );
		
		$this->custom_button = true;
		$this->custom_button_pattern = 'gallery.php?gallery_id=%s';
		$this->custom_button_text = 'add%252Fedit images';
		//$this->child = new gallery_image();





	}

	function onload()
	{
		// add the gallery chooser drop down if appropriate
		if (GALLERY_HAS_CATEGORIES) {

			
			$this->buttons['categories'] = array(
				'text' => 'edit categories', 'type' => 'button', 'pattern' => '/modules/gallery/admin/categories.php?gallery_id=%s'
			);
		}
	}
}
$template = new gallery();


$main_page = 'index.php';
$main_title = 'Return to main page';
//$template->list_top_text .= '<br>[ <a href="./index.php"> Back to main page</a> ]';


include '../../../admin/template.php';
?>