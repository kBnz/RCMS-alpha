<?php
if(substr($_SERVER["SERVER_ADDR"],0,11)=="192.168.25."){define("NO_ACCESSCOUNT","1");}

header('Pragma:');
// Get the session Id passed from SWFUpload. We have to do this to work-around the Flash Player Cookie Bug

require_once("default.php");

// Check the upload
if (!isset($_FILES["Filedata"]) || !is_uploaded_file($_FILES["Filedata"]["tmp_name"]) || $_FILES["Filedata"]["error"] != 0) {
    echo "ERROR:invalid upload";
    exit;
}

$file_id = md5($_FILES["Filedata"]["tmp_name"] + rand()*100000);
$extension = is_ImgFile($_FILES['Filedata']['tmp_name']);
if(!$extension){echo "ERROR:invalid upload";exit;}

$_SESSION[date("His")] = $_FILES["Filedata"];

//日付の自動取得の場合
$auto_ymd = getPhotoYmd($_FILES["Filedata"]["tmp_name"]);
$_SESSION["file_info"][$file_id]["ymd"] = $auto_ymd;

//アップロードされたファイルを一時退避させる
make_dir(TEMP_DIR);
$temp_photo_file = TEMP_DIR."/photo_".$file_id.".".$extension;
$temp_photo_s_file = TEMP_DIR."/photo_".$file_id."_s.".$extension;
$_SESSION["file_info"][$file_id]["filename"] = $_FILES["Filedata"]["name"];
$_SESSION["file_info"][$file_id]["tmp_photo_file"] = $temp_photo_file;
$_SESSION["file_info"][$file_id]["tmp_photo_s_file"] = $temp_photo_s_file;

@move_uploaded_file($_FILES["Filedata"]["tmp_name"],$temp_photo_file);

if(PHOTO_THUMBNAIL_SQUARE == "1"){
    @makeSquareThumbnail($temp_photo_file, $temp_photo_s_file, 200);
}else{
    @makeThumbnail($temp_photo_file,$temp_photo_s_file,200,200);
}

list($width, $height, $type, $attr) = getimagesize($temp_photo_file);

if(FILEMANAGER_MAX_WIDTH && $width > FILEMANAGER_MAX_WIDTH){
    $width = FILEMANAGER_MAX_WIDTH;
    @makeThumbnail($temp_photo_file,$temp_photo_file,$width);
}

sleep(1);

echo "FILEID:" . $file_id  .":". $width . ":".$extension; // Return the file id to the script
