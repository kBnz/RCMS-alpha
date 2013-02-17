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
  $("#uploader").pluploadQueue({
    // General settings
    runtimes : 'flash,silverlight,html5,gears',
    url : '/wysiwyg/ckfinder/plupload_upload.php',
    max_file_size : '30mb',
    chunk_size : '1mb',
    unique_names : false,
    resize : false,

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

});
</script>
</head>
<body>

<form>
  <div id="uploader">Loading....</div>
</form>

</body>
</html>
