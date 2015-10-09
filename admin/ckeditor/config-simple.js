/**
 * @license Copyright (c) 2003-2014, CKSource - Frederico Knabben. All rights reserved.
 * For licensing, see LICENSE.html or http://ckeditor.com/license
 */

CKEDITOR.editorConfig = function( config ) {
	// Define changes to default configuration here. For example:
	config.language  = 'en-gb';
	config.scayt_sLang = 'en_GB';
	config.wsc_lang = 'en_GB';
	// Change the colour below to match the website. Warning: not too dark!
    //config.uiColor = '#afcad6';
    config.width ='97%';   
    config.height ='200px';
    config.contentsCss  = '/css/styles.css';
	
    config.filebrowserBrowseUrl = '/php/filecontroller/filecontroller.php';
    config.filebrowserCmsimageBrowseUrl = '/php/filecontroller/imagecontroller.php';
    config.filebrowserImageBrowseLinkUrl = '/php/filecontroller/imagecontroller.php';

    config.removePlugins = 'filebrowser';
	// Unfortunately the cmsfilebrowse function is NOT compatible with version 4 of ckeditor :(
    config.extraPlugins = 'cmsfilebrowse,cmsimage,simpleLink';
    //config.extraPlugins = 'cmsimage,simpleLink';
    config.filebrowserImageBrowseUrl = '/php/filecontroller/imagecontroller.php';

    config.toolbar =
        [
            { name: 'basicstyles', items : [ 'Bold','Italic','Underline','-','BulletedList','Blockquote'] },
			{ name: 'styles', items : [ 'Format' ] },
            { name: 'links', items : [ 'SimpleLink' ] },
            { name: 'clipboard', items : [ 'Cut','Copy','PasteText','-','Undo','Redo' ] },
            { name: 'special', items : [ 'Scayt','-','Maximize' ] }
        ];

};

// Important by making the editor content work fully with /css/styles.css
CKEDITOR.config.bodyId = 'content';

// Order and list of Format dropdown
CKEDITOR.config.format_tags = 'Normal;Heading2;Heading3;Large;Small';

// Format dropdown items
CKEDITOR.config.format_Normal = { name : 'Normal Text', element : 'p' };
CKEDITOR.config.format_Heading2 = { name : 'Heading', element : 'h2' };
CKEDITOR.config.format_Heading3 = { name : 'Sub Heading', element : 'h3' };
CKEDITOR.config.format_Large = { name : 'Large Text', element : 'p', attributes : { 'class' : 'large' } };
CKEDITOR.config.format_Small = { name : 'Small Text', element : 'p', attributes : { 'class' : 'small' } };