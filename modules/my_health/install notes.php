
Add a new section with the name of the module - should it have a top level menu.

go to http://www.local-base.com/utils/install_listing_module/index.php 
Enter a module name and run the page. Then cut and paste script into database.

For some reason the db scripts don't run in the php. Need to fix 

> add the module tab in the admin menu
> remove the template file name from the page_type table if there isn't a specific new template
(perhaps we should duplictae and rename the content page for the module ?


> add <!-- CS <modulename> searchbox start -->
      <!-- CS <modulename> searchbox end -->
 to the appropriate template
 
> will need to update output.php with the filter adder loop if the search box is not appearing

> Many code changes to be made. follow through module and rename as you go.

Bug:

Will need to check a page name of an item does not clash with a section title, else things will get very confusing
