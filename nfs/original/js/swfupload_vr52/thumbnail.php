<?php
if(substr($_SERVER["SERVER_ADDR"],0,11)=="192.168.25."){define("NO_ACCESSCOUNT","1");}

header('Pragma:no-cache');

	require_once("default.php");
	$image_id = $_REQUEST["id"];
	$logger->info(":swfupload:".$image_id) ;

	if ($image_id === false) {
		header("HTTP/1.0 500 Internal Server Error");
		echo "No ID";
		exit(0);
	}

  foreach (glob(TEMP_DIR."/photo_".$image_id."_s.*") as $path) {
		if (is_file($path) && is_public_file(TEMP_DIR,$path)) {
		    $sFilePath = $path;
				break;
		}
  }

	$logger->info(":swfupload:".$sFilePath) ;

	if (!$sFilePath) {
		header("HTTP/1.0 404 Not found");
		exit(0);
	}

	$img = file_get_contents($sFilePath);

	$ext = is_ImgFile($sFilePath);
	if(!$ext){echo "ERROR:invalid upload";exit;}
	if($ext == "jpg"){
			header("Content-type: image/jpeg") ;
	}elseif($ext == "png"){
			header("Content-type: image/png") ;
	}elseif($ext == "gif"){
			header("Content-type: image/gif") ;
	}
	header("Content-Length: ".strlen($img));
	echo $img;

