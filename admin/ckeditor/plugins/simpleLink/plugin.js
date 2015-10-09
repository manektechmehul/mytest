CKEDITOR.plugins.add( 'simpleLink',
{
	init: function( editor )
	{
		editor.addCommand( 'simpleLinkDialog', new CKEDITOR.dialogCommand( 'simpleLinkDialog' ) );
 
		editor.ui.addButton( 'SimpleLink',
		{
			label: 'Insert a Link',
			command: 'simpleLinkDialog',
			icon: this.path + 'images/link.png'
		} );
 
		CKEDITOR.dialog.add( 'simpleLinkDialog', function( editor )
		{
			return {
				title : 'Link Properties',
				minWidth : 400,
				minHeight : 210,
				contents :
				[
					{
						id : 'general',
						label : 'Settings',
						elements :
						[
							{
								type : 'html',
								html : 'You can put in a link below. First type in what will be displayed:'
							},
							{
								type : 'text',
								id : 'contents',
								label : 'Displayed Text',
								validate : CKEDITOR.dialog.validate.notEmpty( 'The Displayed Text field cannot be empty.' ),
								required : true,
								commit : function( data )
								{
									data.contents = this.getValue();
								}
							},
							{
								type : 'html',
								html : 'The safest and best method is to copy the website link from the address <br>bar of your web browser and then paste it into the box below:'
							},
							{
								type : 'text',
								id : 'url',
								label : 'URL',
								validate : CKEDITOR.dialog.validate.notEmpty( 'The link must have a URL.' ),
								required : true,
								commit : function( data )
								{
									data.url = this.getValue();
								}
							},
						//	{
						//		type : 'select',
						//		id : 'style',
						//		label : 'Style',
						//		items : 
						//		[
						//			[ '<none>', '' ],
						//			[ 'Bold', 'b' ],
						//			[ 'Underline', 'u' ],
						//			[ 'Italics', 'i' ]
						//		],
						//		commit : function( data )
						//		{
						//			data.style = this.getValue();
						//		}
						//	},
							{
								type : 'html',
								html : 'If it is a link to another website <strong>make sure</strong> the checkbox below is ticked:'		
							},
							{
								type : 'checkbox',
								id : 'newPage',
								label : 'Opens in a new page',
								'default' : true,
								commit : function( data )
								{
									data.newPage = this.getValue();
								}
							}
						]
					}
				],
				onOk : function()
				{
					var dialog = this,
						data = {},
						link = editor.document.createElement( 'a' );
					this.commitContent( data );
 
					link.setAttribute( 'href', data.url );
 
					if ( data.newPage )
						link.setAttribute( 'target', '_blank' );
 
					switch( data.style )
					{
						case 'b' :
							link.setStyle( 'font-weight', 'bold' );
						break;
						case 'u' :
							link.setStyle( 'text-decoration', 'underline' );
						break;
						case 'i' :
							link.setStyle( 'font-style', 'italic' );
						break;
					}
 
					link.setHtml( data.contents );
 
					editor.insertElement( link );
				}
			};
		});
	}
});