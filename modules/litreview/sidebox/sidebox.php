<?
// just in case we have loads of items
$num_items_displayed = '50';

$cat_id = 1; // set to default 
if(isset($_GET['cat'])){
    $cat_id = $_GET['cat'];
}


$lit_review_sidebox_sql = "select * from lit_review where published = 1 AND category_id = " . $cat_id . " order by  order_num limit $num_items_displayed";

$lit_review_sidebox_result = mysql_query($lit_review_sidebox_sql);
$lit_review_items = array();
$page_no = 4;
while ($lit_review_sidebox_row = mysql_fetch_array($lit_review_sidebox_result)) {
	$lit_review_items[] = array(
            'title' => $lit_review_sidebox_row['title'],
            'body' => $lit_review_sidebox_row['body'],
            
            'author' => $lit_review_sidebox_row['author'],
            'date_of_publication' => $lit_review_sidebox_row['date_of_publication'],
            'origin' => $lit_review_sidebox_row['origin'],          
            
            'page_no' => $page_no,          
        );
        $page_no = $page_no + 2;
}

/* get Category Name for the Title */
$lit_review_section_title_sql = "SELECT `name` FROM lit_review_categories WHERE id = "  .  $cat_id;
$lit_review_cat_title = db_get_single_value($lit_review_section_title_sql, 'name');
$smarty->assign('current_cat_title',$lit_review_cat_title);






/* make the index page  */
$smarty->assign('lit_review_index', $lit_review_items);
$lit_review_index_sidebox_template_file = "$base_path/modules/litreview/templates/book_index.tpl";
$filters['lit_review_index_filter'] = array ('search_string'  => '/<!-- CS litreview book index start  -->.*<!-- CS litreview book index end  -->/s',
       'replace_string' => '{include file="'. $lit_review_index_sidebox_template_file .'"}');


/* make the book pages  */
$smarty->assign('lit_review_pages', $lit_review_items);
$lit_review_pages_sidebox_template_file = "$base_path/modules/litreview/templates/book_pages.tpl";
$filters['lit_review_pages_filter'] = array ('search_string'  => '/<!-- CS litreview pages start  --> .*<!-- CS litreview pages end  -->/s',
       'replace_string' => '{include file="'. $lit_review_pages_sidebox_template_file .'"}');







?>