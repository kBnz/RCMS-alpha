#!/usr/local/bin/php -q
<?php
$bat_flg = true;

//��{�t�@�C���̃C���N���[�h
if(file_exists($argv[1].'default.php')){
  require_once($argv[1].'default.php');

  $mt = $argv[2];
  $ct = $argv[3];

  if($mt=="" || $ct == ""){
      return;
  }

  //�t�@�C���̃C���N���[�h
  require_once(LIB_PATH.'/system/lib/bat_controller.php');
}
?>
