<?php
session_cache_limiter('must-revalidate');
session_start();

	include ("../php/databaseconnection.php");
	include ("../php/read_config.php");

//	header('Content-type: application/rss+xml');
	header("Content-Type: application/xml; charset=ISO-8859-1"); 
	$site_address = SITE_ADDRESS;
  
	$rss_title = SITE_NAME.' Events';
	$rss_description = SITE_NAME.' Events';
	$rss_link = $site_address.'/events';
	$rss_xls = 'news.xsl';

	$dom = new DOMDocument('1.0');
	$xls = $dom->createProcessingInstruction ("xml-stylesheet", "type=\"text/xsl\" href=\"$rss_xls\"");
	$dom->appendChild($xls);

	$rsselem = $dom->createElement('rss','');
	$rsselem->setAttribute('version', '2.0');
	
	$chanelem = $dom->createElement('channel','');
	$rsselem->appendChild($chanelem);
	
	$post_sql = "select id, title, page_name, body as description, unix_timestamp(startdate) date_of_event from events where published = 1";
	$post_result = mysql_query($post_sql);
	
	$itemelem = $dom->createElement('title',$rss_title);
	$chanelem->appendChild($itemelem);
	$itemelem = $dom->createElement('description',$rss_description);
	$chanelem->appendChild($itemelem);
	$itemelem = $dom->createElement('link',$rss_link);
	$chanelem->appendChild($itemelem);
	
  while ($post_row = mysql_fetch_array($post_result))
  {
    $posting_text = strip_tags($post_row["description"]);

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
	  $elem = $dom->createElement('pubDate', date('D, d M Y H:i:s', $post_row['date_of_event']));
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