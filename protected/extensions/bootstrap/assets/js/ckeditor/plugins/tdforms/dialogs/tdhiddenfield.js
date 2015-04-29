/**
 * @license Copyright (c) 2003-2012, CKSource - Frederico Knabben. All rights reserved.
 * For licensing, see LICENSE.html or http://ckeditor.com/license
 */
CKEDITOR.dialog.add( 'tdhiddenfield', function( editor ) {
	return {
		title: editor.lang.tdforms.tdhidden.dialogTitle,
		hiddenField: null,
		minWidth: 350,
		minHeight: 110,
		onShow: function() {
			delete this.tdhiddenField;

			var editor = this.getParentEditor(),
				selection = editor.getSelection(),
				element = selection.getSelectedElement();

			if ( element && element.data( 'cke-real-element-type' ) && element.data( 'cke-real-element-type' ) == 'tdhiddenfield' ) {
				this.tdhiddenField = element;
				element = editor.restoreRealElement( this.tdhiddenField );
				this.setupContent( element );
				selection.selectElement( this.tdhiddenField );
			}
		},
		onOk: function() {
			var widget_json = '',
				field_name = this.getValueOf( 'info', 'title' ),
				field_type = 'hidden',
				element = this.tdhiddenField,
				isInsertMode = !element;
				editor = this.getParentEditor(),
				element = CKEDITOR.env.ie && !( CKEDITOR.document.$.documentMode >= 8 ) ? editor.document.createElement( '<input title="' + CKEDITOR.tools.htmlEncode( name ) + '">' ) : editor.document.createElement( 'input' );
				widget_json += '{' + '"field_name":' + '"' + field_name + '",' + '"field_type":' + '"' + field_type + '",'
								+ '"field_attr":' + '{' + '"title":' + '"' + field_name + '",' + '"class":' + '"' +field_type + '",'
								+ '"value":' + '"' + this.getValueOf('info','value') + '"' + '}}';
				element.setAttribute( 'type', 'hidden' );
			if( isInsertMode ) {
				var nameItem = $.ajax({ type: "post",url: g_requestURL,data: {fieldInfo:widget_json,formID:g_form_id},cache: false,async: false}).responseText;
				element.setAttribute( 'name', "data_"+nameItem );
				element.setAttribute( 'id', "data_"+nameItem );
			} else {

				//editor.restoreRealElement( this.hiddenField )
				var attrname = editor.restoreRealElement( this.tdhiddenField ).$.getAttribute( 'name' );
				var field_order = attrname ? attrname.split('_')[1] : '';
				if( field_order == '') {
					alert( '属性缺失，请检查！');
					return false;
				} else {
					element.setAttribute( 'name', "data_"+field_order );
					element.setAttribute( 'id', "data_"+field_order );
					$.ajax({ type: "post",url: g_requestURL,data: {fieldInfo:widget_json,formID:g_form_id,fieldOrder:field_order},cache: false,async: false});
				}
			}
			this.commitContent( element );
			var fakeElement = editor.createFakeElement( element, 'cke_hidden', 'tdhiddenfield' );
			if ( !this.tdhiddenField ) {
				editor.insertElement( fakeElement );
			}
			else {
				fakeElement.replace( this.tdhiddenField );
				editor.getSelection().selectElement( fakeElement );
			}

			return true;
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
							else {
								element.removeAttribute( 'title' );
							}
						}
					},
					{
						id: 'value',
						type: 'text',
						label: editor.lang.common.value,
						'default': '',
						accessKey: 'V',
						setup: function( element ) {
							this.setValue( element.getAttribute( 'value' ) || '' );
						},
						commit: function( element ) {
							if ( this.getValue() )
								element.setAttribute( 'value', this.getValue() );
							else
								element.removeAttribute( 'value' );
						}
					}
				]
			}
		]
	};
});
