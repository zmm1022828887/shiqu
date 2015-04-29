/**
 * @license Copyright (c) 2003-2012, CKSource - Frederico Knabben. All rights reserved.
 * For licensing, see LICENSE.html or http://ckeditor.com/license
 */
CKEDITOR.dialog.add( 'suseranddept', function( editor ) {
	var acceptedClass = { user:1,dept:1};
	return {
		title: editor.lang.tdforms.suseranddept.dialogTitle,
		minWidth: 350,
		minHeight: 150,
		onShow: function() {
			delete this.textField;

			var element = this.getParentEditor().getSelection().getSelectedElement();
			if ( element && element.getName() == "input" && ( acceptedClass[ element.getAttribute( 'class' ) ] || !element.getAttribute( 'type' ) ) ) {
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
								+ '"value":""' + '}}';
			if ( isInsertMode ) {
				element = editor.document.createElement( 'div');
				element.setAttribute( 'class', 'input-prepend' );
				var nameItem = $.ajax({ type: "post",url: g_requestURL,data: {fieldInfo:widget_json,formID:g_form_id},cache: false,async: false}).responseText;
				var str = '';
				var icon = field_type == 'user' ? 'icon-user' : 'icon-office';
				str += "<span class='add-on'><i class=" + icon + "></i></span>";
				str += "<input type='text' class='"+field_type+"' title='"+field_name+"' name='data_"+nameItem+"' id='data_"+nameItem+"' readonly></input>";
			}
			var data = { element: element };

			if ( isInsertMode ) {
				editor.insertElement( data.element );
				$(data.element.$).append(str);
			}



			// Element might be replaced by commitment.
			if ( !isInsertMode ) {
				var field_order = element.getAttribute( 'name' ).split('_')[1];
				var element = this.getParentEditor().getSelection().getSelectedElement();//.getParent();
				var parentElement = element.getParent();
				if( element )
					element.remove();

				var str = '';
				str += "<input type='text' class='"+field_type+"' title='"+field_name+"' name='data_"+field_id+"' id='data_"+field_id+"' readonly></input>";
				$.ajax({ type: "post",url: g_requestURL,data: {fieldInfo:widget_json,formID:g_form_id,fieldOrder:field_order},cache: false,async: false});
				$(parentElement.$).append(str);
				data = {element: parentElement };
			}
			this.commitContent( data );
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
						onShow: function( data ) {
							var element = this.getDialog().getParentEditor().getSelection().getSelectedElement();
							if( element )
								this.setValue(element.getAttribute('title'));
						},
						commit: function( data ) {
							var element = data.element;
							if ( this.getValue() )
								element.setAttribute( 'title', this.getValue() );
							else {
								element.removeAttribute( 'title' );
							}
						}
					},
					{
						id: 'type',
						type: 'select',
						label: editor.lang.common.value,
						'default': 'user',
						accessKey: 'M',
						items: [
							[ editor.lang.tdforms.suseranddept.user, 'user' ],
							[ editor.lang.tdforms.suseranddept.dept, 'dept' ]
							],
						onShow: function( element ) {
							var element = this.getDialog().getParentEditor().getSelection().getSelectedElement();
							if( element )
								this.setValue( element.getAttribute( 'class' ) );
						}
					}
				]
			}
		]
	};
});
