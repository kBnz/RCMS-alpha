/*
* @example An iframe-based dialog with custom button handling logics.
*/
var rcms_editor_name;

CKEDITOR.plugins.add( 'rcmsupload',
{
  init : function( editor )
  {
    var pluginName = 'rcmsupload';
    rcms_editor_name = editor.name;

    // Register the command.
    editor.addCommand( pluginName, new CKEDITOR.dialogCommand( pluginName ) );

    // Register the toolbar button.
    editor.ui.addButton( 'RcmsUpload',
      {
        label:editor.lang.RcmsUpload,
        command : pluginName,
        icon: this.path + 'images/tb_rcmsupload_prop.png'
      });

  }
} );

CKEDITOR.dialog.add( 'rcmsupload', function (editor)
{
  var iframe;


  //過去の画像から選ぶ方で利用する
  function SetFileField(fileUrl, data){
      //console.log(editor.name);
      //console.log(data);
		fileUrl = fileUrl.replace(/\/files\/photo\/[^\/]+\//,'/files/photo/').replace(/\/files\/photo\/[^\/]+\//,'/files/photo/').replace(/\/files\/photo\/[^\/]+\//,'/files/photo/');

      window.parent.CKEDITOR.instances[editor.name].insertHtml('<img src="'+fileUrl+'">');
      window.parent.CKEDITOR.dialog.getCurrent().click('cancel');
  }
  window.parent.parent.SetFileField = SetFileField;

  return {
     title : editor.lang.RcmsUpload,
     minWidth : 700,
     minHeight : 320,
     contents :
           [
              {
                 id : 'iframe',
                 label : 'Insert img..',
                 expand : true,
                 elements :
                       [
                          {
                             type : 'iframe',
                             src : '/wysiwyg/ckfinder/rcmsupload.php',
                             width : '100%',
                             height : '300',
                             onContentLoad : function() {
                                 iframe = document.getElementById( this._.frameId );
                                 //document.getElementById(this.domId ).up('.cke_dialog_page_contents').setStyle({
                                 //    height: '100%'
                                 //});
                             }
                          }
                       ]
              }
           ],
     onOk : function()
     {
        // Notify your iframe scripts here...
        //editor.insertHtml(j$( this._.frameId ).contents().find('#thumbnails_container').html());
        //console.log(j$('iframe#'+iframe.id).contents().find('#thumbnails_container').find('img'));
        var img = j$('iframe#'+iframe.id).contents().find('#thumbnails_container').find('img');
        var regist_flg = j$('iframe#'+iframe.id).contents().find('#thumbnails_container').find('input:checkbox');
        var align = j$('iframe#'+iframe.id).contents().find('#thumbnails_container').find('select');
        var width = j$('iframe#'+iframe.id).contents().find('#thumbnails_container').find('input:text');

        var rtn = '';
        img.each(function(i){
            //console.log(j$(regist_flg[i]).attr('checked'));
            if(j$(regist_flg[i]).attr('checked') && j$(this).attr('src')){
                if(j$(align[i]).val() == 'center'){
                    rtn += '<div style="text-align:'+j$(align[i]).val()+';"><img src="'+j$(this).attr('src').replace('_s','')+'" style="width:'+j$(width[i]).val()+'px;"></div>';
                }else{
                    rtn += '<img src="'+j$(this).attr('src').replace('_s','')+'" style="width:'+j$(width[i]).val()+'px;float:'+j$(align[i]).val()+';">';
                }
            }
        });
        editor.insertHtml(rtn);
        //SetFileField("test");
     },
     onCancel : function()
     {
          j$('.cke_dialog_ui_button_ok').css('visibility','visible');
     }
  };
} );
