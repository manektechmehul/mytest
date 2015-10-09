<?php

session_cache_limiter('must-revalidate');

session_start();


// Changes to remove need for register globals and avoid warnings
//  -- start
$admin_tab = "";

// Get Session variables
$session_user_id =  (isset($_SESSION['session_user_id'])) ? $_SESSION['session_user_id'] : "";
$session_user_type_id = (isset($_SESSION['session_user_type_id'])) ? $_SESSION['session_user_type_id'] : "";
$session_access_to_cms = (isset($_SESSION['session_access_to_cms'])) ? $_SESSION['session_access_to_cms'] : "";
// end --

?>
<html>
<head>
<title>Administration</title>
<link href="./css/adminstylesheet.css" rel="stylesheet" type="text/css" />
<link href="http://fonts.googleapis.com/css?family=Gafata" rel="stylesheet" type="text/css">
</head>
<body><?php

unset($_SESSION["session_section_id"]);
unset ($session_section_id);


$path_prefix = "..";

include ($path_prefix . "/php/databaseconnection.php");
include ($path_prefix . "/php/read_config.php");
include ("./cms_functions_inc.php");

$admin_tab = "help";

$use_admin_header = "1";
include ("./process_login_inc.php");
include ("./admin_header_inc.php");

if (($session_user_id) && ($session_access_to_cms))
{

	printf ("<h1>Help</h1>");
	?>

<h1> Content Management System (CMS) </h1>
<p> The content management system (CMS) allows you to add, change and delete the content in the pages of your web site. Admin users are allowed to make changes to all pages. Member users can only change those pages for which they have been given permission by an Admin user. </p>
<p> The instructions below will explain how to add or edit content within the web site:</p>
<ul>
  <li><a href="#introduction">Introduction</a></li>
  <li><a href="#main_sections">Main Sections</a> </li>
  <li><a href="#sub_sections">Sub-sections</a></li>
  <li> <a href="#articles">Articles</a></li>
  <li><a href="#view_uploaded_images">View your uploaded images</a> </li>
  <li><a href="#diary_events">Diary events</a> </li>
</ul>
<h3><br>
<a name="introduction"></a>Introduction </h3>
<p>1. Click on the &lsquo;Content Admin&rsquo; tab at the top of the page. You will be shown a list of the pages that you are able to edit within the web site. </p>
<p> <center><img src="./images/content_admin_shot1.gif" ></center></p>
<p> 2. Please note that each page is made up of content that: </p>
<ul>
  <li>  appears in the main body of the page </li>
  <li> articles that are listed beneath this </li>
</ul>
<p> The content in the main body of the page usually gives introductory or&nbsp;background information. Articles that appear below it are usually updates, latest news, etc. related to the main page&rsquo;s topic. </p>
<p> From the content management tab you can: </p>
<ul>
  <li> Create new main sections </li>
  <li> Edit main sections </li>
  <li> Create New sub-sections </li>
  <li> Edit, Hide, and Delete Sub-sections </li>
  <li> Select Editors for Sub-sections </li>
  <li> Add events to the Diary section </li>
  <li> Add articles to subsections </li>
  <li> View your uploaded images </li>
</ul>
<p></p>
<br>
<h3><a name="main_sections"></a>Main Sections</h3>
<p><strong> Editing the main body of a page: </strong></p>
<p> Each of the pages of your website will be listed down the left side of the page. Each page will have a series of buttons associated with it.</p>
<p> If you wish to edit the content within the main body of a page on your website: </p>

<p> 1. Click on the &lsquo;edit page&rsquo; button. </p>
<table border="0" cellspacing="0" cellpadding="4">
  <tr>
    <td><img src="./images/content_admin_edit.gif" hspace=4 vspace=4 align=left></td>
    <td>You will be taken to a new page with an editor window in which you can edit the content of the page of your website. This interface works along the same lines as familiar word processing software. </td>
  </tr>
</table>
<p>2. You can use the editor toolbar to format the text, ie. bold, italics, bullet lists etc. </p>
<p align="center"><img src="images/content_admin_wysiwyg_toolb.gif"></p>
<p>Please <a href="#Adding_images">see below for instructions on how to add images to the page</a>. </p>
<p> 3. Press the &lsquo;Submit&rsquo; button when you are finished to&nbsp;post&nbsp;the content into the web site. </p>
<p><strong> &nbsp;</strong></p>
<p><strong> Adding a new main section: </strong></p>
<p>If your CMS includes the facility to create a new main section you will see at the bottom of the list of pages is a &ldquo;Create a new main section&rdquo; button which allows you to create an entirely new main section. </p>
<p>&nbsp; </p>
<table border="0" cellspacing="0" cellpadding="4">
  <tr>
    <td>1. Click on the &ldquo;Create a new main section&rdquo; button. </td>
    <td><img src="images/content_admin_create_new_main_section.gif"></td>
  </tr>
</table>
<p>You will be taken to a page which allows you to enter the title and position order number for the new section. </p>
<p> 2. Enter a title for the section in the &ldquo;Name of the new main section&rdquo; field. </p>
<p> At the bottom of the page on the left hand side is list of sections and their position order numbers. This shows the order that sections will be displayed in the toolbar. The order number you choose will determine where in the toolbar the new section will go. If you want the new section to be between two existing sections you should choose an order number that is between the order numbers of those two sections. </p>
<p> 3. Enter the position order number in the &ldquo;Order number of item within the toolbar&rdquo; field. </p>
<p> (Tip: Choose order numbers that are multiples of 10 i.e. 10, 20, 30 as this will allow you to add new sections inbetween at a later date) </p>
<p> 4. Click Submit to complete the creation of the new section.</p>
<h3>&nbsp;</h3>
<h3><a name="sub_sections"></a>Sub Sections:</h3>
<p><strong> Adding a new sub section : </strong></p>
<p> Underneath each section in the list is a &ldquo;Create a new sub section&rdquo; button which allows you to add new sub-sections within the main section.</p>
<table border="0" cellspacing="0" cellpadding="4">
  <tr>
    <td>1. Click on the &ldquo;Create a new sub-section&rdquo; button. </td>
    <td><img src="images/content_admin_create_new_sub_section.gif"></td>
  </tr>
</table>
<p> You will be taken to a page which allows you to enter the title and position order number for the new sub-section. </p>
<p> 2. Enter a title for the sub-section in the &ldquo;Name of the new sub-section&rdquo; field. </p>
<p> If there are already sub-sections for the main section then at the bottom of the page on the left hand side is list of sub-sections and their position order numbers. This shows the order that sub-sections will be displayed in the toolbar. The order number you choose will determine where in the toolbar the new sub-section will go. If you want the new sub-section to be between two existing sub-sections you should choose an order number that is between the order numbers of those two sub-sections. </p>
<p> 3. Enter the position order number in the &ldquo;Order number of item within the toolbar&rdquo; field. </p>
<p> (Tip: Choose order numbers that are multiples of 10 i.e. 10, 20, 30 as this will allow you to add new sub-sections in between at a later date.) </p>
<ul>
  <li> Click Submit to complete the creation of the new sub-section. </li>
</ul>
<p><strong> &nbsp;</strong></p>
<p><strong> Hide page: </strong></p>
<p> From the Content Admin page:</p>
<table border="0" cellspacing="0" cellpadding="4">
  <tr>
    <td>1. Click on the &ldquo;hide page&rdquo; button to the right of the sub-section page you wish to hide. </td>
    <td><img src="images/content_admin_hide_sub_section.gif"></td>
  </tr>
</table>
<p> 2. Select &ldquo;yes&rdquo; to confirm that you want to hide the page or &ldquo;no&rdquo; to cancel hiding the page. </p>
<p><strong> <br>
Show page: </strong></p>
<p> From the Content Admin page:</p>
<table border="0" cellspacing="0" cellpadding="4">
  <tr>
    <td>1. Click on the &ldquo;show page&rdquo; button to the right of the sub-section page you wish to show. </td>
    <td><img src="images/content_admin_show_sub_sect.gif"></td>
  </tr>
</table>
<p> 2. Select &ldquo;yes&rdquo; to confirm that you want to show the page or &ldquo;no&rdquo; to keep the page hidden. </p>
<p><strong> &nbsp;</strong></p>
<p><strong> Select editors: </strong></p>
<p> An admin user can configure which Member users may edit each sub-section page. </p>
<p> From the Content Admin page:</p>
<table border="0" cellspacing="0" cellpadding="4">
  <tr>
    <td>1. Click on the &ldquo;select editors&rdquo; button to the right of the sub-section page you wish to manage editors for. </td>
    <td><img src="images/content_admin_editors.gif"></td>
  </tr>
</table>
<p> 2. Select those users whom you will allow to  edit the sub-section by checking the box against their name. </p>
<p> 3. Click Submit to save the changes. </p>
<p><strong> <br>
Delete page: </strong></p>
<p> From the Content Admin page:</p>
<table border="0" cellspacing="0" cellpadding="4">
  <tr>
    <td>1. Click on the &ldquo;delete page&rdquo; button to the right of the sub-section page you wish to delete. </td>
    <td><img src="images/content_admin_delete_page.gif"></td>
  </tr>
</table>
<p> 2. Select &ldquo;yes&rdquo; to confirm that you want to delete the page or &ldquo;no&rdquo; to keep the page. </p>
<p><em> Please note that once you delete the page you will not be able to retrieve it. <br>
</em></p>
<h3><a name="articles"></a>Articles</h3>
<p> The articles attached to a page are listed below the page's main content. </p>
<p><strong> Add an article: </strong></p>
<p> From the Content Admin page:</p>
<table border="0" cellspacing="0" cellpadding="4">
  <tr>
    <td>1. Click on the add/edit articles button to the right of the sub-section page you wish to add the article to. </td>
    <td><img src="images/content_admin_articles.gif"></td>
  </tr>
</table>
<p> The page will now show a link to add a new article and a list of any existing articles. </p>
<p> 2. Click on the &ldquo;Add an article to the page&rdquo; link </p>
<p> 3. Enter title of the article </p>
<p> 4. Enter the body content </p>
<p> 5. Add a thumbnail image if required. Note the thumbnail image will not be shown in this page, the list of articles or the preview. </p>
<p> 6. Uncheck Auto Resize if you do not want the thumbnail image to be resized </p>
<p> 7. Select the Template Type to determine the position of the thumbnail image within the page that will list the articles. (If &ldquo;no image&rdquo; is selected the thumbnail image will not be displayed) </p>
<p> 8. Specify where this article should appear in the list of articles. </p>
<p> 9. Select a status of &ldquo;Live&rdquo; to make the article visible or &ldquo;Hidden&rdquo; to not display the article </p>
<p> 10. Click Submit to save the article. </p>
<p> 11. If you wish to preview an article please click on the &lsquo;Preview&rsquo; link for the item you wish to view. </p>
<p> 12. If you wish to add a downloadable document to an article (such as a Word document or a PDF file) then&nbsp;please click on the link &lsquo;Add downloadable file&rsquo; </p>
<p><strong> <br>
Add Downloadable File to an article: </strong></p>
<p> From the Content Admin page: </p>
<p> 1. Click on the add/edit articles button to the right of the sub-section page that contains the article you wish to attach the downloadable file to. </p>
<p> The page will now show a link to add a new article and a list of any existing articles. </p>
<p> 2. Click on &ldquo;Add downloadable file&rdquo; to the right of the article you wish to edit. </p>
<p> 3. Enter a title for the downloadable file. </p>
<p> 4. Click on Browse to find the file on your computer that you wish to upload </p>
<p> 5. Click Submit to upload the file. </p>
<p><strong> <br>
Edit an article: </strong></p>
<p> From the Content Admin page: </p>
<p> 1. Click on the add/edit articles button to the right of the sub-section page on which you wish to add/edit the article to. </p>
<p> The page will now show a link to add a new article and a list of any existing articles. </p>
<p> 2. Click on &ldquo;Edit&rdquo; to the right of the article you wish to edit. </p>
<p> 3. Make the required changes </p>
<p> Note: if you no longer want a thumbnail displayed you can select &ldquo;no image&rdquo; in the Template Type. </p>
<p> 4. Click Submit to save the changes. </p>
<p><strong> <br>
Show or Hide an article: </strong></p>
<p> From the Content Admin page: </p>
<p> 1. Click on the add/edit articles button to the right of the sub-section page you wish to add the article to. </p>
<p> The page will now show a link to add a new article and a list of any existing articles. </p>
<p> 2. Click on &ldquo;Edit&rdquo; to the right of the article you wish to edit. </p>
<p> 3. Select a status of &ldquo;Live&rdquo; to make the article visible or &ldquo;Hidden&rdquo; to not display the article </p>
<p> 4. Click Submit to save the changes. </p>
<p>&nbsp; </p>
<h3> <a name="view_uploaded_images"></a>View Your Uploaded Images</h3>
<p> When an image is added to content it is first uploaded to the server. You can see all the images that have been uploaded to the server as follows:</p>
<p> <br>
From the Content Admin page:</p>
<table border="0" cellspacing="0" cellpadding="4">
  <tr valign="top">
    <td><p>1. Click on View Uploaded Images. </p>
    <p> You should now be presented with a list of the images that have been uploaded to the Content Management System. </p></td>
    <td><img src="images/content_admin_view_images.gif"></td>
  </tr>
</table>
<p><strong><a name="Adding_images"></a>Adding images to the content: </strong></p>
<p> When within the web page editor window:</p>
<table border="0" cellspacing="0" cellpadding="4">
  <tr>
    <td>1. Click on the &lsquo;Insert/Edit Image&rsquo; button within the editor toolbar</td>
    <td><img src="images/content_admin_add_image.gif"></td>
  </tr>
</table>
<p> 2. Click on the &lsquo;Browse Server&rsquo; button within the popup window </p>
<p> 3. You can either choose an image from the list or upload your own from your computer by: </p>
<p> a) clicking on the browse button at the bottom of the window </p>
<p> b) select the image from your computer </p>
<p> c) click on the upload button </p>
<p> d) select the file from the list </p>
<p> e) you can choose to align the image to the left or right of the page using the &lsquo;Align&rsquo; drop-down menu </p>
<p> f) click the &lsquo;OK&rsquo; button to add the image to the page </p>
<p>Note: It is recommended that the images you upload from your computer are no larger than 350 pixels wide. Digital photos are often much larger than this so you may wish to use some software to resize your images before you upload them to the web site. You can <a href="http://download.microsoft.com/download/whistler/Install/2/WXP/EN-US/ImageResizerPowertoySetup.exe" target="_blank">download a free  Image Resizing application from Microsoft</a>. Once installed you can resize your images by right-clicking on them and specifying the  size you would like it changed to. </p>
<p> Tip: If you have a lot of images in your site library we suggest you first visit your 'uploaded images' page where you can preview all of the images you have uploaded. Make a note of the image you wish to use on the page you are going to edit/create. This will help you find the image more easily in the filename list you will see when you click to 'add an image'.</p>
<p><br>
  <strong>Deleting images from content: </strong></p>
