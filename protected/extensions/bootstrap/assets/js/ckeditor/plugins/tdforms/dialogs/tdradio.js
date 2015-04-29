/**
 * @license Copyright (c) 2003-2012, CKSource - Frederico Knabben. All rights reserved.
 * For licensing, see LICENSE.html or http://ckeditor.com/license
 */
CKEDITOR.dialog.add( 'tdradio', function( editor ) {
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
/******************************************************/
	return {
		title: editor.lang.tdforms.tdradio.dialogTitle,
		minWidth: 350,
		minHeight: 140,
		onShow: function() {
			delete this.radioButton;

			var element = this.getParentEditor().getSelection().getSelectedElement() ? this.getParentEditor().getSelection().getSelectedElement().getParent() : this.getParentEditor().getSelection().getSelectedElement();
			if ( element && element.getName() == 'label') {
				this.radioButton = element;
				this.element = element;
				this.setupContent( element );
			}
		},
		onOk: function() {
			var editor,widget_json = '',
				field_name = this.getValueOf( 'info', 'name' ),
				field_type = 'label',
				element = this.radioButton,
				isInsertMode = !element;
				widget_json += '{' + '"field_name":' + '"' + field_name + '",' + '"field_type":' + '"' + field_type + '",'
								+ '"field_attr":' + '{' + '"title":' + '"' + field_name + '",' + '"class":' + '"' +field_type + '",'
								+ '"value":' + '"' + this.getValueOf('info', 'value') + '"' + '}}';
			var optionsNames = getOptions( this.getContentElement( 'info', 'cmbName' ) );
			var defaultV = this.getValueOf( 'info', 'value' );
			if ( isInsertMode ) {
				editor = this.getParentEditor();
				element = editor.document.createElement( 'label' );
				for ( var i = 0; i < optionsNames.count(); i++ ) {
					var raidoValue = optionsNames.getItem( i ).getValue();
					if( defaultV == raidoValue )
						element.appendHtml("<input type='radio' checked value='"+raidoValue+"'></input>");
					else
						element.appendHtml("<input type='radio' value='"+raidoValue+"'></input>");
				}
				var nameItem = $.ajax({ type: "post",url: g_requestURL,data: {fieldInfo:widget_json,formID:g_form_id},cache: false,async: false}).responseText;
				element.setAttribute( 'name', "data_"+nameItem );
				element.setAttribute( 'id', 'data_'+nameItem);
			}

			if ( isInsertMode )
				editor.insertElement( element );

			if( !isInsertMode ) {
				var element = this.getParentEditor().getSelection().getSelectedElement().getParent();
				var labelname = element.getAttribute( 'name' );
				var field_order = labelname.split("_")[1];
				element.remove();
				editor = this.getParentEditor();
				element = editor.document.createElement( 'label' );
				for ( var i = 0; i < optionsNames.count(); i++ ) {
					var raidoValue = optionsNames.getItem( i ).getValue();
					if( defaultV == raidoValue )
						element.appendHtml("<input type='radio' checked value='"+raidoValue+"'></input>");
					else
						element.appendHtml("<input type='radio' value='"+raidoValue+"'></input>");
				}
				$.ajax({ type: "post",url: g_requestURL,data: {fieldInfo:widget_json,formID:g_form_id,fieldOrder:field_order},cache: false,async: false});
				element.setAttribute( 'name', labelname );
				element.setAttribute( 'id', labelname );
				editor.insertElement( element );
			}
			this.commitContent({ element: element } );
		},
		contents: [
			{
			id: 'info',
			elements: [
				{
				id: 'name',
				type: 'text',
				label: editor.lang.common.controlName,
				validate:CKEDITOR.dialog.validate.notEmpty( editor.lang.common.validateControlFailed ),
				'default': '',
				accessKey: 'N',
				setup: function( element ) {
					this.setValue( element.getAttribute( 'title' ));
				},
				commit: function( data ) {
					var element = data.element;

					if ( this.getValue() )
						element.setAttribute( 'title', this.getValue());
				}
			},
			{
				id: 'value',
				type: 'text',
				label: editor.lang.common.value,
				'default': '',
				accessKey: 'V',
				onLoad: function() {
					this.getInputElement().setAttribute( 'readOnly', true );
				},
				setup: function( element ) {
					this.setValue( element.getAttribute( 'value' ) || '' );
				},
				commit: function( data ) {
					var element = data.element;

					if ( this.getValue() )
						element.setAttribute( 'value', this.getValue() );
					else
						element.removeAttribute( 'value' );
				}
			},
			{
				type: 'html',
				html: '<span>' + CKEDITOR.tools.htmlEncode( editor.lang.common.tdradio ) + '</span>'
			},
			/******************************************************/
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
						style: 'width:175px',
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
						style: 'width:175px;height:75px',
						items: [],
						onShow: function( element ) {
							if(this.getDialog().getParentEditor().getSelection().getSelectedElement()) {
								var childelement = this.getDialog().getParentEditor().getSelection().getSelectedElement().getParent().getChildren();
								var dvalue = this.getDialog().getValueOf('info','value');
								jQuery("#"+this.getDialog().getContentElement( 'info', 'cmbName' ).domId).find("select").empty();
								for ( var i = 0; i < childelement.count(); i++ ) {

									var oOption = document.createElement('OPTION');
									var oOptionValue = childelement.getItem( i ).getValue();
										oOption.innerHTML = childelement.getItem( i ).getValue();
										oOption.value = childelement.getItem( i ).getValue();

									jQuery("#"+this.getDialog().getContentElement( 'info', 'cmbName' ).domId).find("select").append(oOption);
									if ( childelement.getItem( i ).getValue() == dvalue ) {
										oOption.setAttribute( 'selected', 'selected' );
									}
								}
							}
						},
						onChange: function() {
							var dialog = this.getDialog(),
								optName = dialog.getContentElement( 'info', 'txtOptName' ),
								iIndex = getSelectedIndex( this );
							if( this.getValue() )
								optName.setValue( this.getValue() );
						},
						setup: function( name, element ) {
							if ( name == 'clear' )
								removeAllOptions( this );
							else if ( name == 'option' )
								addOption( this, element.getText(), element.getText(), this.getDialog().getParentEditor().document );
						},
						commit: function( element ) {
							var dialog = this.getDialog(),
								optionsNames = getOptions( this );
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
							var dialog = this.getDialog(),
								parentEditor = dialog.getParentEditor(),
								optName = dialog.getContentElement( 'info', 'txtOptName' ),
								names = dialog.getContentElement( 'info', 'cmbName' );
							//验证重复TODO...
							if( optName.getValue() )
								addOption( names, optName.getValue(), optName.getValue(), dialog.getParentEditor().document );
							else
								alert('请填写选项文本！');

							optName.setValue( "" );
						}
					},
						{
						type: 'button',
						id: 'btnModify',
						label: editor.lang.tdforms.tdselect.btnModify,
						title: editor.lang.tdforms.tdselect.btnModify,
						style: 'width:100%;',
						onClick: function() {
							var dialog = this.getDialog(),
								optName = dialog.getContentElement( 'info', 'txtOptName' ),
								names = dialog.getContentElement( 'info', 'cmbName' ),
								iIndex = getSelectedIndex( names );

							if ( iIndex >= 0 ) {
								modifyOption( names, iIndex, optName.getValue(), optName.getValue() );
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
							var dialog = this.getDialog(),
								names = dialog.getContentElement( 'info', 'cmbName' );

							changeOptionPosition( names, -1, dialog.getParentEditor().document );
						}
					},
						{
						type: 'button',
						id: 'btnDown',
						style: 'width:100%;',
						label: editor.lang.tdforms.tdselect.btnDown,
						title: editor.lang.tdforms.tdselect.btnDown,
						onClick: function() {
							var dialog = this.getDialog(),
								names = dialog.getContentElement( 'info', 'cmbName' );

							changeOptionPosition( names, 1, dialog.getParentEditor().document );
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
						var dialog = this.getDialog(),
							names = dialog.getContentElement( 'info', 'cmbName' ),
							txtValue = dialog.getContentElement( 'info', 'value' );
						txtValue.setValue( names.getValue() );
					}
				},
					{
					type: 'button',
					id: 'btnDelete',
					label: editor.lang.tdforms.tdselect.btnDelete,
					title: editor.lang.tdforms.tdselect.btnDelete,
					onClick: function() {
						var dialog = this.getDialog(),
							names = dialog.getContentElement( 'info', 'cmbName' ),
							selectValue = dialog.getValueOf( 'info', 'value' );
							selectElement = dialog.getContentElement( 'info', 'value'),
							optName = dialog.getContentElement( 'info', 'txtOptName' );

							optName.setValue( "" );
						if(selectValue == names.getValue() )
							selectElement.setValue( "" );
						removeSelectedOptions( names );

					}
				}
				]
			}
			]
		}
		]
	};
});
