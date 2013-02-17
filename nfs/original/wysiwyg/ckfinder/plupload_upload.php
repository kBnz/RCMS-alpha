<?php
if(!defined("ROOT_DIR")){
    //基本ファイルのインクルード
    $lite_mode = true; //軽量動作モード
    require_once("default.php");
}


/**
 * upload.php
 *
 * Copyright 2009, Moxiecode Systems AB
 * Released under GPL License.
 *
 * License: http://www.plupload.com/license
 * Contributing: http://www.plupload.com/contributing
 */

	// HTTP headers for no cache etc
	header('Content-type: text/plain; charset=UTF-8');
	header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
	header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
	header("Cache-Control: no-store, no-cache, must-revalidate");
	header("Cache-Control: post-check=0, pre-check=0", false);
	header("Pragma: no-cache");

	// Settings
	if($_REQUEST["path"]){
		$targetDir = ROOT_DIR."/html".$_REQUEST["path"];
	}elseif(strpos($_COOKIE["CKFinder_Path"],"(")){
		$targetDir = ROOT_DIR."/html".str_replace("%2F","/",substr($_COOKIE["CKFinder_Path"],strpos($_COOKIE["CKFinder_Path"],"(")+1,strpos($_COOKIE["CKFinder_Path"],")")-strpos($_COOKIE["CKFinder_Path"],"(")-1));
	}else{
		$arrCKFinder_Path = split(":",$_COOKIE["CKFinder_Path"]);
		$targetDir = ROOT_DIR."/html".$arrCKFinder_Path[0].substr($arrCKFinder_Path[1],1,-1);
	}

	$cleanupTargetDir = false; // Remove old files
	$maxFileAge = 60 * 60; // Temp file age in seconds

	// 5 minutes execution time
	@set_time_limit(5 * 60);
	// usleep(5000);

	// Get parameters
	$chunk = isset($_REQUEST["chunk"]) ? $_REQUEST["chunk"] : 0;
	$chunks = isset($_REQUEST["chunks"]) ? $_REQUEST["chunks"] : 0;
	$fileName = isset($_REQUEST["name"]) ? $_REQUEST["name"] : '';

	// Clean the fileName for security reasons
	//$fileName = preg_replace('/[^\w\._]+/', '', $fileName); 
	//$fileName = preg_replace('/[^\w\._-]+/', '', $fileName);
	//$fileName = urlencode($fileName);

	$file_info = pathinfo($fileName);
	if(!in_array(strtolower($file_info["extension"]),explode(",",$allowedExtensions))){
		die('{"jsonrpc" : "2.0", "error" : {"code": 199, "message": "extension error."}, "id" : "id"}');
	}

	if(!is_public_file(ROOT_DIR,$targetDir)){//RCMS変更 で小文字に変更する
		die('{"jsonrpc" : "2.0", "error" : {"code": 198, "message": "extension error."}, "id" : "id"}');
	}

	// Create target dir
	if (!file_exists($targetDir))
		@mkdir($targetDir);

	// Remove old temp files
	if (is_dir($targetDir) && ($dir = opendir($targetDir))) {
		while (($file = readdir($dir)) !== false) {
			$filePath = $targetDir . DIRECTORY_SEPARATOR . $file;

			// Remove temp files if they are older than the max age
			if (preg_match('/\\.tmp$/', $file) && (filemtime($filePath) < time() - $maxFileAge))
				@unlink($filePath);
		}

		closedir($dir);
	} else
		die('{"jsonrpc" : "2.0", "error" : {"code": 100, "message": "Failed to open temp directory."}, "id" : "id"}');

	// Look for the content type header
	if (isset($_SERVER["HTTP_CONTENT_TYPE"]))
		$contentType = $_SERVER["HTTP_CONTENT_TYPE"];

	if (isset($_SERVER["CONTENT_TYPE"]))
		$contentType = $_SERVER["CONTENT_TYPE"];


	if (strpos($contentType, "multipart") !== false) {
		if (isset($_FILES['file']['tmp_name']) && is_uploaded_file($_FILES['file']['tmp_name'])) {
			// Open temp file
			$out = fopen($targetDir . DIRECTORY_SEPARATOR . $fileName, $chunk == 0 ? "wb" : "ab");
			if ($out) {
				// Read binary input stream and append it to temp file
				$in = fopen($_FILES['file']['tmp_name'], "rb");

				if ($in) {
					while ($buff = fread($in, 4096))
						fwrite($out, $buff);
				} else
					die('{"jsonrpc" : "2.0", "error" : {"code": 101, "message": "Failed to open input stream."}, "id" : "id"}');

				fclose($out);
				unlink($_FILES['file']['tmp_name']);
			} else
				die('{"jsonrpc" : "2.0", "error" : {"code": 102, "message": "Failed to open output stream."}, "id" : "id"}');
		} else
			die('{"jsonrpc" : "2.0", "error" : {"code": 103, "message": "Failed to move uploaded file."}, "id" : "id"}');
	} else {
		// Open temp file
		$out = fopen($targetDir . DIRECTORY_SEPARATOR . $fileName, $chunk == 0 ? "wb" : "ab");
		if ($out) {
			// Read binary input stream and append it to temp file
			$in = fopen("php://input", "rb");

			if ($in) {
				while ($buff = fread($in, 4096))
					fwrite($out, $buff);
			} else
				die('{"jsonrpc" : "2.0", "error" : {"code": 101, "message": "Failed to open input stream."}, "id" : "id"}');

			fclose($out);
		} else
			die('{"jsonrpc" : "2.0", "error" : {"code": 102, "message": "Failed to open output stream."}, "id" : "id"}');
	}

	if(in_array($file_info["extension"],array("jpg","png","gif")) && defined("FILEMANAGER_MAX_WIDTH") && FILEMANAGER_MAX_WIDTH > 0){
			makeThumbnail($targetDir . DIRECTORY_SEPARATOR . $fileName,$targetDir . DIRECTORY_SEPARATOR . $fileName,FILEMANAGER_MAX_WIDTH,0,$file_info["extension"]);
	}

	// Return JSON-RPC response
	die('{"jsonrpc" : "2.0", "result" : null, "id" : "id"}');