<p> 1. Choose to edit the page or article </p>
<p> 2. Place your mouse over the image and press the right mouse button </p>
<p> 3. Select &lsquo;Cut&rsquo; from the popup menu </p>
<h1>&nbsp;</h1>
<h3><a name="diary_events"></a>Diary Events </h3>
<p> The diary section is a special section in which events can be added allowing users of the site to see upcoming events. </p>
<p><strong> Add diary events: </strong></p>
<p> From the Content Admin page:</p>
<table border="0" cellspacing="0" cellpadding="4">
  <tr>
    <td>1. Click on the add/edit events button to the right of the diary  page. </td>
    <td><img src="images/content_admin_events.gif"></td>
  </tr>
</table>
<p>The page will now show a link to add a new diary item and a list of any existing diary events. </p>
<p> 2. Click on &ldquo;Add a diary item to the page&rdquo; </p>
<p> 3. Select the day, month and year of the event. </p>
<p> 4. Enter the title of the event. </p>
<p> 5. Enter the description of the event. </p>
<p> 6. Click Submit to save the event to the system. </p>
<p><strong> <br>
  Edit diary events: </strong></p>
<p> From the Content Admin page: </p>
<p> 1. Click on the add/edit events button to the right of the diary  page. </p>
<p> The page will now show a link to add a new diary item and a list of any existing diary events. </p>
<p> 2. Click on &ldquo;Edit&rdquo; to the right of the event you wish to edit. </p>
<p> 3. Make the changes required. </p>
<p> 4. Click Submit to save the changes. </p>
<p><strong> <br>
  Delete diary events: </strong></p>
<p> From the Content Admin page: </p>
<p> 1. Click on the add/edit events button to the right of the diary  page. </p>
<p> The page will now show a link to add a new diary item and a list of any existing diary events. </p>
<p> 2. Click on &ldquo;Delete&rdquo; to the right of the event you wish to delete. </p>
<p> 3. Click on &ldquo;Yes&rdquo; to confirm the deletion of the event or &ldquo;No&rdquo; to cancel the deletion </p>
<p>&nbsp; </p>
<?php


	}
	else {
		if ($login)
			echo $login_error;
		else
			include ("./login_inc.php"); 
	}

mysql_close ($link);

include ("./admin_footer_inc.php");
?>


