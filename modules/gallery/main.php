<?
$self = $page;//'portfolio.php';

$image_id = isset($_REQUEST['image']) ? $_REQUEST['image'] : '';
$page = isset($_REQUEST['page']) ? $_REQUEST['page'] : '';
$gid = isset($_REQUEST['gallery_id']) ? $_REQUEST['gallery_id'] : '';

$image_list = array();

$gallery = array();

$image_root = '/UserFiles/Image/';

if (($image_id != '') && is_numeric($image_id))
{
	$image_sql = "select g1.id, g1.title, g1.imagename, g1.description, g3.id previd, g2.id nextid from gallery_image g1 
left outer join gallery g2 on g2.order_num = (select min(order_num) from gallery_image where published = 1 and gallery_id = $gid and order_num > (g1.order_num))
left outer join gallery g3 on g3.order_num = (select max(order_num) from gallery_image where published = 1 and gallery_id = $gid and order_num < (g1.order_num))
where g1.id = '$image_id' and g1.published = 1 and g1.gallery_id = $gid";
	$image_result = mysql_query($image_sql);
	$image_row = mysql_fetch_array($image_result);
	$image = array();
	$imagepath = $image_root.$image_row['imagename'];
	$image['id'] = $image_row['id'];
	$image['thumb'] = show_thumb($imagepath,7);
	$image['title'] = $image_row['title'];
	$image['description'] = $image_row['description'];
	$image['previd'] = $image_row['previd'];
	$image['nextid'] = $image_row['nextid'];

	$gallery['image'] = $image;
	$gallery['image_id'] = $image_id;
	
	/*
	for ($i = 1; $i <= $pages; $i++)
	{
		$numbers .= " <a href='$self?page=$i'>$i</a>";
	}
	*/
	
	
}
else if ($gid)
{
	$gallery_sql = "select * from gallery where id = $gid";
	$gallery_result = mysql_query($gallery_sql);

	$gallery_row = mysql_fetch_array($gallery_result);
	
	$title = $gallery_row['title'];
 
    // CHANGE BOTH ITEMS BELOW TO ALTER LAYOUT
	$images_per_page = 9;
	$columns_in_page = 3;
	
	$count_sql = "select count(*) as num_images from gallery_image where published = true and gallery_id = $gid ";
	$count_result = mysql_query($count_sql);
	$count_row = mysql_fetch_array($count_result);
	$num_images = $count_row['num_images'];
	$start_image = 0;

	if ($page) 
	{
		if (!is_numeric($page)) 
			$page = 1;
		$start_image = ($page - 1) * $images_per_page;
		if ($start_image > $num_images)
			$start_image = $num_images - ($num_images % $images_per_page);
	}
	else 
		$page = 1;

	$sql = "select id, title, imagename, description, name from gallery_image where published = true and gallery_id = $gid order by order_num"; // limit $start_image, $images_per_page";
	$result = mysql_query($sql);
	
	$column = 1;
	while ($row = mysql_fetch_array($result))
	{
		$image = array();
		$imagepath = $row['imagename'];
		$image['id'] = $row['id'];
		
		$background_image = (BACKGROUND_TYPE == 'dark') ? 'gallerysquare_darkbg.png' : 'gallerysquare_lightbg.png';
		$image['thumb'] = show_thumb($imagepath, 10, '', "alt='{$row['title']}'",  '', $background_image);
		$image['title'] = $row['title'];
		$image['name'] = $row['name'];
		$image['description'] = $row['description'];
		$image['column'] = $column;
		$image['imagepath'] = "/UserFiles/Image/$imagepath";
		$image_list[] = $image;
		$column =  ($column % $columns_in_page) + 1;
	}
	
	
	$image_grid_rows = array();
	$previous_pages_images = array();
	$next_pages_images = array();
	$idx = 1;
	$column = 1;
	$state = 1;
	$count = count($image_list);
	
	echo '<div class="gallerytop"></div>';
	foreach ($image_list as $image )
	{
		if (($idx < ($start_image+1)) || ($idx > ($start_image + $images_per_page)))
		{
			$hidden_row = "<span style='display:none'>";
			$hidden_row .= "<a href='{$image['imagepath']}' title='{$image['title']}' class='galleryimage' rel='gallery'>";
			$hidden_row .= $image['thumb'].'</a></span>';
			if ($idx < $start_image)
				$previous_pages_images[] = $hidden_row;
			else
				$next_pages_images[] = $hidden_row;
			$idx++;
		}
		else
		{
			if ($column == 1) {
				$image_row = '  <div class="gallery-strip">'."\n";
			}
	
			
			$image_row .= '<div class="gallery-box"><div class="gallery-thumb">';
			
			$image_row .= "<a href='{$image['imagepath']}' title='{$image['title']} - {$image['description']}' class='galleryimage border-img' rel='gallery'>";
			$image_row .= $image['thumb'].'<div class="gallery-text">'.$image['title'].'</div></a>';
			$image_row .= '</div>';
			$image_row .= '</div>'."\n";
			
			if (($column == $columns_in_page) || ($idx == $count))
				$image_grid_rows[] = $image_row.'</div>'."\n";
			
			/*
			if (($column == $columns_in_page) || ($idx == $count))
			{	
				for ($i = $column; $i < $columns_in_page; $i++)
					 $image_row .= '<td width="120" class="gallery-thumb"></td>'."\n";
				$image_grid_rows[] = $image_row.'</tr>'."\n";
			}
	*/			


			$column = ($column % $columns_in_page) + 1;
			
			$idx++;
		}
	}

	$pages = ceil($num_images / $images_per_page);

    /*
	echo '<div id="page-nav" class="clear" /> ';
	echo "<a href='gallery.php?page=1'><img id='page-nav-left' src='/images/arrow_left_dis.png' /></a> <div class='page-nav-text'>";
	for ($i = 1; $i <= $pages; $i++)
	{
		echo " <a href='gallery.php?page=$i'>$i</a>";
	}
	echo "</div> <a href='gallery.php?page=1'><img id='page-nav-right' src='/images/arrow_right_dis.png' /></a>";
	echo '</div>';
	*/
	
	$gallery['image_grid_rows'] = $image_grid_rows;
	$gallery['previous_pages_images'] = $previous_pages_images;
	$gallery['next_pages_images'] = $next_pages_images;
	$gallery['images'] = $image_list;
	$gallery['pages'] = $pages;
	$gallery['prevpage'] = $page - 1;
	$gallery['nextpage'] = $page + 1;
	$gallery['gallery_id'] = $gid;
}
else
{	
	$gallery_sql = 'select * from gallery where published = 1 order by order_num';
	$gallery_result = mysql_query($gallery_sql);

	while ($gallery_row = mysql_fetch_array($gallery_result)) 
	{
		$image_sql = "select imagename, title from gallery_image where published = 1 and gallery_id = '{$gallery_row[id]}' order by order_num limit 1";
		$image_result = mysql_query($image_sql);
		$image_row = mysql_fetch_array($image_result);
		echo '<div class="galleryindex-image">';
		//echo '<img width="122" height="122" alt="Buzz" src="/images/gallerythumb.jpg"/>';
		echo "<a href='gallery?gallery_id={$gallery_row['id']}'>";
		$imagepath = $image_row['imagename'];
		echo show_thumb($imagepath, 8, '', "alt='{$image_row['title']}'", '', '', 1);
		echo "</a>";
		//{$gallery_row['title']} gallery?gallery_id={$gallery_row['id']}'>{$gallery_row['title']}
		echo "</div>";
		echo "<div class='galleryindex-text'><h2><a href='gallery?gallery_id={$gallery_row['id']}'>{$gallery_row['title']}</a></h2>";
		echo "<p>{$gallery_row['description']}</p>";
		echo "<p class='csbutton'><a href='gallery?gallery_id={$gallery_row['id']}'>View album</a></p></div>";
		echo "<div class='clearfix'></div>";
	}
}

$gallery['page'] = $page;
$gallery['self'] = $self;
$smarty->assign('gallery', $gallery);   	
$smarty->display('subtemplates/gallery.tpl', $content_type_id); 

?>