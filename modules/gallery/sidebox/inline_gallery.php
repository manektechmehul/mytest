<?php
    //define( "GALLERY_HAS_CATEGORIES", "0" );
    function GetGallery( $id )
    {
        if (GALLERY_HAS_CATEGORIES) {
            return getCategoryGallery( $id );
        } else {
            return getSimpleGallery( $id );
        }
    }

    function getCategoryGallery( $id )
    {
        $sql     = "select * from gallery where id = $id";
        $row     = db_get_single_row( $sql );
        $gallery = array(
            'id' => $id,
            'title' => $row['title'],
            'description' => $row['description']
        );
        $get_cats = " SELECT CONCAT('cat-', `id`) AS cats, title FROM  `gallery_category`
   WHERE id IN (SELECT DISTINCT gallery_category_id FROM `gallery_category_lookup` gcl
   INNER JOIN gallery_image gi ON gi.gallery_id
   WHERE gallery_id = $id) AND gallery_id = $id  ORDER BY order_num ";
        $cats = db_get_rows( $get_cats );
        // get images and include categories (cats)
        $sql    = "SELECT *, (SELECT GROUP_CONCAT( CONCAT('cat-',gallery_category_id) SEPARATOR ' ') FROM `gallery_category_lookup`
WHERE gallery_image_id = gi.id) AS cats FROM gallery_image gi WHERE gallery_id = $id and published='1' order by order_num";
        $images = db_get_rows( $sql );

        return array( $gallery, $images, $cats );
    }

    function getSimpleGallery( $id )
    {
        $sql     = "select * from gallery where id = $id";
        $row     = db_get_single_row( $sql );
        $gallery = array(
            'id' => $id,
            'title' => $row['title'],
            'description' => $row['description']
        );
        $sql     = "SELECT * FROM gallery_image WHERE gallery_id = $id and published='1' order by order_num";
        $images  = db_get_rows( $sql );

        return array( $gallery, $images );
    }


    if (( $page_type_row['id'] == 0 ) && ( empty( $article_name ) )) {
        // this might mean - normal - not module page
        if (SITE_HAS_INLINE_GALLERIES) {
            $galleryId = $content_type_row['gallery_id'];
        }
    } else {
        // for modules
        global $name_parts;
        // look up module specific gallery id
        if ($page_type_row['path'] == "case_studies") {
            if (CASESTUDIES_HAS_INLINE_GALLERIES) {
                $sql       = " SELECT gallery  FROM case_study WHERE page_name = '$name_parts[1]'";
                $galleryId = db_get_single_value( $sql );
            }
        }
        if ($page_type_row['path'] == "events") {
            if (EVENTS_HAS_INLINE_GALLERIES) {
                $sql       = " SELECT gallery  FROM events WHERE page_name = '$name_parts[1]'";
                $galleryId = db_get_single_value( $sql );
            }
        }
        if ($page_type_row['path'] == "news") {
            if (NEWS_HAS_INLINE_GALLERIES) {
                $sql       = " SELECT gallery  FROM news WHERE page_name = '$name_parts[1]'";
                $galleryId = db_get_single_value( $sql );
            }
        }
        if ($page_type_row['path'] == "memorybook") {
            if (MEMORYBOOK_HAS_INLINE_GALLERIES) {
                $sql       = " SELECT gallery  FROM memorybook WHERE page_name = '$name_parts[1]'";
                $galleryId = db_get_single_value( $sql );
            }
        }
        if ($page_type_row['path'] == "booking") {
            if (BOOKING_HAS_INLINE_GALLERIES) {
                $sql       = " SELECT gallery  FROM booking WHERE page_name = '$name_parts[1]'";
                $galleryId = db_get_single_value( $sql );
            }
        }
        if ($page_type_row['path'] == "lottery") {
            if (LOTTERY_HAS_INLINE_GALLERIES) {
                $sql       = " SELECT gallery  FROM lottery WHERE page_name = '$name_parts[1]'";
                $galleryId = db_get_single_value( $sql );
            }
        }
    }
    if ($galleryId) {
        if (GALLERY_HAS_CATEGORIES) {
            list( $gallery, $images, $cats ) = GetGallery( $galleryId );
            $smarty->assign( 'gallery', $gallery );
            $smarty->assign( 'images', $images );
            $smarty->assign( 'cats', $cats );
            $galleryTemplateFile = "$base_path/modules/gallery/templates/multiple_category.tpl";
        } else {
            list( $gallery, $images ) = GetGallery( $galleryId );
            $smarty->assign( 'gallery', $gallery );
            $smarty->assign( 'images', $images );
            $galleryTemplateFile = "$base_path/modules/gallery/templates/inline_gallery.tpl";
        }
    }
    $filters['inline_gallery'] = array(
        'search_string' => '/<!-- CS inline gallery start -->(.*)<!-- CS inline gallery end -->/s',
        'replace_string' => '{if isset($gallery)}{include file="' . $galleryTemplateFile . '"}{/if}'
    );
