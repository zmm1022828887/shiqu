/**
 * @license Copyright (c) 2003-2012, CKSource - Frederico Knabben. All rights reserved.
 * For licensing, see LICENSE.html or http://ckeditor.com/license
 */
CKEDITOR.dialog.add( 'writesign', function( editor ) {
    var id = '';
    if(editor.getSelection().getSelectedElement())
        id = editor.getSelection().getSelectedElement().getAttribute( 'name' ).split('_')[1];
    else
        id = 'new';
    return {
        title: editor.lang.tdforms.writesign.dialogTitle,
        minWidth: 350,
        minHeight: 220,
        onShow: function() {

            delete this.writesign;

            var element = this.getParentEditor().getSelection().getSelectedElement();
            if ( element && element.getName() == "img" && element.getAttribute('class') == 'writesign') {
                this.writesign = element;
                this.setupContent( element );
            }
        },
        onOk: function() {
            var editor,
            element = this.writesign,
            widget_json = '',
            field_name = this.getValueOf( 'info', 'title'),
            field_type = this.getValueOf( 'info', 'type'),
            hand_color = field_type == 'handwrite' ? $('#sign_color').val() : "";
            isInsertMode = !element,
            dv = "";
            $(window.frames["writesigniframe"].document).find(".ms-selection .ms-selected").each(function(){
                var field_order = $(this).attr('id').replace(/[^\d]/g,'');
                dv += field_order+',';
            });
            dv = dv.substr(dv.length-1) == ',' ? dv.substr(0,dv.length-1) : dv;
            widget_json += '{' + '"field_name":' + '"' + field_name + '",' + '"field_type":' + '"' + field_type + '",'
            + '"field_attr":' + '{' + '"title":' + '"' + field_name + '",' + '"class":' + '"' + field_type + '",'
            + '"value":' + '"' + dv + '",' + '"hand_color":' + '"' + hand_color + '"' + '}}';
            if ( isInsertMode ) {
                editor = this.getParentEditor();
                element = editor.document.createElement( 'img' );
                element.setAttribute( 'src', baseUrl+'/images/form/writesign.png');
                element.setAttribute( 'class', 'writesign' );
                element.setAttribute( 'title', field_name);
                element.setAttribute('hand_color',hand_color);
                element.setAttribute( 'value', dv);

                var nameItem = $.ajax({
                    type: "post",
                    url: g_requestURL,
                    data: {
                        fieldInfo:widget_json,
                        formID:g_form_id
                    },
                    cache: false,
                    async: false
                }).responseText;
                element.setAttribute( 'name', "data_"+nameItem );
                element.setAttribute( 'id', 'data_'+nameItem);
            }
            this.commitContent( element );

            if ( isInsertMode ) {
                editor.insertElement( element );
            }

            if ( !isInsertMode ) {
                element.setAttribute( 'value', dv);
                element.setAttribute('hand_color',hand_color);
                var field_order = element.getAttribute( 'name' ).split('_')[1];
                $.ajax({
                    type: "post",
                    url: g_requestURL,
                    data: {
                        fieldInfo:widget_json,
                        formID:g_form_id,
                        fieldOrder:field_order
                    },
                    cache: false,
                    async: false
                });
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
                labelLayout:'horizontal',
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
                label: editor.lang.tdforms.writesign.controlType,
                'default': 'addseal',
                labelLayout:'horizontal',
                accessKey: 'N',
                items: [
                [ editor.lang.tdforms.writesign.addSeal,	'addseal' ],
                [ editor.lang.tdforms.writesign.handWrite,	'handwrite' ]
                ],
                setup: function( element ) {
                    this.setValue( element.getAttribute( 'type' ) || '' );
                },
                onChange: function() {
                    var ctype = this.getDialog().getValueOf( 'info', 'type');
                    if(ctype=='handwrite')
                        $('#hand_write').show();
                    else
                        $('#hand_write').hide();
                },
                commit: function( element ) {
                    if ( this.getValue() )
                        element.setAttribute( 'type', this.getValue() );
                }
            },
            {
                type:'html',
                html:'<div role="presentation" class="cke_dialog_ui_select" style="display:none;" id="hand_write"><table role="presentation" class="cke_dialog_ui_hbox"><tbody><tr class="cke_dialog_ui_hbox"><td class="cke_dialog_ui_hbox_first" role="presentation" style="width:50%; padding:0px"><label class="cke_dialog_ui_labeled_label">' + CKEDITOR.tools.htmlEncode( editor.lang.tdforms.writesign.handColor ) + '</label></td><td class="cke_dialog_ui_hbox_last" role="presentation" style="width:50%; padding:0px"><span class="cke_dialog_ui_labeled_content"><div class="cke_dialog_ui_input_select"><select id="sign_color" class="cke_dialog_ui_input_select"><option style="background-color:red" value="0x0000FF">红色</option><option style="background-color:green" value="0x00FF00">绿色</option> <option style="background-color:blue" value="0xFF0000">蓝色</option> <option style="background-color:black" value="0x000000">黑色</option> <option style="background-color:white" value="0xFFFFFF">白色</option></select></div></span></td></tr></tbody></table></div>',
                onShow: function(){
                    if(editor.getSelection().getSelectedElement()) {
                        var selectedColor = editor.getSelection().getSelectedElement().getAttribute( 'hand_color' );
                        if($('#sign_color').find('option[value="'+selectedColor+'"]').length > 0) {
                            $('#sign_color').find('option[value="'+selectedColor+'"]').attr('selected',true);
                        }
                    } else {
                         $('#hand_write').hide();
                    }
                }
            },
            {
                type: 'html',
                html: "<div><p style='font-size:small'>" + CKEDITOR.tools.htmlEncode( editor.lang.common.signatureTip ) + "</p></div>"
            },
            {
                onShow: function() {
                    var selement = editor.getSelection().getSelectedElement();
                    var eclass = selement ? selement.getAttribute( 'class' ) : '';
                    id =  selement ? selement.getAttribute( 'name' ).split('_')[1] : '';
                    g_loadFormFieldURL = g_loadFormFieldURL.replace('_formID',g_form_id);
                    g_loadFormFieldURL = g_loadFormFieldURL.replace('_randomNum',Math.floor(Math.random()*100000));
                    if($("#writesigniframe").attr("src") != '#') {
                        var a = $("#writesigniframe").attr("src").split('/');
                        for(var i=0;i<a.length;i++){
                            if(a[i] == 'fieldOrder')
                                a[i+1] = '_fieldOrder';
                        }
                        g_loadFormFieldURL = a.join('/');
                    }
                    if(id && eclass == 'writesign')
                        g_loadFormFieldURL = g_loadFormFieldURL.replace('_fieldOrder',id);
                    else
                        g_loadFormFieldURL = g_loadFormFieldURL.replace('_fieldOrder','new');
                    $("#writesigniframe").attr("src",g_loadFormFieldURL);
                },
                type: 'html',
                html:'<iframe style="width:500px;height:220px" id="writesigniframe" name="writesigniframe" src="#"></iframe>'
            }
            ]
        }
        ]
    };
});