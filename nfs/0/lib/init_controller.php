#!/usr/local/bin/php -q
<?php
$bat_flg = true;
//基本ファイルのインクルード
if(file_exists($argv[1].'default.php')){
  require_once($argv[1].'default.php');

  //ファイルのインクルード
  require_once(LIB_PATH.'/system/lib/init_controller.php');
}
?>
