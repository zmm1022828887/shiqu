CKEDITOR.dialog.add('cssEditor', function( editor ) {
	return {
		title : editor.lang.tdforms.cssEditor.dialogTitle,
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
	                type:'html',
	                html:"<div>\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\</div>"+
"<div><strong>///////////////////////////////</strong></div>"+
"<div><b>i &nbsp; &nbsp; &nbsp;&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; i</b></div>"+
"<div><b>i &nbsp; &nbsp; &nbsp;&nbsp;O &nbsp; &nbsp; &nbsp; &nbsp; O&nbsp; &nbsp; &nbsp; &nbsp; i</b></div>"+
"<div><b>i &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; ? &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; i &nbsp;</b></div>"+
"<div><b>i &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; WWW&nbsp; &nbsp; &nbsp; &nbsp;&nbsp;i&nbsp;</b></div>"+
"<div><b>i_____________i</b></div>",
	            },
	            {
	            	type:'html',
	            	html:"<p style='color:red'>TODO...</p>",
	            }
                ]
            }
        ],
		onOk: function() {
		}
	};
});