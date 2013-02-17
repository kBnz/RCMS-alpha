#!/usr/local/bin/php -q
<?php
$bat_flg = true;

//基本ファイルのインクルード
if(file_exists($argv[1].'default.php')){
  require_once($argv[1].'default.php');

  $mt = $argv[2];
  $ct = $argv[3];

  if($mt=="" || $ct == ""){
      return;
  }

  //ファイルのインクルード
  require_once(LIB_PATH.'/system/lib/bat_controller.php');
}
?>
