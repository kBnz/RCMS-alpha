#!/usr/local/bin/php -q
<?php
$bat_flg = true;
//��{�t�@�C���̃C���N���[�h
if(file_exists($argv[1].'default.php')){
  require_once($argv[1].'default.php');

  //�t�@�C���̃C���N���[�h
  require_once(LIB_PATH.'/system/lib/init_controller.php');
}
?>
