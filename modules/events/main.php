<?php
include_once 'conf.php';
    
	$levels = count($name_parts);

	$smarty->assign('pageName', $currentPageUrl);

    if ($levels == 1)
    {
        echo $content_row['body'];
        $moduleObject->DisplayList();
	}
    else if ($levels == 2)
    {
        $pageName = $name_parts[1];
        $moduleObject->DisplayItem($pageName);
        $og = $moduleObject->GetOGData($pageName, '/images/sharelogo.jpg');
		$smarty->assign('og', $og);
 		$smarty->assign('EVENTS_HAS_THUMBNAIL',EVENTS_HAS_THUMBNAIL);   
        $title = $moduleObject->GetTitle($pageName);
    }


