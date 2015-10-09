<?php
	include_once "$base_path/modules/$module_path/conf.php";
	$levels = count( $name_parts );
	$smarty->assign( 'pageName', $currentPageUrl );



// if something link www.mywebsite.com/case-studies
	if ( $levels == 1 ) {
		// output any cms content for page
		echo $content_row['body'];


		// echo out smarty block for the search box
		$smarty->assign( 'module_url', $currentPageUrl );
	 	$content_template_file = "$base_path/modules/$module_path/templates/homesearch.tpl";
	 	$smarty->display( "file:$content_template_file" );


		/* regular module functionality
		$moduleObject->SetCategoryForPage( 'Main' );
		if ( $moduleObject->has_categories ) {
			$moduleObject->DisplayFeatureList();
		} else {
		// if there are no categories, we must display all - else how will a a user find them
		$moduleObject->DisplayList();
		}
		*/
	} else if ( $levels == 2 ) {
		// echo 'levels is 2 ';
		$pageName = $name_parts[1];
		if ( $pageName == 'populate' ) {
			$moduleObject->insertRandomData( 30 );
		}
		// if it's an integer - display the single view ... else list the category
		// look up the item - check if it is a valid category name - if so process like a cat else like a single item
		$is_category_page = $moduleObject->isCategoryPage( $name_parts[1] );
		if ( ! $is_category_page ) {
			// if we are not looking a category - check if we have a search system in place
			if ( constant( $module_prefix . "HAS_SEARCH" ) ) {
				if ( $name_parts[1] == 'all' ) // $this->createSearchBox();
				{
					$moduleObject->DisplayAll();
				} else if ( $name_parts[1] == 'results' ) {
					// Get the data
					$searchKeywords = $_GET['keywords'];
					$category       = $_GET['category'];
					// clean the data
					$searchKeywords = preg_replace( '/[^a-z^A-Z]/', '', $searchKeywords );
					//$category = is_numeric($category) ? 0 : $category;
					$moduleObject->DisplaySearchResults( $searchKeywords, $category );
				} else {
					$memories = $moduleObject->getMemories( $pageName );
					$smarty->assign( 'memories', $memories );
					$moduleObject->DisplayItem( $pageName );
					$og = $moduleObject->GetOGData( $pageName, '' );
					$smarty->assign( 'og', $og );
					$title = $moduleObject->GetTitle( $pageName );
					// do this if we are allowing comments for this profile
					$allow_comments = db_get_single_value( 'SELECT allow_comments FROM memorybook WHERE page_name = "' . $pageName .  '" ');

					if ( $allow_comments == "1" ) {
						// todo: only do this if comments are allowed !!!
						// Submit the comments
						include_once( $base_path . "/php/classes/auto_form.php" );
						$form = new auto_form( 13 );
						$form->get_data();
						$submit = ( isset( $_POST['Submit'] ) ) ? $_POST['Submit'] : "";
						if ( $submit ) {
							$form->validate_data();
							if ( $form->has_errors() ) {
								$form->display_errors();
								$smarty->assign( 'form_display', $form_display );
							} else {
								$form->process_data( $page_name );
								$_SESSION['form'] = $form;
								$moduleObject->add_memory( $_POST['FirstName'] . ' ' . $_POST['Surname'], $_POST['memory'], $_POST['email'], $pageName );
								$smarty->assign( 'memory_added', true );
							}
						} else {
							$form->display_form( true );
						}
					} // end allow comments
				}
			} else {
				// if no search just output the appropriate item
				$moduleObject->DisplayItem( $pageName );
				$og = $moduleObject->GetOGData( $pageName );
				$smarty->assign( 'og', $og );
				$title = $moduleObject->GetTitle( $pageName );
			}
		} else {
			$moduleObject->SetCategoryForPage( $name_parts[1] );
			$moduleObject->DisplayList( $name_parts[1] );
			$title .= ': ' . $moduleObject->GetCategoryTitle( $moduleObject->currentCategory );
		}
	}
	//$moduleObject->createSearchBox();