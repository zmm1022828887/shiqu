/**
 * @license Copyright (c) 2003-2012, CKSource - Frederico Knabben. All rights reserved.
 * For licensing, see LICENSE.html or http://ckeditor.com/license
 */
CKEDITOR.dialog.add( 'dimensionalcode', function( editor ) {
	var id = '';
	if(editor.getSelection().getSelectedElement())
		id = editor.getSelection().getSelectedElement().getAttribute( 'name' ).split('_')[1];
	else
		id = 'new';
	return {
		title: editor.lang.tdforms.dimensionalcode.dialogTitle,
		minWidth: 350,
		minHeight: 220,
		onShow: function() {

			delete this.dimensionalcode;

			var element = this.getParentEditor().getSelection().getSelectedElement();
			if ( element && element.getName() == "img" && element.getAttribute('class') == 'dimensionalcode') {
				this.dimensionalcode = element;
				this.setupContent( element );
			}
		},
		onOk: function() {
			var editor,
				element = this.dimensionalcode,
				widget_json = '',
				field_name = this.getValueOf( 'info', 'title'),
				field_type = this.getValueOf( 'info', 'type'),//'qrcode',
				isInsertMode = !element,
				dv = "";
				$(window.frames["dimensionalcodeiframe"].document).find(".ms-selection .ms-selected").each(function(){
					var field_order = $(this).attr('id').replace(/[^\d]/g,'');
					dv += field_order+',';
				});
				dv = dv.substr(dv.length-1) == ',' ? dv.substr(0,dv.length-1) : dv;
				widget_json += '{' + '"field_name":' + '"' + field_name + '",' + '"field_type":' + '"' + field_type + '",'
								+ '"field_attr":' + '{' + '"title":' + '"' + field_name + '",' + '"class":' + '"' + field_type + '",'
								+ '"value":' + '"' + dv + '"' + '}}';

			if ( isInsertMode ) {
				editor = this.getParentEditor();
				element = editor.document.createElement( 'img' );
				element.setAttribute( 'src', baseUrl+'/images/form/dimensionalcode.png');
				element.setAttribute( 'class', 'dimensionalcode' );
				element.setAttribute( 'codeclass', field_type);
				element.setAttribute( 'title', field_name);
				element.setAttribute( 'value', dv);

				var nameItem = $.ajax({ type: "post",url: g_requestURL,data: {fieldInfo:widget_json,formID:g_form_id},cache: false,async: false}).responseText;
				element.setAttribute( 'name', "data_"+nameItem );
				element.setAttribute( 'id', 'data_'+nameItem);
			}
			this.commitContent( element );

			if ( isInsertMode ) {
				editor.insertElement( element );
			}

			if ( !isInsertMode ) {
				element.setAttribute( 'value', dv);
				element.setAttribute( 'codeclass', field_type);
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
						labelLayout:'horizontal',
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
						id: 'type',
						type: 'select',
						labelLayout:'horizontal',
						label: editor.lang.tdforms.dimensionalcode.type,
						items: [
							//[ 'BARCODE',	'barcode' ],
							[ 'PDF417',  'pdf417'],
							[ 'QRCODE',	'qrcode' ]
							],
						setup: function( element ) {
							this.setValue( element.getAttribute( 'codeclass' ) );
						}
					},
					{
						type: 'html',
						html: "<div><p style='font-size:small'>" + CKEDITOR.tools.htmlEncode( editor.lang.common.qrcodeTip ) + "</p></div>"
					},
					{
						onShow: function() {
							var selement = editor.getSelection().getSelectedElement();
							var eclass = selement ? selement.getAttribute( 'class' ) : '';
							id =  selement ? selement.getAttribute( 'name' ).split('_')[1] : '';
                            g_loadFormFieldURL = g_loadFormFieldURL.replace('_formID',g_form_id);
                            g_loadFormFieldURL = g_loadFormFieldURL.replace('_randomNum',Math.floor(Math.random()*100000));
                            if($("#dimensionalcodeiframe").attr("src") != '#') {
                                var a = $("#dimensionalcodeiframe").attr("src").split('/');
                                for(var i=0;i<a.length;i++){
                                    if(a[i] == 'fieldOrder')
                                        a[i+1] = '_fieldOrder';
                                }
                                g_loadFormFieldURL = a.join('/');
                            }
							if(id && eclass == 'dimensionalcode')
                                g_loadFormFieldURL = g_loadFormFieldURL.replace('_fieldOrder',id);
							else
                                g_loadFormFieldURL = g_loadFormFieldURL.replace('_fieldOrder','new');
							$("#dimensionalcodeiframe").attr("src",g_loadFormFieldURL);
						},
						type: 'html',
						html:'<iframe style="width:500px;height:220px" id="dimensionalcodeiframe" name="dimensionalcodeiframe" src="#"></iframe>'
					}
				]
			}
		]
	};
});