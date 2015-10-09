<?php

    include '../../../admin/classes/template.php';

    class categories extends template
    {

        function categories()
        {
            $this->template();
           // $this->debug_log = true;
          //  $this->php_debug = true;
            /* #module specific */
            $this->table          = 'gallery_category';
            $this->group_name     = 'Categories';
            $this->single_name    = 'Category';
            $this->singular       = 'a';

            $gallery_id  = $_GET['gallery_id'];
            $this->parent_id      = $gallery_id;
            $this->parent_id_name = "gallery_id";
            $this->parent_field = 'gallery_id';

            $this->ordered   = true;

            /* #module specific */
            $this->custom_list_sql = 'select * from gallery_category where gallery_id = ' . $gallery_id . ' order by order_num ';
            /* #module specific */
            //   $this->list_bottom_text = sprintf("<a href=\"main.php\" onMouseOut=\"MM_swapImgRestore()\" onMouseOver=\"MM_swapImage('defaultbacktodonations','','/admin/images/buttons/cmsbutton-Back_to_Donations-over.gif',0)\"><img style='border:none' src='./images/buttons/cmsbutton-Back_to_Donations-off.gif' name='defaultbacktodonations'></a>", $PHP_SELF);
            $this->fields = array(
                'title' => array(
                    'name' => 'Title',
                    'formtype' => 'text',
                    'list' => true,
                    'required' => true,
                    'primary' => true
                ),

            );
        }

        function get_crumbs( $page )
        {
            /* #module specific */
            return "<a href='galleries.php'>Gallery Admin</a> > <b>Categories</b>";
        }
    }

    $template = new categories();
    /* #module specific */
    $admin_tab        = "gallery";
    $second_admin_tab = "categories";
    include 'second_level_navigation.php';
    include( "../../../admin/template.php" );
?>


