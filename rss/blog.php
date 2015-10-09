<?php
session_cache_limiter('must-revalidate');
session_start();

	$blog_page_name = $_GET['blog'];

	include ("../php/databaseconnection.php");
	include ("../php/read_config.php");

//	header('Content-type: application/rss+xml');
	header("Content-Type: application/xml; charset=ISO-8859-1"); 
	$site_address = SITE_ADDRESS;
  
    $blog_sql = "select id, title, strapline, page_name from blogs where page_name = '$blog_page_name' ";
    $blog_result = mysql_query($blog_sql);
    $blog_row = mysql_fetch_array($blog_result);
    $blog_id = $blog_row['id'];
  
    $blog_page_name = db_get_single_value ('select page_name from content c join content_type ct on content_type_id = ct.id '.
        'join page_type pt on page_type = pt.id where pt.name = \'blog\'', 'page_name');
    
  
    $rss_title = $blog_row['title'];
    $rss_description = $blog_row['strapline'];
	$rss_link = "$site_address/$blog_page_name/{$blog_row['page_name']}"; //'/news';
    $rss_xls = '/rss/blog.xsl';
    
    
	$dom = new DOMDocument('1.0');
	$xls = $dom->createProcessingInstruction ("xml-stylesheet", "type=\"text/xsl\" href=\"$rss_xls\"");
	$dom->appendChild($xls);

	$rsselem = $dom->createElement('rss','');
	$rsselem->setAttribute('version', '2.0');
	
	$chanelem = $dom->createElement('channel','');
	$rsselem->appendChild($chanelem);
	
	$itemelem = $dom->createElement('title',$rss_title);
	$chanelem->appendChild($itemelem);
	$itemelem = $dom->createElement('description',$rss_description);
	$chanelem->appendChild($itemelem);
	$itemelem = $dom->createElement('link',$rss_link);
	$chanelem->appendChild($itemelem);
    	
	$post_sql = "select id, title, page_name, posting, UNIX_TIMESTAMP(datestamp) pubDate from blog_posts where blog_id = '$blog_id' order by pubDate desc";
	$post_result = mysql_query($post_sql);
	
  while ($post_row = mysql_fetch_array($post_result))
  {
    $posting_text = strip_tags($post_row["posting"]);

	$ln = 100;
    $len = strlen($posting_text);
		$posting_text = htmlspecialchars(addslashes(preg_replace ("/&.*;|\n|\r|'|\"/U", ' ', $posting_text)));
    $posting_text =  trim(substr($posting_text,0,$ln));
		if ($len > $ln) 
			$posting_text=$posting_text . "...";

    $tend = strpos($posting_text, '  ');
    $title = ($post_row['title']) ? $post_row['title'] : substr($posting_text, 0,$tend);

    $itemelem = $dom->createElement('item','');
	  $elem = $dom->createElement('title', $title);
	  $itemelem->appendChild($elem);
	  $elem = $dom->createElement('pubDate', date('D, d M Y H:i:s', $post_row['pubDate']));
	  $itemelem->appendChild($elem);
	  $elem = $dom->createElement('description',$posting_text);
	  $itemelem->appendChild($elem);

    $post_link = $rss_link.'/'.$post_row['page_name'];

    $elem = $dom->createElement('link',$post_link);
    $itemelem->appendChild($elem);
		$elem = $dom->createElement('guid',$post_link);
		$itemelem->appendChild($elem);
    
	  $chanelem->appendChild($itemelem);
	}
	
	// We insert the new element as root (child of the document)
	$dom->appendChild($rsselem);
	
	
  echo $dom->saveXML();
?>