<?php
$content = ob_get_clean();

//	$smarty->debugging = true;
$smarty->assign('title', $title);
$smarty->assign('sectiontitle', $section_title);
$smarty->assign('content', $content);
$smarty->assign('loggedin', $session_user_id);
$smarty->assign('menu', $menu);
$smarty->assign('membermenu', $membermenu);
$smarty->assign('pageName', $currentPageUrl);
$smarty->assign('promo_image', $promo_image);

if (isset($page) && ($page != 'logout.php'))
    $smarty->assign('self', $_SERVER['REQUEST_URI']);
else
    $smarty->assign('self', 'index.php');

$print_url = "/print{$_SERVER['REQUEST_URI']}";
$smarty->assign('print_url', $print_url);


if(isset($filters)){
    // you already have filter items, add these at the end. Currently just the property search box uses this function
    $temp_filters = $filters;
}


$filters = array(
    // top menu
    'literal' => array('search_string' => '/([{}])/s',
        'replace_string' => '{literal}$1{/literal}'),
    // top menu
    'topmenu' => array('search_string' => '/<!-- CS topnav start -->.*<!-- CS topnav end -->/s',
        'replace_string' => '{include file="subtemplates/topnav.tpl" menu="$menu"}'),
    // top menu - top level only 
    'toponlymenu' => array('search_string' => '/<!-- CS top level nav start -->.*<!-- CS top level nav end -->/s',
        'replace_string' => '{include file="subtemplates/toponlynav.tpl" menu="$menu"}'),
    // side menu
    'sidemenu' => array('search_string' => '/<!-- CS side nav start -->.*<!-- CS side nav end-->/s',
        'replace_string' => '{include file="subtemplates/sidenav.tpl" menu="$menu"}'),
    // side subnav menu
    'sidesubmenu' => array('search_string' => '/<!-- CS side subnav start -->.*<!-- CS side subnav end *-->/s',
        'replace_string' => '{include file="subtemplates/sidesubnav.tpl"}'),
    // content
    'print_url' => array('search_string' => '/<!-- CS print url -->/s',
        'replace_string' => '{$print_url}'),
    // content
    'content' => array('search_string' => '/<!-- CS CONTENT STARTS HERE -->.*<!-- CS CONTENT ENDS HERE -->/s',
        'replace_string' => '{$content}'),
    // title
    'title' => array('search_string' => '/<!-- CS title start -->.*<!-- CS title end -->/s',
        'replace_string' => '{$title}'),
    // section title
    'sidetitle' => array('search_string' => '/<!-- CS side title start -->.*<!-- CS side title end -->/s',
        'replace_string' => '{$sectiontitle}'),
    // -- meta tags
    // title
    'meta_title' => array('search_string' => '/<title>.*<\/title>/s',
        'replace_string' => '{$meta_title}'),
    // description
    'meta_description' => array('search_string' => '/<meta name=\"description\"[^>]*content=\"[^\"]*\"[^>]*>/s',
        'replace_string' => '{$meta_description}'),
    // keywords
    'meta_keywords' => array('search_string' => '/<meta name=\"keywords\"[^>]*content=\"[^\"]*\"[^>]*>/s',
        'replace_string' => '{$meta_keywords}'),
    'header_text' => array('search_string' => '/<!-- cs page header text start -->.*<!-- cs page header text end -->/s',
        'replace_string' => SITE_NAME),
//		'header_text' => array ('search_string'  => '/<!-- cs page header text start -->.*<!-- cs page header text end -->/s',
//		       'replace_string' => '{$meta_title}'),			   
    'sifr' => array('search_string' => '/<!-- cs sifr script start -->(.*)<!-- cs sifr script end -->/s',
        'replace_string' => '{literal}$1{/literal}')
);


if(isset($temp_filters)){
   // add these early setters to the list
    foreach ($temp_filters as &$filter) {
    $random_name = uniqid(md5(rand()), true);
    $filters[$random_name] = $filter;
    }    
}


// Now we can include filters in modules
// ... for now ... RHS "news" type menu
$filters['csmenu'] = array('search_string' => '/<!-- CS submenu start-->.*<!-- CS submenu end-->/s',
    'replace_string' => '{include file="subtemplates/cs_sidenav.tpl" news="$menu"}');
/*
  $filters['promo_image'] = array('search_string'  => '/<!-- CS page image start -->(.*)<!-- CS page image end -->/s',
  'replace_string' => '{if $promo_image} {$promo_image} {else} $1 {/if}');
 */
$filters['promo_image'] = array('search_string' => '/<!-- CS page image start -->(.*)<!-- CS page image end -->/s',
    'replace_string' => '{$promo_image}');


if(strlen(trim($og['thumb'])) > 1 ){
	$ogthumb =  $og['thumb'];
}else{
	$ogthumb =  "/images/social.jpg";
}


