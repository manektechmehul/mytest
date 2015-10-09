<?php

function GetDonate($id) {
	global $smarty;
	$sql = "select * from donate where id = $id";
	$row = db_get_single_row($sql);
	return array($row);
}

function build_fixed_values_select($str){
	$out = "<select name='fixed_values' id='fixed_values'>";
	$out.= "<option value='0'>Choose your donation amount</option>";

	$fa = explode("\n", $str);
	foreach($fa as $a){
		$item = explode(":", $a);
		$out.= "<option value='$item[0]'>£$item[0] > $item[1]</option>";

	}

	$out.= "</select>";
	return $out;
}



	if (($page_type_row['id'] == 0) && (empty($article_name))) {
	// this might mean - normal - not module page
	if ( SITE_HAS_DONATE ) {
		//echo " checking for donate options ";
		$donateId = $content_type_row['donate_id'];
	}
}else{
	// for modules
	global $name_parts;
	// look up module specific gallery id
	if($page_type_row['path'] == "case_studies"){
		if ( SITE_HAS_DONATE ) {
			$sql       = " SELECT donate  FROM case_study WHERE page_name = '$name_parts[1]'";
			$donateId = db_get_single_value( $sql );
		}
	}

	if($page_type_row['path'] == "news"){
		if ( SITE_HAS_DONATE ) {
			$sql       = " SELECT donate  FROM news WHERE page_name = '$name_parts[1]'";
			$donateId = db_get_single_value( $sql );
		}
	}

	if($page_type_row['path'] == "booking"){

		if ( SITE_HAS_DONATE ) {
			$sql       = " SELECT donate  FROM booking WHERE page_name = '$name_parts[1]'";
			$donateId = db_get_single_value( $sql );
		}
	}

}
if ($donateId) {
	$row = GetDonate( $donateId );

	if($row[0] != false){
		if($row[0]['fixed_values_display'] == 1){
			// build the fixed value select
			$fixedvalues = build_fixed_values_select($row[0]['fixed_values']);
			$smarty->assign( 'fixedvalues', $fixedvalues );
		}



		$smarty->assign( 'donate', $row[0] );
		$smarty->assign( 'donate_location', $_SERVER['REQUEST_URI'] );
		$donateTemplateFile       = "$base_path/modules/donate/templates/single.tpl";
		$filters['inline_donate'] = array( 'search_string' => '/<!-- CS donate start -->(.*)<!-- CS donate end -->/s',
			'replace_string' => '{if isset($donate)}{include file="' . $donateTemplateFile . '"}{/if}' );
	}else{
		echo "Error. The donation type associated with this page no longer exists.";
	}
}
