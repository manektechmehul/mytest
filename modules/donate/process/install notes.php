
1) Add a new section with the name of the module - should it have a top level menu.
1b) copy the latest version of the xbase directory to the cms modules section.

2) Go to the replacement markers file and from outside the directory replace all markers through this module.
Best done with Dreamweaver at the moment.

== Database

3) run the install.sql and make_tables.sql (AFTER YOU HAVE RUN THE REPLACE ON THEM ! )

== template file
4)  duplicate and rename the content page for the module - module_name.htm - check in page_type table


5) add the module tab in the admin menu (admin/admin_header_inc.php)

     if (SITE_HAS_DONATE == "1") {
                                show_nav_tab_image("/modules/donate/admin/main.php", 'donate', 'donate', 'nav-donate');
                            }

> replace the search box markers in the modules template ... using the lowercase plural
<!-- CS booking searchbox start --> my  module Search box to go here <!-- CS booking searchbox end -->

== hook up galleries


> update modules/gallery/sidebox/inline_gallery.php
goto around line 60

if($page_type_row['path'] == "donate") {
		if ( DONATE_HAS_INLINE_GALLERIES ) {
			$sql       = " SELECT gallery  FROM donate WHERE page_name = '$name_parts[1]'";
			$galleryId = db_get_single_value( $sql );
		}
	} 

make sure your template has:
<!-- CS inline gallery start --> gallery goes here <!-- CS inline gallery end -->

and ?flush !!!!

 

> will need to update output.php with the filter adder loop if the search box is not appearing

Bug:

Will need to check a page name of an item does not clash with a section title, else things will get very confusing

HEre are some mysql data populators to help get things started

/* Execute this many times (say 20) - duplicates the first row */
CREATE TEMPORARY TABLE tmptable_1 SELECT * FROM factor WHERE id = 1;
UPDATE tmptable_1 SET id = NULL;
INSERT INTO factor SELECT * FROM tmptable_1;
DROP TEMPORARY TABLE IF EXISTS tmptable_1;


/* taylor inputs */
UPDATE factor 
SET 
title = CONCAT("Factor Item ", id, " Title"),
description = CONCAT("Factor Item description for item ", id, ". This is great."),
page_name = CONCAT("item-", id, "-page"),
thumb = CONCAT("Test_Images/icons/",id,".png"),
page_image = CONCAT("Test_Images/n1/pic_(",ROUND(RAND() * (70-1) + 1),").jpg"),
header_image = CONCAT("Test_Images/n1/pic_(",ROUND(RAND() * (70-1) + 1),").jpg")
 


/* where 40 is number of items and 9 is number of cats */
INSERT factor_category_lookup
(item_id, category_id) VALUES (ROUND(RAND() * (40-1) + 1), ROUND(RAND() * (9-1) + 1))

DELETE FROM factor WHERE id > 9

DELETE  FROM factor_category_lookup WHERE category_id > 9