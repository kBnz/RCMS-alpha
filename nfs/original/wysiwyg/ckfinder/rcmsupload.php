<?php
$lite_mode = true; //軽量動作モード
require_once("default.php");

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html lang="ja">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<script type="text/javascript" src="https://www.google.com/jsapi"></script> 
<script type="text/javascript"> 
google.load("jquery", "1.7");
</script> 
<script type="text/javascript"> 
var j$ = jQuery.noConflict();
</script> 
<script type="text/javascript">
<!--
j$(document).ready(function() {
		parent.j$('.cke_dialog_ui_button_ok').css('visibility','hidden');
});
-->
</script>

<?php
if($_REQUEST["step"]=="2"){
?>

<style type="text/css">
#divFileProgressContainer .progressWrapper {
	width: 357px;
	overflow: hidden;
}

#divFileProgressContainer .progressContainer {
	margin: 5px;
	padding: 4px;
	border: solid 1px #E8E8E8;
	background-color: #F7F7F7;
	overflow: hidden;
}
/* Message */
#divFileProgressContainer .message {
	margin: 1em 0;
	padding: 10px 20px;
	border: solid 1px #FFDD99;
	background-color: #FFFFCC;
	overflow: hidden;
}
/* Error */
#divFileProgressContainer .red {
	border: solid 1px #B50000;
	background-color: #FFEBEB;
}

/* Current */
#divFileProgressContainer .green {
	border: solid 1px #DDF0DD;
	background-color: #EBFFEB;
}

/* Complete */
#divFileProgressContainer .blue {
	border: solid 1px #CEE2F2;
	background-color: #F0F5FF;
}

#divFileProgressContainer .progressName {
	font-size: 8pt;
	font-weight: 700;
	color: #555;
	width: 323px;
	height: 14px;
	text-align: left;
	white-space: nowrap;
	overflow: hidden;
}

#divFileProgressContainer .progressBarInProgress,
#divFileProgressContainer .progressBarComplete,
#divFileProgressContainer .progressBarError {
	font-size: 0;
	width: 0%;
	height: 2px;
	background-color: blue;
	margin-top: 2px;
}

#divFileProgressContainer .progressBarComplete {
	width: 100%;
	background-color: green;
	visibility: hidden;
}

#divFileProgressContainer .progressBarError {
	width: 100%;
	background-color: red;
	visibility: hidden;
}

#divFileProgressContainer .progressBarStatus {
	margin-top: 2px;
	width: 337px;
	font-size: 7pt;
	font-family: Arial;
	text-align: left;
	white-space: nowrap;
}

#divFileProgressContainer a.progressCancel {
	font-size: 0;
	display: block;
	height: 14px;
	width: 14px;
	background-image: url(/js/swfupload_vr52/cancelbutton.gif);
	background-repeat: no-repeat;
	background-position: -14px 0px;
	float: right;
}

#divFileProgressContainer a.progressCancel:hover {
	background-position: 0px 0px;
}
{/literal}
</style>
<script type="text/javascript" src="/js/swfupload_vr52/swfupload.js" charset="UTF-8"></script>
<script type="text/javascript" src="/js/swfupload_vr52/handlers_simple.js" charset="UTF-8"></script>
<script type="text/javascript" src="<?=getCDNDomain("js")?>/js/swfobject.js" charset="UTF-8"></script>

<script type="text/javascript">
<!--

//FLASHのバージョンを判別
var so_chk = new SWFObject("<?=getCDNDomain("js")?>/js/swfupload_vr52/swfupload.swf", "check", "200", "200", "10", "#FFFFFF");

