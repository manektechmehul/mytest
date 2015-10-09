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
    //config.uiColor = '#d36e6e';
    config.width ='860px';   
    config.height ='350px';
    config.contentsCss  = '/css/editorstyles.css';
	config.allowedContent = true;
	
    config.filebrowserBrowseUrl = '/php/filecontroller/filecontroller.php';
    config.filebrowserCmsimageBrowseUrl = '/php/filecontroller/imagecontroller.php';
    config.filebrowserImageBrowseLinkUrl = '/php/filecontroller/imagecontroller.php';

    //config.removePlugins = 'filebrowser';
    // Unfortunately the cmsfilebrowse function is NOT compatible with version 4 of ckeditor :(
   // config.extraPlugins = 'cmsimage';
   
    config.extraPlugins = 'iframedialog,cmsfilebrowse,cmsimage';
    config.filebrowserImageBrowseUrl = '/php/filecontroller/imagecontroller.php';

    config.toolbar =
        [
            { name: 'document', items : [ 'Source' ] },
            { name: 'clipboard', items : [ 'Cut','Copy','Paste','PasteText','PasteFromWord','-','Undo','Redo' ] },
            { name: 'editing', items : [ 'Find','Replace','-','Scayt' ] },
			
			// Only use if you have bespoke styles
			//{ name: 'styles', items : [ 'Styles','Format' ] },
			
			// Only use if you have no bespoke styles
			{ name: 'styles', items : [ 'Format' ] },
            { name: 'remove', items : [ 'RemoveFormat' ] },
			
			{ name: 'div', items : [ 'CreateDiv' ] },
            { name: 'tools', items : [ 'SelectAll','ShowBlocks','Maximize' ] },
            '/',
            { name: 'basicstyles', items : [ 'Bold','Italic','Underline','Strike','Subscript','Superscript' ] },
            { name: 'paragraph', items : [ 'NumberedList','BulletedList','-','Outdent','Indent','-','Blockquote',
                '-','JustifyLeft','JustifyCenter','JustifyRight','JustifyBlock' ] },
            { name: 'links', items : [ 'Link','Unlink','Anchor' ] },
            { name: 'images', items : [ 'CMSImage' ] },  // our bespoke image page with resizin
           // { name: 'images', items : [ 'Image' ] },
            { name: 'insert', items : [ 'Table','HorizontalRule','SpecialChar','Iframe','-','TextColor','BGColor' ] },
            { name: 'about', items : [ 'About' ] }
        ];

};

// Important by making the editor content work fully with /css/styles.css
CKEDITOR.config.bodyId = 'content';

// Surrounding Div Styles if required
//CKEDITOR.stylesSet.add( 'default',
//[
//    { name : 'Expandable' , element : 'div', attributes : { 'class' : 'readmore' } },
//    { name : 'Promo' , element : 'div', attributes : { 'class' : 'inpagepromo' } }
//]);

// Order and list of Format dropdown
CKEDITOR.config.format_tags = 'Normal;Heading2;Heading3;Heading4;Large;Small;Button;External16x9;External4x3;ExternalSquare;ExternalLetterbox;Heading1';

// Format dropdown items
CKEDITOR.config.format_Normal = { name : 'Normal', element : 'p' };
CKEDITOR.config.format_Heading1 = { name : 'Head 1', element : 'h1' };
CKEDITOR.config.format_Heading2 = { name : 'Head 2', element : 'h2' };
CKEDITOR.config.format_Heading3 = { name : 'Head 3', element : 'h3' };
CKEDITOR.config.format_Heading4 = { name : 'Head 4', element : 'h4' };
CKEDITOR.config.format_Large = { name : 'Large', element : 'p', attributes : { 'class' : 'large' } };
CKEDITOR.config.format_Small = { name : 'Small', element : 'p', attributes : { 'class' : 'small' } };
CKEDITOR.config.format_Button = { name : 'Button', element : 'p', attributes : { 'class' : 'csbutton' } };
CKEDITOR.config.format_External16x9 = { name : 'Ext. 16:9', element : 'div', attributes : { 'class' : 'videoWrapper' } };
CKEDITOR.config.format_External4x3 = { name : 'Ext. 4:3', element : 'div', attributes : { 'class' : 'videoWrapper4by3' } };
CKEDITOR.config.format_ExternalSquare = { name : 'Ext. Square', element : 'div', attributes : { 'class' : 'videoWrapper1by1' } };
CKEDITOR.config.format_ExternalLetterbox = { name : 'Ext. Letterbox', element : 'div', attributes : { 'class' : 'videoWrapper3by1' } };

// Typekit (delete if not required in site)
//CKEDITOR.on(
//   'instanceReady',
//   function(ev) {
//      var $script = document.createElement('script'),
//      $editor_instance = CKEDITOR.instances[ev.editor.name];
// 
// Change the Typekit code below if required in this website
//      $script.type = 'text/javascript';
//      $script.src = '//use.typekit.com/rcv7xoc.js';
//      $script.onload = function() {
//         try{$editor_instance.window.$.Typekit.load();}catch(e){}
//      };
//      $editor_instance.document.getHead().$.appendChild($script);
//   }
//);
