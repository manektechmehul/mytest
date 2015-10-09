<?php

$shop_page_type = 'home';
if (is_array($name_parts))
{
    $part_count = count($name_parts);
    $maker_id = 0;
    if ($part_count > 1)
    {
        $maker_id = db_get_single_value("select id from shop_maker where page_name = '{$name_parts[1]}'", 'id');
    }
    
}


if ($maker_id)
{
    $sql = "select * from shop_maker where id = $maker_id";
    $result = mysql_query($sql);
    $row = mysql_fetch_array($result);
    $title = $row['name'];
    $maker = array (
        'name' => $row['name'],
        'description' => $row['description'],
        'thumbnail' => $row['thumbnail'],
        'page_name' => $row['page_name'],
    );
    
    $smarty->assign('maker', $maker);
    $content_template_file = "$base_path/modules/$module_path/templates/craftsperson.tpl";
}
else
{
    $sql = "select * from shop_maker where special = 0 and published = 1";
    $result = mysql_query($sql);
    $makers = array();
        
    while ($row = mysql_fetch_array($result))
    {
        $thumb = $row['thumbnail'];
        if ($thumb)
             $thumb = get_thumb_address($thumb, 8);
        else
            $thumb = '/cmsimages/missing-crafts-people-image.png';

        $makers[] = array (
            'name' => $row['name'],
            'summary' => $row['summary'],
            'thumbnail' => $thumb,
            'link' => $page_name . '/'. $row['page_name'],
        );
    }
    $smarty->assign('makers', $makers);
    //echo "$base_path/modules/$module_path/templates/craftspeople.tpl";
    $content_template_file = "$base_path/modules/$module_path/templates/craftspeople.tpl";
}

$smarty->display("file:$content_template_file"); 

?>
