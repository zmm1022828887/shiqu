CKEDITOR.dialog.add( 'listview', function( editor ){
    var id = '';
    if(editor.getSelection().getSelectedElement())
        id = editor.getSelection().getSelectedElement().getAttribute( 'name' ).split('_')[1];
    else
        id = 'new';
    return {
        title: editor.lang.tdforms.listview.dialogTitle,
        minWidth:850,
        minHeight:350,
        onLoad: function () {
        },
        onShow: function() {
            delete this.listview;
            var element = this.getParentEditor().getSelection().getSelectedElement();
            if ( element && element.getName() == "img" && element.getAttribute('class') == 'listview') {
                this.listview = element;
                this.setupContent( element );
            }
        },
        onOk: function() {
            var editor,_trstr = '',
            element = this.listview,
            widget_json = '',
            field_type = 'listview',
            field_attr = '',
            field_name = this.getValueOf( 'info', 'title'),
            isInsertMode = !element;
            if ( isInsertMode ) {
                editor = this.getParentEditor();
                element = editor.document.createElement( 'img' );
                element.setAttribute( 'src', baseUrl+'/images/form/listview.png');
                element.setAttribute( 'class', 'listview' );
                element.setAttribute( 'title', field_name);
            }
            widget_json += '{' + '"field_name":' + '"' + field_name + '",' + '"field_type":' + '"' + field_type + '",';
            field_attr += '"field_attr":' + '{' + '"title":' + '"' + field_name + '",' + '"class":"listview",' + '"value":' + '{';
            $(window.frames["listiframe"].document).find("#custom_table tr:gt(0)").each(function(){
                var _index = 'tr_' + $(this).index();
                var serialnumber = $(this).find('td:first').text();
                var headerfield = $(this).find('input[name=headerfield]').val();
                var fieldwidth = $(this).find('input[name=fieldwidth]').val();
                var total = $(this).find('input[name=total]').attr('checked') == undefined ? 0 : 1;
                var computational = $(this).find('input[name=computational]').val();
                var fieldtype = $(this).find('select[name=fieldtype]').val();
                var defaultvalue = $(this).find('input[name=defaultvalue]').val();
                _trstr += '"' + _index + '"' + ":" + "{" + '"serialnumber":' + '"' + serialnumber + '",' + '"headerfield":' + '"' + headerfield + '",'
                + '"fieldwidth":' + '"' + fieldwidth + '",' + '"total":' + '"' + total + '",' + '"computational":' + '"' + computational + '",'
                + '"fieldtype":' + '"' + fieldtype + '",' + '"defaultvalue":' + '"' + defaultvalue + '"' + "},";

            });
            _trstr = _trstr.substr(_trstr.length-1) == ',' ? _trstr.substr(0,_trstr.length-1) : _trstr;
            _trstr += '}';
            field_attr += _trstr + '}';
            widget_json += field_attr + '}';
            if( isInsertMode ) {
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
                width: '100px',
                maxHeight:'200px',
                labelStyle: 'font-size:small',
                inputStyle: 'height:30px;width:100px;font-size:13px;line-height:18px',
                label: editor.lang.common.controlName,
                labelLayout:'horizontal',
                resizable : CKEDITOR.DIALOG_RESIZE_BOTH,
                validate:CKEDITOR.dialog.validate.notEmpty( editor.lang.common.validateControlFailed ),
                'default': '',
                setup: function() {

                },
                onShow: function() {
                    var element = this.getDialog().getParentEditor().getSelection().getSelectedElement();
                    if( element && element.getAttribute('class') == 'listview')
                        this.setValue( element.getAttribute( 'title' ));
                },
                commit: function( element ) {
                    element.setAttribute( 'title', this.getValue());
                }
            },
            {
                type: 'html',
                html: "<div><p style='font-size:small'>列表属性</p></div>"
            },
            {
                onShow: function() {
                    var selement = editor.getSelection().getSelectedElement();
                    var eclass = selement ? selement.getAttribute( 'class' ) : '';
                    id =  selement ? selement.getAttribute( 'name' ).split('_')[1] : '';
                    g_loadListViewURL = g_loadListViewURL.replace('_formID',g_form_id);
                    g_loadListViewURL = g_loadListViewURL.replace('_randomNum',Math.floor(Math.random()*100000));
                    if($("#listiframe").attr("src") != '#') {
                        var a = $("#listiframe").attr("src").split('/');
                        for(var i=0;i<a.length;i++){
                            if(a[i] == 'fieldOrder')
                                a[i+1] = '_fieldOrder';
                        }
                        g_loadListViewURL = a.join('/');
                    }
                    if(id && eclass == 'listview')
                        g_loadListViewURL = g_loadListViewURL.replace('_fieldOrder',id);
                    else
                        g_loadListViewURL = g_loadListViewURL.replace('_fieldOrder','new');
                    $("#listiframe").attr("src",g_loadListViewURL);
                },
                type: 'html',
                html: '<iframe style="width:810px;height:220px" id="listiframe" name="listiframe" src="#"></iframe>'
            }
            ]
        }
        ]
    }
});