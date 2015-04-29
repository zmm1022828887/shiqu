/**
 * @license Copyright (c) 2003-2012, CKSource - Frederico Knabben. All rights reserved.
 * For licensing, see LICENSE.html or http://ckeditor.com/license
 */
CKEDITOR.dialog.add( 'tdtextarea', function( editor ) {
	return {
		title: editor.lang.tdforms.tdtextarea.dialogTitle,
		minWidth: 350,
		minHeight: 220,
		onShow: function() {
			delete this.textarea;

			var element = this.getParentEditor().getSelection().getSelectedElement();
			if ( element && element.getName() == "textarea" ) {
				this.textarea = element;
				this.setupContent( element );
			}
		},
		onOk: function() {
			var editor,
				element = this.textarea,
				widget_json = '',
				field_name = this.getValueOf( 'info', 'title'),
				field_type = 'textarea',
				isInsertMode = !element;
				cols = this.getValueOf( 'info', 'cols');
				rows = this.getValueOf( 'info', 'rows');
				dv = this.getValueOf( 'info', 'value');
				widget_json += '{' + '"field_name":' + '"' + field_name + '",' + '"field_type":' + '"' + field_type + '",'
								+ '"field_attr":' + '{' + '"title":' + '"' + field_name + '",' + '"class":' + '"' + field_type + '",'
								+ '"cols":' + '"' + cols + '",' + '"rows":' + '"' + rows + '",' + '"value":' + '"' + dv + '"' + '}}';

			if ( isInsertMode ) {
				editor = this.getParentEditor();
				element = editor.document.createElement( 'textarea' );
				var nameItem = $.ajax({ type: "post",url: g_requestURL,data: {fieldInfo:widget_json,formID:g_form_id},cache: false,async: false}).responseText;
				element.setAttribute( 'name', "data_"+nameItem );
				element.setAttribute( 'id', 'data_'+nameItem);
			}
			this.commitContent( element );

			if ( isInsertMode ) {
				editor.insertElement( element );
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
				id: 'title',
				type: 'text',
				label: editor.lang.common.controlName,
				validate:CKEDITOR.dialog.validate.notEmpty( editor.lang.common.validateControlFailed ),
				'default': '',
				accessKey: 'N',
				setup: function( element ) {
					this.setValue( element.getAttribute( 'title' ) || '' );
				},
				commit: function( element ) {
					if ( this.getValue() )
						element.setAttribute( 'title', this.getValue() );
				}
			},
				{
				type: 'hbox',
				widths: [ '50%', '50%' ],
				children: [
					{
					id: 'cols',
					type: 'text',
					label: editor.lang.tdforms.tdtextarea.cols,
					'default': '',
					accessKey: 'C',
					style: 'width:50px',
					validate: CKEDITOR.dialog.validate.integer( editor.lang.common.validateNumberFailed ),
					setup: function( element ) {
						var value = element.hasAttribute( 'cols' ) && element.getAttribute( 'cols' );
						this.setValue( value || '' );
					},
					commit: function( element ) {
						if ( this.getValue() )
							element.setAttribute( 'cols', this.getValue() );
						else
							element.removeAttribute( 'cols' );
					}
				},
					{
					id: 'rows',
					type: 'text',
					label: editor.lang.tdforms.tdtextarea.rows,
					'default': '',
					accessKey: 'R',
					style: 'width:50px',
					validate: CKEDITOR.dialog.validate.integer( editor.lang.common.validateNumberFailed ),
					setup: function( element ) {
						var value = element.hasAttribute( 'rows' ) && element.getAttribute( 'rows' );
						this.setValue( value || '' );
					},
					commit: function( element ) {
						if ( this.getValue() )
							element.setAttribute( 'rows', this.getValue() );
						else
							element.removeAttribute( 'rows' );
					}
				}
				]
			},
			{
				id: 'value',
				type: 'textarea',
				label: editor.lang.common.value,
				'default': '',
				setup: function( element ) {
					this.setValue( element.$.defaultValue );
				},
				commit: function( element ) {
					element.$.value = element.$.defaultValue = this.getValue();
				}
			}

			]
		}
		]
	};
});
