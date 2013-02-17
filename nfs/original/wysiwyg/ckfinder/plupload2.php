<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" dir="ltr">
<head>
<meta http-equiv="content-type" content="text/html; charset=UTF-8"/>
<link rel="stylesheet" href="/js/plupload/css/plupload.queue.css" type="text/css" media="screen" />
<title>Plupload - Queue widget example</title>
<style type="text/css">
body {background: #ffffff;margin:0;padding:0;}
#uploader {margin:0;}
</style>
<script type="text/javascript" src="https://www.google.com/jsapi"></script>
<script type="text/javascript">
  google.load("jquery", "1.4.4");
</script>
<script type="text/javascript" src="/js/plupload/gears_init.js"></script>
<script type="text/javascript" src="/js/plupload/plupload.full.min.js"></script>
<script type="text/javascript" src="/js/plupload/jquery.plupload.queue.min.js"></script>
<?
if($_SERVER["HTTP_ACCEPT_LANGUAGE"]){
    $arrLang = explode(",",$_SERVER["HTTP_ACCEPT_LANGUAGE"]);
    if($arrLang[0]=="ja"){
?>
<script type="text/javascript" src="/js/plupload/i18n/ja.js"></script>
<?
    }
}
?>
<script type="text/javascript">
// Convert divs to queue widgets when the DOM is ready
$(function() {
  var uploader = new plupload.Uploader({
    // General settings
    runtimes : 'flash,silverlight,html5,gears',
    url : '/wysiwyg/ckfinder/plupload_upload.php?path=<?=urlencode($_REQUEST["path"])?>&dummy=<?=date("YmdHis")?>',
    max_file_size : '30mb',
    chunk_size : '1mb',
    unique_names : false,
    resize : false,
    browse_button : 'pickfiles',

    // Resize images on clientside if we can
    //resize : {width : 320, height : 240, quality : 90},

    // Specify what files to browse for
    //filters : [
    //  {title : "Image files", extensions : "jpg,gif,png"},
    //  {title : "JS files", extensions : "js"},
    //  {title : "CSS files", extensions : "css"},
    //  {title : "ALL", extensions : "*"}
    //],

    // Flash settings
    flash_swf_url : '/js/plupload/plupload.flash.swf',

    // Silverlight settings
    silverlight_xap_url : '/js/plupload/plupload.silverlight.xap'
  });

	uploader.init();

	uploader.bind('FilesAdded', function(up, files) {
		$.each(files, function(i, file) {
			$('#filelist').append(
				'<div id="' + file.id + '">' +
				file.name + ' (' + plupload.formatSize(file.size) + ') <b></b>' +
			'</div>');
		});
		uploader.start();

		up.refresh(); // Reposition Flash/Silverlight
	});

	uploader.bind('UploadProgress', function(up, file) {
		$('#' + file.id + " b").html(file.percent + "%");
	});

	uploader.bind('Error', function(up, err) {
		$('#filelist').append("<div>Error: " + err.code +
			", Message: " + err.message +
			(err.file ? ", File: " + err.file.name : "") +
			"</div>"
		);

		up.refresh(); // Reposition Flash/Silverlight
	});

	uploader.bind('FileUploaded', function(up, file) {
		$('#' + file.id + " b").html("100%");
    parent.document.getElementById('<?=$_REQUEST["return_elm"]?>').innerHTML = '<img src="<?=$_REQUEST["path"]?>/'+file.name+'" border="0"/>';
    $('#thumnail').append('<img src="<?=$_REQUEST["path"]?>/'+file.name+'" style="max-width:300px;"/>');
	});


});
</script>
<script type="text/javascript" src="/js/swfobject2/swfobject.js"></script>
<link href="/js/jqueryUploadify/uploadify.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="/js/jqueryUploadify/jquery.uploadify.v2.1.0.min.js"></script>
<script type="text/javascript">
$(document).ready(function() {
	$("#uploadify").uploadify({
		'uploader'       : '/js/jqueryUploadify/uploadify.swf',
		'script'         : '/js/jqueryUploadify/uploadify.php',
		'cancelImg'      : '/js/jqueryUploadify/cancel.png',
		'folder'         : 'uploads',
		'queueID'        : 'fileQueue',
		'auto'           : true,
		'multi'          : true
	});
});
</script>

</head>
<body>
<div id="fileQueue"></div>
<input type="file" name="uploadify" id="uploadify" />
<p><a href="javascript:jQuery('#uploadify').uploadifyClearQueue()">Cancel All Uploads</a></p>
<hr/>
<form>
<div id="container">
    <div id="filelist">ファイルを選択してください。</div>
    <br />
    <a id="pickfiles" href="#">[Select files]</a>
    <br />
    <div id="thumnail"></div>
</div>
</form>

</body>
</html>
