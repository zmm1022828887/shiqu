/**
 * @license Copyright (c) 2003-2012, CKSource - Frederico Knabben. All rights reserved.
 * For licensing, see LICENSE.html or http://ckeditor.com/license
 */
//日历控件
CKEDITOR.dialog.add( 'cdxdtpicker', function( editor ) {
	var acceptedTypes = { date:'yyyy-MM-dd',datetime:'yyyy-MM-dd HH:mm:ss',custom:1};
	return {
		title: editor.lang.tdforms.cdxdtpicker.dialogTitle,
		minWidth: 350,
		minHeight: 150,
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
				field_name = this.getValueOf( 'info', 'title'),//控件名称
				field_type = this.getValueOf( 'info', 'type' ),//日期类型
				format = '',
				field_attr = new Array,
				isInsertMode = !element;
			if( isInsertMode ) {
				element = editor.document.createElement( 'div' );
				element.setAttribute( 'class', 'input-prepend' );
			}
			if( field_type == 'custom' ) {
				format = $('#custom').val();
			}
			else
				format = acceptedTypes[this.getValueOf( 'info', 'type' )];
			widget_json += '{' + '"field_name":' + '"' + field_name + '",' + '"field_type":' + '"' + field_type + '",'
							+ '"field_attr":' + '{' + '"title":' + '"' + field_name + '",' + '"class":' + '"' +field_type + '",'
							+ '"format":' + '"' + format + '"' + '}}';
			var data = { element: element };

			if ( isInsertMode ) {
				var nameItem = $.ajax({ type: "post",url: g_requestURL,data: {fieldInfo:widget_json,formID:g_form_id},cache: false,async: false}).responseText;
				var str = '';
				str += "<span class='add-on'><i class='icon-calendar'></i></span>";
				str += "<input type='text' class='"+field_type+"' title='"+field_name+"' name='data_"+nameItem+"' id='data_"+nameItem+"' format = '"+format+"'></input>";
				editor.insertElement( data.element );
				$(data.element.$).append(str);
			}



			// Element might be replaced by commitment.
			if ( !isInsertMode ) {
				var field_order = element.getAttribute( 'name' ).split('_')[1];
				var element = editor.getSelection().getSelectedElement();//.getParent();
				var parentElement = element.getParent();
				if( element )
					$(element.$).remove();
				var str = '';
				str += "<input type='text' class='"+field_type+"' title='"+field_name+"' name='data_"+field_order+"' id='data_"+field_order+"' format = '"+format+"' />";
				$(parentElement.$).append(str);
				data = { element: parentElement};
				$.ajax({ type: "post",url: g_requestURL,data: {fieldInfo:widget_json,formID:g_form_id,fieldOrder:field_order},cache: false,async: false});

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
						labelLayout:'horizontal',
						'default': '',
						accessKey: 'N',
						setup: function( element ) {
							this.setValue( element.getAttribute('title'));
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
						labelLayout:'horizontal',
						'default': 'date',
						accessKey: 'M',
						items: [
							[ editor.lang.tdforms.cdxdtpicker.date,	'date' ],
							[ editor.lang.tdforms.cdxdtpicker.datetime,		'datetime' ],
							[ editor.lang.tdforms.cdxdtpicker.time,	'custom' ]
							],
						onShow: function() {
							var element = this.getDialog().getParentEditor().getSelection().getSelectedElement();
							if( element )
								this.setValue(element.getAttribute('class'));
						},
						setup: function( element ) {
							this.setValue( element.getAttribute( 'class' ) );
						},
						onChange: function() {
							if($("#wrap").length > 0)
								$("#wrap").remove();
							var dialog = this.getDialog(),
								dateType = dialog.getValueOf( 'info', 'type'),
								thisObj = dialog.getContentElement( 'info', 'type');
							var selectR = dialog.getParentEditor().getSelection().getSelectedElement();
							var custom = selectR ? selectR.getAttribute( 'class' ) : '';
							var customV = '';
							if( custom == 'custom')
								customV = selectR.getAttribute( 'format' );
							if( dateType == 'custom' ) {
								var str = "<div id='wrap'><br><input type='text' name='custom' id='custom' class='cke_dialog_ui_input_text' value='"+customV+"'></input></div>";
								$("#"+thisObj.domId).find("select").after(str);
							} else {
								if($("#wrap").length > 0)
									$("#wrap").remove();
							}
						},
						commit: function( ) {

						}
					},
					{
						type: 'html',
						html: "<div><tr class='cke_dialog_ui_hbox'><td>说明：<br>日历控件选择的日期、<br>时间将回填到该输入框中，<br>自定义格式详见《工作流使用详解》</td></tr></div>"
					}
				]
			}
		]
	};
});
