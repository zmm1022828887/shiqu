/**
 * Copyright (c) 2003-2012, CKSource - Frederico Knabben. All rights reserved.
 * For licensing, see LICENSE.html or http://ckeditor.com/license
 */


CKEDITOR.editorConfig = function( config )
{
//   config.startupFocus
//   config.startupMode

   config.toolbarCanCollapse = false;
   config.resize_enabled = false;
   config.autoParagraph = false;
   config.enterMode = CKEDITOR.ENTER_BR;
   config.pasteFromWordPromptCleanup = true;
   config.pasteFromWordRemoveStyles = true;
   config.language = 'zh-CN';//
   config.plugins="about,a11yhelp,basicstyles,bidi,blockquote,clipboard,colorbutton,colordialog,contextmenu,div,elementspath,enterkey,entities,filebrowser,find,floatingspace,font,format,tdforms,horizontalrule,htmlwriter,image,iframe,indent,justify,link,list,liststyle,magicline,maximize,newpage,pagebreak,pastefromword,pastetext,removeformat,resize,selectall,showblocks,showborders,smiley,sourcearea,specialchar,stylescombo,tab,tabletools,templates,toolbar,undo,wysiwygarea";
   config.toolbar_Simple =
   [
      ['Bold','Italic','-','Link','Unlink','-','Font','FontSize','TextColor','BGColor','Smiley']
   ];
   config.toolbar_Basic =
   [
      { name: 'basicstyles',items : [ 'Bold','Italic','NumberedList','BulletedList','-','JustifyLeft','JustifyCenter','JustifyRight','JustifyBlock','Link','Unlink' ]},
      { name: 'clipboard', items : [ 'Font','FontSize','TextColor','BGColor','Image','Smiley','-','Templates','Maximize' ]}
   ];
   config.toolbar_Default =
   [
      { name: 'basicstyles', items : [ 'Bold','Italic','Underline','Strike','Subscript','Superscript','-','RemoveFormat' ] },
      { name: 'clipboard',   items : [ 'Cut','Copy','Paste','PasteText','PasteFromWord' ] },
      { name: 'styles',      items : [ 'Styles','Format','Font','FontSize' ] },
      { name: 'colors',      items : [ 'TextColor','BGColor' ] },
      '/',
      { name: 'paragraph',   items : [ 'NumberedList','BulletedList','-','Outdent','Indent','-','JustifyLeft','JustifyCenter','JustifyRight','JustifyBlock' ] },
      { name: 'links',       items : [ 'Link','Unlink','Anchor' ] },
      { name: 'insert',      items : [ 'Image','Flash','Table','HorizontalRule','Smiley','SpecialChar','PageBreak' ] },
      { name: 'edit',        items : [ 'Undo','Redo' ] },
      { name: 'document',    items : [ 'Source','Preview','Templates' ] },
      { name: 'tools',       items : [ 'Maximize', 'ShowBlocks' ] }
   ];
    config.toolbar_Form=
    [
    {name:"document",items:["Source"]},
    {name:"clipboard", items:["Cut","Copy","Paste","PasteText","PasteFromWord"]},
    {name:"tool",items:["Undo", "Redo", "Find", "Replace","SelectAll", "RemoveFormat"]},
    {name:"link",items:["Link","Unlink"]},
    {name:"object",items:["Image","Table","HorizontalRule","SpecialChar"]},
    {name:"about",items:["About","ShowBlocks","Maximize"]},
    "/",
    {name:"style", items:["Styles","Format","Font","FontSize"]  },
    {name:"bold", items:["Bold","Italic","Underline"] },
    {name:"oposition",items:["JustifyLeft","JustifyCenter","JustifyRight","JustifyBlock"]},
    {name:"list", items:["NumberedList","BulletedList","-","Outdent","Indent"] },
    {name:"color",items:["TextColor","BGColor"]},
    "/",
    {name:"tdforms",items:"TdTextField TdTextarea TdSelect TdRadio TdCheckbox TdHiddenField - Sinput Cdxdtpicker Suseranddept Listview Signature Writesign Dimensionalcode".split(" ")}];
   //config.templates_files   = ['/module/html_model/view/xml.php?MODEL_TYPE=' + (window.HTML_MODEL_TYPE ? window.HTML_MODEL_TYPE : '')];
   config.font_names = '宋体;新宋体;仿宋_GB2312;仿宋;黑体;楷体_GB2312;楷体;隶书;幼圆;Arial;Comic Sans MS;Courier New;Fixedsys;Georgia;Tahoma;Times New Roman;Verdana;';
   config.fontSize_sizes = '8pt/8pt;9pt/9pt;10pt/10pt;11pt/11pt;12pt/12pt;13pt/13pt;14pt/14pt;16pt/16pt;18pt/18pt;24pt/24pt;36pt/36pt;48/48pt;'
   config.image_previewText = ' ';
   config.toolbar = 'Simple';
};

function getEditorText(id)
{
   var editor = eval("CKEDITOR.instances." + id);
   var element = CKEDITOR.dom.element.createFromHtml('<div>' + editor.getData() + '</div>');
   return element.getText();
}
function getEditorHtml(id)
{
   return eval("CKEDITOR.instances." + id + ".getData()");
}
function setEditorHtml(id, html)
{
   var editor = eval("CKEDITOR.instances." + id);
   if(editor)
      editor.setData(html);
}
function checkEditorDirty(id)
{
   return eval("CKEDITOR.instances." + id + ".checkDirty()");
}
function resetEditorDirty(id)
{
   return eval("CKEDITOR.instances." + id + ".resetDirty()");
}
function insertEditorImage(id, src)
{
   if(isUndefined(id))
      id = 'CONTENT';

   var editor = eval("CKEDITOR.instances." + id);
   if(editor)
      editor.insertHtml('<img src="'+src+'">');
}
function addEditorEvent(editor, event, handler)
{
   editor.document.on(event, handler);
}

function reg_replace(str, to_null, tagName)
{
   var re = new RegExp("<([\\s|/]*?)" + tagName + "([\\s\\S]*?)>", "ig");
   return str.replace(re, (to_null ? "" : "&lt;$1" + tagName + "$2&gt;"));
}

CKEDITOR.on("instanceReady", function(e) {
   e.editor.on("key", function(evt) {
      if (evt.data.keyCode == '32') {
         return false;
      }
   });

   e.editor.on("blur", function(evt) {return;
      var data = this.getData();
      data = reg_replace(data, 1, 'html');
      data = reg_replace(data, 1, 'head');
      data = reg_replace(data, 1, 'meta');
      data = reg_replace(data, 1, 'link');
      data = reg_replace(data, 1, 'script');
      data = reg_replace(data, 1, 'body');
      data = reg_replace(data, 1, 'frame');
      data = reg_replace(data, 1, 'frameset');
      data = reg_replace(data, 1, 'iframe');
      data = reg_replace(data, 1, 'applet');
      data = reg_replace(data, 1, 'layer');

      this.setData(data, function(){
         this.checkDirty();
         this.updateElement();
      });
   });
});