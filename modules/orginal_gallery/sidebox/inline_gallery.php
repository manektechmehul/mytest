<?php


function GetGallery($id) {
	global $smarty;

	$sql = "select * from gallery where id = $id";

	// echo $sql;

	$row = db_get_single_row($sql);
	$gallery =  array(
		'id' => $id,
		'title' => $row['title'],
		'description' => $row['description']
	);

	$sql = "SELECT * FROM gallery_image WHERE gallery_id = $id and published='1' order by order_num";
	$images = db_get_rows($sql);

	return array($gallery, $images);
}

//echo "start here " . $page_type_row['id'];

// need to get the content item id
// var_dump($page_type_row);



if (($page_type_row['id'] == 0) && (empty($article_name))) {
	// this might mean - normal - not module page
	if ( SITE_HAS_INLINE_GALLERIES ) {
		$galleryId = $content_type_row['gallery_id'];
	}

}else{
	// for modules
	global $name_parts;
	// look up module specific gallery id
	if($page_type_row['path'] == "case_studies"){
		if ( CASESTUDIES_HAS_INLINE_GALLERIES ) {
			$sql       = " SELECT gallery  FROM case_study WHERE page_name = '$name_parts[1]'";
			$galleryId = db_get_single_value( $sql );
		}
	}

	if($page_type_row['path'] == "events") {
		if ( EVENTS_HAS_INLINE_GALLERIES ) {
			$sql       = " SELECT gallery  FROM events WHERE page_name = '$name_parts[1]'";
			$galleryId = db_get_single_value( $sql );
		}
	}

	if($page_type_row['path'] == "news") {
		if ( NEWS_HAS_INLINE_GALLERIES ) {
			$sql       = " SELECT gallery  FROM news WHERE page_name = '$name_parts[1]'";
			$galleryId = db_get_single_value( $sql );
		}
	}
	 if($page_type_row['path'] == "memorybook") {
		if ( MEMORYBOOK_HAS_INLINE_GALLERIES ) {
			$sql       = " SELECT gallery  FROM memorybook WHERE page_name = '$name_parts[1]'";
			$galleryId = db_get_single_value( $sql );
		}
	}
	if($page_type_row['path'] == "booking") {
		if ( BOOKING_HAS_INLINE_GALLERIES ) {
			$sql       = " SELECT gallery  FROM booking WHERE page_name = '$name_parts[1]'";
			$galleryId = db_get_single_value( $sql );
		}
	} 
	if($page_type_row['path'] == "lottery") {
		if ( LOTTERY_HAS_INLINE_GALLERIES ) {
			$sql       = " SELECT gallery  FROM lottery WHERE page_name = '$name_parts[1]'";
			$galleryId = db_get_single_value( $sql );
		}
	} 
	 

}

if ($galleryId) {
	list($gallery,$images) = GetGallery($galleryId);
	$smarty->assign('gallery', $gallery);
	$smarty->assign('images', $images);
}



$galleryTemplateFile = "$base_path/modules/gallery/templates/inline_gallery.tpl";

$filters['inline_gallery'] = array('search_string'  => '/<!-- CS inline gallery start -->(.*)<!-- CS inline gallery end -->/s',
                                'replace_string' => '{if isset($gallery)}{include file="'.$galleryTemplateFile.'"}{/if}');
