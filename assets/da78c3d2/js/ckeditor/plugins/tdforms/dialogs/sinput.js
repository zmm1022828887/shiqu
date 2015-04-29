CKEDITOR.dialog.add( 'sinput', function( editor ) {

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
	//加载外部数据

	var items = $.ajax({type:'GET',url:g_loadEntityURL,async:false,dataType: 'json'}).responseText;
	return {
		title : editor.lang.tdforms.sinput.dialogTitle,
		minWidth : 400,
		minHeight : 200,
		onLoad: function() {

		},
        onShow: function() {
            var selection = editor.getSelection(),
                element = selection.getStartElement();
            if ( element )
                element = element.getAscendant( 'img', true );

            if ( !element || element.getName() != 'img' || element.data( 'cke-realelement' ) ) {
                element = editor.document.createElement( 'img' );
                this.insertMode = true;
            }
            else
                this.insertMode = false;

            this.element = element;
            if ( !this.insertMode )
                this.setupContent( this.element );
        },
        contents: [
            {
                id: '_form',
                elements: [
                {
            		type:'text',
            		id:'title',
            		label:editor.lang.common.controlName,
            		labelLayout:'horizontal',
            		validate:CKEDITOR.dialog.validate.notEmpty( editor.lang.common.validateControlFailed ),
                    setup: function( element ) {
                        this.setValue( element.getAttribute( "title" ) );
                    },
                    commit: function( element ) {
                        element.setAttribute( "title", this.getValue() );
                    }
                },
	            {
	                type:'select',
	                id: 'main_id',
	                label: editor.lang.common.value,
	                validate:CKEDITOR.dialog.validate.notEmpty('请选择实体！'),
	                labelLayout: 'horizontal',
					items: eval(items),
	                setup: function( element ) {
	                		this.setValue( element.getAttribute('value') );
	                },
	                commit: function( element ) {
	                	var dialog = this.getDialog(),
	                		optionsNames = getOptions( this ),
	                		optionsValues = getOptions( dialog.getContentElement( '_form', 'main_id' ) ),
	                		selectValue = dialog.getContentElement( '_form', 'main_id' ).getValue();
	                		element.setAttribute( 'class', 'sinput' );
	                		//element.setAttribute( 'readonly', 'true' );
						for ( var i = 0; i < optionsNames.count(); i++ ) {
							if ( optionsValues.getItem( i ).getValue() == selectValue ) {
								element.setAttribute( 'value', optionsValues.getItem( i ).getValue() );
							}
						}
	                }
	            }
                ]
            }
        ],
		onOk: function() {
	        var dialog = this,widget_json = '',
	        	field_name = this.getValueOf( '_form', 'title'),
	        	field_type = 'sinput',
	            sinput = this.element;
				widget_json += '{' + '"field_name":' + '"' + field_name + '",' + '"field_type":' + '"' + field_type + '",'
								+ '"field_attr":' + '{' + '"title":' + '"' + field_name + '",' + '"class":' + '"' +field_type + '",'
								+ '"value":' + '"' + this.getValueOf('_form','main_id') + '"' + '}}';

	        if ( this.insertMode ) {
        		sinput.setAttribute( 'title', dialog.getValueOf( '_form', 'title' ) );
				var nameItem = $.ajax({ type: "post",url: g_requestURL,data: {fieldInfo:widget_json,formID:g_form_id},cache: false,async: false}).responseText;
				sinput.setAttribute( 'src',baseUrl+'/images/form/sinput.png');
				sinput.setAttribute( 'name', "data_"+nameItem );
				sinput.setAttribute( 'id', 'data_'+nameItem);
	            editor.insertElement( sinput );
	        } else {
				var field_order = sinput.getAttribute( 'name' ).split('_')[1];
				$.ajax({ type: "post",url: g_requestURL,data: {fieldInfo:widget_json,formID:g_form_id,fieldOrder:field_order},cache: false,async: false});
	        }
	        this.commitContent( sinput );
		}
	};
} );