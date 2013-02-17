<?php
if(!defined("ROOT_DIR")){
    //基本ファイルのインクルード
    require_once("default.php");
}

if(!$_SESSION["member_id"]){echo "require login.";exit;}

/**
 * upload.php
 *
 * Copyright 2009, Moxiecode Systems AB
 * Released under GPL License.
 *
 * License: http://www.plupload.com/license
 * Contributing: http://www.plupload.com/contributing
 */

	// Settings
	if($_REQUEST["file_path"] && is_public_file(ROOT_DIR."/html/files/",ROOT_DIR."/html".$_REQUEST["file_path"])){
		$file_path = ROOT_DIR."/html".$_REQUEST["file_path"];
	}else{
		exit;
	}
	//$file_path = preg_replace('/[^\w\._-]+/', '', $file_path);

	$file_info = pathinfo($file_path);
	if(!in_array(strtolower($file_info["extension"]),explode(",",$allowedExtensions))){
		die('{"jsonrpc" : "2.0", "error" : {"code": 199, "message": "extension error."}, "id" : "id"}');
	}

	if(!is_public_file(ROOT_DIR."/html/files/user/",$file_path)){//RCMS変更 で小文字に変更する
		die('{"jsonrpc" : "2.0", "error" : {"code": 198, "message": "extension error."}, "id" : "id"}');
	}

	file_put_contents($file_path,file_get_contents($_REQUEST["image"]));

header("location:standalone.php");
exit;
