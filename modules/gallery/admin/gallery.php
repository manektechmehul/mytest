<?php

    include '../../../admin/classes/template.php';

    class gallery extends template
    {

        function gallery()
        {
            $this->template();
            $this->table       = 'gallery_image';
            $this->group_name  = 'Galleries';
            $this->single_name = 'Image';
            $this->singular    = 'a';
            $this->hideable    = true;
            $this->ordered = true;
            $gallery_id = $_GET['gallery_id'];
            $this->parent_id      = $gallery_id;
            $this->parent_field   = 'gallery_id';
            $this->parent_id_name = 'gallery_id';
            //$this->list_top_text = sprintf ("<a href=\"categories.php\" onMouseOut=\"MM_swapImgRestore()\" onMouseOver=\"MM_swapImage('defaultcategories','','/admin/images/buttons/cmsbutton-Categories-over.gif',0)\"><img style='border:none' src='./images/buttons/cmsbutton-Categories-off.gif' name='defaultcategories'></a>", $PHP_SELF);
            $this->debug_log = false;
            $this->buttons = array(
                'edit' => array( 'text' => 'add', 'type' => 'standard_edit' ),
                'hide' => array( 'text' => 'hide', 'type' => 'standard_hide' ),
                'delete' => array( 'text' => 'delete', 'type' => 'standard_delete' ),
                'move' => array( 'text' => 'move', 'type' => 'standard_move'),

            );
            $this->fields = array(
                'title' => array(
                    'name' => 'Title&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;',
                    'formtype' => 'text',
                    'list' => true,
                    'required' => true,
                    'primary' => true
                ),
                'description' => array('name' => 'Description', 'formtype' => 'text',  'required' => true,),


                'imagename' => array('name' => 'Image', 'formtype' => 'image', 'list' => false, 'required' => true, 'size' => 2, 'list' => true,),

            );
            $this->links = array(
                'gallery_category' => array(
                    'link_table' => 'gallery_category_lookup',
                    'table' => 'gallery_category',
                    'name' => 'title'
                )
            );
        }


        function onload()
        {
            // add the gallery chooser drop down if appropriate
            if (GALLERY_HAS_CATEGORIES) {


                $this->fields['gallery_category'] = array(
                    'name' => 'Categories',
                    'formtype' => 'checklink',
                    'required' => false,
                    'not_field' => true,
                    'link' => 'gallery_category',
                    'customfunction' => 'category_checklist'
                );
            }
        }


        function category_checklist( $id, $fieldname, $field )
        {
            // $checklink = $this->links[$field['link']];
            $gallery_id = $_GET['gallery_id'];

            $linksql = "SELECT bc.id, bc.title, l.gallery_image_id FROM gallery_category bc LEFT OUTER JOIN gallery_category_lookup l
                   ON bc.id = l.gallery_category_id AND l.gallery_image_id = '$id' WHERE bc.`gallery_id` = '$gallery_id'
                   ORDER BY title";



            $linkresult = mysql_query( $linksql );
            $template   = '<span class="form-checkbox"><input type=checkbox name=%s[] %s value=%s>%s</span>';
            $inner = '';
            while ($linkrow = mysql_fetch_array( $linkresult )) {
                $checked = ( $linkrow['gallery_image_id'] ) ? "checked" : "";
                $inner .= sprintf( $template, $fieldname, $checked, $linkrow['id'], $linkrow['title'] );
            }
            printf( '<tr valign=top><td>%s</td><td><div class="form-checkbox-group">%s</div></td></tr>', $field['name'],
                $inner );
        }

        function get_crumbs()
        {
            return "<a href='galleries.php'>Gallery</a> > <b>{$this->single_name} Admin</b>";
        }
    }

    $template = new gallery();

    $main_page  = 'index.php';
    $main_title = 'Return to main page';
    $admin_tab = "gallery";

    include( "../../../admin/template.php" );
