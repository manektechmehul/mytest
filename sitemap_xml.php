<?php
//  test comment
include 'php/databaseconnection.php';
include 'php/read_config.php';

function makeLoc($basePage, $page) {
    if (!empty($basePage))
        $basePage .= '/';
    if ($page == '/')
        $page = '';

    $siteAddress = SITE_ADDRESS;
    if (substr($siteAddress, -1, 1) !== '/')
        $siteAddress .= '/';
    $out = sprintf("<url>\n<loc>%s</loc>\n</url>\n", $siteAddress . $basePage . $page);
    return $out;
}

function getPageData() {
    $sql = "select * from content c
	                join content_type ct on ct.id = content_type_id
	                join section s on s.id = c.section_id
	                where section_type_id = '1'  AND s.status = '1' and template_type = 'main_body' ORDER BY s.order_num, ct.order_num";

    return db_get_rows($sql);
}

function outputPageData() {
    $pages = getPageData();
    foreach ($pages as $page) {
        echo makeLoc('', $page['page_name']);
    }
}

function getPageNameForPageType($type) {
    $newsPageType = db_get_single_value("select id from page_type where module_or_type = '$type'");
    return db_get_single_value("select page_name from content c join content_type ct on ct.id = content_type_id where template_type = 'main_body' and page_type = '{$newsPageType}'");
}

function getNewsPageData() {
    $pageName = getPageNameForPageType('news');
    // If the site has page_names for news
    // $sql="select page_name from news where published = 1";
    // otherwise
    $sql = "select page_name from news where published = 1";
    return array($pageName, db_get_rows($sql));
}

function outputNewsPageData() {
    list($basePageName, $pages) = getNewsPageData();
    foreach ($pages as $page) {
        echo makeLoc($basePageName, $page['page_name']);
    }
}



function getEventsPageData() {
    $pageName = getPageNameForPageType('events');
    $sql = "select page_name from events where published = 1 and now() < enddate";
    return array($pageName, db_get_rows($sql));
}

function outputEventsPageData() {
    list($basePageName, $pages) = getEventsPageData();
    foreach ($pages as $page) {
        echo makeLoc($basePageName, $page['page_name']);
    }
}

function getCaseStudiesPageData() {
    $pageName = getPageNameForPageType('case studies');
    $sql = "select page_name from case_study where published = 1";
    return array($pageName, db_get_rows($sql));
}

function outputCaseStudiesPageData() {
    list($basePageName, $pages) = getCaseStudiesPageData();
    foreach ($pages as $page) {
        echo makeLoc($basePageName, $page['page_name']);
    }
}

function getDocumentsPageData() {
    $pageName = getPageNameForPageType('documents');
    $sql = "SELECT DISTINCT dc.* FROM documents d 
INNER JOIN documents_category_link dcl ON dcl.`documents_id` = d.id
INNER JOIN documents_category dc ON dc.id = dcl.`documents_category_id`
WHERE d.`published` = 1 ORDER BY order_num";
    return array($pageName, db_get_rows($sql));
}

function outputDocumentsPageData() {
    list($basePageName, $pages) = getDocumentsPageData();
    foreach ($pages as $page) {
        echo makeLoc($basePageName, $page['page_name']);
    }
}

function getVideosPageData() {
    $pageName = getPageNameForPageType('video');
    $sql = "select page_name from video where published = 1";
    return array($pageName, db_get_rows($sql));
}

function outputVideosPageData() {
    list($basePageName, $pages) = getVideosPageData();
    foreach ($pages as $page) {
        echo makeLoc($basePageName, $page['page_name']);
    }
}

function getBlogsPageData() {
    $pageName = getPageNameForPageType('blogs');
    $sql = "select page_name from blogs where published = 1";
    return array($pageName, db_get_rows($sql));
}

function outputBlogsPageData() {
    list($basePageName, $pages) = getBlogsPageData();
    foreach ($pages as $page) {
        echo makeLoc($basePageName, $page['page_name']);
    }
}


 
function outputShopPageData() {
    
    $basePageName = getPageNameForPageType('shop');
    $sql = "SELECT sp.page_name FROM shop_product sp
INNER JOIN shop_product_category spc ON spc.`shop_product_id` = sp.`id` 
INNER JOIN shop_category sc ON sc.id = spc.`shop_category_id`
WHERE 
sp.published = 1
AND 
sc.id IN (
SELECT sc.id FROM shop_category sc
INNER JOIN shop_category sc2 ON sc2.id = sc.`parent_id`
WHERE sc2.online = 1 AND sc.online = 1
) GROUP BY page_name"; // in a category that is on !
        
    $pages = db_get_rows($sql);      
    foreach ($pages as $page) {
        echo makeLoc($basePageName, $page['page_name']);
    }
}
 
function  outputProfilesPageData() {
    
    $basePageName = getPageNameForPageType('profiles');
    $sql = "SELECT p.page_name FROM profiles p
INNER JOIN profiles_category_lookup pcl ON pcl.item_id = p.id
WHERE p.published = 1 
GROUP BY page_name";  
        
    $pages = db_get_rows($sql);      
    foreach ($pages as $page) {
        echo makeLoc($basePageName, $page['page_name']);
    }
}

function getPropertyPageData() {
    $pageName = getPageNameForPageType('property');
    echo "<pre>"; print_r($pageName); exit;
    // If the site has page_names for news
    // $sql="select page_name from news where published = 1";
    // otherwise
    $sql = "select page_name from property where published = 1";
    return array($pageName, db_get_rows($sql));
}

function outputPropertyPageData() {
    list($basePageName, $pages) = getPropertyPageData();
    foreach ($pages as $page) {
        echo makeLoc($basePageName, $page['page_name']);
    }
}
 



echo '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
echo '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . "\n";
echo '<!-- created automatically by Creative Stream Site Manager www.creativestream.co.uk -->' . "\n";


outputPageData();
if (SITE_HAS_NEWS) {
    outputNewsPageData();
}
if (SITE_HAS_EVENTS) {
    outputEventsPageData();
}
if (SITE_HAS_CASE_STUDIES) {
    outputCaseStudiesPageData();
}
if (SITE_HAS_DOCUMENTS) {
    outputDocumentsPageData();
}
if (SITE_HAS_VIDEO) {
    outputVideosPageData();
}
if (SITE_HAS_BLOG) {
    outputBlogsPageData();
}
if (SITE_HAS_PROFILES) {
    outputProfilesPageData();
}

// need to add shop
// 
// SITE_HAS_GALLERY
if(SITE_HAS_SHOP){
    outputShopPageData();
} 
//
if(SITE_HAS_PROFILES){
     outputProfilesPageData();
}
 // do at a later stage :-)
//SITE_HAS_LITREVIEW 
//SITE_HAS_PROPERTY 
if(SITE_HAS_PROPERTY){    
     outputPropertyPageData();
}

echo "</urlset>";