/**
 * @license Copyright (c) 2003-2012, CKSource - Frederico Knabben. All rights reserved.
 * For licensing, see LICENSE.html or http://ckeditor.com/license
 */
CKEDITOR.dialog.add( 'tdselect', function( editor ) {
	// Add a new option to a SELECT object (combo or list).
	function addOption( combo, optionText, optionValue, documentObject, index ) {
		combo = getSelect( combo );
		var oOption;
		if ( documentObject )
			oOption = documentObject.createElement( "OPTION" );
		else
			oOption = document.createElement( "OPTION" );

		if ( combo && oOption && oOption.getName() == 'option' ) {
			if ( CKEDITOR.env.ie ) {
				if ( !isNaN( parseInt( index, 10 ) ) )
					combo.$.options.add( oOption.$, index );
				else
					combo.$.options.add( oOption.$ );

				oOption.$.innerHTML = optionText.length > 0 ? optionText : '';
				oOption.$.value = optionValue;
			} else {
				if ( index !== null && index < combo.getChildCount() )
					combo.getChild( index < 0 ? 0 : index ).insertBeforeMe( oOption );
				else
					combo.append( oOption );

				oOption.setText( optionText.length > 0 ? optionText : '' );
				oOption.setValue( optionValue );
			}
		} else
			return false;

		return oOption;
	}
	// Remove all selected options from a SELECT object.
	function removeSelectedOptions( combo ) {
		combo = getSelect( combo );

		// Save the selected index
		var iSelectedIndex = getSelectedIndex( combo );

		// Remove all selected options.
		for ( var i = combo.getChildren().count() - 1; i >= 0; i-- ) {
			if ( combo.getChild( i ).$.selected )
				combo.getChild( i ).remove();
		}

		// Reset the selection based on the original selected index.
		setSelectedIndex( combo, iSelectedIndex );
	}
	//Modify option  from a SELECT object.
	function modifyOption( combo, index, title, value ) {
		combo = getSelect( combo );
		if ( index < 0 )
			return false;
		var child = combo.getChild( index );
		child.setText( title );
		child.setValue( value );
		return child;
	}

	function removeAllOptions( combo ) {
		combo = getSelect( combo );
		while ( combo.getChild( 0 ) && combo.getChild( 0 ).remove() ) {
			/*jsl:pass*/
		}
	}
	// Moves the selected option by a number of steps (also negative).
	function changeOptionPosition( combo, steps, documentObject ) {
		combo = getSelect( combo );
		var iActualIndex = getSelectedIndex( combo );
		if ( iActualIndex < 0 )
			return false;

		var iFinalIndex = iActualIndex + steps;
		iFinalIndex = ( iFinalIndex < 0 ) ? 0 : iFinalIndex;
		iFinalIndex = ( iFinalIndex >= combo.getChildCount() ) ? combo.getChildCount() - 1 : iFinalIndex;

		if ( iActualIndex == iFinalIndex )
			return false;

		var oOption = combo.getChild( iActualIndex ),
			sText = oOption.getText(),
			sValue = oOption.getValue();

		oOption.remove();

		oOption = addOption( combo, sText, sValue, ( !documentObject ) ? null : documentObject, iFinalIndex );
		setSelectedIndex( combo, iFinalIndex );
		return oOption;
	}

	function getSelectedIndex( combo ) {
		combo = getSelect( combo );
		return combo ? combo.$.selectedIndex : -1;
	}

	function setSelectedIndex( combo, index ) {
		combo = getSelect( combo );
		if ( index < 0 )
			return null;
		var count = combo.getChildren().count();
		combo.$.selectedIndex = ( index >= count ) ? ( count - 1 ) : index;
		return combo;
	}

	function getOptions( combo ) {
		combo = getSelect( combo );
		return combo ? combo.getChildren() : false;
	}

	function getSelect( obj ) {
		if ( obj && obj.domId && obj.getInputElement().$ ) // Dialog element.
		return obj.getInputElement();
		else if ( obj && obj.$ )
			return obj;
		return false;
	}

	return {
		title: editor.lang.tdforms.tdselect.dialogTitle,
		minWidth: CKEDITOR.env.ie ? 460 : 395,
		minHeight: CKEDITOR.env.ie ? 320 : 300,
		onShow: function() {
			delete this.selectBox;
			this.setupContent( 'clear' );
			var element = this.getParentEditor().getSelection().getSelectedElement();
			if ( element && element.getName() == "select" ) {
				this.selectBox = element;
				this.setupContent( element.getName(), element );

				// Load Options into dialog.
				var objOptions = getOptions( element );
				for ( var i = 0; i < objOptions.count(); i++ )
					this.setupContent( 'option', objOptions.getItem( i ) );
			}
		},
		onOk: function() {
			var editor = this.getParentEditor(),
				element = this.selectBox,
				widget_json = '',
				field_name = this.getValueOf( 'info', 'txtName'),
				field_type = 'select',
				isInsertMode = !element,
				size = this.getValueOf( 'info', 'txtSize'),
				multiple = this.getValueOf( 'info', 'chkMulti');
				widget_json += '{' + '"field_name":' + '"' + field_name + '",' + '"field_type":' + '"' + field_type + '",'
								+ '"field_attr":' + '{' + '"title":' + '"' + field_name + '",' + '"class":' + '"' +field_type + '",'
								+ '"value":' + '"' + this.getValueOf('info', 'txtValue') + '",'
								+ '"size":' + '"' + size + '",' + '"multiple":' + '"' + multiple + '"' + '}}';
			if ( isInsertMode )
				element = editor.document.createElement( 'select' );
			this.commitContent( element );

			if ( isInsertMode ) {
				var nameItem = $.ajax({ type: "post",url: g_requestURL,data: {fieldInfo:widget_json,formID:g_form_id},cache: false,async: false}).responseText;
				element.setAttribute( 'name', "data_"+nameItem );
				element.setAttribute( 'id', "data_"+nameItem );
				editor.insertElement( element );
				if ( CKEDITOR.env.ie ) {
					var sel = editor.getSelection(),
						bms = sel.createBookmarks();
					setTimeout( function() {
						sel.selectBookmarks( bms );
					}, 0 );
				}
			}

			if ( !isInsertMode ) {
				var field_order = element.getAttribute( 'name' ).split('_')[1];
				$.ajax({ type: "post",url: g_requestURL,data: {fieldInfo:widget_json,formID:g_form_id,fieldOrder:field_order},cache: false,async: false});
			}
		},
		contents: [
			{
			id: 'info',
			elements: [
				{
				id: 'txtName',
				type: 'text',
				widths: [ '25%', '75%' ],
				labelLayout: 'horizontal',
				label: editor.lang.common.controlName,
				validate:CKEDITOR.dialog.validate.notEmpty( editor.lang.common.validateControlFailed ),
				'default': '',
				accessKey: 'N',
				style: 'width:350px',
				setup: function( name, element ) {
					if ( name == 'clear' )
						this.setValue( this[ 'default' ] || '' );
					else if ( name == 'select' ) {
						this.setValue( element.data( 'cke-saved-title' ) || element.getAttribute('title') || '' );
					}
				},
				commit: function( element ) {
					if ( this.getValue() )
						element.data( 'cke-saved-title', this.getValue());
					else {
						element.data( 'cke-saved-title', false );
						element.removeAttribute( 'title' );
					}
				}
			},
				{
				id: 'txtValue',
				type: 'text',
				widths: [ '25%', '75%' ],
				labelLayout: 'horizontal',
				label: editor.lang.common.value,
				style: 'width:350px',
				'default': '',
				className: 'cke_disabled',
				onLoad: function() {
					this.getInputElement().setAttribute( 'readOnly', true );
				},
				setup: function( name, element ) {
					if ( name == 'clear' )
						this.setValue( '' );
					else if ( name == 'option' && element.getAttribute( 'selected' ) )
						this.setValue( element.$.value );
				}
			},
				{
				type: 'hbox',
				widths: [ '175px', '170px' ],
				children: [
					{
					id: 'txtSize',
					type: 'text',
					labelLayout: 'horizontal',
					label: editor.lang.tdforms.tdselect.size,
					'default': '',
					accessKey: 'S',
					style: 'width:175px',
					validate: function() {
						var func = CKEDITOR.dialog.validate.integer( editor.lang.common.validateNumberFailed );
						return ( ( this.getValue() === '' ) || func.apply( this ) );
					},
					setup: function( name, element ) {
						if ( name == 'select' )
							this.setValue( element.getAttribute( 'size' ) || '' );
						if ( CKEDITOR.env.webkit )
							this.getInputElement().setStyle( 'width', '86px' );
					},
					commit: function( element ) {
						if ( this.getValue() )
							element.setAttribute( 'size', this.getValue() );
						else
							element.removeAttribute( 'size' );
					}
				},
					{
					type: 'html',
					html: '<span>' + CKEDITOR.tools.htmlEncode( editor.lang.tdforms.tdselect.lines ) + '</span>'
				}
				]
			},
				{
				type: 'html',
				html: '<span>' + CKEDITOR.tools.htmlEncode( editor.lang.tdforms.tdselect.opAvail ) + '</span>'
			},
				{
				type: 'hbox',
				widths: [ '115px', '115px', '100px' ],
				children: [
					{
					type: 'vbox',
					children: [
						{
						id: 'txtOptName',
						type: 'text',
						label: editor.lang.tdforms.tdselect.opText,
						style: 'width:115px',
						setup: function( name, element ) {
							if ( name == 'clear' )
								this.setValue( "" );
						}
					},
						{
						type: 'select',
						id: 'cmbName',
						label: '',
						title: '',
						size: 5,
						style: 'width:115px;height:75px',
						items: [],
						onChange: function() {
							var dialog = this.getDialog(),
								values = dialog.getContentElement( 'info', 'cmbValue' ),
								optName = dialog.getContentElement( 'info', 'txtOptName' ),
								optValue = dialog.getContentElement( 'info', 'txtOptValue' ),
								iIndex = getSelectedIndex( this );

							setSelectedIndex( values, iIndex );
							optName.setValue( this.getValue() );
							optValue.setValue( values.getValue() );
						},
						setup: function( name, element ) {
							if ( name == 'clear' )
								removeAllOptions( this );
							else if ( name == 'option' )
								addOption( this, element.getText(), element.getText(), this.getDialog().getParentEditor().document );
						},
						commit: function( element ) {
							var dialog = this.getDialog(),
								optionsNames = getOptions( this ),
								optionsValues = getOptions( dialog.getContentElement( 'info', 'cmbValue' ) ),
								selectValue = dialog.getContentElement( 'info', 'txtValue' ).getValue();

							removeAllOptions( element );

							for ( var i = 0; i < optionsNames.count(); i++ ) {
								var oOption = addOption( element, optionsNames.getItem( i ).getValue(), optionsValues.getItem( i ).getValue(), dialog.getParentEditor().document );
								if ( optionsValues.getItem( i ).getValue() == selectValue ) {
									oOption.setAttribute( 'selected', 'selected' );
									oOption.selected = true;
								}
							}
						}
					}
					]
				},
					{
					type: 'vbox',
					children: [
						{
						id: 'txtOptValue',
						type: 'text',
						label: editor.lang.tdforms.tdselect.opValue,
						style: 'width:115px',
						setup: function( name, element ) {
							if ( name == 'clear' )
								this.setValue( "" );
						}
					},
						{
						type: 'select',
						id: 'cmbValue',
						label: '',
						size: 5,
						style: 'width:115px;height:75px',
						items: [],
						onChange: function() {
							var dialog = this.getDialog(),
								names = dialog.getContentElement( 'info', 'cmbName' ),
								optName = dialog.getContentElement( 'info', 'txtOptName' ),
								optValue = dialog.getContentElement( 'info', 'txtOptValue' ),
								iIndex = getSelectedIndex( this );

							setSelectedIndex( names, iIndex );
							optName.setValue( names.getValue() );
							optValue.setValue( this.getValue() );
						},
						setup: function( name, element ) {
							if ( name == 'clear' )
								removeAllOptions( this );
							else if ( name == 'option' ) {
								var oValue = element.getValue();
								addOption( this, oValue, oValue, this.getDialog().getParentEditor().document );
								if ( element.getAttribute( 'selected' ) == 'selected' )
									this.getDialog().getContentElement( 'info', 'txtValue' ).setValue( oValue );
							}
						}
					}
					]
				},
					{
					type: 'vbox',
					padding: 5,
					children: [
						{
						type: 'button',
						id: 'btnAdd',
						style: '',
						label: editor.lang.tdforms.tdselect.btnAdd,
						title: editor.lang.tdforms.tdselect.btnAdd,
						style: 'width:100%;',
						onClick: function() {
							//Add new option.
							var dialog = this.getDialog(),
								parentEditor = dialog.getParentEditor(),
								optName = dialog.getContentElement( 'info', 'txtOptName' ),
								optValue = dialog.getContentElement( 'info', 'txtOptValue' ),
								names = dialog.getContentElement( 'info', 'cmbName' ),
								values = dialog.getContentElement( 'info', 'cmbValue' );
                                if(!optValue.getValue() || !optName.getValue()) {
                                    alert('选项值不能为空！');
                                    return false;
                                }
							addOption( names, optName.getValue(), optName.getValue(), dialog.getParentEditor().document );
							addOption( values, optValue.getValue(), optValue.getValue(), dialog.getParentEditor().document );

							optName.setValue( "" );
							optValue.setValue( "" );
						}
					},
						{
						type: 'button',
						id: 'btnModify',
						label: editor.lang.tdforms.tdselect.btnModify,
						title: editor.lang.tdforms.tdselect.btnModify,
						style: 'width:100%;',
						onClick: function() {
							//Modify selected option.
							var dialog = this.getDialog(),
								optName = dialog.getContentElement( 'info', 'txtOptName' ),
								optValue = dialog.getContentElement( 'info', 'txtOptValue' ),
								names = dialog.getContentElement( 'info', 'cmbName' ),
								values = dialog.getContentElement( 'info', 'cmbValue' ),
								iIndex = getSelectedIndex( names );

							if ( iIndex >= 0 ) {
								modifyOption( names, iIndex, optName.getValue(), optName.getValue() );
								modifyOption( values, iIndex, optValue.getValue(), optValue.getValue() );
							}
						}
					},
						{
						type: 'button',
						id: 'btnUp',
						style: 'width:100%;',
						label: editor.lang.tdforms.tdselect.btnUp,
						title: editor.lang.tdforms.tdselect.btnUp,
						onClick: function() {
							//Move up.
							var dialog = this.getDialog(),
								names = dialog.getContentElement( 'info', 'cmbName' ),
								values = dialog.getContentElement( 'info', 'cmbValue' );

							changeOptionPosition( names, -1, dialog.getParentEditor().document );
							changeOptionPosition( values, -1, dialog.getParentEditor().document );
						}
					},
						{
						type: 'button',
						id: 'btnDown',
						style: 'width:100%;',
						label: editor.lang.tdforms.tdselect.btnDown,
						title: editor.lang.tdforms.tdselect.btnDown,
						onClick: function() {
							//Move down.
							var dialog = this.getDialog(),
								names = dialog.getContentElement( 'info', 'cmbName' ),
								values = dialog.getContentElement( 'info', 'cmbValue' );

							changeOptionPosition( names, 1, dialog.getParentEditor().document );
							changeOptionPosition( values, 1, dialog.getParentEditor().document );
						}
					}
					]
				}
				]
			},
				{
				type: 'hbox',
				widths: [ '40%', '20%', '40%' ],
				children: [
					{
					type: 'button',
					id: 'btnSetValue',
					label: editor.lang.tdforms.tdselect.btnSetValue,
					title: editor.lang.tdforms.tdselect.btnSetValue,
					onClick: function() {
						//Set as default value.
						var dialog = this.getDialog(),
							values = dialog.getContentElement( 'info', 'cmbValue' ),
							txtValue = dialog.getContentElement( 'info', 'txtValue' );
						txtValue.setValue( values.getValue() );
					}
				},
					{
					type: 'button',
					id: 'btnDelete',
					label: editor.lang.tdforms.tdselect.btnDelete,
					title: editor.lang.tdforms.tdselect.btnDelete,
					onClick: function() {
						// Delete option.
						var dialog = this.getDialog(),
							names = dialog.getContentElement( 'info', 'cmbName' ),
							values = dialog.getContentElement( 'info', 'cmbValue' ),
							optName = dialog.getContentElement( 'info', 'txtOptName' ),
							optValue = dialog.getContentElement( 'info', 'txtOptValue' );

						removeSelectedOptions( names );
						removeSelectedOptions( values );

						optName.setValue( "" );
						optValue.setValue( "" );
					}
				},
					{
					id: 'chkMulti',
					type: 'checkbox',
					label: editor.lang.tdforms.tdselect.chkMulti,
					'default': '',
					accessKey: 'M',
					value: "checked",
					setup: function( name, element ) {
						if ( name == 'select' )
							this.setValue( element.getAttribute( 'multiple' ) );
						if ( CKEDITOR.env.webkit )
							this.getElement().getParent().setStyle( 'vertical-align', 'middle' );
					},
					commit: function( element ) {
						if ( this.getValue() )
							element.setAttribute( 'multiple', this.getValue() );
						else
							element.removeAttribute( 'multiple' );
					}
				}
				]
			}
			]
		}
		]
	};
});
