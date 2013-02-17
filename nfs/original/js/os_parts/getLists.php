<?php
/**
 * ブログパーツに対応したモジュールコンテンツのリストを吐き出す。
 * For Ajax
 * @author 高澤
 */

//基本ファイルのインクルード
require_once("default.php");

//os_Parts関数のインクルード
require_once(find_lib_path('os_parts/class/os_PartsUtil.php'));

//パラメータごとの処理
if (isset($_REQUEST['moduleName'])) {
    //コンテンツ一覧を出力
    $moduleName = $_REQUEST['moduleName'];
    $list = os_PartsUtil::getContentsList($moduleName);
} else if (isset($_REQUEST['phpID'])) {
    //テンプレート一覧を出力
    $phpID = (int)$_REQUEST['phpID'];
    $list = os_PartsUtil::getTemplatesList($phpID);
} else {
    exit('{}');
}

foreach ($list as $key => $val) {
    $list[$key] = mb_convert_encoding($val,'utf-8','auto');
}
echo json_encode($list);
