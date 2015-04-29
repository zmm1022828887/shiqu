/**
 * @license Copyright (c) 2003-2012, CKSource - Frederico Knabben. All rights reserved.
 * For licensing, see LICENSE.html or http://ckeditor.com/license
 */

/**
 * @fileOverview Forms Plugin
 */

CKEDITOR.plugins.add( 'tdforms', {
	requires: 'dialog,fakeobjects',
	lang: 'en,zh,zh-cn',
	icons: 'tdcheckbox,tdhiddenfield,tdradio,tdselect,tdtextarea,tdtextfield,sinput,cdxdtpicker,suseranddept,listview,jsEditor,cssEditor,signature,writesign,dimensionalcode', // %REMOVE_LINE_CORE%
	onLoad: function() {
		CKEDITOR.addCss( '.cke_editable form' +
			'{' +
				'border: 1px dotted #FF0000;' +
				'padding: 2px;' +
			'}\n' );

		CKEDITOR.addCss( 'img.cke_hidden' +
			'{' +
				'background-image: url(' + CKEDITOR.getUrl( this.path + 'images/tdhiddenfield.gif' ) + ');' +
				'background-position: center center;' +
				'background-repeat: no-repeat;' +
				'border: 1px solid #a9a9a9;' +
				'width: 16px !important;' +
				'height: 16px !important;' +
			'}' );

	},
	init: function( editor ) {
		var lang = editor.lang,
			order = 0,
			textfieldClasses = { bind_entity:1,user:1,dept:1 },
			textfieldTypes = { email:1,password:1,search:1,tel:1,text:1,url:1 },
			dateType = {date:1,datetime:1,time:1};
		var addButtonCommand = function( buttonName, commandName, dialogFile ) {
				var def = {};
				commandName == 'form' && ( def.context = 'form' );

				editor.addCommand( commandName, new CKEDITOR.dialogCommand( commandName, def ) );

				editor.ui.addButton && editor.ui.addButton( buttonName, {
					label: lang.common[ buttonName.charAt( 0 ).toLowerCase() + buttonName.slice( 1 ) ],
					command: commandName,
					toolbar: 'tdforms,' + ( order += 10 )
				});
				CKEDITOR.dialog.add( commandName, dialogFile );
			};
		//add prefix "td"
		var dialogPath = this.path + 'dialogs/';
		addButtonCommand( 'TdCheckbox', 'tdcheckbox', dialogPath + 'tdcheckbox.js' );
		addButtonCommand( 'TdRadio', 'tdradio', dialogPath + 'tdradio.js' );
		addButtonCommand( 'TdTextField', 'tdtextfield', dialogPath + 'tdtextfield.js' );
		addButtonCommand( 'TdTextarea', 'tdtextarea', dialogPath + 'tdtextarea.js' );
		addButtonCommand( 'TdSelect', 'tdselect', dialogPath + 'tdselect.js' );
		addButtonCommand( 'Sinput', 'sinput', dialogPath + 'sinput.js' );//特殊控件 绑定编号实体
		addButtonCommand( 'Cdxdtpicker', 'cdxdtpicker', dialogPath + 'cdxdtpicker.js' );//日历控件
		addButtonCommand( 'Suseranddept', 'suseranddept', dialogPath + 'suseranddept.js' );//选人选部门控件
		addButtonCommand( 'Listview', 'listview', dialogPath + 'listview.js');//列表控件
		addButtonCommand( 'Remove', 'remove', dialogPath + 'remove.js');
		addButtonCommand( 'jsEditor', 'jsEditor', dialogPath + 'jsEditor.js');
		addButtonCommand( 'cssEditor', 'cssEditor', dialogPath + 'cssEditor.js');
		addButtonCommand('Signature','signature',dialogPath + 'signature.js');
		addButtonCommand('Writesign','writesign',dialogPath + 'writesign.js');
		addButtonCommand('Dimensionalcode','dimensionalcode',dialogPath + 'dimensionalcode.js');//二维码控件

		// If the "image" plugin is loaded.
		var imagePlugin = CKEDITOR.plugins.get( 'image' );
		imagePlugin && addButtonCommand( 'ImageButton', 'imagebutton', CKEDITOR.plugins.getPath( 'image' ) + 'dialogs/image.js' );

		addButtonCommand( 'TdHiddenField', 'tdhiddenfield', dialogPath + 'tdhiddenfield.js' );

		// If the "menu" plugin is loaded, register the menu items.
		if ( editor.addMenuItems ) {
			var items = {
				tdcheckbox: {
					label: lang.tdforms.tdcheckbox.dialogTitle,
					command: 'tdcheckbox',
					group: 'tdcheckbox'
				},

				tdradio: {
					label: lang.tdforms.tdradio.dialogTitle,
					command: 'tdradio',
					group: 'tdradio'
				},

				tdtextfield: {
					label: lang.tdforms.tdtextfield.dialogTitle,
					command: 'tdtextfield',
					group: 'tdtextfield'
				},

				tdhiddenfield: {
					label: lang.tdforms.tdhidden.dialogTitle,
					command: 'tdhiddenfield',
					group: 'tdhiddenfield'
				},

				imagebutton: {
					label: lang.image.titleButton,
					command: 'imagebutton',
					group: 'imagebutton'
				},

				tdselect: {
					label: lang.tdforms.tdselect.dialogTitle,
					command: 'tdselect',
					group: 'tdselect'
				},

				tdtextarea: {
					label: lang.tdforms.tdtextarea.dialogTitle,
					command: 'tdtextarea',
					group: 'tdtextarea'
				},
				sinput: {
					label: lang.tdforms.sinput.dialogTitle,
					command: 'sinput',
					group: 'sinput'
				},
				cdxdtpicker: {
					label: lang.tdforms.cdxdtpicker.dialogTitle,
					command: 'cdxdtpicker',
					group: 'cdxdtpicker'
				},
				suseranddept: {
					label: lang.tdforms.suseranddept.dialogTitle,
					command: 'suseranddept',
					group: 'suseranddept'
				},
				listview: {
					label: lang.tdforms.listview.dialogTitle,
					command: 'listview',
					group: 'listview'
				},
				remove: {
					label: lang.tdforms.remove.dialogTitle,
					command: 'remove',
					group: 'remove'
				},
				jsEditor: {
					label: 'JS',
					command: 'jsEditor',
					group: 'jsEditor'
				},
				cssEditor: {
					label: 'CSS',
					command: 'cssEditor',
					group: 'cssEditor'
				},
				signature: {
					label: lang.tdforms.signature.dialogTitle,
					command: 'signature',
					group: 'signature'
				},
                writesign: {
                  label: lang.tdforms.writesign.dialogTitle,
                  command: 'writesign',
                  group: 'writesign'
                },
				dimensionalcode: {
					label: lang.tdforms.dimensionalcode.dialogTitle,
					command: 'dimensionalcode',
					group: 'dimensionalcode'
				}
			};
			editor.addMenuItems( items );
		}

		// If the "contextmenu" plugin is loaded, register the listeners.
		if ( editor.contextMenu ) {
			editor.contextMenu.addListener( function( element ) {
				if ( element && !element.isReadOnly() ) {
					var name = element.getName();
					if ( name == 'select' )
						return { tdselect: CKEDITOR.TRISTATE_OFF };

					if ( name == 'textarea' )
						return { tdtextarea: CKEDITOR.TRISTATE_OFF };

					if ( name == 'input' ) {
						var type = element.getAttribute( 'type' ) || 'text';
						var inputClass = element.getAttribute( 'class' );
						switch ( type ) {
							case 'checkbox':
								return { tdcheckbox: CKEDITOR.TRISTATE_OFF };

							case 'radio':
								return { tdradio: CKEDITOR.TRISTATE_OFF };

							case 'image':
								return imagePlugin ? { imagebutton: CKEDITOR.TRISTATE_OFF } : null;
						}

						if( textfieldClasses[ inputClass] && (inputClass == 'user' || inputClass == 'dept') ) {
							return { suseranddept: CKEDITOR.TRISTATE_OFF };
						} else if( dateType[inputClass]) {
							return { cdxdtpicker: CKEDITOR.TRISTATE_OFF };
						} else if ( textfieldTypes[ type ] ) {
							return { tdtextfield: CKEDITOR.TRISTATE_OFF };
						}
					}
					 if ( name == 'img' && element.data( 'cke-real-element-type' ) == 'tdhiddenfield' )
						return { tdhiddenfield: CKEDITOR.TRISTATE_OFF };
				}
			});
		}

		editor.on( 'doubleclick', function( evt ) {
			var element = evt.data.element;
			if ( element.is( 'select' ) )
				evt.data.dialog = 'tdselect';
			else if ( element.is( 'textarea' ) )
				evt.data.dialog = 'tdtextarea';
			else if ( element.is( 'img' ) && element.data( 'cke-real-element-type' ) == 'tdhiddenfield' )
				evt.data.dialog = 'tdhiddenfield';
			else if ( element.is( 'input' ) ) {
				var type = element.getAttribute( 'type' ) || 'text';
				var inputClass = element.getAttribute( 'class' );
				switch ( type ) {
					case 'checkbox':
						evt.data.dialog = 'tdcheckbox';
						break;
					case 'radio':
						evt.data.dialog = 'tdradio';
						break;
					case 'image':
						evt.data.dialog = 'imagebutton';
						break;
				}
				if ( textfieldClasses[ inputClass ] && (inputClass == 'user' || inputClass == 'dept' ) )
					evt.data.dialog = 'suseranddept';
				else if( dateType[ inputClass ] )
					evt.data.dialog = 'cdxdtpicker';
				else if ( textfieldTypes[ type ] )
					evt.data.dialog = 'tdtextfield';
			}
		});
	},

	afterInit: function( editor ) {
		var dataProcessor = editor.dataProcessor,
			htmlFilter = dataProcessor && dataProcessor.htmlFilter,
			dataFilter = dataProcessor && dataProcessor.dataFilter;

		// Cleanup certain IE form elements default values.
		if ( CKEDITOR.env.ie ) {
			htmlFilter && htmlFilter.addRules({
				elements: {
					input: function( input ) {
						var attrs = input.attributes,
							type = attrs.type;
						// Old IEs don't provide type for Text inputs #5522
						if ( !type )
							attrs.type = 'text';
						if ( type == 'checkbox' || type == 'radio' )
							attrs.value == 'on' && delete attrs.value;
					}
				}
			});
		}

		if ( dataFilter ) {
			dataFilter.addRules({
				elements: {
					input: function( element ) {
						if ( element.attributes.type == 'hidden' )
							return editor.createFakeParserElement( element, 'cke_hidden', 'tdhiddenfield' );
					}
				}
			});
		}
	}
});

if ( CKEDITOR.env.ie ) {
	CKEDITOR.dom.element.prototype.hasAttribute = CKEDITOR.tools.override( CKEDITOR.dom.element.prototype.hasAttribute, function( original ) {
		return function( name ) {
			var $attr = this.$.attributes.getNamedItem( name );

			if ( this.getName() == 'input' ) {
				switch ( name ) {
					case 'class':
						return this.$.className.length > 0;
					case 'checked':
						return !!this.$.checked;
					case 'value':
						var type = this.getAttribute( 'type' );
						return type == 'checkbox' || type == 'radio' ? this.$.value != 'on' : this.$.value;
				}
			}

			return original.apply( this, arguments );
		};
	});
}