if(so_chk.installedVer.major < 10){
j$(document).ready(function() {
		j$('swfu_container').hide();
		j$('flash_10_instll').show();
		alert("Flash Player10をインストールしてください。");
});

}else{

var swfu;
j$(document).ready(function() {
			swfu = new SWFUpload({
				// Backend Settings
				upload_url: "/js/swfupload_vr52/upload.php",	// Relative to the SWF file or absolute
				post_params: {"RCMSSESS": "<?=substr(htmlspecialchars(session_id()),9)?>"},

				// File Upload Settings
				file_size_limit : "10 MB",	// 10MB
				file_types : "*.jpg;*.gif;*.png",
				file_types_description : "Images",
				file_upload_limit : "0",

				// Event Handler Settings - these functions as defined in Handlers.js
				//  The handlers are not part of SWFUpload but are part of my website and control how
				//  my website reacts to the SWFUpload events.
				file_queue_error_handler : fileQueueError,
				file_dialog_complete_handler : fileDialogComplete,
				upload_progress_handler : uploadProgress,
				upload_error_handler : uploadError,
				upload_success_handler : uploadSuccess,
				upload_complete_handler :  function(){parent.j$(".cke_dialog_ui_button_ok").css("visibility","visible");},

				// Button Settings
				//button_image_url : "https://d1madao7btrzkt.cloudfront.net/js/swfupload_vr52/SmallSpyGlassWithTransperancy_17x18.png",	// Relative to the SWF file
				button_image_url : "<?=getCDNDomain("js")?>/js/swfupload_vr52/btn_upload.gif",	// Relative to the SWF file
				button_placeholder_id : "spanButtonPlaceholder",
				button_width: 320,
				button_height: 25,
				//button_text : '<span class="button">ここをクリックして、画像を選択してください</span>',//画像を選択してください
				//button_text_style : '.button { font-size: 16pt;}',
				//button_text_top_padding: 7,
				//button_text_left_padding: 18,
				button_window_mode: SWFUpload.WINDOW_MODE.WINDOW, //TRANSPARENT はIEの文字入力に不具合を起こす
				button_cursor: SWFUpload.CURSOR.HAND,
				
				// Flash Settings
				flash_url : "/js/swfupload_vr52/swfupload.swf",

				custom_settings : {
					upload_target : "divFileProgressContainer"
				},
				
				// Debug Settings
				debug: false
			});
		});
}

//-->
</script>
<?php
}
?>
</head>
<body style="height:250px;">
<?php
if($_REQUEST["step"]=="2"){
?>
        <div id="divFileProgressContainer" style="height: 75px;"></div>
        <div id="thumbnails_container">
          <table>
          <tbody id="thumbnails"></tbody>
          </table>
        </div>
        <div style="display: inline;">
            <span id="spanButtonPlaceholder">準備中...</span>
        </div>
      </div>
      <div id="flash_10_instll" style="margin: 0px 10px;display: none;">
         このページではFLASH Player10が必要です。FLASH Player10をインストールしてください。
          <p><a href="{'/label/url_of_adove_flash_player_download'|translate}{* AdobeへのURL *}" target="_blank"><img alt="get_adobe_reader" src="https://www.adobe.com/images/shared/download_buttons/get_flash_player.gif" height="31" width="88"></a></p>
          <p>
          <a href="{'/label/url_of_adove_flash_player_download'|translate}{* AdobeへのURL *}" target="_blank">AdobeR Flash Player のダウンロード</a>
         </p>
      </div>
<?php
}else{
?>
①または②のいずれかをお選びください<br>
<div style="cursor: pointer;width:450px;height:56px;font-size:17px;text-align:center;background-image:url('<?=getCDNDomain("js")?>/images/management/upload_btn01.jpg');" onClick="location.href='/wysiwyg/ckfinder/rcmsupload.php?step=2';"><br>①新しい画像をアップロードして、記事に貼り付ける</div>
<br><br>
<div style="cursor: pointer;width:450px;height:56px;font-size:17px;text-align:center;background-image:url('<?=getCDNDomain("js")?>/images/management/upload_btn02.gif');" onClick="location.href='/wysiwyg/ckfinder/ckfinder.html?action=js&func=SetFileField&thumbFunc=SetFileField&columns=columns_1';"><br>②過去にアップロードした画像を選んで、記事に貼り付ける</div>
<?php
}
?>
</body>
</html>