$filters['og'] = array('search_string' => '/<!-- CS og start -->(.*)<!-- CS og end -->/s',
'replace_string' => '<meta property="og:title" content="{$title}" />              
<meta property="og:type" content="article"/>
<meta property="og:url" content="{$site_address}{$smarty.server.REQUEST_URI}"/>
<meta property="og:site_name" content="{$smarty.const.SITE_NAME}"/>
<meta property="og:description" content="{$og.description}{$summary}{$description}" />
<meta property="og:image" content="{$site_address}'   . $ogthumb . '" />');



$javascriptStr = '';
if (!empty($javascript)) {
    foreach ($javascript as $javascriptLink) {
        $javascriptStr .= "<script language=\"javascript\" type=\"text/javascript\" src=\"$javascriptLink\" ></script>\n";
    }
}
$smarty->assign('javascriptStr', $javascriptStr);

$filters['javascriptFiles'] = array('search_string' => '/<!-- CS javascript -->/s',
    'replace_string' => "{\$javascriptStr}");

if (!$virtual_page)
    $virtual_page = strip_tags($_SERVER['REQUEST_URI']);
$google_analytics_code = GOOGLE_ANALYTICS_CODE;
$smarty->assign('virtual_page', $virtual_page);
$smarty->assign('google_analytics_code', $google_analytics_code);
$filters['google_analytics'] = array('search_string' => '/<!-- CS google_analytics -->/s',
    'replace_string' => '<script type="text/javascript">
var gaJsHost = (("https:" == document.location.protocol) ? "https://ssl." : "http://www.");
document.write(unescape("%3Cscript src=\'" + gaJsHost + "google-analytics.com/ga.js\' type=\'text/javascript\'%3E%3C/script%3E"));
</script>
<script type="text/javascript">
var pageTracker = _gat._getTracker("{$google_analytics_code}");
pageTracker._initData();
pageTracker._trackPageview();
</script>');


/*  Module sideboxes  */
$modules_sql = 'select * from module';
$modules_result = mysql_query($modules_sql);
$currentModulePath = $module_path;

while ($modules_row = mysql_fetch_array($modules_result)) {
    $mod_constant = $modules_row['constant'];
    
    if (defined($mod_constant) && (constant($mod_constant) != 0)) {
        $module_path = $modules_row['path'];
        $module_row_path = $module_path;
        $sidebox_path = "$base_path/modules/$module_path/sidebox";
       
        if (file_exists($sidebox_path))
            foreach (glob("$sidebox_path/*.php") as $sidebox_include) {
                include ($sidebox_include);                  
            }
          
    }
}

$module_path = $currentModulePath;

$filters['clean-up'] = array('search_string' => '/<!-- CS ([a-zA-Z _]*) start -->(.*)<!-- CS \1 end -->/is','replace_string' => '');
$filters['clean-up2'] = array('search_string' => '/<!-- CS ([a-zA-Z _]*) start-->(.*)<!-- CS \1 end-->/is','replace_string' => '');

function cs_filter($tpl_source, &$smarty) {
    global $filters;
    if (substr($tpl_source, 0, 2) == '<!')
        foreach ($filters as $filter)
            $tpl_source = preg_replace($filter['search_string'], $filter['replace_string'], $tpl_source);

    return $tpl_source;
}

$smarty->register_prefilter('cs_filter');
if (DEBUG_MODE == 'DEBUG')
    $smarty->debugging = true;

if (file_exists("./modules/$module_path/metatags.php")) {
    include ("./modules/$module_path/metatags.php");
} else {
    include ('./php/classes/metatags.php');
    $tags = new tags();
    $tags->get_database_values($link, $content_type_id);
}

$meta_title = "<title>" . $tags->title->value_or_default() . "</title>";
$meta_description = "<meta name=\"description\" lang=\"en\" content=\"" . $tags->description->value_or_default() . "\"/>";
$meta_keywords = "<meta name=\"keywords\" lang=\"en\" content=\"" . $tags->keywords->value_or_default() . "\"/>";

$smarty->assign('meta_title', $meta_title);
$smarty->assign('meta_description', $meta_description);
$smarty->assign('meta_keywords', $meta_keywords);
#echo $template_file; exit;
if ($print_template)
    $template_file = 'print.htm';
if (!isset($template_file) || (!$template_file)) {
    $template_file = 'index.htm';
    if (($tpl_type == 'content') && (file_exists('./templates/content.htm')))
        $template_file = 'content.htm';
    if (($tpl_type == 'portfolio') && (file_exists('./templates/portfolio.htm')))
        $template_file = 'portfolio.htm';
}

if ($_SESSION["session_display_style"] == 'clear')
    $template_file = 'accessibility/clear.htm';
if ($_SESSION["session_display_style"] == 'contrast')
    $template_file = 'accessibility/contrast.htm';

/* * ******** Sub Level Template Override ******************** */
// Override a template on a sub level - by adding it to the templates directory and naming like:
// parentTemplateName-level-2.htm or parentTemplateName-level-3.htm
$_level_sql = "SELECT ct.level
FROM content c
INNER JOIN content_type ct ON c.`content_type_id` = ct.`id`
WHERE c.page_name = '" . $page_name . "'";
$_level = db_get_single_value($_level_sql);

if (intval($_level) > 0) {
    $template_name_head = str_replace('.htm', '', $template_file);
    $sub_template_filename = $template_name_head . '-level-' . ($_level + 1) . '.htm';
    if (file_exists($base_path . '/templates/' . $sub_template_filename)) {
        $template_file = $sub_template_filename;
    }
}

// page not found
if($content_type_id == '7'){
    header("HTTP/1.0 404 Not Found");
}

$smarty->compile_dir = './templates/templates_c';
$smarty->display($template_file, $content_type_id);

if (isset($_GET['debug']) || $show_debug_footer )
{
 include ('debug_footer.php');
}