/**
 * @license Copyright (c) 2003-2012, CKSource - Frederico Knabben. All rights reserved.
 * For licensing, see LICENSE.html or http://ckeditor.com/license
 */
CKEDITOR.dialog.add( 'tdtextfield', function( editor ) {
	var acceptedTypes = { v_email:1,v_int:1,v_float:1,v_date:1,v_text:1 };
	function autoCommit( data ) {
		var element = data.element;
		var value = this.getValue();
		value ? element.setAttribute( this.id, value ) : element.removeAttribute( this.id );
	}
	function autoSetup( element ) {
		var value = element.hasAttribute( this.id ) && element.getAttribute( this.id );
		this.setValue( value || '' );
	}

	return {
		title: editor.lang.tdforms.tdtextfield.dialogTitle,
		minWidth: editor.lang.tdforms.tdtextfield.minWidth,
		minHeight: editor.lang.tdforms.tdtextfield.minHeight,
		onShow: function() {
			delete this.textField;

			var element = this.getParentEditor().getSelection().getSelectedElement();
			if ( element && element.getName() == "input" && ( acceptedTypes[ element.getAttribute( 'class' ) ] || !element.getAttribute( 'type' ) ) ) {
				this.textField = element;
				this.setupContent( element );
			}
		},
		onOk: function() {
			var editor = this.getParentEditor(),
				element = this.textField,
				widget_json = '',
				field_name = this.getValueOf( 'info', 'title'),
				field_type = this.getValueOf( 'info', 'type'),
				isInsertMode = !element;
				widget_json += '{' + '"field_name":' + '"' + field_name + '",' + '"field_type":' + '"' + field_type + '",'
								+ '"field_attr":' + '{' + '"title":' + '"' + field_name + '",' + '"class":' + '"' +field_type + '",'
								+ '"value":' + '"' +this.getValueOf( 'info', 'value') + '",' + '"size":' + '"' + this.getValueOf('info','size') + '",'
								+ '"maxLength":' + '"' + this.getValueOf('info','maxLength') + '"' +'}}';
			if ( isInsertMode ) {
				element = editor.document.createElement( 'input' );
				element.setAttribute( 'type', 'text' );
			}

			var data = { element: element };

			if ( isInsertMode ) {
				var nameItem = $.ajax({ type: "post",url: g_requestURL,data: {fieldInfo:widget_json,formID:g_form_id},cache: false,async: false}).responseText;
				element.setAttribute( 'name', 'data_'+nameItem );
				element.setAttribute( 'id', 'data_'+nameItem);
				editor.insertElement( data.element );
			}

			this.commitContent( data );

			// Element might be replaced by commitment.
			if ( !isInsertMode ) {
				var field_order = element.getAttribute( 'name' ).split('_')[1];
				$.ajax({ type: "post",url: g_requestURL,data: {fieldInfo:widget_json,formID:g_form_id,fieldOrder:field_order},cache: false,async: false});
				editor.getSelection().selectElement( data.element );
			}
		},
		onLoad: function() {
			this.foreach( function( contentObj ) {
				if ( contentObj.getValue ) {
					if ( !contentObj.setup )
						contentObj.setup = autoSetup;
					if ( !contentObj.commit )
						contentObj.commit = autoCommit;
				}
			});
		},
		contents: [
			{
			id: 'info',
			elements: [
				{
				type: 'hbox',
				widths: [ '50%', '50%' ],
				children: [
					{
						id: 'title',
						type: 'text',
						label: editor.lang.common.controlName,
						validate:CKEDITOR.dialog.validate.notEmpty( editor.lang.common.validateControlFailed ),
						'default': '',
						accessKey: 'N',
						setup: function( element ) {
							this.setValue( element.getAttribute( 'title' ) || '' );
						},
						commit: function( data ) {
							var element = data.element;
							if ( this.getValue() )
								element.setAttribute( 'title', this.getValue() );
						}
					},
					{
						id: 'value',
						type: 'text',
						label: editor.lang.common.value,
						'default': '',
						accessKey: 'V',
						commit: function( data ) {
							if ( CKEDITOR.env.ie && !this.getValue() ) {
								var element = data.element,
									fresh = new CKEDITOR.dom.element( 'input', editor.document );
								element.copyAttributes( fresh, { value:1 } );
								fresh.replace( element );
								data.element = fresh;
							} else
								autoCommit.call( this, data );
						}
					}
				]
			},
				{
				type: 'hbox',
				widths: [ '50%', '50%' ],
				children: [
					{
					id: 'size',
					type: 'text',
					label: editor.lang.tdforms.tdtextfield.charWidth,
					'default': '',
					accessKey: 'C',
					style: 'width:50px',
					validate: CKEDITOR.dialog.validate.integer( editor.lang.common.validateNumberFailed )
				},
					{
					id: 'maxLength',
					type: 'text',
					label: editor.lang.tdforms.tdtextfield.maxChars,
					'default': '',
					accessKey: 'M',
					style: 'width:50px',
					validate: CKEDITOR.dialog.validate.integer( editor.lang.common.validateNumberFailed )
				}
				],
				onLoad: function() {
					// Repaint the style for IE7 (#6068)
					if ( CKEDITOR.env.ie7Compat )
						this.getElement().setStyle( 'zoom', '100%' );
				}
			},
				{
				id: 'type',
				type: 'select',
				label: editor.lang.tdforms.tdtextfield.type,
				validate:CKEDITOR.dialog.validate.notEmpty( editor.lang.common.validateDataType ),
				'default': 'text',
				accessKey: 'M',
				items: [
					[ editor.lang.tdforms.tdtextfield.typeText,	'v_text' ],
					[ editor.lang.tdforms.tdtextfield.typeInt,	'v_int' ],
					[ editor.lang.tdforms.tdtextfield.typeFloat,'v_float' ],
					[ editor.lang.tdforms.tdtextfield.typeDate,	'v_date' ],
					[ editor.lang.tdforms.tdtextfield.typeEmail,'v_email' ]
					],
				setup: function( element ) {
					this.setValue( element.getAttribute( 'class' ) );
				},
				commit: function( data ) {
					var element = data.element;

					if ( CKEDITOR.env.ie ) {
						var elementType = element.getAttribute( 'class' );
						var myType = this.getValue();

						if ( elementType != myType ) {
							var replace = CKEDITOR.dom.element.createFromHtml( '<input type="text" class="' + myType + '"></input>', editor.document );
							element.copyAttributes( replace, { type:1 } );
							replace.replace( element );
							data.element = replace;
						}
					} else
						element.setAttribute( 'class', this.getValue() );
				}
			}
			]
		}
		]
	};
});
