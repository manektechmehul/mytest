/*
Copyright (c) 2003-2012, CKSource - Frederico Knabben. All rights reserved.
For licensing, see LICENSE.html or http://ckeditor.com/license
*/

(function()
{
	var cmsImageDialog = function( editor, dialogType )
	{
		// Load image preview.
		var IMAGE = 1,
			LINK = 2,
			PREVIEW = 4,
			CLEANUP = 8,
			regexGetSize = /^\s*(\d+)((px)|\%)?\s*$/i,
			regexGetSizeOrEmpty = /(^\s*(\d+)((px)|\%)?\s*$)|^$/i,
			pxLengthRegex = /^\d+px$/;

		var onSizeChange = function()
		{
			var value = this.getValue(),	// This = input element.
				dialog = this.getDialog(),
				aMatch  =  value.match( regexGetSize );	// Check value
			if ( aMatch )
			{
				if ( aMatch[2] == '%' )			// % is allowed - > unlock ratio.
					switchLockRatio( dialog, false );	// Unlock.
				value = aMatch[1];
			}

			// Only if ratio is locked
			if ( dialog.lockRatio )
			{
				var oImageOriginal = dialog.originalElement;
				if ( oImageOriginal.getCustomData( 'isReady' ) == 'true' )
				{
					if ( this.id == 'txtHeight' )
					{
						if ( value && value != '0' )
							value = Math.round( oImageOriginal.$.width * ( value  / oImageOriginal.$.height ) );
						if ( !isNaN( value ) )
							dialog.setValueOf( 'info', 'txtWidth', value );
					}
					else		//this.id = txtWidth.
					{
						if ( value && value != '0' )
							value = Math.round( oImageOriginal.$.height * ( value  / oImageOriginal.$.width ) );
						if ( !isNaN( value ) )
							dialog.setValueOf( 'info', 'txtHeight', value );
					}
				}
			}
			updatePreview( dialog );
		};

		var updatePreview = function( dialog )
		{
			//Don't load before onShow.
			if ( !dialog.originalElement || !dialog.preview )
				return 1;

			// Read attributes and update imagePreview;
			dialog.commitContent( PREVIEW, dialog.preview );
			return 0;
		};

		// Custom commit dialog logic, where we're intended to give inline style
		// field (txtdlgGenStyle) higher priority to avoid overwriting styles contribute
		// by other fields.
		function commitContent()
		{
			var args = arguments;
			var inlineStyleField = this.getContentElement( 'advanced', 'txtdlgGenStyle' );
			inlineStyleField && inlineStyleField.commit.apply( inlineStyleField, args );

			this.foreach( function( widget )
			{
				if ( widget.commit &&  widget.id != 'txtdlgGenStyle' )
					widget.commit.apply( widget, args );
			});
		}

		// Avoid recursions.
		var incommit;

		// Synchronous field values to other impacted fields is required, e.g. border
		// size change should alter inline-style text as well.
		function commitInternally( targetFields )
		{
			if ( incommit )
				return;

			incommit = 1;

			var dialog = this.getDialog(),
				element = dialog.imageElement;
			if ( element )
			{
				// Commit this field and broadcast to target fields.
				this.commit( IMAGE, element );

				targetFields = [].concat( targetFields );
				var length = targetFields.length,
					field;
				for ( var i = 0; i < length; i++ )
				{
					field = dialog.getContentElement.apply( dialog, targetFields[ i ].split( ':' ) );
					// May cause recursion.
					field && field.setup( IMAGE, element );
				}
			}

			incommit = 0;
		}

		var switchLockRatio = function( dialog, value )
		{
			if ( !dialog.getContentElement( 'info', 'ratioLock' ) )
				return null;

			var oImageOriginal = dialog.originalElement;

			// Dialog may already closed. (#5505)
			if( !oImageOriginal )
				return null;

			// Check image ratio and original image ratio, but respecting user's preference.
			if ( value == 'check' )
			{
				if ( !dialog.userlockRatio && oImageOriginal.getCustomData( 'isReady' ) == 'true'  )
				{
					var width = dialog.getValueOf( 'info', 'txtWidth' ),
						height = dialog.getValueOf( 'info', 'txtHeight' ),
						originalRatio = oImageOriginal.$.width * 1000 / oImageOriginal.$.height,
						thisRatio = width * 1000 / height;
					dialog.lockRatio  = false;		// Default: unlock ratio

					if ( !width && !height )
						dialog.lockRatio = true;
					else if ( !isNaN( originalRatio ) && !isNaN( thisRatio ) )
					{
						if ( Math.round( originalRatio ) == Math.round( thisRatio ) )
							dialog.lockRatio = true;
					}
				}
			}
			else if ( value != undefined )
				dialog.lockRatio = value;
			else
			{
				dialog.userlockRatio = 1;
				dialog.lockRatio = !dialog.lockRatio;
			}

			var ratioButton = CKEDITOR.document.getById( btnLockSizesId );
			if ( dialog.lockRatio )
				ratioButton.removeClass( 'cke_btn_unlocked' );
			else
				ratioButton.addClass( 'cke_btn_unlocked' );

			ratioButton.setAttribute( 'aria-checked', dialog.lockRatio );

			// Ratio button hc presentation - WHITE SQUARE / BLACK SQUARE
			if ( CKEDITOR.env.hc )
			{
				var icon = ratioButton.getChild( 0 );
				icon.setHtml(  dialog.lockRatio ? CKEDITOR.env.ie ? '\u25A0': '\u25A3' : CKEDITOR.env.ie ? '\u25A1' : '\u25A2' );
			}

			return dialog.lockRatio;
		};

		var resetSize = function( dialog )
		{
			var oImageOriginal = dialog.originalElement;
			if ( oImageOriginal.getCustomData( 'isReady' ) == 'true' )
			{
				var widthField = dialog.getContentElement( 'info', 'txtWidth' ),
					heightField = dialog.getContentElement( 'info', 'txtHeight' );
				widthField && widthField.setValue( oImageOriginal.$.width );
				heightField && heightField.setValue( oImageOriginal.$.height );
			}
			updatePreview( dialog );
		};

		var setupDimension = function( type, element )
		{
			if ( type != IMAGE )
				return;

			function checkDimension( size, defaultValue )
			{
				var aMatch  =  size.match( regexGetSize );
				if ( aMatch )
				{
					if ( aMatch[2] == '%' )				// % is allowed.
					{
						aMatch[1] += '%';
						switchLockRatio( dialog, false );	// Unlock ratio
					}
					return aMatch[1];
				}
				return defaultValue;
			}

			var dialog = this.getDialog(),
				value = '',
				dimension = this.id == 'txtWidth' ? 'width' : 'height',
				size = element.getAttribute( dimension );

			if ( size )
				value = checkDimension( size, value );
			value = checkDimension( element.getStyle( dimension ), value );

			this.setValue( value );
		};

		var previewPreloader;

		var onImgLoadEvent = function()
		{
			// Image is ready.
			var original = this.originalElement;
			original.setCustomData( 'isReady', 'true' );
			original.removeListener( 'load', onImgLoadEvent );
			original.removeListener( 'error', onImgLoadErrorEvent );
			original.removeListener( 'abort', onImgLoadErrorEvent );

			// Hide loader
			CKEDITOR.document.getById( imagePreviewLoaderId ).setStyle( 'display', 'none' );

			// New image -> new domensions
			if ( !this.dontResetSize )
				resetSize( this );

			if ( this.firstLoad )
				CKEDITOR.tools.setTimeout( function(){ switchLockRatio( this, 'check' ); }, 0, this );

			this.firstLoad = false;
			this.dontResetSize = false;
		};

		var onImgLoadErrorEvent = function()
		{
			// Error. Image is not loaded.
			var original = this.originalElement;
			original.removeListener( 'load', onImgLoadEvent );
			original.removeListener( 'error', onImgLoadErrorEvent );
			original.removeListener( 'abort', onImgLoadErrorEvent );

			// Set Error image.
			var noimage = CKEDITOR.getUrl( editor.skinPath + 'images/noimage.png' );

			if ( this.preview )
				this.preview.setAttribute( 'src', noimage );

			// Hide loader
			CKEDITOR.document.getById( imagePreviewLoaderId ).setStyle( 'display', 'none' );
			switchLockRatio( this, false );	// Unlock.
		};

		var numbering = function( id )
			{
				return CKEDITOR.tools.getNextId() + '_' + id;
			},
			btnLockSizesId = numbering( 'btnLockSizes' ),
			btnResetSizeId = numbering( 'btnResetSize' ),
			imagePreviewLoaderId = numbering( 'ImagePreviewLoader' ),
			previewLinkId = numbering( 'previewLink' ),
			previewImageId = numbering( 'previewImage' );

		return {
			title : editor.lang.image[ dialogType == 'image' ? 'title' : 'titleButton' ],
			minWidth : 420,
			minHeight : 360,
			onShow : function()
			{
				this.imageElement = false;
				this.linkElement = false;

				// Default: create a new element.
				this.imageEditMode = false;
				this.linkEditMode = false;

				this.lockRatio = true;
				this.userlockRatio = 0;
				this.dontResetSize = false;
				this.firstLoad = true;
				this.addLink = false;

				var editor = this.getParentEditor(),
					sel = editor.getSelection(),
					element = sel && sel.getSelectedElement(),
					link = element && element.getAscendant( 'a' );

				//Hide loader.
				CKEDITOR.document.getById( imagePreviewLoaderId ).setStyle( 'display', 'none' );
				// Create the preview before setup the dialog contents.
				previewPreloader = new CKEDITOR.dom.element( 'img', editor.document );
				this.preview = CKEDITOR.document.getById( previewImageId );

				// Copy of the image
				this.originalElement = editor.document.createElement( 'img' );
				this.originalElement.setAttribute( 'alt', '' );
				this.originalElement.setCustomData( 'isReady', 'false' );

				if ( link )
				{
					this.linkElement = link;
					this.linkEditMode = true;

					// Look for Image element.
					var linkChildren = link.getChildren();
					if ( linkChildren.count() == 1 )			// 1 child.
					{
						var childTagName = linkChildren.getItem( 0 ).getName();
						if ( childTagName == 'img' || childTagName == 'input' )
						{
							this.imageElement = linkChildren.getItem( 0 );
							if ( this.imageElement.getName() == 'img' )
								this.imageEditMode = 'img';
							else if ( this.imageElement.getName() == 'input' )
								this.imageEditMode = 'input';
						}
					}
					// Fill out all fields.
					if ( dialogType == 'image' )
						this.setupContent( LINK, link );
				}

				if ( element && element.getName() == 'img' && !element.data( 'cke-realelement' )
					|| element && element.getName() == 'input' && element.getAttribute( 'type' ) == 'image' )
				{
					this.imageEditMode = element.getName();
					this.imageElement = element;
				}

				if ( this.imageEditMode )
				{
					// Use the original element as a buffer from  since we don't want
					// temporary changes to be committed, e.g. if the dialog is canceled.
					this.cleanImageElement = this.imageElement;
					this.imageElement = this.cleanImageElement.clone( true, true );

					// Fill out all fields.
					this.setupContent( IMAGE, this.imageElement );
				}
				else
					this.imageElement =  editor.document.createElement( 'img' );

				// Refresh LockRatio button
				switchLockRatio ( this, true );

				// Dont show preview if no URL given.
				if ( !CKEDITOR.tools.trim( this.getValueOf( 'info', 'txtUrl' ) ) )
				{
					this.preview.removeAttribute( 'src' );
					this.preview.setStyle( 'display', 'none' );
				}
			},
			onOk : function()
			{
				// Edit existing Image.
				if ( this.imageEditMode )
				{
					var imgTagName = this.imageEditMode;

					// Image dialog and Input element.
					if ( dialogType == 'image' && imgTagName == 'input' && confirm( editor.lang.image.button2Img ) )
					{
						// Replace INPUT-> IMG
						imgTagName = 'img';
						this.imageElement = editor.document.createElement( 'img' );
						this.imageElement.setAttribute( 'alt', '' );
						editor.insertElement( this.imageElement );
					}
					// ImageButton dialog and Image element.
					else if ( dialogType != 'image' && imgTagName == 'img' && confirm( editor.lang.image.img2Button ))
					{
						// Replace IMG -> INPUT
						imgTagName = 'input';
						this.imageElement = editor.document.createElement( 'input' );
						this.imageElement.setAttributes(
							{
								type : 'image',
								alt : ''
							}
						);
						editor.insertElement( this.imageElement );
					}
					else
					{
						// Restore the original element before all commits.
						this.imageElement = this.cleanImageElement;
						delete this.cleanImageElement;
					}
				}
				else	// Create a new image.
				{
					// Image dialog -> create IMG element.
					if ( dialogType == 'image' )
						this.imageElement = editor.document.createElement( 'img' );
					else
					{
						this.imageElement = editor.document.createElement( 'input' );
						this.imageElement.setAttribute ( 'type' ,'image' );
					}
					this.imageElement.setAttribute( 'alt', '' );
				}

				// Create a new link.
				if ( !this.linkEditMode )
					this.linkElement = editor.document.createElement( 'a' );

				// Set attributes.
				this.commitContent( IMAGE, this.imageElement );
				this.commitContent( LINK, this.linkElement );

				// Remove empty style attribute.
				if ( !this.imageElement.getAttribute( 'style' ) )
					this.imageElement.removeAttribute( 'style' );

				// Insert a new Image.
				if ( !this.imageEditMode )
				{
					if ( this.addLink )
					{
						//Insert a new Link.
						if ( !this.linkEditMode )
						{
							editor.insertElement( this.linkElement );
							this.linkElement.append( this.imageElement, false );
						}
						else	 //Link already exists, image not.
							editor.insertElement( this.imageElement );
					}
					else
						editor.insertElement( this.imageElement );
				}
				else		// Image already exists.
				{
					//Add a new link element.
					if ( !this.linkEditMode && this.addLink )
					{
						editor.insertElement( this.linkElement );
						this.imageElement.appendTo( this.linkElement );
					}
					//Remove Link, Image exists.
					else if ( this.linkEditMode && !this.addLink )
					{
						editor.getSelection().selectElement( this.linkElement );
						editor.insertElement( this.imageElement );
					}
				}
			},
			onLoad : function()
			{
				if ( dialogType != 'image' )
					this.hidePage( 'Link' );		//Hide Link tab.
				var doc = this._.element.getDocument();

				if ( this.getContentElement( 'info', 'ratioLock' ) )
				{
					this.addFocusable( doc.getById( btnResetSizeId ), 5 );
					this.addFocusable( doc.getById( btnLockSizesId ), 5 );
				}

				this.commitContent = commitContent;
			},
			onHide : function()
			{
				if ( this.preview )
					this.commitContent( CLEANUP, this.preview );

				if ( this.originalElement )
				{
					this.originalElement.removeListener( 'load', onImgLoadEvent );
					this.originalElement.removeListener( 'error', onImgLoadErrorEvent );
					this.originalElement.removeListener( 'abort', onImgLoadErrorEvent );
					this.originalElement.remove();
					this.originalElement = false;		// Dialog is closed.
				}

				delete this.imageElement;
			},
			contents : [
				{
					id : 'info',
					label : editor.lang.image.infoTab,
					accessKey : 'I',
					elements :
					[
						{
							type : 'vbox',
							padding : 0,
							children :
							[
								{
									type : 'hbox',
									widths : [ '280px', '110px' ],
									align : 'right',
									children :
									[
										{
											id : 'txtUrl',
											type : 'text',
											label : editor.lang.common.url,
											required: true,
											onChange : function()
											{
												var dialog = this.getDialog(),
													newUrl = this.getValue();

												//Update original image
												if ( newUrl.length > 0 )	//Prevent from load before onShow
												{
                                                    var thumbSize = dialog.getContentElement( 'info', 'cmbSize').getValue();
                                                    newUrl = "/php/thumbimage.php?img="+newUrl+"&size="+thumbSize;

                                                    dialog = this.getDialog();
													var original = dialog.originalElement;

													dialog.preview.removeStyle( 'display' );

													original.setCustomData( 'isReady', 'false' );
													// Show loader
													var loader = CKEDITOR.document.getById( imagePreviewLoaderId );
													if ( loader )
														loader.setStyle( 'display', '' );

													original.on( 'load', onImgLoadEvent, dialog );
													original.on( 'error', onImgLoadErrorEvent, dialog );
													original.on( 'abort', onImgLoadErrorEvent, dialog );
													original.setAttribute( 'src', newUrl );

													// Query the preloader to figure out the url impacted by based href.
													previewPreloader.setAttribute( 'src', newUrl );
													dialog.preview.setAttribute( 'src', previewPreloader.$.src );
													updatePreview( dialog );
												}
												// Dont show preview if no URL given.
												else if ( dialog.preview )
												{
													dialog.preview.removeAttribute( 'src' );
													dialog.preview.setStyle( 'display', 'none' );
												}
											},
											setup : function( type, element )
											{
												if ( type == IMAGE )
												{
													var url = element.data( 'cke-saved-src' ) || element.getAttribute( 'src' );
													if(url.indexOf("img=") !== -1){
                                                    	url=url.match(/.*img=(.*)&.*/)[1];
													}
													var field = this;

													this.getDialog().dontResetSize = true;

													field.setValue( url );		// And call this.onChange()
													// Manually set the initial value.(#4191)
													field.setInitValue();
												}
											},
											commit : function( type, element )
											{
												if ( type == IMAGE && ( this.getValue() || this.isChanged() ) )
												{
                                                    dialog = this.getDialog();
                                                    url = this.getValue();
                                                    var thumbSize = dialog.getContentElement( 'info', 'cmbSize').getValue();
                                                    url = "/php/thumbimage.php?img="+url+"&size="+thumbSize;

													element.data( 'cke-saved-src', url );
													element.setAttribute( 'src', url );
												}
												else if ( type == CLEANUP )
												{
													element.setAttribute( 'src', '' );	// If removeAttribute doesn't work.
													element.removeAttribute( 'src' );
												}
											},
											validate : CKEDITOR.dialog.validate.notEmpty( editor.lang.image.urlMissing )
										},
										{
											type : 'button',
											id : 'browse',
											// v-align with the 'txtUrl' field.
											// TODO: We need something better than a fixed size here.
											style : 'display:inline-block;margin-top:10px;',
											align : 'center',
											label : editor.lang.common.browseServer,
											hidden : true,
											filebrowser : 'info:txtUrl'
										}
									]
								}
							]
						},
						{
							id : 'txtAlt',
							type : 'text',
							label : editor.lang.image.alt,
							accessKey : 'T',
							'default' : '',
							onChange : function()
							{
								updatePreview( this.getDialog() );
							},
							setup : function( type, element )
							{
								if ( type == IMAGE )
									this.setValue( element.getAttribute( 'alt' ) );
							},
							commit : function( type, element )
							{
								if ( type == IMAGE )
								{
									if ( this.getValue() || this.isChanged() )
										element.setAttribute( 'alt', this.getValue() );
								}
								else if ( type == PREVIEW )
								{
									element.setAttribute( 'alt', this.getValue() );
								}
								else if ( type == CLEANUP )
								{
									element.removeAttribute( 'alt' );
								}
							}
						},
						{
							type : 'hbox',
							children :
							[
								{
									id : 'basic',
									type : 'vbox',
									children :
									[
										{
											type : 'hbox',
											widths : [ '50%', '50%' ],
											children :
											[
												{
													type : 'vbox',
													padding : 1,
                                                    style : 'width: 80px',
													children :
													[
                                                        {
                                                            id : 'cmbSize',
                                                            type : 'select',
                                                            widths : [ '35%','65%' ],
                                                            style : 'width:90px',
                                                            label : 'Size',
                                                            'default' : '40',
                                                            items :
                                                                [
                                                                    ['Third/Half Width','40'],
                                                                    ['Full Width','41'],
                                                                    ['Large','3'],
                                                                    ['Medium','4'],
                                                                    ['Small','5'],
                                                                    ['Original','23']
                                                                ],
                                                            onChange: function () {
                                                                var url = '';
                                                                var dialog = this.getDialog();
                                                                var original = dialog.originalElement;
                                                                url = dialog.getContentElement( 'info', 'txtUrl').getValue();
                                                                if ( url.length > 0 )	//Prevent from load before onShow
                                                                {
                                                                    url = "/php/thumbimage.php?img="+url+"&size="+this.getValue();

                                                                    original.setAttribute( 'src', url );
                                                                    // Query the preloader to figure out the url impacted by based href.
                                                                    previewPreloader.setAttribute( 'src', url );
                                                                    dialog.preview.setAttribute( 'src', previewPreloader.$.src );
                                                                    updatePreview( dialog );
                                                                }
                                                            },
                                                            setup : function (type, element) {
                                                                var url = element.data( 'cke-saved-src' ) || element.getAttribute( 'src' );
                                                                var size=url.match(/.*&size=(\d*)/)[1];
                                                                this.setValue(size)
                                                            }
                                                        }

													]
												}
											]
										},
										{
											type : 'vbox',
											padding : 1,
											children :
											[
												{
													type : 'text',
													id : 'txtBorder',
													width: '60px',
													label : editor.lang.image.border,
													'default' : '',
													onKeyUp : function()
													{
														updatePreview( this.getDialog() );
													},
													onChange : function()
													{
														commitInternally.call( this, 'advanced:txtdlgGenStyle' );
													},
													validate : CKEDITOR.dialog.validate.integer( editor.lang.image.validateBorder ),
													setup : function( type, element )
													{
														if ( type == IMAGE )
														{

															var value,
																borderStyle = element.getStyle( 'border-width' );
															borderStyle = borderStyle && borderStyle.match( /^(\d+px)(?: \1 \1 \1)?$/ );
															value = borderStyle && parseInt( borderStyle[ 1 ], 10 );
															isNaN ( parseInt( value, 10 ) ) && ( value = element.getAttribute( 'border' ) );
															this.setValue( value );
														}
													},
													commit : function( type, element, internalCommit )
													{
														var value = parseInt( this.getValue(), 10 );
														if ( type == IMAGE || type == PREVIEW )
														{
															if ( !isNaN( value ) )
															{
																element.setStyle( 'border-width', CKEDITOR.tools.cssLength( value ) );
																element.setStyle( 'border-style', 'solid' );
															}
															else if ( !value && this.isChanged() )
																element.removeStyle( 'border' );

															if ( !internalCommit && type == IMAGE )
																element.removeAttribute( 'border' );
														}
														else if ( type == CLEANUP )
														{
															element.removeAttribute( 'border' );
															element.removeStyle( 'border-width' );
															element.removeStyle( 'border-style' );
															element.removeStyle( 'border-color' );
														}
													}
												},
												{
													id : 'cmbAlign',
													type : 'select',
													widths : [ '35%','65%' ],
													style : 'width:90px',
													label : editor.lang.common.align,
													'default' : '',
													items :
													[
														[ editor.lang.common.notSet , ''],
														[ editor.lang.common.alignLeft , 'left'],
														[ editor.lang.common.alignRight , 'right'],
                                                        [ 'Half Width Left', 'halfwidthleft'],
                                                        [ 'Half Width Right', 'halfwidthright'],
                                                        [ 'Third Width Left', 'thirdwidthleft'],
                                                        [ 'Third Width Right', 'thirdwidthright'],
                                                        [ 'Full Width', 'fullwidth'],
                                                        [ 'No align', 'noalign'],
                                                        [ 'Left (no border)', 'leftnoborder'],
                                                        [ 'Right (no border)', 'rightnoborder'],
                                                        [ 'Half Width Left (no border)', 'halfwidthleftnoborder'],
                                                        [ 'Half Width Right (no border)', 'halfwidthrightnoborder'],
                                                        [ 'Third Width Left (no border)', 'thirdwidthleftnoborder'],
                                                        [ 'Third Width Right (no border)', 'thirdwidthrightnoborder'],
                                                        [ 'Full Width (no border)', 'fullwidthnoborder'],
                                                        [ 'No Align (no border)', 'noalignnoborder']

														// Backward compatible with v2 on setup when specified as attribute value,
														// while these values are no more available as select options.
														//	[ editor.lang.image.alignAbsBottom , 'absBottom'],
														//	[ editor.lang.image.alignAbsMiddle , 'absMiddle'],
														//  [ editor.lang.image.alignBaseline , 'baseline'],
														//  [ editor.lang.image.alignTextTop , 'text-top'],
														//  [ editor.lang.image.alignBottom , 'bottom'],
														//  [ editor.lang.image.alignMiddle , 'middle'],
														//  [ editor.lang.image.alignTop , 'top']
													],
													onChange : function()
													{
														updatePreview( this.getDialog() );
														commitInternally.call( this, 'advanced:txtdlgGenStyle' );
													},
													setup : function( type, element )
													{
														if ( type == IMAGE )
														{
                                                            var value = '';
                                                            if (element.hasClass('left'))
                                                                value = 'left';
                                                            if (element.hasClass('right'))
                                                                value = 'right';
                                                            if (element.hasClass('halfwidthleft'))
                                                                value = 'halfwidthleft';
                                                            if (element.hasClass('halfwidthright'))
                                                                value = 'halfwidthright';
                                                            if (element.hasClass('thirdwidthleft'))
                                                                value = 'thirdwidthleft';
                                                            if (element.hasClass('thirdwidthright'))
                                                                value = 'thirdwidthright';
                                                            if (element.hasClass('fullwidth'))
                                                                value = 'fullwidth';
                                                            if (element.hasClass('noalign'))
                                                                value = 'noalign';
                                                            if (element.hasClass('leftnoborder'))
                                                                value = 'leftnoborder';
                                                            if (element.hasClass('rightnoborder'))
                                                                value = 'rightnoborder';
                                                            if (element.hasClass('halfwidthleftnoborder'))
                                                                value = 'halfwidthleftnoborder';
                                                            if (element.hasClass('halfwidthrightnoborder'))
                                                                value = 'halfwidthrightnoborder';
                                                            if (element.hasClass('thirdwidthleftnoborder'))
                                                                value = 'thirdwidthleftnoborder';
                                                            if (element.hasClass('thirdwidthrightnoborder'))
                                                                value = 'thirdwidthrightnoborder';
                                                            if (element.hasClass('fullwidthnoborder'))
                                                                value = 'fullwidthnoborder';
                                                            if (element.hasClass('noalignnoborder'))
                                                                value = 'noalignnoborder';

															this.setValue( value );
														}
													},
													commit : function( type, element, internalCommit )
													{
														var value = this.getValue();
                                                        element.removeClass( 'left' );
                                                        element.removeClass( 'right' );
                                                        element.removeClass( 'halfwidthleft' );
                                                        element.removeClass( 'halfwidthright' );
                                                        element.removeClass( 'thirdwidthleft' );
                                                        element.removeClass( 'thirdwidthright' );
                                                        element.removeClass( 'fullwidth' );
                                                        element.removeClass( 'noalign' );
                                                        element.removeClass( 'leftnoborder' );
                                                        element.removeClass( 'rightnoborder' );
                                                        element.removeClass( 'halfwidthleftnoborder' );
                                                        element.removeClass( 'halfwidthrightnoborder' );
                                                        element.removeClass( 'thirdwidthleftnoborder' );
                                                        element.removeClass( 'thirdwidthrightnoborder' );
                                                        element.removeClass( 'fullwidthnoborder' );
                                                        element.removeClass( 'noalignnoborder' );
														if ( type == IMAGE || type == PREVIEW )
														{
                                                            if ( value )
                                                                element.addClass( value );

                                                        }
													}
												}
											]
										}
									]
								},
								{
									type : 'vbox',
									height : '250px',
									children :
									[
										{
											type : 'html',
											id : 'htmlPreview',
											style : 'width:95%;',
											html : '<div>' + CKEDITOR.tools.htmlEncode( editor.lang.common.preview ) +'<br>'+
											'<div id="' + imagePreviewLoaderId + '" class="ImagePreviewLoader" style="display:none"><div class="loading">&nbsp;</div></div>'+
											'<div class="ImagePreviewBox"><table><tr><td>'+
											'<a href="javascript:void(0)" target="_blank" onclick="return false;" id="' + previewLinkId + '">'+
											'<img id="' + previewImageId + '" alt="" /></a>' +
											( editor.config.image_previewText ||
											'Lorem ipsum dolor sit amet, consectetuer adipiscing elit. '+
											'Maecenas feugiat consequat diam. Maecenas metus. Vivamus diam purus, cursus a, commodo non, facilisis vitae, '+
											'nulla. Aenean dictum lacinia tortor. Nunc iaculis, nibh non iaculis aliquam, orci felis euismod neque, sed ornare massa mauris sed velit. Nulla pretium mi et risus. Fusce mi pede, tempor id, cursus ac, ullamcorper nec, enim. Sed tortor. Curabitur molestie. Duis velit augue, condimentum at, ultrices a, luctus ut, orci. Donec pellentesque egestas eros. Integer cursus, augue in cursus faucibus, eros pede bibendum sem, in tempus tellus justo quis ligula. Etiam eget tortor. Vestibulum rutrum, est ut placerat elementum, lectus nisl aliquam velit, tempor aliquam eros nunc nonummy metus. In eros metus, gravida a, gravida sed, lobortis id, turpis. Ut ultrices, ipsum at venenatis fringilla, sem nulla lacinia tellus, eget aliquet turpis mauris non enim. Nam turpis. Suspendisse lacinia. Curabitur ac tortor ut ipsum egestas elementum. Nunc imperdiet gravida mauris.' ) +
											'</td></tr></table></div></div>'
										}
									]
								}
							]
						}
					]
				},
				{
					id : 'Link',
					label : editor.lang.link.title,
					padding : 0,
					elements :
					[
						{
							id : 'txtUrl',
							type : 'text',
							label : editor.lang.common.url,
							style : 'width: 100%',
							'default' : '',
							setup : function( type, element )
							{
								if ( type == LINK )
								{
									var href = element.data( 'cke-saved-href' );
									if ( !href )
										href = element.getAttribute( 'href' );
									this.setValue( href );
								}
							},
							commit : function( type, element )
							{
								if ( type == LINK )
								{
									if ( this.getValue() || this.isChanged() )
									{
										var url = decodeURI( this.getValue() );
										element.data( 'cke-saved-href', url );
										element.setAttribute( 'href', url );

										if ( this.getValue() || !editor.config.image_removeLinkByEmptyURL )
											this.getDialog().addLink = true;
									}
								}
							}
						},
						{
							type : 'button',
							id : 'browse',
							filebrowser :
							{
								action : 'Browse',
								target: 'Link:txtUrl',
								url: editor.config.filebrowserImageBrowseLinkUrl
							},
							style : 'float:right',
							hidden : true,
							label : editor.lang.common.browseServer
						},
						{
							id : 'cmbTarget',
							type : 'select',
							label : editor.lang.common.target,
							'default' : '',
							items :
							[
								[ editor.lang.common.notSet , ''],
								[ editor.lang.common.targetNew , '_blank'],
								[ editor.lang.common.targetTop , '_top'],
								[ editor.lang.common.targetSelf , '_self'],
								[ editor.lang.common.targetParent , '_parent']
							],
							setup : function( type, element )
							{
								if ( type == LINK )
									this.setValue( element.getAttribute( 'target' ) || '' );
							},
							commit : function( type, element )
							{
								if ( type == LINK )
								{
									if ( this.getValue() || this.isChanged() )
										element.setAttribute( 'target', this.getValue() );
								}
							}
						}
					]
				},
				{
					id : 'Upload',
					hidden : true,
					filebrowser : 'uploadButton',
					label : editor.lang.image.upload,
					elements :
					[
						{
							type : 'file',
							id : 'upload',
							label : editor.lang.image.btnUpload,
							style: 'height:40px',
							size : 38
						},
						{
							type : 'fileButton',
							id : 'uploadButton',
							filebrowser : 'info:txtUrl',
							label : editor.lang.image.btnUpload,
							'for' : [ 'Upload', 'upload' ]
						}
					]
				},
				{
					id : 'advanced',
					label : editor.lang.common.advancedTab,
					elements :
					[
						{
							type : 'hbox',
							widths : [ '50%', '25%', '25%' ],
							children :
							[
								{
									type : 'text',
									id : 'linkId',
									label : editor.lang.common.id,
									setup : function( type, element )
									{
										if ( type == IMAGE )
											this.setValue( element.getAttribute( 'id' ) );
									},
									commit : function( type, element )
									{
										if ( type == IMAGE )
										{
											if ( this.getValue() || this.isChanged() )
												element.setAttribute( 'id', this.getValue() );
										}
									}
								},
								{
									id : 'cmbLangDir',
									type : 'select',
									style : 'width : 100px;',
									label : editor.lang.common.langDir,
									'default' : '',
									items :
									[
										[ editor.lang.common.notSet, '' ],
										[ editor.lang.common.langDirLtr, 'ltr' ],
										[ editor.lang.common.langDirRtl, 'rtl' ]
									],
									setup : function( type, element )
									{
										if ( type == IMAGE )
											this.setValue( element.getAttribute( 'dir' ) );
									},
									commit : function( type, element )
									{
										if ( type == IMAGE )
										{
											if ( this.getValue() || this.isChanged() )
												element.setAttribute( 'dir', this.getValue() );
										}
									}
								},
								{
									type : 'text',
									id : 'txtLangCode',
									label : editor.lang.common.langCode,
									'default' : '',
									setup : function( type, element )
									{
										if ( type == IMAGE )
											this.setValue( element.getAttribute( 'lang' ) );
									},
									commit : function( type, element )
									{
										if ( type == IMAGE )
										{
											if ( this.getValue() || this.isChanged() )
												element.setAttribute( 'lang', this.getValue() );
										}
									}
								}
							]
						},
						{
							type : 'text',
							id : 'txtGenLongDescr',
							label : editor.lang.common.longDescr,
							setup : function( type, element )
							{
								if ( type == IMAGE )
									this.setValue( element.getAttribute( 'longDesc' ) );
							},
							commit : function( type, element )
							{
								if ( type == IMAGE )
								{
									if ( this.getValue() || this.isChanged() )
										element.setAttribute( 'longDesc', this.getValue() );
								}
							}
						},
						{
							type : 'hbox',
							widths : [ '50%', '50%' ],
							children :
							[
								{
									type : 'text',
									id : 'txtGenClass',
									label : editor.lang.common.cssClass,
									'default' : '',
									setup : function( type, element )
									{
										//if ( type == IMAGE )
										//	this.setValue( element.getAttribute( 'class' ) );
									},
									commit : function( type, element )
									{
										if ( type == IMAGE )
										{
											//if ( this.getValue() || this.isChanged() )
											//	element.setAttribute( 'class', this.getValue() );
										}
									}
								},
								{
									type : 'text',
									id : 'txtGenTitle',
									label : editor.lang.common.advisoryTitle,
									'default' : '',
									onChange : function()
									{
										updatePreview( this.getDialog() );
									},
									setup : function( type, element )
									{
										if ( type == IMAGE )
											this.setValue( element.getAttribute( 'title' ) );
									},
									commit : function( type, element )
									{
										if ( type == IMAGE )
										{
											if ( this.getValue() || this.isChanged() )
												element.setAttribute( 'title', this.getValue() );
										}
										else if ( type == PREVIEW )
										{
											element.setAttribute( 'title', this.getValue() );
										}
										else if ( type == CLEANUP )
										{
											element.removeAttribute( 'title' );
										}
									}
								}
							]
						},
						{
							type : 'text',
							id : 'txtdlgGenStyle',
							label : editor.lang.common.cssStyle,
							validate : CKEDITOR.dialog.validate.inlineStyle( editor.lang.common.invalidInlineStyle ),
							'default' : '',
							setup : function( type, element )
							{
								if ( type == IMAGE )
								{
									var genStyle = element.getAttribute( 'style' );
									if ( !genStyle && element.$.style.cssText )
										genStyle = element.$.style.cssText;
									this.setValue( genStyle );

									var height = element.$.style.height,
										width = element.$.style.width,
										aMatchH  = ( height ? height : '' ).match( regexGetSize ),
										aMatchW  = ( width ? width : '').match( regexGetSize );

									this.attributesInStyle =
									{
										height : !!aMatchH,
										width : !!aMatchW
									};
								}
							},
							onChange : function ()
							{
								commitInternally.call( this,
									[ 'info:cmbFloat', 'info:cmbAlign',
									  'info:txtVSpace', 'info:txtHSpace',
									  'info:txtBorder',
									  'info:txtWidth', 'info:txtHeight' ] );
								updatePreview( this );
							},
							commit : function( type, element )
							{
								if ( type == IMAGE && ( this.getValue() || this.isChanged() ) )
								{
									element.setAttribute( 'style', this.getValue() );
								}
							}
						}
					]
				}
			]
		};
	};

	CKEDITOR.dialog.add( 'cmsimage', function( editor )
		{
			return cmsImageDialog( editor, 'image' );
		});

	CKEDITOR.dialog.add( 'cmsimagebutton', function( editor )
		{
			return cmsImageDialog( editor, 'imagebutton' );
		});
})();
